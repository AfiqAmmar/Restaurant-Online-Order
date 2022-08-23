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
        <h4 class="mb-3">Order History</h4>
        <div class="card">
          <div class="card-body">
            <table id="orderHistoryTable" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Table Number</th>
                  <th>Order Time</th>
                  <th>Order Date</th>
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
        $('#orderHistoryTable').DataTable({
            "lengthChange": false,
            "pageLength": 10,
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "autoWidth": false,
            "ajax": {
                "url": "{{ route('getOrders') }}",
                "dataType": "json",
                "type": "POST",
                "data": function(d) {
                    return $.extend({}, d, {
                        _token: "{{csrf_token()}}",
                    });
                }
            },
            "columns": [
              {"data": "table_number"},
              {"data": "order_time"},
              {"data": "order_date"},
              {"data": "action"}
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
                "orderable": false,
                "targets": 3
            }],
            dom: 'Plfrtip',
            "language": {
                "emptyTable": "No records found"
            },
            order: [
                [2, 'desc']
            ],
        }).buttons().container().appendTo('#orderHistoryTable .col-md-6:eq(0)');
    })
</script>
@endpush