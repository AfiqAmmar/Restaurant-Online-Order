@extends('layouts.app')

@section('content')

<section class="content mt-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h4 class="mb-3">Invoice for Table {{ $table->id }}</h4>                    
                <div class="invoice p-3 mb-3">
                    <div class="row">
                        <div class="col-12">
                        <h4>
                        <i class="fas fa-globe"></i> Red Card Cafe
                        <small class="float-right">Date: {{ date("d/m/Y") }}</small>
                        </h4>
                    </div>
                </div> 
                <div class="row invoice-info">
                    <div class="col-sm-6 invoice-col">
                        <address>
                            <strong>Red Card Cafe</strong><br>
                            17 & 18, Jalan Medan Pusat Bandar 4A,<br>
                            Seksyen 9, 43650 Bandar Baru Bangi, Selangor<br>
                            Phone:  03-8211 1310<br>
                        </address>
                    </div>
                    
                    <div class="col-sm-4 invoice-col">
                        <b>Payment Due:</b> {{ $date }}<br>
                        <b>Table:</b> {{ $table->id }}
                        <br>
                        <b>Time:</b> {{ $time }}
                    </div>
                </div>
                                
                <div class="row">
                    <div class="col-12 table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Description</th>
                                    <th>Price(RM)</th>
                                    <th>Qty</th>
                                    <th>Subtotal(RM)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lists as $list)
                                    @foreach ($list->menus as $ordering)
                                        <tr>
                                            <td>{{ $ordering->name }}</td>
                                            <td>{{ $ordering->description }}</td>
                                            <td>{{ $ordering->price }}</td>
                                            <td>{{ $ordering->pivot->quantity }}</td>
                                            <td>{{ $ordering->price*$ordering->pivot->quantity }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="row justify-content-end">
                    <div class="col-6">
                        <p class="lead"><b>Amount Due:</b></p>
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th style="width:50%">Subtotal:(RM)</th>
                                    <td>{{ $amount_exl_tax }}</td>
                                </tr>
                                @foreach ($taxes as $tax)
                                    <tr>
                                        <th>{{ $tax->name }} ({{ $tax->percentage }}%)</th>
                                        <td>{{ $tax_amount[$tax->name] }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <th>Total:</th>
                                    <td>{{ $amount_incl_tax }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row no-print">
                    <div class="col-12">
                        <button type="button" class="btn btn-success float-right" id="submitPaymentButton" data-toggle="modal" data-target="#submitPaymentModal">
                            <i class="far fa-credit-card"></i> Submit Payment
                        </button> 
                        <a target="_blank" href="{{ url('billing/pdf/' . $table->id ) }}">
                            <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;">
                                <i class="fas fa-download"></i> Generate PDF
                            </button>
                        </a>
                        <div class="modal fade" id="submitPaymentModal">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-success">
                                        <h4 class="modal-title">Payment</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                <div class="modal-body">
                                    <p>Are you sure the payment for this table has been confirmed?</p>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <form action="{{ $table->id }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <button type="submit" class="btn btn-success" type="submit">Confirm</button>
                                    </form>
                                </div>
                            </div>
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