@extends('layouts.app')

@section('content')

<section class="content mt-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header" style="background-color:#FFC300;>
                        <h3 class="card-title">Food Ranking</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus text-dark"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times text-dark"></i>
                                </button>
                            </div>
                    </div>
                    <div class="card-body">
                        <table id="foodRankTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Menu Name</th>
                                    <th>Orders</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row justify-content-center">
                    @foreach ($categories as $category)
                        @if ($category->category == 0)
                            <div class="col-md-6">
                                <div class="card card-warning">
                                    <div class="card-header" style="background-color:#FFC300;">
                                        <h6 class="text-center">{{ $category->name }}</h6>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="{{ str_replace(' ', '', $category->name) }}"
                                            style="min-height: 200px; height: 2px; max-height: 250px; max-width: 100%;">
                                        </canvas>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header" style="background-color:#87afc7;">
                        <h3 class="card-title">Beverage Ranking</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus text-dark"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times text-dark"></i>
                                </button>
                            </div>
                    </div>
                    <div class="card-body">
                        <table id="beverageRankTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Menu Name</th>
                                    <th>Orders</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row justify-content-center">
                    @foreach ($categories as $category)
                        @if ($category->category == 1)
                            <div class="col-md-6">
                                <div class="card card-warning">
                                    <div class="card-header" style="background-color:#87afc7;">
                                        <h6 class="text-center">{{ $category->name }}</h6>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="{{ str_replace(' ', '', $category->name) }}"
                                            style="min-height: 200px; height: 2px; max-height: 250px; max-width: 100%;">
                                        </canvas>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('script')

    <script src="{{ asset('adminlte/plugins/chart.js/Chart.min.js') }}"></script>

    <script>

        // food rank table
        $(function() {
            $('#foodRankTable').DataTable({
                "lengthChange": false,
                "pageLength": 10,
                "processing": true,
                "serverSide": true,
                "responsive": true,
                "autoWidth": false,
                "searching": false,
                "ajax": {
                    "url": "{{ route('getFoodRank') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d) {
                        return $.extend({}, d, {
                            _token: "{{csrf_token()}}",
                        });
                    }
                },
                "columns": [{
                    "data": "rank",
                }, {
                    "data": "name",
                }, {
                    "data": "orders"
                }],
                "columnDefs": [{
                    "orderable": true,
                    "targets": 0
                }, {
                    "orderable": false,
                    "targets": 1
                }, {
                    "orderable": false,
                    "targets": 2
                }],
                dom: 'Plfrtip',
                "language": {
                    "emptyTable": "No records found"
                },
                order: [
                    [0, 'asc']
                ],
            }).buttons().container().appendTo('#foodRankTable .col-md-6:eq(0)');
        })

        // beverages rank table
        $(function() {
            $('#beverageRankTable').DataTable({
                "lengthChange": false,
                "pageLength": 10,
                "processing": true,
                "serverSide": true,
                "responsive": true,
                "autoWidth": false,
                "searching": false,
                "ajax": {
                    "url": "{{ route('getBeverageRank') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d) {
                        return $.extend({}, d, {
                            _token: "{{csrf_token()}}",
                        });
                    }
                },
                "columns": [{
                    "data": "rank",
                }, {
                    "data": "name",
                }, {
                    "data": "orders"
                }],
                "columnDefs": [{
                    "orderable": true,
                    "targets": 0
                }, {
                    "orderable": false,
                    "targets": 1
                }, {
                    "orderable": false,
                    "targets": 2
                }],
                dom: 'Plfrtip',
                "language": {
                    "emptyTable": "No records found"
                },
                order: [
                    [0, 'asc']
                ],
            }).buttons().container().appendTo('#beverageRankTable .col-md-6:eq(0)');
        })

        // loop pie chart
        let analyze_data = <?php echo json_encode($analyze_data); ?>;
        let categories_arr = <?php echo json_encode($categories_arr); ?>;
        
        for (let i = 0; i < categories_arr.length; i++) {
            let menu_names = [];
            let menu_datas = [];
            for (var key in analyze_data[categories_arr[i]]) {
                // console.log(key);
                // console.log(analyze_data[categories_arr[i]][key]);
                menu_names.push(key);
                menu_datas.push(analyze_data[categories_arr[i]][key]);
            }
            // console.log(menu_names);
            // console.log(menu_datas);
            console.log('next');
            let categories_no_space = categories_arr[i].replace(" ", "");
            var donutChartCanvas = $('#' + categories_no_space).get(0).getContext('2d')
            var donutData = {
                labels: menu_names,
                datasets: [{
                    data: menu_datas,
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
                type: 'pie',
                data: donutData,
                options: donutOptions
            })
        }

    </script>

@endpush