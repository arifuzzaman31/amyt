@extends('layout.app')
@section('title', 'Create Service | '.env('APP_NAME'))
@push('style')
<style>
.modal-dialog {
    max-width: 90%;
  }
</style>
@endpush
@section('content')
<div class="container-fluid">
    <div class="row layout-top-spacing" id="cancel-row">
        <div class="col-12">
            <form id="service-form" action="{{ route('service.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- Service Details Card -->
                <div class="card mb-4">
                    <div class="card-header text-white">
                        <h5 class="mb-0">Service Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="basicFlatpickr">Service Date</label>
                                    <input id="basicFlatpickr" type="date" class="form-control" name="service_date" placeholder="Select Challan Date..">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="invoice_no">Invoice No</label>
                                    <input type="text" class="form-control" id="invoice_no" name="invoice_no">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="customer_id">Select Customer</label>
                                    <select class="form-control" id="customer_id" name="customer_id" required>
                                        <option value=""></option>
                                    </select>
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
                                    <textarea class="form-control" placeholder="Description for the service order" rows="3" name="description"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Item List Card -->
                <div class="card mb-4">
                    <div class="card-header text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Item List</h5>
                        <button type="button" class="btn btn-light" id="openModalBtn">
                            Add Item
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="addedItemsSection" style="display: none;">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Yarn Count</th>
                                            <th>Color</th>
                                            <th>Quantity</th>
                                            <th>Extra Quantity</th>
                                            <th>Gross Weight</th>
                                            <th>Net Weight</th>
                                            <th>Bobin</th>
                                            <th>Remark</th>
                                        </tr>
                                    </thead>
                                    <tbody id="addedItemsTableBody">
                                        <!-- Items will be added here dynamically -->
                                    </tbody>
                                    <tfoot>
                                        <tr class="font-weight-bold">
                                            <td colspan="2" class="text-right">Total:</td>
                                            <td id="totalQuantityCell">0</td>
                                            <td id="totalExtraQuantityCell">0</td>
                                            <td id="totalGrossWeightCell">0</td>
                                            <td id="totalNetWeightCell">0</td>
                                            <td id="totalBobinCell">0</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div id="noItemsSection">
                            <p class="text-muted">No items added yet.</p>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="text-center mb-4">
                    <button type="submit" class="btn btn-success btn-lg px-5">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Item Modal -->
<div class="modal fade" id="addItemModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header text-white">
                <h5 class="modal-title">Add/Edit Items</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Yarn Count</th>
                                <th>Color</th>
                                <th>Quantity</th>
                                <th>Extra Quantity</th>
                                <th>Gross Weight</th>
                                <th>Net Weight</th>
                                <th>Bobin</th>
                                <th>Remark</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="itemsTableBody">
                            <!-- Items will be added here dynamically -->
                        </tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-info mt-2" id="addItemBtn">
                    Add Another Item
                </button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveItemsBtn">Done</button>
            </div>
        </div>
    </div>
</div>

<!-- Hidden fields for form data -->
<input type="hidden" id="dataItem" name="dataItem">
<input type="hidden" id="total_amount" name="total_amount">
<input type="hidden" id="payment_status" name="payment_status" value="0">
<input type="hidden" id="discount" name="discount" value="0">
<input type="hidden" id="discount_type" name="discount_type" value="0">
<input type="hidden" id="status" name="status" value="0">
@endsection
@push('script')
<script src="{{ asset('admin-assets/challan.js') }}"></script>
@endpush