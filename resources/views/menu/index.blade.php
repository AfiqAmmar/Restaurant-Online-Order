@extends('layouts.app')

@section('content')

@if(session()->has('success'))
    <div class="alert alert-success d-flex justify-content-center message">
        {{ session()->get('success') }}
    </div>
@endif

<section class="content mt-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h4 class="mb-3">Menu Management</h4>
                <div class="card">
                    <div class="card-header">
                        <div class="float-right">
                            <button class="btn btn-success card-title mr-3"><a class="text-white" href="/category">Manage Menu Category</a></button>
                            <button class="btn btn-success card-title"><a class="text-white" href="/menu/add">Add New Menu</a></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="menuTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('script')
<script>
     $(function() {
        $('#menuTable').DataTable({
            "lengthChange": false,
            "searching": true,
            "pageLength": 10,
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "autoWidth": false,
            "ajax": {
                "url": "{{ route('getMenus') }}",
                "dataType": "json",
                "type": "POST",
                "data": function(d) {
                    return $.extend({}, d, {
                        _token: "{{csrf_token()}}",
                    });
                }
            },
            "columns": [{
                "data": "name",
            }, {
                "data": "category",
            }, {
                "data": "price",
            }, {
                "data": "action"
            }],
            "columnDefs": [{
                "orderable": true,
                "targets": 0
            }, {
                "orderable": true,
                "targets": 1
            }, {
                "orderable": true,
                "targets": 2
            }, {
                "orderable": false,
                "targets": 3
            }],
            dom: 'Plfrtip',
            "language": {
                "emptyTable": "No records found"
            },
            order: [
                [0, 'asc']
            ],
        }).buttons().container().appendTo('#menuTable .col-md-6:eq(0)');
    })
</script>
@endpush