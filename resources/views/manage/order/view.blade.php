@extends('layouts.app')

@section('content')

<section class="content mt-4">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <h4 class="mb-3">Order #{{$order->id}}</h4>
        <div class="card card-light">
          <div class="card-header">
            <button class="btn btn-success">
              <a href="/order-history" class="text-white" id="backButton">
                Back to Order History</a>
            </button>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col">
                <h4>Table Number: <b>{{ $table->table_number }}</b></h4>
              </div>
              <div class="col">
                <h4>Order Time: <b>{{ $order->created_at->format('g:i a')}}</b></h4>
              </div>
              <div class="col">
                <h4>Order Date: <b>{{ $order->created_at->format('d/m/Y')}}</b></h4>
              </div>
            </div>
            <hr>
            <h4 class="mb-3">Menus</h4>
            <table id="orderTable" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Quantity</th>
                  <th>Remarks</th>
                  <th>Sides</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
</section>

@endsection

@push('script')

<script>
  $(function() {
        $('#orderTable').DataTable({
            "lengthChange": false,
            "searching": false,
            "pageLength": 10,
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "autoWidth": false,
            "ajax": {
                "url": "{{ route('getOrder') }}",
                "dataType": "json",
                "type": "POST",
                "data": function(d) {
                    return $.extend({}, d, {
                        _token: "{{csrf_token()}}",
                        id: "{{$order->id}}"
                    });
                }
            },
            "columns": [
              {"data": "name"},
              {"data": "quantity"},
              {"data": "remarks"},
              {"data": "sides"}
            ],
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
            }],
            dom: 'Plfrtip',
            "language": {
                "emptyTable": "No records found"
            },
            order: [
                [0, 'asc']
            ],
        }).buttons().container().appendTo('#orderTable .col-md-6:eq(0)');
    })
</script>

@endpush