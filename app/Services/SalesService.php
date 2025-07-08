<?php

namespace App\Services;

use App\Models\Service;
use App\Models\ServiceItem;
use Illuminate\Support\Facades\DB;

class SalesService
{
    protected $serviceModel;
    protected $serviceItemModel;

    public function __construct(Service $serviceModel, ServiceItem $serviceItemModel)
    {
        $this->serviceModel = $serviceModel;
        $this->serviceItemModel = $serviceItemModel;
    }

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
            // Return the created service
            // If you want to return the service with its items, you can use:
            // return $this->serviceModel::with('items')->find($service->id);
            return response()->json([
                'status' => 'success',
                'message' => 'Service created successfully',
                'service' => $service->load('items') // Load items relationship
            ], 201);
            // Otherwise, just return the service itself
            // return $this->serviceModel::create($data);


        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to create service',
                'error' => $th->getMessage()
            ], 500);
            //throw $th;
        }
    }

    public function updateService($id, array $data)
    {
        $service = $this->serviceModel::findOrFail($id);
        $service->update($data);
        return $service;
    }

    public function deleteService($id)
    {
        return $this->serviceModel::destroy($id);
    }

    public function getServiceItems($serviceId)
    {
        return $this->serviceItemModel::where('service_id', $serviceId)->get();
    }
}
