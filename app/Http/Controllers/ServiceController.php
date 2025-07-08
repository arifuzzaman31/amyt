<?php

namespace App\Http\Controllers;

use App\salesServices\Service;
use App\Services\SalesService;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function __construct(private SalesService $salesService) {}

    public function index()
    {
        return $this->salesService->getAllServices();
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_date' => 'required',
            'customer_id' => 'required'
        ]);
        // return response()->json( $request->all(), 201);
        return $this->salesService->createService($request->all());
    }

    public function show($id)
    {
        return $this->salesService->getServiceItems($id);
    }

    public function update(Request $request, $id)
    {
        $result = $this->salesService->updateService($id, $request->all());
        if (!$result) {
            return response()->json(['message' => 'Service not found'], 404);
        }
        return $result;
    }

    public function destroy($id)
    {
        $this->salesService->deleteService($id);
        return response()->noContent();
    }
}
