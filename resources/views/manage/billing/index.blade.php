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
                                @if ($table_arr[$table->id] == 0)
                                    <div class="col-3">
                                        <div class="card">
                                            <div class="card-header bg-secondary">
                                                <h6 class="text-center">Table</h6>
                                            </div>
                                            <div class="card-body">
                                                <button type="button" class="btn btn-block btn-default btn-lg">{{ $table->table_number }}</button>
                                            </div>
                                        </div>
                                    </div> 
                                    @continue
                                @else
                                    @if($table->orders[$table_arr[$table->id]-1]->payment_status == 0)
                                        <div class="col-3">
                                            <div class="card">
                                                <div class="card-header bg-secondary">
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
                                                    <button type="button" class="btn btn-block btn-default btn-lg">{{ $table->table_number }}</button>
                                                </div>
                                            </div>
                                        </div>   
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

@endpush