@extends('admin.layouts.master')

@section('title')
    <title>Category List Page</title>
@endsection

@section('breadcrumb-title')
    <div class="breadcrumb-title pe-3">Category</div>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
    <li class="breadcrumb-item active" aria-current="page">Coupons </li>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Provider Name</th>
                        <th>Discount</th>
                        <th>Minimum Amount</th>
                        <th>Starting </th>
                        <th>Enging</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($coupons as $coupon)
                        <tr>
                            <td>{{ $coupon->name }}</td>
                            <td>{{ $coupon->code }}</td>
                            <td>{{ $coupon->provider->company_name }}</td>
                            <td>{{ $coupon->discount }}</td>
                            <td>{{ $coupon->min_amount }}</td>
                            <td>{{ $coupon->start }}</td>
                            <td>{{ $coupon->end }}</td>
                            <td>
                                <div class="d-flex order-actions justify-content-center">
                                    <!-- Edit Button -->
                                    <div class="row row-cols-auto g-3">
                                        <div class="col">
                                            <form method="GET" action="{{ route('coupon-edit', $coupon->id) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-primary">
                                                    <i class='bx bxs-edit mr-0'></i>
                                                </button>
                                            </form>
                                        </div>
                                        <div class="col">
                                            <form id="deleteForm{{ $coupon->id }}" method="POST" action="{{ route('delete-coupon', $coupon->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-outline-danger" onclick="showConfirmationPopup('deleteForm{{ $coupon->id }}')"><i class='bx bxs-trash me-0'></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
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
