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
    $search = $data['search'] ?? '';
    
    $query = $this->serviceModel::with('customer:id,name,phone')
                ->where('status', $status);
    
    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->whereHas('customer', function ($customerQuery) use ($search) {
                $customerQuery->where('name', 'like', "%{$search}%")
                             ->orWhere('phone', 'like', "%{$search}%");
            })
            ->orWhere('invoice_no', 'like', "%{$search}%")
            ->orWhere('service_date', 'like', "%{$search}%");
        });
    }
    
    return $query->paginate($perPage);
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
                // Convert empty strings to null for integer fields
                $item['unit_attr_id'] = $item['unit_attr_id'] === '' ? null : $item['unit_attr_id'];
                $item['color_id'] = $item['color_id'] === '' ? null : $item['color_id'];
                $item['weight_attr_id'] = $item['weight_attr_id'] === '' ? null : $item['weight_attr_id'];
                $item['bobin'] = $item['bobin'] === '' ? null : $item['bobin'];
                
                $item['service_id'] = $service->id; // Associate the service item with the created service
                $this->serviceItemModel::create($item);
            }
        }
        DB::commit();
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
    }
}

public function updateService($id, array $data)
{
    $service = $this->serviceModel::find($id);
    if (!$service) {
        return [
            'status' => false,
            'message' => 'Service not found'
        ];
    }

    try {
        DB::beginTransaction();

        // FIX: Decode the JSON string from the form into a PHP array
        if (isset($data['dataItem'])) {
            $data['dataItem'] = json_decode($data['dataItem'], true);
        }

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

        // Now, this check will work correctly because $data['dataItem'] is an array
        if (isset($data['dataItem']) && is_array($data['dataItem'])) {
            // Get IDs of items sent from the frontend
            $incomingItemIds = collect($data['dataItem'])
                ->pluck('id')
                ->filter() // Removes nulls
                ->all();

            // Get IDs of items currently in the database for this service
            $existingItemIds = $service->items()->pluck('id')->all();

            // Find items that exist in DB but were not sent from frontend (i.e., deleted by user)
            $itemsToDelete = array_diff($existingItemIds, $incomingItemIds);

            if (!empty($itemsToDelete)) {
                $this->serviceItemModel::whereIn('id', $itemsToDelete)->delete();
            }

            // Loop through the items sent from the frontend
            foreach ($data['dataItem'] as $itemData) {
                if (isset($itemData['id']) && $itemData['id']) {
                    // Item has an ID, so it's an existing item. Update it.
                    $this->serviceItemModel::where('id', $itemData['id'])->update($itemData);
                } else {
                    // Item has no ID, so it's a new item. Create it.
                    $itemData['service_id'] = $service->id;
                    $this->serviceItemModel::create($itemData);
                }
            }
        } else {
            // This case now correctly handles if the user sends an empty array or no items at all
            $service->items()->delete();
        }

        // Unset dataItem so it doesn't try to save it to the services table
        unset($data['dataItem']);
        $service->update($data);
        DB::commit();

        return [
            'status' => true,
            'message' => 'Challan updated successfully',
            'service' => $service->load('items')
        ];
    } catch (\Throwable $th) {
        DB::rollBack();
        return [
            'status' => false,
            'message' => 'Failed to update challan',
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
        $service = $this->serviceModel::with([
            'items.yarnCount', 
            'items.unitAttr', 
            'items.color', 
            'items.weightAttr'
        ])->findOrFail($serviceId);
        
        // Get raw values for items (bypass accessors that format numbers)
        $service->items->each(function ($item) {
            $item->quantity = $item->getOriginal('quantity');
            $item->unit_price = $item->getOriginal('unit_price');
            $item->extra_quantity = $item->getOriginal('extra_quantity');
            $item->extra_quantity_price = $item->getOriginal('extra_quantity_price');
            $item->gross_weight = $item->getOriginal('gross_weight');
            $item->net_weight = $item->getOriginal('net_weight');
        });
        
        return $service;
    }

    public function convertToInvoice($id, array $data)
    {
        try {
            DB::beginTransaction();
            $service = $this->serviceModel::findOrFail($id);

            // Verify that the service is a challan (status = 3)
            if ($service->status != AllStatic::ALL_STATIC['SERVICE_STATUS']['DRAFT']) {
                return [
                    'status' => false,
                    'message' => 'Only challans (draft status) can be converted to invoices'
                ];
            }

            // Update service items with new quantity and price
            if (isset($data['dataItem']) && is_array($data['dataItem'])) {
                foreach ($data['dataItem'] as $itemData) {
                    if (isset($itemData['id']) && $itemData['id']) {
                        $existingItem = $this->serviceItemModel::find($itemData['id']);
                        if ($existingItem) {
                            // Update only quantity and price fields
                            $existingItem->update([
                                'quantity' => $itemData['quantity'],
                                'unit_price' => $itemData['unit_price'],
                                'extra_quantity' => $itemData['extra_quantity'] ?? 0,
                                'extra_quantity_price' => $itemData['extra_quantity_price'] ?? 0,
                            ]);
                        }
                    }
                }
            }

            // Change status from challan (3) to invoice (1)
            $service->status = AllStatic::ALL_STATIC['SERVICE_STATUS']['APPROVED'];
            $service->save();

            DB::commit();
            return [
                'status' => true,
                'message' => 'Challan converted to invoice successfully',
                'service' => $service->load('items')
            ];
        } catch (\Throwable $th) {
            DB::rollBack();
            return [
                'status' => false,
                'message' => 'Failed to convert challan to invoice',
                'error' => $th->getMessage()
            ];
        }
    }
}
