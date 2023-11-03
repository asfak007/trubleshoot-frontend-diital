<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ececec;
            margin: 0;
            padding: 0;
        }

        #invoice {
            padding: 30px;
        }

        .invoice {
            background-color: #FFF;
            padding: 15px;
        }

        .invoice header {
            padding: 10px 0;
            border-bottom: 1px solid #4283B3;
        }

        .invoice .company-details {
            text-align: right;
        }

        .invoice .company-details .name {
            margin: 0;
            font-size: 24px;
            color: #4283B3;
        }

        .invoice .contacts {
            margin-bottom: 20px;
        }

        .invoice .invoice-to {
            text-align: left;
        }

        .invoice .invoice-to .to {
            margin: 0;
            font-size: 18px;
        }

        .invoice .invoice-details {
            text-align: right;
        }

        .invoice .invoice-details .invoice-id {
            margin: 0;
            color: #4283B3;
            font-size: 24px;
        }

        .invoice main {
            padding-bottom: 50px;
        }

        .invoice main .thanks {
            margin: 0;
            font-size: 32px;
            color: #4283B3;
            margin-bottom: 50px;
        }

        .invoice main .notices {
            border-left: 6px solid #4283B3;
            padding-left: 10px;
        }

        .invoice main .notices .notice {
            font-size: 18px;
        }

        .invoice table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 20px;
        }

        .invoice table td, .invoice table th {
            padding: 15px;
            background: #eee;
            border-bottom: 1px solid #fff;
        }

        .invoice table th {
            white-space: nowrap;
            font-weight: 400;
            font-size: 18px;
        }

        .invoice table td h3 {
            margin: 0;
            font-weight: 400;
            color: #4283B3;
            font-size: 24px;
        }

        .invoice table .qty, .invoice table .total, .invoice table .unit {
            text-align: right;
            font-size: 18px;
        }

        .invoice table .no {
            color: rgb(0, 0, 0);
            font-size: 24px;
            background: #ffffff;
        }

        .invoice table .unit {
            background: #ddd;
        }

        .invoice table .total {
            background: #ffffff;
            color: #fff;
        }

        .invoice table tbody tr:last-child td {
            border: none;
        }

        .invoice table tfoot td {
            background: 0 0;
            border-bottom: none;
            white-space: nowrap;
            text-align: right;
            padding: 10px 20px;
            font-size: 18px;
            border-top: 1px solid #aaa;
        }

        .invoice table tfoot tr:first-child td {
            border-top: none;
        }

        .invoice table tfoot tr:last-child td {
            color: #4283B3;
            font-size: 24px;
            border-top: 1px solid #4283B3;
        }

        .invoice table tfoot tr td:first-child {
            border: none;
        }

        .invoice footer {
            width: 100%;
            text-align: center;
            color: #777;
            border-top: 1px solid #aaa;
            padding: 8px 0;
        }
    </style>
</head>
<body>
<div id="invoice">
    <div class="invoice">
        <header>
            <div class="row">
                <div class="col">
                    <img width="200" src="{{asset('assets/images/logo-icon.png')}}" class="logo-icon" alt="Company Logo">
                </div>
                <div class="col company-details">
                    <h2 class="name">Troubleshoot</h2>
                    <div>Dhaka Bangladesh</div>
                    <div>+8801100000001</div>
                    <div>troubleshoot.com</div>
                </div>
            </div>
        </header>
        <main>
            <div class="row contacts">
                <div class="col invoice-to">
                    <div class="text-gray-light text-uppercase">Invoice to:</div>
                    <h2 class="to">{{ $booking->metadata['customer']['first_name'].' '.$booking->metadata['customer']['last_name'] }}</h2>
                    <div class="address">{{$booking->metadata['customer']['address']['apartment_number'].', '. $booking->metadata['customer']['address']['apartment_name'].', '.$booking->metadata['customer']['address']['street_one'].', '. $booking->metadata['customer']['address']['city'] }}</div>
                    <div class="tel">{{$booking->metadata['customer']['phone'] }}</div>
                    <div>{{ $booking->metadata['customer']['email'] }}</div>
                </div>
                <div class="col invoice-details">
                    <h1 class="invoice-id text-uppercase">Invoice</h1>
                    <div class="date">Date of invoice: {{ $booking->created_at->format('d/m/y') }}</div>
                    <div class="date">Due date: {{ $booking->created_at->format('d/m/y') }}</div>
                </div>
            </div>
            <table>
                <thead>
                <tr>
                    <th>#</th>
                    <th class="text-left text-uppercase">Description</th>
                    <th class="text-left text-uppercase">Cost</th>
                    <th class="text-right text-uppercase">Quantity</th>
                    <th class="text-right text-uppercase">Total</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="unit">01</td>
                    <td class="text-left">
                        <p>{{$booking->metadata['service']['name']}}</p>
                    </td>
                    <td class="unit">{{$booking->metadata['service']['price']}}$</td>
                    <td class="qty">{{$booking->quantity}}</td>
                    <td class="unit">{{$booking->metadata['calculation']['with_tax']}}$</td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="2"></td>
                    <td colspan="2" class="text-uppercase">Subtotal</td>
                    <td>{{$booking->metadata['calculation']['price']}}$</td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td colspan="2" class="text-uppercase">Discount</td>
                    <td>- {{$booking->metadata['calculation']['total_discount']}}$</td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td colspan="2" class="text-uppercase">Tax (%)</td>
                    <td>+ {{$booking->metadata['calculation']['total_tax']}}$</td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td colspan="2" class="text-uppercase">Grand total</td>
                    <td>{{$booking->metadata['calculation']['total_amount']}}$</td>
                </tr>
                </tfoot>
            </table>
            <div class="thanks">Thank you!</div>
            <div class="notices">
                <div class="notice">All rights reserved By Troubleshoot</div>
            </div>
        </main>
    </div>
</div>
</body>
</html>
