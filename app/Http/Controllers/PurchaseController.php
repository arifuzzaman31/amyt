<?php

namespace App\Http\Controllers;

use App\Services\PurchaseService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class PurchaseController extends Controller
{
    public function __construct(private PurchaseService $purchaseService) {}

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json($this->purchaseService->getAll());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id', // Assuming suppliers table
            'purchase_date' => 'required|date',
            'total_amount' => 'required|numeric',
            'document_file' => 'nullable|file|mimes:doc,docx,pdf|max:2048', // Example validation for doc/pdf
            'image_file' => 'nullable|file|image|max:2048' // Example validation for image
        ]);
        try {
            //code...
            $data = $request->all();
            
            if ($request->hasFile('document_file')) {
                $data['document_file'] = $request->file('document_file');
            }
            if ($request->hasFile('image_file')) {
                $data['image_file'] = $request->file('image_file');
            }
            
            $purchase = $this->purchaseService->create($data);
            // return response()->json(, Response::HTTP_CREATED);
            return response()->json(['message' => 'Purchase Created Successful!','data' => $purchase], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => $th->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $purchase = $this->purchaseService->find($id);
        if (!$purchase) {
            return response()->json(['message' => 'Purchase not found'], Response::HTTP_NOT_FOUND);
        }
        return response()->json($purchase);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'supplier_id' => 'sometimes|required|exists:suppliers,id',
            'purchase_date' => 'sometimes|required|date',
            'total_amount' => 'sometimes|required|numeric',
            'document_file' => 'nullable|file|mimes:doc,docx,pdf|max:2048',
            'image_file' => 'nullable|file|image|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('document_file')) {
            $data['document_file'] = $request->file('document_file');
        }
        if ($request->hasFile('image_file')) {
            $data['image_file'] = $request->file('image_file');
        }

        $purchase = $this->purchaseService->update($id, $data);

        if (!$purchase) {
            return response()->json(['message' => 'Purchase not found or update failed'], Response::HTTP_NOT_FOUND);
        }
        return response()->json($purchase);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        if ($this->purchaseService->delete($id)) {
            return response()->json(null, Response::HTTP_NO_CONTENT);
        }
        return response()->json(['message' => 'Purchase not found or delete failed'], Response::HTTP_NOT_FOUND);
    }
}
