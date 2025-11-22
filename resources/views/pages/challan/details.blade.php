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
                    
                    <!-- Challan Title -->
                    <div class="text-center mb-4">
                        <h4 class="font-weight-bold">CHALLAN</h4>
                    </div>
                    
                    <!-- Challan Details -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p><strong>Customer:</strong> {{ $service->customer->name ?? 'N/A' }}</p>
                            <p><strong>Service Date:</strong> {{ $service->service_date->format('d M, Y') }}</p>
                        </div>
                        <div class="col-md-6 text-right">
                            <p><strong>Challan No:</strong> {{ $service->invoice_no ?? 'N/A' }}</p>
                            <p><strong>Payment Status:</strong> 
                                @if($service->payment_status == 0)
                                    <span class="badge badge-warning">Due</span>
                                @elseif($service->payment_status == 1)
                                    <span class="badge badge-success">Paid</span>
                                @elseif($service->payment_status == 2)
                                    <span class="badge badge-info">Refunded</span>
                                @endif
                            </p>
                            <p><strong>Status:</strong> 
                                @if($service->status == 0)
                                    <span class="badge badge-secondary">Pending</span>
                                @elseif($service->status == 1)
                                    <span class="badge badge-success">Approved</span>
                                @elseif($service->status == 2)
                                    <span class="badge badge-danger">Rejected</span>
                                @elseif($service->status == 3)
                                    <span class="badge badge-info">Draft</span>
                                @elseif($service->status == 4)
                                    <span class="badge badge-primary">Closed</span>
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
                                    <th>Color</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Gross Weight</th>
                                    <th>Net Weight</th>
                                    <th>Bobin</th>
                                    <th>Remark</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($service->items as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->yarnCount->name ?? 'N/A' }}</td>
                                        <td>{{ $item->color->name ?? 'N/A' }}</td>
                                        <td>{{ $item->quantity }} {{ $item->unitAttr->name ?? '' }}</td>
                                        <td>{{ number_format($item->unit_price, 2) }}</td>
                                        <td>{{ $item->gross_weight }} {{ $item->weightAttr->name ?? '' }}</td>
                                        <td>{{ $item->net_weight }} {{ $item->weightAttr->name ?? '' }}</td>
                                        <td>{{ $item->bobin ?? 'N/A' }}</td>
                                        <td>{{ $item->remark ?? 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No items found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-right font-weight-bold">Total:</td>
                                    <td class="font-weight-bold">
                                        {{ $service->items->sum('quantity') }} 
                                        {{ $service->items->first()->unitAttr->name ?? '' }}
                                    </td>
                                    <td></td>
                                    <td class="font-weight-bold">
                                        {{ $service->items->sum('gross_weight') }} 
                                        {{ $service->items->first()->weightAttr->name ?? '' }}
                                    </td>
                                    <td class="font-weight-bold">
                                        {{ $service->items->sum('net_weight') }} 
                                        {{ $service->items->first()->weightAttr->name ?? '' }}
                                    </td>
                                    <td class="font-weight-bold">{{ $service->items->sum('bobin') }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <!-- Additional Information -->
                    @if($service->description || $service->addition_info)
                        <div class="row mb-4">
                            <div class="col-md-6">
                                @if($service->description)
                                    <h6>Description:</h6>
                                    <p>{{ $service->description }}</p>
                                @endif
                            </div>
                            <div class="col-md-6">
                                @if($service->addition_info)
                                    <h6>Additional Information:</h6>
                                    <p>{{ $service->addition_info }}</p>
                                @endif
                            </div>
                        </div>
                    @endif
                    
                    <!-- Signature Section -->
                    <div class="row mt-5">
                        <div class="col-md-6 text-center">
                            <p>Receiver Signature</p>
                            <div class="border-bottom pt-5"></div>
                        </div>
                        <div class="col-md-6 text-center">
                            <p>For Al - Makkah Yarn Thread</p>
                            <div class="border-bottom pt-5"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Document Link -->
                @if($service->document_link)
                    <div class="mb-4">
                        <h6>Document:</h6>
                        <a href="{{$service->document_link }}" target="_blank" class="btn btn-sm btn-primary">
                            <i class="fas fa-file-pdf"></i> View Document
                        </a>
                    </div>
                @endif
                
                <!-- Action Buttons -->
                <div class="row mt-4">
                    <div class="col-12 text-center">
                        <a href="#" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                        <a href="#" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <button class="btn btn-success" onclick="printChallan()">
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
function printChallan() {
    // Create a new window for printing
    const printWindow = window.open('', '_blank');
    
    // Get the printable content
    const printableContent = document.getElementById('printableArea').innerHTML;
    
    // Create a complete HTML document for printing
    const printDocument = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>Challan Print</title>
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