<?php

namespace App\Http\Controllers;

use App\salesServices\Service;
use App\Services\SalesService;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function __construct(private SalesService $salesService) {}

    public function index(Request $request)
    {
        return $this->salesService->getAllServices($request->all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_date' => 'required',
            'customer_id' => 'required'
        ]);
        // return response()->json($request->all());
        $result = $this->salesService->createService($request->all());
        return response()->json($result, $result['status'] ? 201 : 400);
    }

    public function show(Request $request, $id)
    {
        $service = $this->salesService->getServiceItems($id);
        
        // Return JSON if it's an API request
        if ($request->wantsJson() || $request->expectsJson()) {
            return response()->json($service);
        }
        
        // Return view for web requests
        return view('pages.challan.edit_challan', ['service' => $service]);
    }

    public function showDetails($id)
    {
        return view('pages.challan.details', [
            'service' => $this->salesService->getServiceItems($id)
        ]);
    }

    public function update(Request $request, $id)
    {
        $result = $this->salesService->updateService($id, $request->all());
        return response()->json($result, $result['status'] ? 201 : 400);
    }

    public function serviceStatus($id)
    {
        $response = $this->salesService->approveService($id);
        return response()->json($response, $response['status'] ? 200 : 400);
    }

    public function destroy($id)
    {
        $response = $this->salesService->deleteService($id);
        return response()->json($response, $response['status'] ? 200 : 400);
    }

    public function convertToInvoice(Request $request, $id)
    {
        $result = $this->salesService->convertToInvoice($id, $request->all());
        return response()->json($result, $result['status'] ? 200 : 400);
    }
}
