@extends('layouts.app')

@section('content')

<section class="content mt-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-6">
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
                            <div class="col-6">
                                <div class="card card-warning">
                                    <div class="card-header" style="background-color:#FFC300;">
                                        <h6 class="text-center">{{ $category->name }}</h6>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="{{ $category->name }}"
                                            style="min-height: 200px; height: 2px; max-height: 250px; max-width: 100%;">
                                        </canvas>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="col-6">
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
                            <div class="col-6">
                                <div class="card card-warning">
                                    <div class="card-header" style="background-color:#87afc7;">
                                        <h6 class="text-center">{{ $category->name }}</h6>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="{{ $category->name }}"
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

    <script>

        // food rank table
        $(function() {
            $('#foodRankTable').DataTable({
                "lengthChange": false,
                "pageLength": 100,
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
                "pageLength": 100,
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
    </script>

@endpush