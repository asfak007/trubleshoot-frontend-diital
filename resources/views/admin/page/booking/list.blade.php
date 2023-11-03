@extends('admin.layouts.master')

@section('title')
    <title>Payment List Page</title>
@endsection

@section('breadcrumb-title')
    <div class="breadcrumb-title pe-3">Payment</div>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
    <li class="breadcrumb-item active" aria-current="page">Payment list</li>
@endsection

@section('content')
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
        <div class="col">
            <a href="{{route('booking.list','completed')}}" class="card-link">
                <div  class="card radius-10">

                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Completed</p>
                                <h4 class="my-1">{{ $completedCount }}</h4>
                            </div>
                            <div class="widgets-icons bg-light-success text-success ms-auto"><i class="bx bxs-wallet"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col">
            <a href="{{route('booking.list','pending')}}" class="card-link">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Pending
                                </p>
                                <h4 class="my-1">{{$pendingCount}}</h4>
                            </div>
                            <div class="widgets-icons bg-light-info text-info ms-auto"><i class='bx bxs-group'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col">
            <a href="{{route('booking.list', 'canceled')}}" class="card-link">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Canceled
                                </p>
                                <h4 class="my-1">{{ $canceledCount }}</h4>
                            </div>
                            <div class="widgets-icons bg-light-danger text-danger ms-auto"><i class='bx bxs-binoculars'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col">
            <a href="{{route('booking.list','progressing')}}" class="card-link">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">In Progress
                                </p>
                                <h4 class="my-1">{{ $progressingCount }}</h4>
                            </div>
                            <div class="widgets-icons bg-light-warning text-warning ms-auto"><i class='bx bx-line-chart-down'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>

                    <tr>
                        <th>Booking Id</th>
                        <th>Customer Info</th>
                        <th>Total Amount</th>
                        <th>Payment Status</th>
                        <th>Status</th>>
                        <th>Schedule Date</th>
                        <th>Booking Date</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($bookings as $booking)
                        <tr>
                            <td>{{$booking->id}}</td>
                            <td>Name: {{ optional($booking->customer)->first_name . ' ' . optional($booking->customer)->last_name }}<br>
                                Phone: {{ optional($booking->customer)->phone }}</td>
                            <td>{{$booking->total_amount}}</td>
                            <td><span class="badge bg-{{ $booking->is_paid ? 'success' : 'warning' }} text-dark">
                                {{ $booking->is_paid ? 'Paid' : 'Unpaid' }}</span></td>

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
                                        $badgeClass = 'danger';
                                    } elseif ($booking->status === 'cancelled') {
                                        $badgeClass = 'danger';
                                    }
                                @endphp
                                <span class="badge bg-{{ $badgeClass }} ">
        {{ ucfirst($booking->status) }}
    </span>
                            </td>
                            <td>{{$booking->schedule}}</td>
                            <td>{{$booking->created_at}}</td>
                            <td><a href="{{route('booking.details',$booking->id)}}" class="btn btn-outline-secondary px-5">Details</a></td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <style>
        .confirmation-popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            border-radius: 8px;
            padding: 20px;
            opacity: 0; /* Start with 0 opacity */
            transition: opacity 0.3s ease; /* Add a transition effect for opacity */
        }

        .confirmation-popup.show {
            display: block; /* Show the pop-up */
            opacity: 1; /* Make it fully opaque when visible */
        }

        .confirmation-popup h3 {
            margin-top: 0;
            margin-bottom: 15px;
            font-size: 18px;
            color: #333;
        }

        .confirmation-popup .btn-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .confirmation-popup .btn-container button {
            margin: 0 10px;
            padding: 8px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .confirmation-popup .btn-confirm {
            background-color: #5cb85c;
            color: #ffffff;
        }

        .confirmation-popup .btn-cancel {
            background-color: #d9534f;
            color: #ffffff;
        }
    </style>

    <!-- The JavaScript to show the confirmation pop-up -->
    <script>
        // JavaScript for the confirmation pop-up using Swal
        function showConfirmationPopup(form) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You will not be able to recover this category!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'swal2-confirm',
                    cancelButton: 'swal2-cancel'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(form).submit();
                }
            });
        }
    </script>
    </script>
@endsection
