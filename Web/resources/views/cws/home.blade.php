@extends('cws.layouts.app')

@section('name_page')
    <div class="page-title-box align-self-center d-none d-md-block">
        <h4 class="page-title mb-0">Home</h4>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-xl-3">
        <div class="card">
            <div class="card-body">
                <div>
                    <div class="d-flex align-items-center">
                        <div class="avatar">
                            <div class="avatar-title rounded bg-soft-primary">
                                <i class="bx bx-check-shield font-size-24 mb-0 text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 font-size-15">Total Sales</h6>
                        </div>
                    </div>

                    <div>
                        <h4 class="mt-4 pt-1 mb-0 font-size-22">$34,123.20 <span class="text-success fw-medium font-size-13 align-middle"> <i class="mdi mdi-arrow-up"></i> 8.34% </span> </h4>
                        <div class="d-flex mt-1 align-items-end overflow-hidden">
                            <div class="flex-grow-1">
                                <p class="text-muted mb-0 text-truncate">Total Sales World Wide</p>
                            </div>
                            <div class="flex-shrink-0">
                                <div id="mini-1" data-colors='["#1f58c7"]' class="apex-charts"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3">
        <div class="card">
            <div class="card-body">
                <div>
                    <div class="d-flex align-items-center">
                        <div class="avatar">
                            <div class="avatar-title rounded bg-soft-primary">
                                <i class="bx bx-cart-alt font-size-24 mb-0 text-primary"></i>
                            </div>
                        </div>

                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 font-size-15">Total Orders</h6>
                        </div>
                    </div>

                    <div>
                        <h4 class="mt-4 pt-1 mb-0 font-size-22">63,234.20 <span class="text-danger fw-medium font-size-13 align-middle"> <i class="mdi mdi-arrow-down"></i> 3.68% </span> </h4>
                        <div class="d-flex mt-1 align-items-end overflow-hidden">
                            <div class="flex-grow-1">
                                <p class="text-muted mb-0 text-truncate">Total Orders World Wide</p>
                            </div>
                            <div class="flex-shrink-0">
                                <div id="mini-2" data-colors='["#1f58c7"]' class="apex-charts"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3">
        <div class="card">
            <div class="card-body">
                <div>
                    <div class="d-flex align-items-center">
                        <div class="avatar">
                            <div class="avatar-title rounded bg-soft-primary">
                                <i class="bx bx-package font-size-24 mb-0 text-primary"></i>
                            </div>
                        </div>

                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 font-size-15">Today Visitor</h6>
                        </div>
                    </div>

                    <div>
                        <h4 class="mt-4 pt-1 mb-0 font-size-22">425,34.45 <span class="text-danger fw-medium font-size-13 align-middle"> <i class="mdi mdi-arrow-down"></i> 2.64% </span> </h4>
                        <div class="d-flex mt-1 align-items-end overflow-hidden">
                            <div class="flex-grow-1">
                                <p class="text-muted mb-0 text-truncate">Total Visitor World Wide</p>
                            </div>
                            <div class="flex-shrink-0">
                                <div id="mini-3" data-colors='["#1f58c7"]' class="apex-charts"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3">
        <div class="card">
            <div class="card-body">
                <div>
                    <div class="d-flex align-items-center">
                        <div class="avatar">
                            <div class="avatar-title rounded bg-soft-primary">
                                <i class="bx bx-rocket font-size-24 mb-0 text-primary"></i>
                            </div>
                        </div>

                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 font-size-15">Total Expense</h6>
                        </div>
                    </div>

                    <div>
                        <h4 class="mt-4 pt-1 mb-0 font-size-22">6,482.46 <span class="text-success fw-medium font-size-13 align-middle"> <i class="mdi mdi-arrow-down"></i> 5.79% </span> </h4>
                        <div class="d-flex mt-1 align-items-end overflow-hidden">
                            <div class="flex-grow-1">
                                <p class="text-muted mb-0 text-truncate">Total Expense World Wide</p>
                            </div>
                            <div class="flex-shrink-0">
                                <div id="mini-4" data-colors='["#1f58c7"]' class="apex-charts"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
