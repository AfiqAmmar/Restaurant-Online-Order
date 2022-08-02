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
                <h4 class="mb-3">Staff Management</h4>
                <div class="card">
                    <div class="card-header">
                        <div class="float-right">
                            <button class="btn btn-success card-title"><a class="text-white" href="/staff/add">Add New Staff</a></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="staffTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Phone Number</th>
                                    <th>Role</th>
                                    <th>Start Date</th>
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
        $('#staffTable').DataTable({
            "lengthChange": false,
            "searching": true,
            "pageLength": 10,
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "autoWidth": false,
            "ajax": {
                "url": "{{ route('getStaffs') }}",
                "dataType": "json",
                "type": "POST",
                "data": function(d) {
                    return $.extend({}, d, {
                        _token: "{{csrf_token()}}",
                    });
                }
            },
            "columns": [{
                "data": "fname",
            }, {
                "data": "lname",
            }, {
                "data": "email",
            }, {
                "data": "phone_number",
            }, {
                "data": "role",
            }, {
                "data": "start_date",
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
                "orderable": true,
                "targets": 3
            },{
                "orderable": true,
                "targets": 4
            },{
                "orderable": true,
                "targets": 5
            },{
                "orderable": false,
                "targets": 6
            }],
            dom: 'Plfrtip',
            "language": {
                "emptyTable": "No records found"
            },
            order: [
                [0, 'asc']
            ],
        }).buttons().container().appendTo('#staffTable .col-md-6:eq(0)');
    })
</script>
@endpush