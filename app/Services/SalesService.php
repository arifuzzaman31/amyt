<?php

namespace App\Services;

use App\CustomConst\AllStatic;
use App\Models\AmytStock;
use App\Models\CustomerStock;
use App\Models\Service;
use App\Models\ServiceItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SalesService
{
    public function __construct(protected Service $serviceModel, protected ServiceItem $serviceItemModel) {}

    public function getAllServices($data)
    {
        $perPage = $data['per_page'] ?? 10;
        $status = $data['type'] ?? 1;
        $services = $this->serviceModel::with('customer:id,name')
                    ->where('status',$status)
                    ->paginate($perPage);
        return $services;
    }

    public function createService(array $data)
    {
        try {
            DB::beginTransaction();
            if (isset($data['document_link'])) {
                $data['document_link'] = storeFile($data['document_link'], 'services/documents');
            }
            $data['dataItem'] = json_decode($data['dataItem'], true);
            $data['status'] = AllStatic::ALL_STATIC['SERVICE_STATUS']['DRAFT']; // Assuming 0 means pending
            $service = $this->serviceModel::create($data);
            if (isset($data['dataItem']) && is_array($data['dataItem'])) {
                foreach ($data['dataItem'] as $item) {
                    $item['service_id'] = $service->id; // Associate the service item with the created service
                    $this->serviceItemModel::create($item);
                }
            }
            DB::commit();
            // return $this->serviceModel::with('items')->find($service->id);
            return [
                'status' => true,
                'message' => 'Service created successfully',
                'service' => $service->load('items') // Load items relationship
            ];
        } catch (\Throwable $th) {
            DB::rollBack();
            return [
                'status' => false,
                'message' => 'Failed to create service',
                'error' => $th->getMessage()
            ];
            //throw $th;
        }
    }

    public function updateService($id, array $data)
    {
        $service = $this->serviceModel::find($id);
        if (!$service) {
            return null; // Service not found
        }

        try {
            DB::beginTransaction();

            if (isset($data['update_document_link']) && $data['update_document_link'] instanceof \Illuminate\Http\UploadedFile) {
                if (isset($service->document_link) && $service->document_link) {
                    $previousFile = str_replace('/storage/', '', $service->document_link);
                    $filePath = Storage::disk('public')->path($previousFile);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
                $data['document_link'] = storeFile($data['update_document_link'], 'services/documents');
            }
            if (isset($data['dataItem']) && is_array($data['dataItem'])) {
                $incomingItemIds = collect($data['dataItem'])
                    ->pluck('id')
                    ->filter()
                    ->all();

                $existingItemIds = $service->items()->pluck('id')->all();

                $itemsToDelete = array_diff($existingItemIds, $incomingItemIds);

                if (!empty($itemsToDelete)) {
                    $this->serviceItemModel::whereIn('id', $itemsToDelete)->delete();
                }

                foreach ($data['dataItem'] as $key => $item) {
                    if (isset($item['id']) && $item['id']) {
                        $existingItem = $this->serviceItemModel::find($item['id']);
                        if ($existingItem) {
                            $existingItem->update($item);
                        }
                    } else {
                        $item['service_id'] = $service->id;
                        $this->serviceItemModel::create($item);
                    }
                }
            } else {
                $service->items()->delete();
            }

            unset($data['dataItem']);
            $service->update($data);
            DB::commit();
            return [
                'status' => true,
                'message' => 'Service updated successfully',
                'service' => $service->load('items')
            ];
        } catch (\Throwable $th) {
            DB::rollBack();
            return [
                'status' => false,
                'message' => 'Failed to update service',
                'error' => $th->getMessage()
            ];
        }
    }

    public function approveService($id)
    {
        try {
            DB::beginTransaction();
            $service = $this->serviceModel::findOrFail($id);

            $service->items->each(function ($item) {
                $customerId = $item->service->customer_id;
                $yarnCountId = $item->yarn_count_id;
                $requiredQuantity = $item->quantity;

                // Fetch customer stock
                $customerStock = CustomerStock::where('customer_id', $customerId)
                    ->where('yarn_count_id', $yarnCountId)
                    ->first();

                $customerAvailable = $customerStock?->quantity ?? 0;

                if ($customerStock) {
                    if ($customerAvailable >= $requiredQuantity) {
                        // Enough stock in customer stock
                        $customerStock->quantity -= $requiredQuantity;
                        $customerStock->save();
                    } else {
                        // Not enough customer stock — take what’s there, rest from Amyt
                        $fromAmyt = $requiredQuantity - $customerAvailable;

                        // Decrease all customer stock
                        $customerStock->quantity = 0;
                        $customerStock->save();

                        // Decrease from Amyt stock
                        $amytStock = AmytStock::where('yarn_count_id', $yarnCountId)->first();
                        if (!$amytStock || $amytStock->quantity < $fromAmyt) {
                            throw new \Exception("Insufficient total stock for yarn count ID: $yarnCountId");
                        }

                        $amytStock->quantity -= $fromAmyt;
                        $amytStock->save();
                    }
                } else {
                    // No customer stock at all — take all from Amyt
                    $amytStock = AmytStock::where('yarn_count_id', $yarnCountId)->first();
                    if (!$amytStock || $amytStock->quantity < $requiredQuantity) {
                        throw new \Exception("Insufficient total stock for yarn count ID: $yarnCountId");
                    }

                    $amytStock->quantity -= $requiredQuantity;
                    $amytStock->save();
                }
            });

            $service->status = AllStatic::ALL_STATIC['SERVICE_STATUS']['APPROVED']; // Assuming 1 means approved
            $service->save();
            DB::commit();
            return [
                'status' => 'success',
                'message' => 'Service approved successfully',
                'service' => $service
            ];
        } catch (\Throwable $th) {
            DB::rollBack();
            return [
                'status' => 'error',
                'message' => 'Failed to approve service',
                'error' => $th->getMessage()
            ];
        }
    }
    public function deleteService($id)
    {
        try {
            $result = $this->serviceModel::destroy($id);
            return [
                'status' => $result ? 'success' : 'error',
                'message' => $result ? 'Service deleted successfully' : 'Service not found'
            ];
        } catch (\Throwable $th) {
            return [
                'status' => 'error',
                'message' => $th->getMessage()
            ];
        }
    }

    public function getServiceItems($serviceId)
    {
        return $this->serviceModel::with('items')->findOrFail($serviceId);
    }
}
