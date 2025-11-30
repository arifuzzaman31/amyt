@extends('layout.app')

@section('content')
<div class="row layout-top-spacing">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- Printable Content -->
                <div id="printableArea">
                    <!-- Company Header -->
                    <div class="text-center mb-4">
                        <h3 class="font-weight-bold">AL - MAKKAH YARN THREAD</h3>
                        <p class="mb-0">123 Textile Street, Industrial Area</p>
                        <p class="mb-0">City, Country - 12345</p>
                        <p class="mb-0">Phone: +1234567890 | Email: info@almakkahyarn.com</p>
                    </div>
                    
                    <!-- Purchase Title -->
                    <div class="text-center mb-4">
                        <h4 class="font-weight-bold">PURCHASE ORDER</h4>
                    </div>
                    
                    <!-- Purchase Details -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p><strong>Supplier:</strong> {{ $purchase->supplier->name ?? 'N/A' }}</p>
                            <p><strong>Purchase Date:</strong> {{ $purchase->purchase_date->format('d M, Y') }}</p>
                        </div>
                        <div class="col-md-6 text-right">
                            <p><strong>Challan No:</strong> {{ $purchase->challan_no ?? 'N/A' }}</p>
                            <p><strong>Payment Status:</strong> 
                                @if($purchase->payment_status == 0)
                                    <span class="badge badge-warning">Due</span>
                                @elseif($purchase->payment_status == 1)
                                    <span class="badge badge-success">Paid</span>
                                @elseif($purchase->payment_status == 2)
                                    <span class="badge badge-info">Refunded</span>
                                @endif
                            </p>
                            <p><strong>Status:</strong> 
                                @if($purchase->status == 0)
                                    <span class="badge badge-secondary">Pending</span>
                                @elseif($purchase->status == 1)
                                    <span class="badge badge-success">Approved</span>
                                @elseif($purchase->status == 2)
                                    <span class="badge badge-danger">Rejected</span>
                                @elseif($purchase->status == 3)
                                    <span class="badge badge-info">Draft</span>
                                @elseif($purchase->status == 4)
                                    <span class="badge badge-primary">Closed</span>
                                @endif
                            </p>
                            <p><strong>Is Stocked:</strong> 
                                @if($purchase->is_stocked == 0)
                                    <span class="badge badge-warning">Not Yet</span>
                                @else
                                    <span class="badge badge-success">Stocked</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <!-- Items Table -->
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Sl No.</th>
                                    <th>Yarn Count</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($purchase->items as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->yarn->name ?? 'N/A' }}</td>
                                        <td>{{ number_format($item->quantity, 2) }}</td>
                                        <td>{{ number_format($item->unit_price, 2) }}</td>
                                        <td class="text-right">{{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No items found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-right font-weight-bold">Subtotal:</td>
                                    <td class="text-right font-weight-bold">
                                        {{ number_format($purchase->items->sum(function($item) { return $item->quantity * $item->unit_price; }), 2) }}
                                    </td>
                                </tr>
                                @if($purchase->discount > 0)
                                <tr>
                                    <td colspan="4" class="text-right">Discount 
                                        @if($purchase->discount_type == 0)
                                            ({{ $purchase->discount }}%)
                                        @endif:
                                    </td>
                                    <td class="text-right">
                                        @if($purchase->discount_type == 0)
                                            {{ number_format($purchase->items->sum(function($item) { return $item->quantity * $item->unit_price; }) * $purchase->discount / 100, 2) }}
                                        @else
                                            {{ number_format($purchase->discount, 2) }}
                                        @endif
                                    </td>
                                </tr>
                                @endif
                                <tr class="table-active">
                                    <td colspan="4" class="text-right font-weight-bold h5">Total Amount:</td>
                                    <td class="text-right font-weight-bold h5">
                                        {{ number_format($purchase->total_amount, 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <!-- Additional Information -->
                    @if($purchase->description || $purchase->additional_info)
                        <div class="row mb-4">
                            <div class="col-md-6">
                                @if($purchase->description)
                                    <h6>Description:</h6>
                                    <p>{{ $purchase->description }}</p>
                                @endif
                            </div>
                            <div class="col-md-6">
                                @if($purchase->additional_info)
                                    <h6>Additional Information:</h6>
                                    <p>{{ $purchase->additional_info }}</p>
                                @endif
                            </div>
                        </div>
                    @endif
                    
                    <!-- Signature Section -->
                    <div class="row mt-5">
                        <div class="col-md-6 text-center">
                            <p>Supplier Signature</p>
                            <div class="border-bottom pt-5"></div>
                        </div>
                        <div class="col-md-6 text-center">
                            <p>For Al - Makkah Yarn Thread</p>
                            <div class="border-bottom pt-5"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Documents -->
                @if($purchase->document_path || $purchase->image_path)
                    <div class="mb-4 mt-4">
                        <h6>Attached Files:</h6>
                        @if($purchase->document_path)
                            <a href="{{ asset('storage/' . $purchase->document_path) }}" target="_blank" class="btn btn-sm btn-primary mr-2">
                                <i class="fas fa-file-pdf"></i> View Document
                            </a>
                        @endif
                        @if($purchase->image_path)
                            <a href="{{ asset('storage/' . $purchase->image_path) }}" target="_blank" class="btn btn-sm btn-info">
                                <i class="fas fa-image"></i> View Image
                            </a>
                        @endif
                    </div>
                @endif
                
                <!-- Action Buttons -->
                <div class="row mt-4">
                    <div class="col-12 text-center">
                        <a href="{{ url('admin/purchase-list') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                        <button class="btn btn-success" onclick="printPurchase()">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style media="print">
    /* Hide everything except the printable area when printing */
    body * {
        visibility: hidden;
    }
    
    #printableArea, #printableArea * {
        visibility: visible;
    }
    
    #printableArea {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    
    /* Adjust table for print */
    @media print {
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table th, .table td {
            border: 1px solid #000;
            padding: 8px;
        }
        
        .table thead th {
            background-color: #f2f2f2 !important;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
        }
        
        .badge {
            border: 1px solid #000;
            padding: 2px 6px;
        }
        
        .border-bottom {
            border-bottom: 1px solid #000 !important;
        }
    }
</style>
@endpush

@push('script')
<script>
function printPurchase() {
    // Create a new window for printing
    const printWindow = window.open('', '_blank');
    
    // Get the printable content
    const printableContent = document.getElementById('printableArea').innerHTML;
    
    // Create a complete HTML document for printing
    const printDocument = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>Purchase Order Print</title>
            <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    padding: 20px;
                }
                .table {
                    width: 100%;
                    border-collapse: collapse;
                }
                .table th, .table td {
                    border: 1px solid #000;
                    padding: 8px;
                }
                .table thead th {
                    background-color: #f2f2f2;
                }
                .badge {
                    border: 1px solid #000;
                    padding: 2px 6px;
                    font-weight: normal;
                }
                .border-bottom {
                    border-bottom: 1px solid #000;
                }
                @media print {
                    body {
                        margin: 0;
                        padding: 15px;
                    }
                    .table {
                        page-break-inside: avoid;
                    }
                    tr {
                        page-break-inside: avoid;
                    }
                }
            </style>
        </head>
        <body>
            ${printableContent}
        </body>
        </html>
    `;
    
    // Write the content to the new window
    printWindow.document.write(printDocument);
    printWindow.document.close();
    
    // Wait for the content to load before printing
    printWindow.onload = function() {
        printWindow.print();
        // Optionally close the window after printing
        // printWindow.close();
    };
}
</script>
@endpush

