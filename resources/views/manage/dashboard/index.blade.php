@extends('layouts.app')

@section('content')
    <section class="content mt-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-6">
                    <div class="card card-danger">
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
                    <div class="card card-danger">
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
                                <strong>Total Sales = RM{{ $total_today }}</strong> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')

    <script src="{{ asset('adminlte/plugins/chart.js/Chart.min.js')}}"></script>

    <script>
        let categories = <?php echo json_encode($categories_arr); ?>;
        let datas = <?php echo json_encode($today_data_arr); ?>;
        var donutChartCanvas = $('#donutChartDaily').get(0).getContext('2d')
        var donutData = {
        labels: categories,
        datasets: [
            {
            data: datas,
            backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#872657', '#7e587e', '#87afc7', '#00ced1', '#fff380', '#704214', '#f8b88b'], // 13 total colours
            weight: 10,
            }
        ],
        }
        var donutOptions = {
        maintainAspectRatio : false,
        responsive : true,
        }

        new Chart(donutChartCanvas, {
        type: 'doughnut',
        data: donutData,
        options: donutOptions
        })
    </script>

    <script>
        // let categories = <?php echo json_encode($categories_arr); ?>;
        // let datas = <?php echo json_encode($today_data_arr); ?>;
        var donutChartCanvas = $('#donutChartWeekly').get(0).getContext('2d')
        var donutData = {
        labels: categories,
        datasets: [
            {
            data: datas,
            backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#872657', '#7e587e', '#87afc7', '#00ced1', '#fff380', '#704214', '#f8b88b'], // 13 total colours
            weight: 10,
            }
        ],
        }
        var donutOptions = {
        maintainAspectRatio : false,
        responsive : true,
        }

        new Chart(donutChartCanvas, {
        type: 'doughnut',
        data: donutData,
        options: donutOptions
        })
    </script>

@endpush
