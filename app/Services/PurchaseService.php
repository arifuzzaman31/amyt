<?php

namespace App\Services;

use App\Models\AmytStock;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        try {
            //code...
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
            DB::beginTransaction();
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
            //if status approved, and payment_status is 1 then quantity add to amyt_stock
            if ($purchase->status == 1 && $purchase->payment_status == 1) {
                $purchase->items->each(function ($item) {
                    $stock = AmytStock::where('yarn_count_id', $item->yarn_count_id)->first();
                    if ($stock) {
                        $stock->incrementQuantity($item->quantity);
                    } else {
                        // If stock does not exist, create it
                        AmytStock::create([
                            'yarn_count_id' => $item->yarn_count_id,
                            'quantity' => $item->quantity,
                        ]);
                    }
                });
            }
            DB::commit();
            return $purchase;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
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
            //$purchase->items()->delete();
            $purchase->items()->detach(); // detach all items first
            if (!empty($purchaseItemsData) && is_array($purchaseItemsData)) {
                foreach ($purchaseItemsData as $itemData) {
                    $item = new \App\Models\PurchaseItem($itemData);
                    $purchase->items()->save($item);
                }
            }
        }

        if ($purchase->status == 1 && $purchase->payment_status == 1) {
            // If status is approved and payment is done, we need to decrement stock
            $purchase->items->each(function ($item) {
                $stock = AmytStock::where('yarn_count_id', $item->yarn_count_id)->first();
                if ($stock) {
                    $stock->decrementQuantity($item->quantity);
                }
            });
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
