@extends('admin.layouts.master')
@section('title')
    <title> Add Extra Service Page</title>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
    </li>
    <li class="breadcrumb-item active" aria-current="page"> Extra Service Inputs</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="card">
                <div class="card-body p-4">
                    <form class="row g-3" id="categoryForm" method="POST" action="{{route('store-extra')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="col-md-12">
                            <label for="input1" class="form-label"> Name</label>
                            <input type="text" class="form-control" id="input1" name="name" placeholder="Extra Service  Name" required>
                        </div>
                        <div class="col-md-12">
                            <label for="input7" class="form-label">Service</label>
                            <select id="input7" class="form-select" name="service_id" required>
                                <option disabled selected>Choose...</option>
                                @foreach($service as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="input1" class="form-label"> Minimum Price</label>
                            <input type="number" class="form-control" id="input1" name="min_price" placeholder="100" required>
                        </div>
                        <div class="col-md-12">
                            <div class="d-md-flex d-grid justify-content-end gap-3">
                                <button type="submit" class="btn btn-primary px-4">Submit</button>
                                <button type="button" class="btn btn-light px-4" onclick="resetMyForm()">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

    <script>
        function submitForm() {
            // Add your code here to handle form submission
            // For example, you can use JavaScript or AJAX to submit the form data to the server
        }

        function resetMyForm() {
            document.getElementById("categoryForm").reset();
        }
    </script>
@endsection
