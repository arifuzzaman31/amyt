<?php

namespace App\Services;

use App\CustomConst\AllStatic;
use App\Models\Service;
use App\Models\ServiceItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SalesService
{
    public function __construct(protected Service $serviceModel,protected ServiceItem $serviceItemModel){}

    public function getAllServices()
    {
        return $this->serviceModel::with('customer:id,name')->get();
    }

    public function createService(array $data)
    {
        try {
            DB::beginTransaction();
            if (isset($data['document_link'])) {
                $data['document_link'] = storeFile($data['document_link'], 'services/documents');
            }
            $data['dataItem'] = json_decode($data['dataItem'], true);
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

            if (isset($data['document_link']) && $data['document_link'] instanceof \Illuminate\Http\UploadedFile) {
                if (isset($service->document_link) && $service->document_link) {
                    $previousFile = str_replace('/storage/', '', $service->document_link);
                    $filePath = Storage::disk('public')->path($previousFile);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
                $data['document_link'] = storeFile($data['document_link'], 'services/documents');
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
            // decrease from auth user yarn count
            
            $service->status = AllStatic::PURCHASE_STATUS_APPROVED ; // Assuming 1 means approved
            $service->save();
            return [
                'status' => true,
                'message' => 'Service approved successfully',
                'service' => $service
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
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
                'status' => $result ? true : false,
                'message' => $result ? 'Service deleted successfully' : 'Service not found'
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => $th->getMessage()
            ];
        }
    }

    public function getServiceItems($serviceId)
    {
        return $this->serviceModel::with('items')->findOrFail($serviceId);
    }
}
