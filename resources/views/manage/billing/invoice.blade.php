@extends('layouts.app')

@section('content')

<section class="content mt-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h4 class="mb-3">Invoice for Table 4</h4>                    
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
                        <b>Invoice #007612</b><br>
                        <br>
                        <b>Payment Due:</b> {{ $date }}<br>
                        <b>Table:</b> 1
                        <br>
                        <b>Time:</b> {{ $time }}
                    </div>
                </div>
                                
                <div class="row">
                    <div class="col-12 table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Qty</th>
                                    <th>Product</th>
                                    <th>Description</th>
                                    <th>Subtotal(RM)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Call of Duty</td>
                                    <td>El snort testosterone trophy driving gloves handsome</td>
                                    <td>$64.50</td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>Need for Speed IV</td>
                                    <td>Wes Anderson umami biodiesel</td>
                                    <td>$50.00</td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>Monsters DVD</td>
                                    <td>Terry Richardson helvetica tousled street art master</td>
                                    <td>$10.70</td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>Grown Ups Blue Ray</td>
                                    <td>Tousled lomo letterpress</td>
                                    <td>$25.99</td>
                                </tr>
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
                                    <td>250.30</td>
                                </tr>
                                <tr>
                                    <th>Tax (9.3%)</th>
                                    <td>10.34</td>
                                </tr>
                                <tr>
                                    <th>Shipping:</th>
                                    <td>5.80</td>
                                </tr>
                                <tr>
                                    <th>Total:</th>
                                    <td>265.24</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="row no-print">
                    <div class="col-12">
                        <button type="button" class="btn btn-success float-right"><i class="far fa-credit-card"></i> Submit Payment</button>
                        <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;">
                            <i class="fas fa-download"></i> Generate PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('script')

@endpush