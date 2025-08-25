@extends('layout.app')
@section('content')
<div class="row layout-top-spacing" id="cancel-row">
    <div class="col-12">
        <form id="purchase-form">
            @csrf
            <!-- Purchase Order Details Section -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">Purchase Order Details</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="purchase_date">Purchase Date</label>
                                <input id="basicFlatpickr" name="purchase_date" value="2019-09-04" class="form-control flatpickr flatpickr-input active" type="text" placeholder="Select Purchase Date..">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="supplier_id">Select Supplier</label>
                                <select class="form-control" id="supplier_id" name="supplier_id" required>
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="challan_no">Challan No</label>
                                <input type="text" class="form-control" id="challan_no" name="challan_no">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="document_link">Document (e.g., Invoice PDF)</label>
                                <input type="file" class="form-control-file" id="document_link" name="document_link">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" placeholder="Description for the purchase order" rows="3" name="description"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Item List Section -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">Item List</h5>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addItemModal">
                            Add Item
                        </button>
                    </div>

                    <!-- Add Item Modal -->
                    <div class="modal fade" id="addItemModal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Add/Edit Items</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover" id="itemsTable">
                                            <thead>
                                                <tr>
                                                    <th>Yarn Count</th>
                                                    <th>Quantity</th>
                                                    <th>Unit Price</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Items will be added here dynamically -->
                                            </tbody>
                                        </table>
                                    </div>
                                    <button type="button" class="btn btn-info mt-2" id="addItemRow">Add Another Item</button>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary" data-dismiss="modal">Done</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Items Display Section -->
                    <div id="addedItemsSection" style="display: none;">
                        <h6 class="mb-3">Added Items:</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped mb-4">
                                <thead>
                                    <tr>
                                        <th>Yarn Count</th>
                                        <th>Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody id="addedItemsTable">
                                    <!-- Items will be displayed here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div id="noItemsSection">
                        <p class="text-muted">No items added yet.</p>
                    </div>

                    <!-- Totals and Status Section -->
                    <div id="totalsSection" style="display: none;">
                        <div class="row justify-content-end">
                            <div class="col-md-5">
                                <div class="form-group row">
                                    <label for="subtotal" class="col-sm-5 col-form-label text-right">Subtotal:</label>
                                    <div class="col-sm-7">
                                        <input type="text" readonly class="form-control text-right" id="subtotal">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="discount" class="col-sm-5 col-form-label text-right">Discount:</label>
                                    <div class="col-sm-7">
                                        <input type="number" class="form-control" id="discount" name="discount" placeholder="Discount value">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="discount_type" class="col-sm-5 col-form-label text-right">Discount Type:</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" id="discount_type" name="discount_type">
                                            <option value="">Select Type</option>
                                            <option value="0">Percentage (%)</option>
                                            <option value="1">Fixed Amount</option>
                                        </select>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <label for="discount_amount_display" class="col-sm-5 col-form-label text-right">Discount Amount:</label>
                                    <div class="col-sm-7">
                                        <input type="text" readonly class="form-control text-right" id="discount_amount_display">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="grand_total_display" class="col-sm-5 col-form-label text-right"><strong>Grand Total:</strong></label>
                                    <div class="col-sm-7">
                                        <input type="text" name="total_amount" readonly class="form-control text-right font-weight-bold" id="grand_total_display">
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <label for="payment_status" class="col-sm-5 col-form-label text-right">Payment Status:</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" id="payment_status" name="payment_status">
                                            <option value="0">Due</option>
                                            <option value="1">Paid</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="status" class="col-sm-5 col-form-label text-right">Order Status:</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" id="status" name="status">
                                            <option value="1">Approved</option>
                                            <option value="3">Draft</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="row">
                <div class="col-md-4 offset-md-4">
                    <button type="submit" class="btn btn-success btn-block">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    const yarnCounts = @json($yarnCounts);
</script>
@endsection
@push('script')
<script src="{{ asset('admin-assets/purchase-order.js') }}"></script>
@endpush