@extends('layouts.app')

@section('content')
    <section class="content mt-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>{{ $new_orders }}</h3>
                            <p>New Orders</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="/order-queue" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> 
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $customers_count }}</h3>
                            <p>Customer Registration</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <div class="small-box-footer"></div> 
                    </div>
                </div>

                <div class="col-lg-3 col-6">

                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $regular_customers_count }}</h3>
                            <p>Regular Customers</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <div class="small-box-footer"></div> 
                    </div>
                </div>

                <div class="col-lg-3 col-6">

                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $available_table }}</h3>
                            <p>Available Table</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="/billing" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <div class="card card-dark">
                        <div class="card-header">
                            <h3 class="card-title">Today's Sales</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="donutChartDaily"
                                style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;">
                            </canvas>
                        </div>
                        <div class="card-footer bg-default">
                            <div class="text-center">
                                <strong>Total Sales = RM{{ $total_today }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card card-dark">
                        <div class="card-header">
                            <h3 class="card-title">Weekly Sales</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="donutChartWeekly"
                                style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;">
                            </canvas>
                        </div>
                        <div class="card-footer bg-default">
                            <div class="text-center">
                                <strong>Total Sales = RM{{ $total_week }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="card card-dark">
                        <div class="card-header">
                            <h3 class="card-title">Monthly Sales</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="donutChartMonthly"
                                style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;">
                            </canvas>
                        </div>
                        <div class="card-footer bg-default">
                            <div class="text-center">
                                <strong>Total Sales = RM{{ $total_month }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card card-dark">
                        <div class="card-header">
                            <h3 class="card-title">Yearly Sales</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="donutChartYearly"
                                style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;">
                            </canvas>
                        </div>
                        <div class="card-footer bg-default">
                            <div class="text-center">
                                <strong>Total Sales = RM{{ $total_year }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script src="{{ asset('adminlte/plugins/chart.js/Chart.min.js') }}"></script>

    <script>
        let categories = <?php echo json_encode($categories_arr); ?>;
        let datas = <?php echo json_encode($today_data_arr); ?>;
        var donutChartCanvas = $('#donutChartDaily').get(0).getContext('2d')
        var donutData = {
            labels: categories,
            datasets: [{
                data: datas,
                backgroundColor: ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#872657', '#7e587e',
                    '#87afc7', '#00ced1', '#fff380', '#704214', '#f8b88b'
                ], // 13 total colours
                weight: 10,
            }],
        }
        var donutOptions = {
            maintainAspectRatio: false,
            responsive: true,
        }

        new Chart(donutChartCanvas, {
            type: 'doughnut',
            data: donutData,
            options: donutOptions
        })
    </script>

    <script>
        let datas_week = <?php echo json_encode($week_data_arr); ?>;
        var donutChartCanvas = $('#donutChartWeekly').get(0).getContext('2d')
        var donutData = {
            labels: categories,
            datasets: [{
                data: datas_week,
                backgroundColor: ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#872657', '#7e587e',
                    '#87afc7', '#00ced1', '#fff380', '#704214', '#f8b88b'
                ], // 13 total colours
                weight: 10,
            }],
        }
        var donutOptions = {
            maintainAspectRatio: false,
            responsive: true,
        }

        new Chart(donutChartCanvas, {
            type: 'doughnut',
            data: donutData,
            options: donutOptions
        })
    </script>

    <script>
        let datas_month = <?php echo json_encode($month_data_arr); ?>;
        var donutChartCanvas = $('#donutChartMonthly').get(0).getContext('2d')
        var donutData = {
            labels: categories,
            datasets: [{
                data: datas_month,
                backgroundColor: ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#872657', '#7e587e',
                    '#87afc7', '#00ced1', '#fff380', '#704214', '#f8b88b'
                ], // 13 total colours
                weight: 10,
            }],
        }
        var donutOptions = {
            maintainAspectRatio: false,
            responsive: true,
        }

        new Chart(donutChartCanvas, {
            type: 'doughnut',
            data: donutData,
            options: donutOptions
        })
    </script>

    <script>
        let datas_year = <?php echo json_encode($year_data_arr); ?>;
        var donutChartCanvas = $('#donutChartYearly').get(0).getContext('2d')
        var donutData = {
            labels: categories,
            datasets: [{
                data: datas_year,
                backgroundColor: ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#872657', '#7e587e',
                    '#87afc7', '#00ced1', '#fff380', '#704214', '#f8b88b'
                ], // 13 total colours
                weight: 10,
            }],
        }
        var donutOptions = {
            maintainAspectRatio: false,
            responsive: true,
        }

        new Chart(donutChartCanvas, {
            type: 'doughnut',
            data: donutData,
            options: donutOptions
        })
    </script>
@endpush
