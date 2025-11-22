<?php

namespace App\Http\Controllers;

use App\Services\YarnService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class YarnController extends Controller
{
    public function __construct(private YarnService $yarnService) {}

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('limit') ?? 5; // Default to 10 items per page
        $relations = $request->query('relation', []); // this will be an array
        $isPaginate = $request->query('isPaginate', 'yes'); // this will be an array
        $query = [
            'relations' => $relations,
            'isPaginate' => $isPaginate == 'yes' ? true : false, // Convert to boolean
        ];
        $yarns = $this->yarnService->getAllPaginated((int) $perPage, $query);
        return response()->json($yarns);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        // Basic validation, adjust as per your Yarn model's requirements
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:yarn_counts,name', // Corrected table name for unique rule
            'count' => 'nullable|string|max:50', // Example: yarn count like 20s, 30s
            'type' => 'nullable|string|max:100', // Example: Cotton, Polyester, Blend
            // Add other fields that your Yarn model might have
        ]);

        $yarn = $this->yarnService->create($validatedData);
        cache()->forget('yarn_count_list'); // Clear cache if you are caching yarns
        return response()->json($yarn, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $yarn = $this->yarnService->find($id);
        if (!$yarn) {
            return response()->json(['message' => 'Yarn not found'], Response::HTTP_NOT_FOUND);
        }
        return response()->json($yarn);
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
        $yarn = $this->yarnService->find($id);
        if (!$yarn) {
            return response()->json(['message' => 'Yarn not found'], Response::HTTP_NOT_FOUND);
        }

        // Basic validation, adjust as per your Yarn model's requirements
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255|unique:yarn_counts,name,' . $id, // Corrected table name
            'count' => 'nullable|string|max:50',
            'type' => 'nullable|string|max:100',
            // Add other fields
        ]);

        $updatedYarn = $this->yarnService->update($id, $validatedData);
        cache()->forget('yarn_count_list');
        return response()->json($updatedYarn);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        if ($this->yarnService->delete($id)) {
            cache()->forget('yarn_count_list');
            return response()->json(null, Response::HTTP_NO_CONTENT);
        }
        return response()->json(['message' => 'Yarn not found or delete failed'], Response::HTTP_NOT_FOUND);
    }

    /**
     * Get all yarns without pagination (for dropdowns, etc.).
     *
     * @return JsonResponse
     */
    public function allYarns(): JsonResponse
    {
        $yarns = Cache::remember('yarn_count_list', 60*3, function () {
            return $this->yarnService->getAll();
        });
        return response()->json($yarns);
    }
}
