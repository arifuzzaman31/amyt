@extends('layout.app')
@section('title', 'Dashboard')
@push('style')
<style>
    :root {
        --primary-color: #4e73df;
        --success-color: #1cc88a;
        --info-color: #36b9cc;
        --warning-color: #f6c23e;
        --danger-color: #e74a3b;
        --secondary-color: #858796;
    }
    
    body {
        background-color: #f8f9fc;
        font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }
    
    .sidebar {
        min-height: 100vh;
        background: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }
    
    .sidebar-brand {
        height: 4.375rem;
        text-decoration: none;
        font-size: 1rem;
        font-weight: 800;
        padding: 1.5rem 1rem;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 0.05rem;
        z-index: 1;
    }
    
    .sidebar-brand-icon {
        font-size: 2rem;
    }
    
    .sidebar-nav .nav-item {
        position: relative;
    }
    
    .sidebar-nav .nav-link {
        display: block;
        padding: 0.75rem 1rem;
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
    }
    
    .sidebar-nav .nav-link:hover {
        color: #fff;
        background-color: rgba(255, 255, 255, 0.1);
    }
    
    .sidebar-nav .nav-link.active {
        color: #fff;
        font-weight: 700;
    }
    
    .sidebar-nav .nav-link.active::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 3px;
        background-color: #fff;
    }
    
    .card {
        border: none;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        margin-bottom: 1.5rem;
        transition: transform 0.2s;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
    
    .card-header {
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
        font-weight: 700;
        color: var(--secondary-color);
    }
    
    .stats-card {
        border-left: 0.25rem solid;
    }
    
    .stats-card.primary {
        border-left-color: var(--primary-color);
    }
    
    .stats-card.success {
        border-left-color: var(--success-color);
    }
    
    .stats-card.info {
        border-left-color: var(--info-color);
    }
    
    .stats-card.warning {
        border-left-color: var(--warning-color);
    }
    
    .stats-card .card-body {
        padding: 1.25rem;
    }
    
    .stats-card .stats-icon {
        font-size: 2rem;
        color: #dddfeb;
    }
    
    .stats-card.primary .stats-icon {
        color: #dddfeb;
    }
    
    .stats-card.success .stats-icon {
        color: #1cc88a;
    }
    
    .stats-card.info .stats-icon {
        color: #36b9cc;
    }
    
    .stats-card.warning .stats-icon {
        color: #f6c23e;
    }
    
    .stats-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: #5a5c69;
    }
    
    .stats-label {
        font-size: 1rem;
        font-weight: 400;
        color: var(--secondary-color);
    }
    
    .table thead th {
        border-bottom: 2px solid #e3e6f0;
        font-weight: 700;
        color: var(--secondary-color);
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.05em;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9fc;
    }
    
    .badge {
        padding: 0.375rem 0.75rem;
        font-size: 0.75rem;
        font-weight: 700;
    }
    
    .progress {
        height: 0.5rem;
        background-color: #eaecf4;
    }
    
    .top-bar {
        background-color: #fff;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        height: 4.375rem;
    }
    
    .chart-area {
        position: relative;
        height: 320px;
        width: 100%;
    }
    
    @media (max-width: 768px) {
        .sidebar {
            position: fixed;
            top: 0;
            left: -100%;
            height: 100vh;
            z-index: 1000;
            transition: left 0.3s;
        }
        
        .sidebar.active {
            left: 0;
        }
    }
</style>
@endpush
@section('content')

