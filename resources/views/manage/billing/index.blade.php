@extends('layouts.app')

@section('content')

<section class="content mt-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h4 class="mb-3">Billing Management</h4>
                <div class="card">
                    <div class="card-header bg-dark">  
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($tables as $table)
                                @if ($table->table_number == 0)
                                    @continue 
                                @else
                                    @if (empty($table->orders[0]))
                                        <div class="col-3">
                                            <div class="card">
                                                <div class="card-header bg-secondary">
                                                    <h6 class="text-center">Table</h6>
                                                </div>
                                                <div class="card-body">
                                                    <a href="{{ url('billing/' . $table->id ) }}"><button type="button" class="btn btn-block btn-default btn-lg">{{ $table->table_number }}</button></a>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        @if ($table->orders[0]->payment_status == 0)
                                            <div class="col-3">
                                                <div class="card">
                                                    <div class="card-header bg-success">
                                                        <h6 class="text-center">Table</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <a href="{{ url('billing/' . $table->id ) }}"><button type="button" class="btn btn-block btn-success btn-lg">{{ $table->table_number }}</button></a>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-3">
                                                <div class="card">
                                                    <div class="card-header bg-secondary">
                                                        <h6 class="text-center">Table</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <a href="{{ url('billing/' . $table->id ) }}"><button type="button" class="btn btn-block btn-default btn-lg">{{ $table->table_number }}</button></a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif    
                                    @endif
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('script')

<script src="{{ asset('adminlte/plugins/filterizr/jquery.filterizr.min.js')}}"></script>
<script src="{{ asset('adminlte/plugins/ekko-lightbox/ekko-lightbox.min.js')}}"></script>

<script>
  $(function () {
    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
      event.preventDefault();
      $(this).ekkoLightbox({
        alwaysShowClose: true
      });
    });

    $('.filter-container').filterizr({gutterPixels: 3});
    $('.btn[data-filter]').on('click', function() {
      $('.btn[data-filter]').removeClass('active');
      $(this).addClass('active');
    });
  })
</script>

@endpush