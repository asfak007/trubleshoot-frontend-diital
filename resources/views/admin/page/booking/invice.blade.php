@extends('admin.layouts.master')

@section('title')
    <title>Invoice Page</title>
@endsection

@section('breadcrumb-title')
    <div class="breadcrumb-title pe-3">Invoice</div>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
    <li class="breadcrumb-item active" aria-current="page"></li>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div id="invoice">
                <div class="toolbar hidden-print">
                    <div class="text-end">
{{--                        <button type="button" class="btn btn-dark"><i class="fa fa-print"></i> Print</button>--}}
{{--                        <button type="button" class="btn btn-danger"><i class="fa fa-file-pdf-o"></i> Export as PDF</button>--}}
                        <a href="{{route('download-invoice',$booking->id)}}" class="btn btn-danger">Export as PDF</a>
                    </div>
                    <hr>
                </div>
                <div class="invoice overflow-auto">
                    <div style="min-width: 600px">
                        <header>
                            <div class="row">
                                <div class="col">
                                    <a href="javascript:;">
                                        <img src="{{asset('assets/images/logo-img.png')}}" width="80" alt="">
                                    </a>
                                </div>
                                <div class="col company-details">
                                    <h2 class="name">
                                        <a target="_blank" href="javascript:;">
                                            Troubleshoot
                                        </a>
                                    </h2>
                                    <div>455 Foggy Heights, AZ 85004, US</div>
                                    <div>(123) 456-789</div>
                                    <div>troubleshoot.com</div>
                                </div>
                            </div>
                        </header>
                        <main>
                            <div class="row contacts">
                                <div class="col invoice-to">
                                    <div class="text-gray-light">INVOICE TO:</div>
                                    <h2 class="to">{{ ($booking->metadata['customer']['first_name'] ?? '') . ' ' . ($booking->metadata['customer']['last_name'] ?? '')}}</h2>
                                    <div class="address">{{($booking->metadata['customer']['address']['apartment_number']?? '').', '. ($booking->metadata['customer']['address']['apartment_name']?? '').', '.($booking->metadata['customer']['address']['street_one']?? '').', '. ($booking->metadata['customer']['address']['city'] ?? '')}}</div>
                                    <div class="address">{{$booking->metadata['customer']['phone']?? '' }}</div>

                                    <div class="email"><a href="mailto:john@example.com">{{ $booking->metadata['customer']['email']?? '' }}</a>
                                    </div>
                                </div>
                                <div class="col invoice-details">
                                    <h1 class="invoice-id">INVOICE </h1>
                                    <div class="date">Date of Invoice: {{ $booking->created_at->format('d/m/y') }}</div>
                                    <div class="date">Due Date: {{ $booking->created_at->format('d/m/y') }}</div>
                                </div>
                            </div>
                            <table>
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="text-left">DESCRIPTION</th>
                                    <th class="text-right">Cost</th>
                                    <th class="text-right">Quantity</th>
                                    <th class="text-right">TOTAL</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="no">01</td>
                                    <td class="text-left">
                                           <p>{{$booking->metadata['service']['name'] ?? ''}}</p>
                                    <td class="unit">${{$booking->metadata['service']['price'] ?? ''}}</td>
                                    <td class="qty">{{$booking->quantity}}</td>
                                    <td class="total">${{$booking->metadata['calculation']['with_tax'] ?? ''}}</td>
                                </tr>

                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="2"></td>
                                    <td colspan="2">SUBTOTAL</td>
                                    <td>${{$booking->metadata['calculation']['price'] ?? ''}}</td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td colspan="2">TAX </td>
                                    <td>${{$booking->metadata['calculation']['total_tax'] ?? ''}}</td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td colspan="2"> DISCOUNT</td>
                                    <td>${{$booking->metadata['calculation']['total_discount'] ?? ''}}</td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td colspan="2">GRAND TOTAL</td>
                                    <td>${{$booking->metadata['calculation']['total_amount'] ?? ''}}</td>
                                </tr>
                                </tfoot>
                            </table>
                            <div class="thanks">Thank you!</div>
{{--                            <div class="notices">--}}
{{--                                <div>NOTICE:</div>--}}
{{--                                <div class="notice">A finance charge of 1.5% will be made on unpaid balances after 30 days.</div>--}}
{{--                            </div>--}}
                        </main>
                    </div>
                    <div></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection