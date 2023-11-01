@extends('admin.layouts.master')

@section('title')
    <title>Booking Details Page</title>
@endsection



@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
    <li class="breadcrumb-item active" aria-current="page">Booking Details list</li>
@endsection

@section('content')
  <div class="row">
      <div class="col-xl-12 mx-auto">
          <div class="card">
              <div class="card-body p-5">
               <div class="card-title">
                   <div class="row row-cols-sm-12">
                       <div class="col-md-10">
                           <h3 class="text-secondary">Booking # {{  $details->id }}</h3>
                           <p>Booking Placed : {{$details->created_at}}</p>
                       </div>
                       <div class="col-md-2 ">
                           <a href="{{ route('invoice',$details->id) }}" class="btn btn-primary px-5">Invoice</a>
                       </div>
                   </div>

               </div>
                  <hr>
                  <div>
                      <div class="row row-cols-sm-12">
                          <div class="col-8">
                              <h5 class="my-1">Payment Method</h5>
                              <h6 class="c1 mb-2 text-sky-600p">{{ $details->payment_method == 'cod' ? "Hand Cash" : 'Online' }}</h6>
                              <p>
                                  <span>Amount : </span> {{$details->total_amount}}$
                              </p>
                          </div>
                          <div class="col-4">
                              <p class="mb-2"><span>Booking Status :</span> <span class="c1 text-primary" >{{$details->status}}</span></p>
                              <p class="mb-2"><span>Payment Status : </span> <span class="{{ $details->is_paid  ? 'text-success' : 'text-danger' }}">{{ $details->is_paid ? 'Paid' : 'Unpaid' }}</span>
                              </p>
                              <h6 class="card-title cursor-pointer">Service Schedule Date : <span >{{ $details->schedule }}</span></h6>
                          </div>


                      </div>


                  </div>
                  <hr>
                  <div class="row ">
                      <div class="col-4">
                          <div class="card bg-light radius-10">
                              <div class="card-body">
                                  <div class="justify-content-start gap-2">
                                      <h6 class="">Customer Information</h6>
                                  <p class="text-primary"><strong>{{$details->metadata['customer']['first_name'] ?? ''}} {{ $details->metadata['customer']['last_name'] ?? ''}}</strong></p>
                                      <p class="card-text fs-9">Phone: {{$details->metadata['customer']['phone']?? ''}}</p>
                                      <p class="card-text fs-9">Address: {{$details->metadata['customer']['address']['apartment_number']?? ''}}, {{$details->metadata['customer']['address']['street_one']?? ''}}, {{$details->metadata['customer']['address']['city']?? ''}}</p>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="col-4">
                          <div class="card bg-light radius-10">
                              <div class="card-body">
                                  <div class="justify-content-start gap-2">
                                      <h6 class="">Provider Information</h6>
                                      <p class="text-primary"><strong>{{$details->provider->company_name ?? ''}}</strong></p>
                                      <p class="card-text fs-9">Phone: {{$details->provider->contact_person_phone ?? ''}}</p>
                                      <p class="card-text fs-9">Address: {{$details->provider->address ?? ''}}</p>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="col-4">
                          <div class="card bg-light radius-10">
                              <div class="card-body">
                                  <div class="justify-content-start gap-2">
                                      <h6 class="">Handyman Information</h6>
                                      @if($details->handyman != null)
                                          <p class="text-primary"><strong>{{ $details->handyman->name ?? ''}}</strong></p>
                                      <p class="card-text fs-9">Phone: {{ $details->handyman->phone ?? ''}}</p>
                                      <p class="card-text fs-9">Address: {{ $details->handyman->address ?? ''}}</p>
                                      @endif

                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="d-flex justify-content-start gap-2">
                          <h4 class="mb-3">Booking Summary</h4>
                      </div>
                      <table class="table table-borderless mb-1.5">
                          <thead class="table-light">
                          <tr>
                              <th scope="col">Service</th>
                              <th scope="col">price</th>
                              <th scope="col">Quantity</th>
                              <th scope="col">Discount</th>
                              <th scope="col">Total</th>
                          </tr>
                          </thead>
                          <tbody>
                          <tr>
                              <th scope="row">{{ $details->metadata['service']['name'] ?? ''}}</th>
                              <td>{{ $details->metadata['service']['price'] ?? ''}}</td>
                              <td>{{ $details->quantity ?? ''}}</td>
                              <td>{{ $details->metadata['service']['discount'] ?? ''}} %</td>
                              <td>{{$details->metadata['calculation']['with_discount']?? ''}}</td>
                          </tr>
                          </tbody>
                      </table>
                      <hr>
                     <div class="row justify-content-end">
                         <div class="col-sm-10 col-md-6 col-xl-5">
                             <div class="table-responsive">
                                 <table class="table-sm title-color align-right w-100">
                                  <tbody>
                                       <tr>
                                           <td>Sub Total</td>
                                           <td>{{ $details->metadata['calculation']['price'] ?? ''}}$</td>
                                       </tr>
                                       <tr>
                                           <td>Discount</td>
                                           <td>{{ $details->metadata['calculation']['total_discount'] ?? ''}}$</td>
                                       </tr>
{{--                                       <tr>--}}
{{--                                           <td>Coupon Discount</td>--}}
{{--                                           <td>{{ $details->metadata['calculation']['price'] }}$</td>--}}
{{--                                       </tr>--}}
                                       <tr>
                                           <td>Tax</td>
                                           <td>{{ $details->metadata['calculation']['total_tax'] ?? ''}}$</td>
                                       </tr>
                                       <tr>
                                           <td>Extra Service Charge</td>
                                           <td>{{ $details->additional_charge ?? ''}}$</td>
                                       </tr>

                                       <tr>
                                           <th>Total</th>
                                           <th>{{ $details->total_amount ?? ''}}$</th>
                                       </tr>
                                  </tbody>
                                 </table>
                             </div>
                         </div>
                     </div>
                  <!--end row-->
          </div>
      </div>
  </div>


@endsection


