<?php

namespace App\Services;

use App\Models\Purchase; // Assuming a Purchase model exists or will be created
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PurchaseService
{
    /**
     * Get all purchases.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll()
    {
        return Purchase::with('supplier', 'items.yarn')->latest()->get();
    }

    /**
     * Create a new purchase.
     *
     * @param array $data
     * @return Purchase
     */
    public function create(array $data): Purchase
    {
        $purchaseItemsData = [];
        if (isset($data['dataItem'])) {
            $purchaseItemsData = is_string($data['dataItem']) ? json_decode($data['dataItem'], true) : $data['dataItem'];
            unset($data['dataItem']); // Remove from main purchase data
        }

        $purchaseData = $data;

        if (isset($data['document_file']) && $data['document_file'] instanceof \Illuminate\Http\UploadedFile) {
            $purchaseData['document_path'] = $this->storeFile($data['document_file'], 'purchases/documents');
        }
        // Remove the file object itself, as we only want to store the path
        unset($purchaseData['document_file']);


        if (isset($data['image_file']) && $data['image_file'] instanceof \Illuminate\Http\UploadedFile) {
            $purchaseData['image_path'] = $this->storeFile($data['image_file'], 'purchases/images');
        }
        // Remove the file object itself
        unset($purchaseData['image_file']);

        // Remove document_link if it's still being passed, to avoid conflict if schema has it
        unset($purchaseData['document_link']);

        if (!isset($purchaseData['discount_type'])) {
            $purchaseData['discount_type'] = null;
        }
        $purchase = new Purchase();
        $purchase->fill($purchaseData);
        $purchase->save();

        if (!empty($purchaseItemsData) && is_array($purchaseItemsData)) {
            foreach ($purchaseItemsData as $itemData) {
                $item = new \App\Models\PurchaseItem($itemData);
                $purchase->items()->save($item);
            }
        }
        $purchase->load('items.yarn'); // Eager load items after creation
        return $purchase;
    }

    /**
     * Find a purchase by its ID.
     *
     * @param int $id
     * @return Purchase|null
     */
    public function find(int $id): ?Purchase
    {
        return Purchase::with('supplier', 'items.yarn')->find($id);
    }

    /**
     * Update an existing purchase.
     *
     * @param int $id
     * @param array $data
     * @return Purchase|null
     */
    public function update(int $id, array $data): ?Purchase
    {
        $purchase = $this->find($id);
        if (!$purchase) {
            return null;
        }

        $updateData = $data;

        if (isset($data['document_file']) && $data['document_file'] instanceof \Illuminate\Http\UploadedFile) {
            // Delete old file if it exists and a new one is uploaded
            if ($purchase->document_path) {
                Storage::disk('public')->delete($purchase->document_path);
            }
            $updateData['document_path'] = $this->storeFile($data['document_file'], 'purchases/documents');
            unset($updateData['document_file']);
        }

        if (isset($data['image_file']) && $data['image_file'] instanceof \Illuminate\Http\UploadedFile) {
            // Delete old file if it exists and a new one is uploaded
            if ($purchase->image_path) {
                Storage::disk('public')->delete($purchase->image_path);
            }
            $updateData['image_path'] = $this->storeFile($data['image_file'], 'purchases/images');
        }
        // Remove the file object itself
        unset($updateData['image_file']);
        
        // Remove document_link if it's still being passed
        unset($updateData['document_link']);

        // Handle purchase items update
        if (isset($data['dataItem'])) {
            $purchaseItemsData = is_string($data['dataItem']) ? json_decode($data['dataItem'], true) : $data['dataItem'];
            unset($updateData['dataItem']); // Remove from main purchase data

            // Simple approach: delete old items and add new ones
            // For more complex scenarios, you might want to update existing items, delete removed ones, add new ones
            $purchase->items()->delete();
            if (!empty($purchaseItemsData) && is_array($purchaseItemsData)) {
                foreach ($purchaseItemsData as $itemData) {
                    $item = new \App\Models\PurchaseItem($itemData);
                    $purchase->items()->save($item);
                }
            }
        }

        $purchase->fill($updateData);
        $purchase->save();

        return $purchase;
    }

    /**
     * Delete a purchase by its ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $purchase = $this->find($id);
        if ($purchase) {
            // Delete associated files
            if ($purchase->document_path) {
                Storage::disk('public')->delete($purchase->document_path);
            }
            if ($purchase->image_path) {
                Storage::disk('public')->delete($purchase->image_path);
            }
            return $purchase->delete();
        }
        return false;
    }

    /**
     * Store the uploaded file.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $directory
     * @return string|false
     */
    protected function storeFile(\Illuminate\Http\UploadedFile $file, string $directory)
    {
        if (!$file->isValid()) {
            return false;
        }
        $fileName = Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();
        return $file->storeAs($directory, $fileName, 'public');
    }
}