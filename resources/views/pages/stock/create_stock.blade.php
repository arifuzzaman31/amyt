@extends('layout.app')

@section('title', 'Customer Stock | '.env('APP_NAME'))

@push('styles')
<style>
    /* Custom styles for better design */
    .layout-px-spacing {
        padding: 0 1.5rem;
    }
    
    .form-section-styled {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .form-section-styled h5 {
        color: #3b3f5c;
        font-weight: 600;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #e9ecef;
    }
    
    .form-group {
        margin-bottom: 1rem;
    }
    
    .form-group label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 0.5rem;
    }
    
    .btn-primary {
        background-color: #4e73df;
        border-color: #4e73df;
    }
    
    .btn-primary:hover {
        background-color: #2e59d9;
        border-color: #2653d4;
    }
    
    .card {
        border: none;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }
    
    .card-header {
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
    }
    
    .table th {
        border-top: none;
        font-weight: 600;
        color: #5a5c69;
    }
    
    .modal-header {
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
    }
    
    .modal-footer {
        background-color: #f8f9fc;
        border-top: 1px solid #e3e6f0;
    }
    
    .input-group-text {
        background-color: #eaecf4;
        border: 1px solid #d1d3e2;
    }
    
    .custom-file-label {
        overflow: hidden;
    }
    
    .custom-file-label::after {
        background-color: #eaecf4;
        color: #5a5c69;
        border-left: 1px solid #d1d3e2;
    }
    
    /* Fix for Select2 Bootstrap 4 theme */
    .select2-container--bootstrap4 .select2-selection--single {
        height: calc(1.5em + 0.75rem + 2px);
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        line-height: 1.5;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
    }
    
    .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
        padding-left: 0;
    }
    
    .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow {
        height: calc(1.5em + 0.75rem + 2px);
    }
    
    /* Empty state styling */
    .empty-state {
        padding: 3rem;
        text-align: center;
    }
    
    .empty-state i {
        font-size: 3rem;
        color: #d1d3e2;
        margin-bottom: 1rem;
    }
    
    .empty-state p {
        color: #858796;
        margin: 0;
    }
    
    /* Totals section styling */
    .totals-section {
        background-color: #f8f9fc;
        border-radius: 0.35rem;
        padding: 1rem;
    }
    
    .totals-section .form-group {
        margin-bottom: 0.75rem;
    }
    
    .totals-section hr {
        margin: 1rem 0;
        border-top: 1px solid #e3e6f0;
    }
    
    /* Button styling */
    .btn-block {
        display: block;
        width: 100%;
    }
    
    .btn-success {
        background-color: #1cc88a;
        border-color: #1cc88a;
    }
    
    .btn-success:hover {
        background-color: #17a673;
        border-color: #169b6b;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .layout-px-spacing {
            padding: 0 1rem;
        }
        
        .form-section-styled {
            padding: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="m-0 font-weight-bold text-primary">Add Customer Stock</h4>
                        <a href="{{ route('customer-stock-list') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form id="customer-stock-form" action="{{ route('customer-stock-in') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <!-- Basic Information Section -->
                        <div class="form-section-styled">
                            <h5>Basic Information</h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="stock_date">Date <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="date" class="form-control" id="stock_date" name="in_date" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="customer_id">Customer <span class="text-danger">*</span></label>
                                        <select class="form-control" id="customer_id" name="customer_id" required>
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="challan_no">Challan No</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="challan_no" name="challan_no" readonly>
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" type="button" id="editChallanNo">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 edit-note">
                                                        <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                                      </svg>
                                                </button>
                                            </div>
                                        </div>
                                        <small class="form-text text-muted">Auto-generated but editable</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="document_link">Document</label>
                                        <div class="custom-file input-group">
                                            <input type="file" class="custom-file-input" id="document_link" name="document_link">
                                            <label class="custom-file-label" for="document_link">Choose file</label>
                                        </div>
                                        <small class="form-text text-muted mt-3">PDF, JPG, PNG up to 2MB</small>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="2" placeholder="Enter description for the customer stock"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Items Section -->
                        <div class="form-section-styled">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Items</h5>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addItemModal">
                                    <i class="fas fa-plus mr-1"></i> Add Item
                                </button>
                            </div>

                            <!-- Items Display Table -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="itemsDisplayTable" style="display: none;">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Yarn Count</th>
                                            <th>Quantity</th>
                                            <th>Unit Price</th>
                                            <th>Subtotal</th>
                                            <th width="50">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="itemsDisplayTableBody">
                                        <!-- Items will be displayed here -->
                                    </tbody>
                                </table>
                                <div id="noItemsMessage" class="empty-state">
                                    <i class="fas fa-box-open"></i>
                                    <p>No items added yet. Click "Add Item" to add items.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Summary Section -->
                        <div class="row mt-4" id="totalsSection" style="display: none;">
                            <div class="col-md-5 ml-auto">
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <h5 class="card-title mb-4">Order Summary</h5>
                                        
                                        <div class="form-group row mb-2">
                                            <label class="col-sm-5 col-form-label text-right">Subtotal:</label>
                                            <div class="col-sm-7">
                                                <input type="text" readonly class="form-control text-right" id="subtotal">
                                            </div>
                                        </div>
                                        
                                        <div class="form-group row mb-2">
                                            <label class="col-sm-5 col-form-label text-right">Discount:</label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    <input type="number" class="form-control form-control-sm" id="discount" name="discount" placeholder="0.00">
                                                    <select class="form-control form-control-sm col-5" id="discount_type" name="discount_type">
                                                        <option value="0">%</option>
                                                        <option value="1">Fixed</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group row mb-2">
                                            <label class="col-sm-5 col-form-label text-right">Discount Amount:</label>
                                            <div class="col-sm-7">
                                                <input type="text" readonly class="form-control text-right" id="discount_amount_display">
                                            </div>
                                        </div>
                                        
                                        <div class="form-group row mb-3">
                                            <label class="col-sm-5 col-form-label text-right"><strong>Total:</strong></label>
                                            <div class="col-sm-7">
                                                <input type="text" readonly class="form-control text-right font-weight-bold" id="grand_total_display">
                                            </div>
                                        </div>
                                        
                                        <hr>
                                        
                                        <div class="form-group row mb-2">
                                            <label class="col-sm-5 col-form-label text-right">Payment Status:</label>
                                            <div class="col-sm-7">
                                                <select class="form-control form-control-sm" id="payment_status" name="payment_status">
                                                    <option value="0">Due</option>
                                                    <option value="1">Paid</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group row mb-0">
                                            <label class="col-sm-5 col-form-label text-right">Status:</label>
                                            <div class="col-sm-7">
                                                <select class="form-control form-control-sm" id="status" name="status">
                                                    <option value="1">Approved</option>
                                                    <option value="3">Draft</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden input for items data -->
                        <input type="hidden" id="item_data_input" name="dataItem">

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end mt-4">
                            <button type="reset" class="btn btn-outline-secondary mr-2">
                                <i class="fas fa-redo mr-1"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save mr-1"></i> Save Stock
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Item Modal -->
<div class="modal fade" id="addItemModal" tabindex="-1" role="dialog" aria-labelledby="addItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addItemModalLabel">Add Items</h5>
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
                                <th width="50">Action</th>
                            </tr>
                        </thead>
                        <tbody id="itemsModalTableBody">
                            <!-- Items will be added here dynamically -->
                        </tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-info mt-2" id="addItemRow">
                    <i class="fas fa-plus mr-1"></i> Add Another Item
                </button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveItems">
                    <i class="fas fa-check mr-1"></i> Done
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    // Pass data from Laravel to JavaScript
    const yarnCounts = @json($yarnCounts ?? []);
</script>
<script src="{{ asset('admin-assets/customer-stock.js') }}"></script>
@endpush