@extends('admin.layouts.master')
@section('title')
    <title>Profile Update Page</title>
@endsection
@section('breadcrumb-title')
    <div class="breadcrumb-title pe-3"> Profile Update</div>
@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
    <li class="breadcrumb-item active" aria-current="page">Update  Inputs</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-8 mx-auto">
            <div class="card">
                <div class="card-body p-4">
                    <form class="row g-3" id="categoryForm" method="POST" action="{{ route('profile-update') }}" enctype="multipart/form-data">
                        @csrf
                         <!-- Use the PUT method for updating -->

                        <div class="col-md-12">
                            <label for="input1" class="form-label">Name</label>
                            <input type="text" class="form-control" id="input1" name="name"  required value="{{ $user->name }}">
                        </div>

                        <div class="col-md-12">
                            <label for="input1" class="form-label">Email</label>
                            <input type="text" class="form-control" id="input1" name="email"  required value="{{ $user->email }}">
                        </div>

                        <div class="col-md-12">
                            <label for="input1" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="input1" name="phone"  required value="{{ $user->phone }}">
                        </div>

                        <div class="col-md-12">
                            @if (!empty($user->image))
                                <label class="form-label">Existing Image</label>
                                <div class="mb-3">

                                    <img id="existingImage" src="{{ config('app.url') . $user->image }}" alt="Category Image" class="img-thumbnail" style="width: 200px; height: 200px;">
                                </div>
                            @endif
                            <label for="input3" class="form-label">Category Image</label>
                            <input type="file" class="form-control" id="input3" name="image" onchange="previewImage(event)">
                        </div>
                        <div class="col-md-12">
                            <div class="d-md-flex d-grid justify-content-end gap-3">
                                <button type="submit" class="btn btn-primary px-4">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function () {
                const imgElement = document.getElementById('existingImage');
                imgElement.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        function resetForm() {
            document.getElementById("categoryForm").reset();
        }
    </script>
@endsection

