@extends('layouts.app')

@section('content')

@if(session()->has('success'))
    <div class="alert alert-success d-flex justify-content-center message" id="message">
        {{ session()->get('success') }}
    </div>

    <script>
        setTimeout(function(){
        if ($('#message').length > 0) {
            $('#message').remove();
            }
        }, 3000)
    </script>

@endif

<section class="content mt-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h4 class="mb-3">Table Management</h4>
                <div class="card">
                    <div class="card-header">
                        <button class="btn btn-success card-title float-right"><a class="text-white" href="/table/add">Add New Table</a></button>
                    </div>
                    <div class="card-body">
                        <table id="tableTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Table Number</th>
                                    <th>Capacity</th>
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
        $('#tableTable').DataTable({
            "lengthChange": false,
            "searching": true,
            "pageLength": 10,
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "autoWidth": false,
            "ajax": {
                "url": "{{ route('getTables') }}",
                "dataType": "json",
                "type": "POST",
                "data": function(d) {
                    return $.extend({}, d, {
                        _token: "{{csrf_token()}}",
                    });
                }
            },
            "columns": [{
                "data": "table_number",
            }, {
                "data": "capacity",
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
        }).buttons().container().appendTo('#tableTable .col-md-6:eq(0)');
    })
</script>
@endpush