<?php

namespace App\Services;

use App\Models\Yarn; // Assuming Yarn model exists at App\Models\Yarn
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class YarnService
{
    /**
     * Get all yarns with pagination.
     *
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAllPaginated(int $perPage = 3,$query = [])
    {
       $data = Yarn::latest();
         if (!empty($query['relations'])) {
            $relations = $query['relations'];
    
            if (in_array('customerStock', $relations)) {
                $data->with(['customerStock:id,yarn_count_id,quantity']);
            }
    
            if (in_array('amytStock', $relations)) {
                $data->with(['amytStock:id,yarn_count_id,quantity']);
            }
        }
        if (isset($query['isPaginate']) && $query['isPaginate'] == true) {
            return $data->paginate($perPage);
        }
        return $data->get();
    }

    /**
     * Get all yarns.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll()
    {
        return Yarn::latest()->get();
    }

    /**
     * Create a new yarn.
     *
     * @param array $data
     * @return Yarn
     */
    public function create(array $data): Yarn
    {
        // Add any specific logic for creating a yarn if needed
        // For example, handling specific fields or relationships
        return Yarn::create($data);
    }

    /**
     * Find a yarn by its ID.
     *
     * @param int $id
     * @return Yarn|null
     */
    public function find(int $id): ?Yarn
    {
        return Yarn::find($id);
    }

    /**
     * Update an existing yarn.
     *
     * @param int $id
     * @param array $data
     * @return Yarn|null
     */
    public function update(int $id, array $data): ?Yarn
    {
        $yarn = $this->find($id);
        if ($yarn) {
            // Add any specific logic for updating a yarn if needed
            $yarn->update($data);
            return $yarn;
        }
        return null;
    }

    /**
     * Delete a yarn by its ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $yarn = $this->find($id);
        if ($yarn) {
            return $yarn->delete();
        }
        return false;
    }
}