<div class="statbox">
    <div class="widget-header">
        <!-- Dashboard Content -->
        <div class="container-fluid p-4">
            <!-- Statistics Cards -->
            <div class="row">
                <!-- Monthly Sales -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stats-card primary h-100">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <div class="stats-value">$45,231</div>
                                    <div class="stats-label">Monthly Sales</div>
                                    <div class="mt-2">
                                        <span class="text-success mr-2">
                                            <i class="fas fa-arrow-up"></i> 12.5%
                                        </span>
                                        <span class="text-muted">from last month</span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-dollar-sign stats-icon"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Total Customers -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stats-card success h-100">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <div class="stats-value">2,453</div>
                                    <div class="stats-label">Total Customers</div>
                                    <div class="mt-2">
                                        <span class="text-success mr-2">
                                            <i class="fas fa-arrow-up"></i> 8.2%
                                        </span>
                                        <span class="text-muted">from last month</span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-users stats-icon"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Total Orders -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stats-card info h-100">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <div class="stats-value">1,234</div>
                                    <div class="stats-label">Total Orders</div>
                                    <div class="mt-2">
                                        <span class="text-danger mr-2">
                                            <i class="fas fa-arrow-down"></i> 3.1%
                                        </span>
                                        <span class="text-muted">from last month</span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-shopping-cart stats-icon"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Total Products -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stats-card warning h-100">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <div class="stats-value">456</div>
                                    <div class="stats-label">Total Products</div>
                                    <div class="mt-2">
                                        <span class="text-success mr-2">
                                            <i class="fas fa-arrow-up"></i> 5.4%
                                        </span>
                                        <span class="text-muted">from last month</span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-box stats-icon"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Charts Row -->
            <div class="row">

                <div class="col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-chart-one">
                        <div class="widget-heading">
                            <h5 class="">Revenue</h5>
                            <ul class="tabs tab-pills">
                                <li><a href="javascript:void(0);" id="tb_1" class="tabmenu">Monthly</a></li>
                            </ul>
                        </div>

                        <div class="widget-content">
                            <div class="tabs tab-content">
                                <div id="content_1" class="tabcontent"> 
                                    <div id="revenueMonthly"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-chart-two">
                        <div class="widget-heading">
                            <h5 class="">Sales by Category</h5>
                        </div>
                        <div class="widget-content">
                            <div id="chart-2" class=""></div>
                        </div>
                    </div>
                </div></div>
            <!-- Recent Orders and Top Products -->
            <div class="row">
                <!-- Recent Orders -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-table mr-1"></i>
                            Recent Orders
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Customer</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>#12345</td>
                                            <td>John Doe</td>
                                            <td>$234.00</td>
                                            <td><span class="badge badge-success">Completed</span></td>
                                        </tr>
                                        <tr>
                                            <td>#12346</td>
                                            <td>Jane Smith</td>
                                            <td>$567.00</td>
                                            <td><span class="badge badge-warning">Pending</span></td>
                                        </tr>
                                        <tr>
                                            <td>#12347</td>
                                            <td>Mike Johnson</td>
                                            <td>$123.00</td>
                                            <td><span class="badge badge-info">Processing</span></td>
                                        </tr>
                                        <tr>
                                            <td>#12348</td>
                                            <td>Sarah Williams</td>
                                            <td>$890.00</td>
                                            <td><span class="badge badge-success">Completed</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Top Products -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-star mr-1"></i>
                            Top Products
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Sales</th>
                                            <th>Revenue</th>
                                            <th>Trend</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Laptop Pro</td>
                                            <td>234</td>
                                            <td>$46,800</td>
                                            <td><i class="fas fa-arrow-up text-success"></i></td>
                                        </tr>
                                        <tr>
                                            <td>Smartphone X</td>
                                            <td>189</td>
                                            <td>$37,800</td>
                                            <td><i class="fas fa-arrow-up text-success"></i></td>
                                        </tr>
                                        <tr>
                                            <td>Tablet Plus</td>
                                            <td>156</td>
                                            <td>$23,400</td>
                                            <td><i class="fas fa-arrow-down text-danger"></i></td>
                                        </tr>
                                        <tr>
                                            <td>Headphones Pro</td>
                                            <td>123</td>
                                            <td>$12,300</td>
                                            <td><i class="fas fa-arrow-up text-success"></i></td>
                                        </tr>
                                        <tr>
                                            <td>Smart Watch</td>
                                            <td>98</td>
                                            <td>$9,800</td>
                                            <td><i class="fas fa-minus text-warning"></i></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        });
    </script>
@endpush