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
                <h4 class="mb-3">Tax Management</h4>
                <div class="card">
                    <div class="card-header">
                        <button class="btn btn-success card-title float-right"><a class="text-white" href="/tax/add">Add New Tax</a></button>
                    </div>
                    <div class="card-body">
                        <table id="taxTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Tax Name</th>
                                    <th>Percentage(%)</th>
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
        $('#taxTable').DataTable({
            "lengthChange": false,
            "searching": true,
            "pageLength": 10,
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "autoWidth": false,
            "ajax": {
                "url": "{{ route('getTaxes') }}",
                "dataType": "json",
                "type": "POST",
                "data": function(d) {
                    return $.extend({}, d, {
                        _token: "{{csrf_token()}}",
                    });
                }
            },
            "columns": [{
                "data": "tax_name",
            }, {
                "data": "percentage",
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
        }).buttons().container().appendTo('#taxTable .col-md-6:eq(0)');
    })
</script>
@endpush