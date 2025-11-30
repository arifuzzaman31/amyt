@extends('layout.app')
@section('title', 'Edit Challan | '.env('APP_NAME'))
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
            <form id="service-form" action="{{ route('service.update', $service->id) }}" method="POST"
                enctype="multipart/form-data" data-service='@json($service)'
                data-service-items='@json($service->items)'>
                @csrf
                @method('PUT')
                <input type="hidden" name="service_id" value="{{ $service->id }}">

                <!-- Hidden fields for form data -->
                <input type="hidden" id="dataItem" name="dataItem">
                <input type="hidden" id="total_amount" name="total_amount">
                <input type="hidden" id="payment_status" name="payment_status" value="{{ $service->payment_status }}">
                <input type="hidden" id="discount" name="discount" value="{{ $service->discount }}">
                <input type="hidden" id="discount_type" name="discount_type" value="{{ $service->discount_type }}">
                <input type="hidden" id="status" name="status" value="{{ $service->status }}">

                <!-- Service Details Card -->
                <div class="card mb-4">
                    <div class="card-header text-white">
                        <h5 class="mb-0">Edit Challan Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="basicFlatpickr">Challan Date</label>
                                    <input id="basicFlatpickr" type="date"
                                        class="form-control" name="service_date" placeholder="Select Challan Date..">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="invoice_no">Invoice No</label>
                                    <input type="text" class="form-control" id="invoice_no" name="invoice_no"
                                        value="{{ $service->invoice_no }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="customer_id">Select Customer</label>
                                    <select class="form-control" id="customer_id" name="customer_id" required>
                                        <option value=""></option>
                                        @if($service->customer_id)
                                        <option value="{{ $service->customer_id }}" selected>{{ $service->customer->name
                                            }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="document_link">Document (e.g., Invoice PDF)</label>
                                    <input type="file" class="form-control-file" id="document_link"
                                        name="document_link">
                                    @if($service->document_link)
                                    <div class="mt-2">
                                        <a href="{{ asset($service->document_link) }}" target="_blank"
                                            class="btn btn-sm btn-info">View Current Document</a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" placeholder="Description for the challan"
                                        rows="3" name="description">{{ $service->description }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Item List Card -->
                <div class="card mb-4">
                    <div class="card-header text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Item List</h5>
                        <button type="button" class="btn btn-info" id="openModalBtn">
                            Add Item
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="addedItemsSection" style="display: block;">
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
                                        @foreach($service->items as $item)
                                        <tr>
                                            <td>{{ $item->yarnCount->name }}</td>
                                            <td>{{ $item->color->name }}</td>
                                            <td>{{ $item->quantity }} {{ $item->unitAttr->name }}</td>
                                            <td>{{ $item->extra_quantity }}</td>
                                            <td>{{ $item->gross_weight }} {{ $item->weightAttr->name }}</td>
                                            <td>{{ $item->net_weight }} {{ $item->weightAttr->name }}</td>
                                            <td>{{ $item->bobin }}</td>
                                            <td>{{ $item->remark }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="font-weight-bold">
                                            <td colspan="2" class="text-right">Total:</td>
                                            <td id="totalQuantityCell">{{ $service->items->sum('quantity') }} {{
                                                $service->items->first()->unitAttr->name ?? '' }}</td>
                                            <td id="totalExtraQuantityCell">{{ $service->items->sum('extra_quantity') }}
                                            </td>
                                            <td id="totalGrossWeightCell">{{ $service->items->sum('gross_weight') }} {{
                                                $service->items->first()->weightAttr->name ?? '' }}</td>
                                            <td id="totalNetWeightCell">{{ $service->items->sum('net_weight') }} {{
                                                $service->items->first()->weightAttr->name ?? '' }}</td>
                                            <td id="totalBobinCell">{{ $service->items->sum('bobin') }}</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div id="noItemsSection" style="display: none;">
                            <p class="text-muted">No items added yet.</p>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit" class="btn btn-success btn-lg px-5">Update</button>
                    <a href="{{url('admin/challan-list')}}" type="button" class="btn btn-dark btn-lg px-5">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Item Modal -->
<div class="modal fade" id="addItemModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-default text-white">
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
                                <th>Available Stock</th>
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
@endsection

@push('script')
<script src="{{ asset('admin-assets/edit_challan.js') }}"></script>
@endpush