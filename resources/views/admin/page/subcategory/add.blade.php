@extends('admin.layouts.master')
@section('title')
    <title> Add Category Page</title>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
    </li>
    <li class="breadcrumb-item active" aria-current="page"> Category Inputs</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="card">
                <div class="card-body p-4">
                    <form class="row g-3" id="categoryForm" method="POST" action="{{ route('subcategories.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="col-md-12">
                            <label for="input1" class="form-label">Subcategory Name</label>
                            <input type="text" class="form-control" id="input1" name="name" placeholder="Category Name" required>
                        </div>
                        <div class="col-md-12">
                            <label for="input7" class="form-label">Category</label>
                            <select id="input7" class="form-select" name="category_id" required>
                                <option disabled selected>Choose...</option>
                                @foreach($categories as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <div id="imagePreview" class="mb-3"></div>
                            <label for="input3" class="form-label">Image</label>
                            <input type="file" class="form-control" id="input3" name="image" required onchange="previewImage(event)">
                        </div>
                        <div class="col-md-12">
                            <div class="d-md-flex d-grid justify-content-end gap-3">
                                <button type="submit" class="btn btn-primary px-4">Submit</button>
                                <button type="button" class="btn btn-light px-4" onclick="resetForm()">Reset</button>
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

        function resetForm() {
            const imagePreview = document.getElementById('imagePreview');
            imagePreview.innerHTML = '';
            document.getElementById("categoryForm").reset();
        }
    </script>
@endsection
