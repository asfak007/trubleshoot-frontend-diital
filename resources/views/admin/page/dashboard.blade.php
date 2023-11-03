@php use App\Models\User;use Illuminate\Support\Facades\Auth; @endphp
@extends('admin.layouts.master')
@section('title')
    <title> Home </title>
@endsection
{{--@section('style')--}}
{{--    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>--}}
{{--@endsection--}}
@section('breadcrumb-title')
    <div class="breadcrumb-title pe-3">Dashboard</div>
@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@section('content')
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
        <div class="col">
            <div class="card radius-10 border-start border-0 border-4 border-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Bookings</p>
                            <h4 class="my-1 text-info">{{ $totalBookingsCount }}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-blues text-white ms-auto"><i class='bx bxs-cart'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 border-start border-0 border-4 border-danger">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Revenue</p>
                            <h4 class="my-1 text-danger">৳ {{$totalRevenue}}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-burning text-white ms-auto"><i class='bx bxs-wallet'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 border-start border-0 border-4 border-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Service</p>
                            <h4 class="my-1 text-success">{{$totalServiceCount}}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto"><i class='bx bxs-bar-chart-alt-2' ></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 border-start border-0 border-4 border-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Customers</p>
                            <h4 class="my-1 text-warning">{{$totalCustomerCount}}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-orange text-white ms-auto"><i class='bx bxs-group'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="card">
        <div class="card-body">
            <canvas id="bookingChart" style="height: 400px;"></canvas>
        </div>
    </div>

    <div class="row">
        <div class="col-4 col-lg-4 ">
            <div class="card radius-10">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6 class="mb-0">Recent Provider</h6>
                        </div>

                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Photo</th>
                                <th>Ratings</th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($providers as $provider)
                            <tr>
                                <td>{{$provider->company_name}}</td>
                                <td><img src="{{asset($provider->image) ?? ''}}" class="product-img-2" alt="product img"></td>
                                <td>{{$provider->avg_rating}}</td>

                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-4 col-lg-4 ">
            <div class="card radius-10">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6 class="mb-0">Recent Customers</h6>
                        </div>
{{--                        <div class="dropdown ms-auto">--}}
{{--                            <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown"><i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>--}}
{{--                            </a>--}}
{{--                            <ul class="dropdown-menu">--}}
{{--                                <li><a class="dropdown-item" href="javascript:;">Action</a>--}}
{{--                                </li>--}}
{{--                                <li><a class="dropdown-item" href="javascript:;">Another action</a>--}}
{{--                                </li>--}}
{{--                                <li>--}}
{{--                                    <hr class="dropdown-divider">--}}
{{--                                </li>--}}
{{--                                <li><a class "dropdown-item" href="javascript:;">Something else here</a>--}}
{{--                                </li>--}}
{{--                            </ul>--}}
{{--                        </div>--}}
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Photo</th>
                                <th>Date</th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($customers as $customer)
                            <tr>
                                <td>{{$customer->name}}</td>
                                <td><img src="{{asset($customer->image) ?? ''}}" class="product-img-2" alt="product img"></td>
                                <td>{{$customer->created_at}}</td>

                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-4 ">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6 class="mb-0">Recent Bookings</h6>
                        </div>
{{--                        <div class="dropdown ms-auto">--}}
{{--                            <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown"><i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>--}}
{{--                            </a>--}}
{{--                            <ul class="dropdown-menu">--}}
{{--                                <li><a class="dropdown-item" href="javascript:;">Action</a>--}}
{{--                                </li>--}}
{{--                                <li><a class="dropdown-item" href="javascript:;">Another action</a>--}}
{{--                                </li>--}}
{{--                                <li>--}}
{{--                                    <hr class="dropdown-divider">--}}
{{--                                </li>--}}
{{--                                <li><a class "dropdown-item" href="javascript:;">Something else here</a>--}}
{{--                                </li>--}}
{{--                            </ul>--}}
{{--                        </div>--}}
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                            <tr>
                                <th>Booking Id</th>
                                <th>Photo</th>
                                <th>Date</th>
                                <th>Status</th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($bookings as $booking)
                                <tr>
                                    <td>{{$booking->id}}</td>
                                    <td><img src="{{ asset($booking->service?->image) }}" class="product-img-2" alt="product img"></td>
                                    <td>{{$booking->created_at}}</td>

                                    <td>
                                        @php
                                            $badgeClass = 'secondary ';
                                            if ($booking->status === 'accepted') {
                                                $badgeClass = 'success';
                                            } elseif ($booking->status === 'completed') {
                                                $badgeClass = 'success ';
                                            } elseif ($booking->status === 'progressing') {
                                                $badgeClass = 'info';
                                            } elseif ($booking->status === 'rejected') {
                                                $badgeClass = 'gradient-bloody';
                                            } elseif ($booking->status === 'cancelled') {
                                                $badgeClass = 'danger';
                                            }elseif ($booking->status === 'pending') {
                                                $badgeClass = 'gradient-blooker';
                                            }
                                        @endphp
                                        <span class="badge bg-{{ $badgeClass }} ">
                                        {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Get the booking data from the server
        $.ajax({
            url: "{{ route('get.booking.data') }}", // Update this route to match your application
            method: "GET",
            success: function (data) {
                // Data received from the server
                const bookingData = data;

                // Define the labels for the months
                const monthLabels = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

                // Create an array to hold the count of completed bookings for each month
                const dataCounts = Array(12).fill(0);

                // Populate the dataCounts array with the actual counts from the data
                bookingData.forEach(item => {
                    const monthIndex = item.month - 1; // Months are 1-based
                    dataCounts[monthIndex] = item.count;
                });

                // Create a Chart.js chart
                const ctx = document.getElementById("bookingChart").getContext("2d");
                new Chart(ctx, {
                    type: "line",
                    data: {
                        labels: monthLabels,
                        datasets: [{
                            label: "Booking Count",
                            data: dataCounts,
                            borderColor: "rgba(75, 192, 192, 1)",
                            backgroundColor: "rgba(75, 192, 192, 0.2)",
                            tension: 0.4,
                            borderWidth: 3,
                            pointBackgroundColor: "rgba(75, 192, 192, 1)",
                            pointRadius: 6,
                            pointHoverRadius: 8,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                beginAtZero: true,
                            },
                            y: {
                                beginAtZero: true,
                            },
                        },
                        elements: {
                            line: {
                                fill: false,
                            },
                        },
                        plugins: {
                            legend: {
                                display: false,
                            },
                            tooltips: {
                                enabled: true,
                            },
                        },
                    },
                });
            },
            error: function () {
                // Handle the error
            }
        });
    </script>
@endsection

