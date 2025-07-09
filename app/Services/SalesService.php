<?php

namespace App\Services;

use App\Models\Service;
use App\Models\ServiceItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SalesService
{
    public function __construct(protected Service $serviceModel,protected ServiceItem $serviceItemModel){}

    public function getAllServices()
    {
        return $this->serviceModel::all();
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

            $data['dataItem'] = json_decode($data['dataItem'], true);
            $service->update($data);

            if (isset($data['dataItem']) && is_array($data['dataItem'])) {
                // Delete existing items
                $this->serviceItemModel::where('service_id', $id)->delete();
                // Create new items
                foreach ($data['dataItem'] as $item) {
                    $item['service_id'] = $id; // Associate the service item with the updated service
                    $this->serviceItemModel::create($item);
                }
            }
            DB::commit();
            return $service->load('items'); // Load items relationship
        } catch (\Throwable $th) {
            DB::rollBack();
            return [
                'message' => 'Failed to update service',
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
        return $this->serviceItemModel::where('service_id', $serviceId)->get();
    }
}
