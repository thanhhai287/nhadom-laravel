@extends('layouts.app')

@section('title', 'Home')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item active">Home</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-md-6 col-lg-6 col-xl-3">
                <div class="card border-0">
                    <div class="card-body p-0 d-flex align-items-center shadow-sm">
                        <div class="bg-gradient-primary p-4 mfe-3 rounded-left">
                            <i class="bi bi-bar-chart font-2xl"></i>
                        </div>
                        <div>
                            <div class="text-value text-primary">{{ format_currency($revenue_today) }}</div>
                            <div class="text-muted text-uppercase font-weight-bold small">Doanh thu hôm nay</div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-6 col-xl-3">
                <div class="card border-0">
                    <div class="card-body p-0 d-flex align-items-center shadow-sm">

                        <div class=" p-4 mfe-3 rounded-left">
                            <div class="text-value text-primary">{{ format_currency($revenue_today_cash) }}</div>
                            <div class="text-muted text-uppercase font-weight-bold small">Tiền mặt</div>
                        </div>
                        <div class=" p-4 mfe-3 rounded-left">
                            <div class="text-value text-primary">{{ format_currency($revenue_today_bank) }}</div>
                            <div class="text-muted text-uppercase font-weight-bold small">Chuyển khoản</div>
                        </div>
                    </div>
                </div>
            </div>
            @can('show_total_stats')
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="card border-0">
                        <div class="card-body p-0 d-flex align-items-center shadow-sm">
                            <div class="bg-gradient-warning p-4 mfe-3 rounded-left">
                                <i class="bi bi-arrow-return-left font-2xl"></i>
                            </div>
                            <div>
                                <div class="text-value text-warning">{{ format_currency($revenue) }}</div>
                                <div class="text-muted text-uppercase font-weight-bold small">Tổng doanh thu</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="card border-0">
                        <div class="card-body p-0 d-flex align-items-center shadow-sm">
                            <div class="bg-gradient-info p-4 mfe-3 rounded-left">
                                <i class="bi bi-trophy font-2xl"></i>
                            </div>
                            <div>
                                <div class="text-value text-info">{{ format_currency($profit) }}</div>
                                <div class="text-muted text-uppercase font-weight-bold small">Lợi nhuận</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
        @can('show_total_stats')
        <div class="row">
            <div class="col-md-6 col-lg-6 col-xl-3">
                <div class="card border-0">
                    <div class="card-body p-0 d-flex align-items-center shadow-sm">
                        <div class="bg-gradient-warning p-4 mfe-3 rounded-left">
                            <i class="bi bi-arrow-return-left font-2xl"></i>
                        </div>
                        <div>
                            <div class="text-value text-warning">{{ format_currency($revenue_month) }}</div>
                            <div class="text-muted text-uppercase font-weight-bold small">Tổng doanh thu trong tháng {{ \Carbon\Carbon::now('Asia/Ho_Chi_Minh')->format('m-Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-6 col-xl-3">
                <div class="card border-0">
                    <div class="card-body p-0 d-flex align-items-center shadow-sm">
                        <div class="bg-gradient-info p-4 mfe-3 rounded-left">
                            <i class="bi bi-trophy font-2xl"></i>
                        </div>
                        <div>
                            <div class="text-value text-info">{{ format_currency($profit_month) }}</div>
                            <div class="text-muted text-uppercase font-weight-bold small">Lợi nhuận trong tháng {{ \Carbon\Carbon::now('Asia/Ho_Chi_Minh')->format('m-Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endcan
        @can('show_weekly_sales_purchases|show_month_overview')
        <div class="row mb-4">
            @can('show_weekly_sales_purchases')
            <div class="col-xl-9 col-lg-12 col-md-12 col-sm-12">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header">
                        Doanh thu trong tháng {{ \Carbon\Carbon::now('Asia/Ho_Chi_Minh')->format('m-Y') }}
                    </div>
                    <div class="card-body">
                        <canvas id="salesPurchasesChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-12 col-md-12 col-sm-12">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header">
                        Tổng quan trong hôm nay
                    </div>
                    <div class="card-body d-flex justify-content-center">
                        <div class="chart-container" style="position: relative; height:auto; width:280px">
                            <canvas id="currentDailyChart"></canvas>
                        </div>
                    </div>
                    <div class="card-header">
                        Tổng quan trong tháng {{ \Carbon\Carbon::now('Asia/Ho_Chi_Minh')->format('m-Y') }}
                    </div>
                    <div class="card-body d-flex justify-content-center">
                        <div class="chart-container" style="position: relative; height:auto; width:280px">
                            <canvas id="currentMonthChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            @endcan
        </div>
    @endcan

    @can('show_total_stats')
            <div class="row " style="width: 100%; margin: 0!important;">
                <div class="col-lg-6 col-md-6 col-sm 12 " style="padding-right: 0">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header">
                            Tổng ly bán được trong hôm nay: <span class="font-weight-bold text-danger">{{$total_statistics_day}}</span>
                        </div>
                        <div class="card-body p-0 align-items-center shadow-sm d-flex flex-column justify-content-start flex-wrap">
                            <div class="row"  style="width: 100%">
                                <div class="col-6 p-0" style="padding: 0px 0px 0px 10px!important;">
                                    @for($i = 0; $i < count($statistics_day) / 2; $i += 1)
                                        @php
                                            $statistic = $statistics_day->get($i);
                                        @endphp
                                        @if($statistic != null && $statistic->count != null && $statistic->count > 0)
                                        <div class="mr-3  d-flex justify-content-between">
                                            <div class="text-muted" style="font-weight: 600;">{{$statistic->product_name}}: </div>
                                            <div class="text-danger" style="font-weight: 500;">{{$statistic->count}}</div>
                                        </div>
                                        @endif
                                    @endfor
                                </div>
                                <div class="col-6 p-0" style="padding: 0px 0px 0px 10px!important;">
                                    @for($i = count($statistics_day) / 2; $i < count($statistics_month); $i += 1)
                                        @php
                                            $statistic = $statistics_day->get($i);
                                        @endphp
                                        @if($statistic != null && $statistic->count != null  && $statistic->count > 0)
                                        <div class="mr-3 d-flex justify-content-between">
                                            <div class="text-muted" style="font-weight: 600;">{{$statistic->product_name}}: </div>
                                            <div class="text-danger" style="font-weight: 500;">{{$statistic->count}}</div>
                                        </div>
                                        @endif
                                    @endfor
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm 12" style="padding-right: 0">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header">
                            Tổng ly bán được trong tháng {{ \Carbon\Carbon::now('Asia/Ho_Chi_Minh')->format('m-Y') }}: <span class="text-danger font-weight-bold">{{$total_statistics_month}}</span>
                        </div>
                        <div class="card-body p-0 align-items-center shadow-sm d-flex flex-column justify-content-start flex-wrap">
                            <div class="row" style="width: 100%">
                                <div class="col-6 p-0" style="padding: 0px 0px 0px 10px!important;">
                                    @for($i = 0; $i < count($statistics_month) / 2; $i += 1)
                                        @php
                                            $statistic = $statistics_month->get($i);
                                        @endphp

                                        <div class="mr-3  d-flex justify-content-between">
                                            <div class="text-muted" style="font-weight: 600;">{{$statistic->product_name}}: </div>
                                            <div class="text-danger" style="font-weight: 500;">{{$statistic->count}}</div>
                                        </div>
                                    @endfor
                                </div>
                                <div class="col-6 p-0" style="padding: 0px 0px 0px 10px!important;">
                                    @for($i = round(count($statistics_month) / 2); $i < count($statistics_month); $i += 1)
                                        @php
                                            $statistic = $statistics_month->get($i);
                                        @endphp
                                        <div class="mr-3 d-flex justify-content-between">
                                            <div class="text-muted" style="font-weight: 600;">{{$statistic->product_name}}: </div>
                                            <div class="text-danger" style="font-weight: 500;">{{$statistic->count}}</div>
                                        </div>
                                    @endfor
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
    @endcan
    </div>
@endsection

@section('third_party_scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.0/chart.min.js"
            integrity="sha512-asxKqQghC1oBShyhiBwA+YgotaSYKxGP1rcSYTDrB0U6DxwlJjU59B67U8+5/++uFjcuVM8Hh5cokLjZlhm3Vg=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection

@push('page_scripts')
    @vite('resources/js/chart-config.js')
@endpush
