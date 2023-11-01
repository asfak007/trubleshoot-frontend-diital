@extends('admin.layouts.master')

@section('title')
    <title>provider List Page</title>
@endsection

@section('breadcrumb-title')
    <div class="breadcrumb-title pe-3">provider</div>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
    <li class="breadcrumb-item active" aria-current="page">provider Inputs</li>
@endsection

@section('content')
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
        <div class="col">
            <a href="{{route('provider.list')}}" class="card-link">
            <div  class="card radius-10">

                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total provider</p>
                            <h4 class="my-1">{{ $totalProviders ?? "Error"}}</h4>
                        </div>
                        <div class="widgets-icons bg-light-success text-success ms-auto"><i class="bx bxs-wallet"></i>
                        </div>
                    </div>
                </div>
            </div>
            </a>
        </div>
        <div class="col">
            <a href="{{route('provider.list', 'onboarding')}}" class="card-link">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Onboarding Request
                                </p>
                                <h4 class="my-1">{{ $onboardingRequests }}</h4>
                            </div>
                            <div class="widgets-icons bg-light-info text-info ms-auto"><i class='bx bxs-group'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col">
            <a href="{{route('provider.list', 'inactive')}}" class="card-link">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Inactive provider
                            </p>
                            <h4 class="my-1">{{ $inactiveProviders }}</h4>
                        </div>
                        <div class="widgets-icons bg-light-danger text-danger ms-auto"><i class='bx bxs-binoculars'></i>
                        </div>
                    </div>
                </div>
            </div>
            </a>
        </div>
        <div class="col">
            <a href="{{route('provider.list','active')}}" class="card-link">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Active provider
                                </p>
                                <h4 class="my-1">{{ $activeProviders }}</h4>
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
                        <th>Name</th>
                        <th>Contact Info</th>

                        <th>Total Service</th>
                        <th>Total Booking Served</th>

                        <th>Status</th>

                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($providers as $provider)
                        <tr>
                            <td>{{ $provider->company_name }}</td>
                             <td>
                                 <h5>{{$provider->contact_person_name}}</h5>
                                 <p>{{ $provider->contact_person_phone }}</p>
                                 <p>{{ $provider->account_email }}</p>

                             </td>
                            <td>{{ $provider->services_count ?? null }}</td>
                            <td>0</td>

                            <td><span class="badge bg-{{ $provider->status ? 'success' : 'danger' }}">
                                    {{ $provider->status ? 'Active' : 'Deactivate' }}</span></td>


                            <td>
                                <div class="d-flex order-actions justify-content-center">
                                    <!-- Edit Button -->
                                    <div class="row row-cols-auto g-3">
                                        <div class="col">
                                            <form method="GET" action="">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-primary">
                                                    <i class='bx bxs-book-content mr-0'></i>
                                                </button>
                                            </form>
                                        </div>
                                        <div class="col">
                                            <form id="deleteForm{{ $provider->id }}" method="POST" action="{{ route('provider.delete', $provider->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-outline-danger" onclick="showConfirmationPopup('deleteForm{{ $provider->id }}')"><i class='bx bxs-trash me-0'></i>
                                                </button>
                                            </form>
                                        </div>

                                    </div>
{{--                                    <a href="" class="btn btn-outline-primary" role="button">--}}
{{--                                                                                                    <i class='bx bxs-edit mr-0'></i> Edit--}}
{{--                                                                                                </a>--}}

{{--                                                                                                <!-- Delete Form with Confirmation -->--}}
{{--                                    <form id="deleteForm{{ $provider->id }}" method="POST" action="{{ route('categories.destroy', $provider->id) }}">--}}
{{--                                                                                                    @csrf--}}
{{--                                                                                                    @method('DELETE')--}}
{{--                                                                                                    <!-- Update the type to "button" -->--}}
{{--                                                                                                    <button type="button" class="ms-3 delete-icon btn btn-danger" onclick="showConfirmationPopup('deleteForm{{ $provider->id }}')">--}}
{{--                                                                                                        <i class="bx bxs-trash"></i> Delete--}}
{{--                                                                                                    </button>--}}
{{--                                                                                                </form>--}}
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
                text: 'You will not be able to recover this provider!',
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
