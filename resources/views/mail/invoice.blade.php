<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    {{-- <link rel="stylesheet" href="{{ asset('css/invoice.css') }}"> --}}
    <style>

        * {
            margin:0;
            padding: 0;
        }
        /* Invoice */
        .text-right {
            text-align: right;
        }

        .invoice {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            font-family: Arial, sans-serif;
        }

        /* Header */

        .invoice header {
            text-align: center;
            margin-bottom: 20px;
        }

        .invoice header h1 {
            font-size: 24px;
            font-weight: bold;
            margin-top: 0;
        }

        .invoice header h2 {
            font-size: 18px;
            font-weight: normal;
            margin-top: 5px;
        }

        .invoice header p {
            margin-bottom: 5px;
        }

        /* Logo */

        .invoice header .logo {
            margin-bottom: 5px;
        }

        /* .invoice header .logo img {
            max-width: 100px;
        } */

        /* Meta */

        .invoice .meta {
            /* margin-bottom: 20px; */
            font-size: 1.5rem;
            text-align: center;
            border-top:1px  solid black;
            border-bottom:1px  solid black;
            padding: 5px;

        }

        .invoice .meta p {
           margin: 0;
        }

        /* Billing Details */

        .invoice .billing-details {
            margin-bottom: 20px;
        }

        .invoice .billing-details p {
            margin-bottom: 5px;
            font-weight: bold;
        }

        /* Table */

        .border-grey {
            background-color: #C5C6C7;
        }

        .invoice table {
            width: 100%;
            border-collapse: collapse;
        }

        .invoice table thead th {
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .invoice table tbody td,
        .invoice table tbody th {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        /* Notes */

        .invoice .notes {
            margin-bottom: 20px;
        }

        .invoice .notes p {
            margin-bottom: 5px;
        }

        .invoice .notes p:last-child {
            font-weight: bold;
        }

        /* Footer */

        .invoice .footer {
            text-align: center;
        }

        /* Strong emphasis */

        strong {
            font-weight: bold;
        }

        /* Additional CSS for specific elements */

        .invoice .table-responsive {
            overflow-x: auto;
        }

        .invoice .table-billing, .invoice .table-note {
            margin: 1rem 0 1rem 0;
        }

        .invoice .table-bordered {
            border: 1px solid #ddd;
        }

        .invoice .table-bordered th,
        .invoice .table-bordered td {
            border: 1px solid #ddd;
        }

        .invoice .text-right {
            text-align: right;
        }

        .invoice .text-center {
            text-align: center;
        }

        .invoice .no-border {
            border: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="invoice">
                    <p class="text-right">
                        <strong>DATE:</strong> {{ date('d/m/Y H:i') }}
                    </p>
                    <header>
                        <div class="logo">
                            <img width="250px" height="60px" src="data:image/jpg;base64,{{ base64_encode(file_get_contents(public_path('asset/logo.png'))) }}">
                        </div>
                        <p>{{ $profile->address }}</p>
                        <div>
                            <span>
                                <img class="sosmed-logo" src="data:image/jpg;base64,{{ base64_encode(file_get_contents(public_path('asset/ig-logo.png'))) }}"> shopluxuryhub</span>
                            <span>
                                <img class="sosmed-logo" src="data:image/jpg;base64,{{ base64_encode(file_get_contents(public_path('asset/call-logo.png'))) }}">
                                {{ $profile->phone }}
                            </span>  
                            
                        </div>
                        <div>
                            <img class="sosmed-logo" src="data:image/jpg;base64,{{ base64_encode(file_get_contents(public_path('asset/web-logo.png'))) }}">
                            {{ $profile->link }}
                        </div>
                    </header>

                    <div class="meta">                       
                        <p>
                            <strong>RECEIPT</strong>
                        </p>
                    </div>

                    <div class="table-responsive table-billing">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="border-grey" width="50%">BILLING DETAILS</th>
                                    <th>INVOICE : {{ $order->code }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>NAME: {{ $order->user->name }}</td>
                                    <td>PAYMENT OPTION: {{ $order->paymentable->payment_channel ? 'TRANSFER' : 'CASH' }}</td>                                    
                                </tr>
                                <tr>
                                    <td>CONTACT: {{ $order->user->no_hp }}</td>
                                    <td>BANK DETAILS: {{ $order->paymentable->payment_channel ?? '-' }}</td>                                    
                                </tr>
                                <tr>
                                    <td colspan="2">ADDRESS: {{ $order?->address?->address }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>                    
                    
                    @foreach ($order->orderDetail as $detail)                        
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="border-grey" width="50%">ITEM</th>
                                        <th class="border-grey">PRICE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <p>
                                                NAME PRODUCT : {{ $detail->product->name }}
                                            </p>
                                            <p>
                                                WEIGHT (KG) : {{ $detail->product->weight }}
                                            </p>
                                            <p>
                                                WIDTH (CM) : {{ $detail->product->width }}
                                            </p>
                                            <p>
                                                LENGTH (CM) : {{ $detail->product->length }}
                                            </p>
                                            <p>
                                                HEIGTH (CM) : {{ $detail->product->height }}
                                            </p>
                                            <p>
                                                COLOR : {{ $detail->product->color->name }}
                                            </p>
                                        </td>
                                        <td class="text-left">Rp. {{ $detail->product->sale_price_formatted }} | $ {{ $detail->product->sale_usd_formatted }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @endforeach


                    <div class="notes" style="margin-top:10px;">
                        <p>
                            <strong>INFORMATION:</strong>
                        </p>
                        <p>
                            <ol style="margin-left:20px;">
                                <li>
                                    FOR ITEMS THAT HAVE BEEN PURCHASED CANNOT BE EXCHANGED
                                </li>
                            </ol>
                        </p>
                    </div>
                   
                </div>
            </div>
        </div>
    </div>
</body>
</html>