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
            float: left;
            margin-right: 20px;
        }

        .invoice header .logo img {
            max-width: 100px;
        }

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
                        <strong>DATE:</strong> {{ date('d/m/Y H:i:s') }}
                    </p>
                    <header>
                        <h1>LUXURI</h1>
                        <p>Mandarin Gallery, 333A Orchard Placed +03-08509510, Singapore 235597</p>
                        <p>shopluxuryhub+65-6530-3008</p>
                        <p>www.luxuryhub.id</p>
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
                                    <th>INVOICE : POS/1/2/2024</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>NAME: NONI</td>
                                    <td>PAYMENT OPTION: TRANSFER NOW</td>                                    
                                </tr>
                                <tr>
                                    <td>NAME: NONI</td>
                                    <td>PAYMENT OPTION: TRANSFER NOW</td>                                    
                                </tr>
                                <tr>
                                    <td colspan="2">ADDRESS: JL. ABC ABC</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>                    

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
                                            NAME PRODUCT : HERMES KELLY
                                        </p>
                                        <p>
                                            WEIGHT (KG) : 1
                                        </p>
                                        <p>
                                            WIDTH (CM) : 7
                                        </p>
                                        <p>
                                            LENGTH (CM) : 15
                                        </p>
                                        <p>
                                            HEIGTH (CM) : 10
                                        </p>
                                        <p>
                                            COLOR : ORANGE
                                        </p>
                                    </td>
                                    <td class="text-left">Rp. 30.000.000 | $ 2.000</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive table-note">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="border-grey">NOTE</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <p>
                                            Submit Product: 13/11/2023
                                        </p>
                                        <p>
                                            Akhir Pemasangan: 13/11/2024
                                        </p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="notes">
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