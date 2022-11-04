<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Invoice for Table {{ $table->id }}</title>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css?v=3.2.0') }}">
    
</head>

<style>

</style>

<body>
    <div class="wrapper">

        <section class="invoice">

            <div class="row invoice-info">
                <div class="col-sm-6 invoice-col">
                    <address class="text-center">
                        <strong>Red Card Cafe</strong><br>
                            17 & 18, Jalan Medan Pusat Bandar 4A,<br>
                            Seksyen 9, 43650 Bandar Baru Bangi, Selangor<br>
                            Phone:  03-8211 1310<br>
                    </address>
                </div>

                <div class="col-sm-4 invoice-col">
                    <br>
                    <b>Payment Due:</b> {{ $date }}<br>
                    <b>Table:</b> 1
                    <br>
                    <b>Time:</b> {{ $time }}
                </div>
            </div>

            <div class="row">
                <div class="col-12 table-responsive">
                    <br>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Subtotal (RM)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lists as $list)
                                @foreach ($list->menus as $ordering)
                                    <tr>
                                        <td>{{ $ordering->name }}</td>
                                        <td>{{ $ordering->pivot->quantity }}</td>
                                        <td>{{ $ordering->price*$ordering->pivot->quantity }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>

            <div class="row justify-contend-end">
                <div class="col-6">
                    <p class="lead"><strong>Amount Due: </strong></p>
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th style="width:50%">Subtotal (RM): </th>
                                <td>{{ $amount_exl_tax }}</td>
                            </tr>
                            @foreach ($taxes as $tax)
                                <tr>
                                    <th>{{ $tax->name }} ({{ $tax->percentage }}%) (RM): </th>
                                    <td>{{ $tax_amount[$tax->name] }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <th>Total (RM): </th>
                                <td>{{ $amount_incl_tax }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script type="text/php">
        if (isset($pdf)) {
        $text = "Page {PAGE_NUM} / {PAGE_COUNT}";
        $size = 10;
        $font = $fontMetrics->getFont("Verdana");
        $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
        $x = ($pdf->get_width() - $width) / 2;
        $y = $pdf->get_height() - 35;
        $pdf->page_text($x, $y, $text, $font, $size);
        }
    </script>
</body>

</html>
