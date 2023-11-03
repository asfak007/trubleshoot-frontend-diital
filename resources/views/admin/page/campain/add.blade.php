@extends('admin.layouts.master')
@section('style')
    <link rel="stylesheet" href="{{asset('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css')}}" />
    <link rel="stylesheet" href="{{asset('https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css')}}" />
@endsection
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
        <div class="col">
            <div class="card">
                <div class="card-body">

                    <div class="tab-content py-3">
                        <div class="tab-pane fade show active" id="successhome" role="tabpanel">
                            <form class="row g-3" id="Form2" method="POST" action="{{ route('campaign.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="col-md-12">
                                    <label for="input1" class="form-label"> Name</label>
                                    <input type="text" class="form-control" id="input1" name="name" placeholder="Category Name" required>
                                </div>
                                <div class="col-md-12">
                                    <label for="input7" class="form-label">Zone</label>
                                    <select id="zoneSelect" class="form-select" name="zone_id" required>
                                        <option disabled selected>Choose...</option>
                                        @foreach($zones as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label for="multiple-select-field" class="form-label">Category select</label>
                                    <select class="form-select" id="categorySelect" name="category_id" data-placeholder="Choose anything" >
                                        <!-- Options will be loaded dynamically -->
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <div id="imagePreview" class="mb-3"></div>
                                    <label for="input3" class="form-label">Campaign Image</label>
                                    <input type="file" class="form-control" id="input3" name="image" required onchange="previewImage(event)">
                                </div>
                                <div class="col-md-12">
                                    <label for="input1" class="form-label"> Discount (%)</label>
                                    <input type="number" class="form-control" id="input1" name="discount" placeholder="10" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Starting Date:</label>
                                    <input type="date" name="start" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Ending Date:</label>
                                    <input type="date" name="end" class="form-control" required>
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
        </div>
    </div>
@endsection

@section('script')
    <script src="{{asset('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js')}}"></script>
    <script src="{{asset('assets/plugins/select2/js/select2-custom.js')}}"></script>
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function () {
                const imagePreview = document.getElementById('imagePreview');
                imagePreview.innerHTML = ''; // Clear any existing preview
                const imgElement = document.createElement('img');
                imgElement.src = reader.result;
                imgElement.alt = 'Category Image';
                imgElement.className = 'img-thumbnail';
                imgElement.style.width = '200px';
                imgElement.style.height = '200px';
                imagePreview.appendChild(imgElement);
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
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
        $(document).ready(function() {
            const BASE_URL = "{{ url('/') }}";

            function fetchCategoriesByZone() {
                const zoneId = $('#zoneSelect').val();

                if (!zoneId) {
                    $('#categorySelect').empty().append('<option disabled selected>Choose...</option>');
                    return;
                }

                $.ajax({
                    url: `${BASE_URL}/api/zones/${zoneId}/categories`,
                    type: 'GET',
                    success: function(categories) {
                        const categorySelect = $('#categorySelect');
                        categorySelect.empty();
                        categorySelect.append('<option disabled selected>Choose...</option>');

                        $.each(categories, function(index, category) {
                            categorySelect.append(`<option value="${category.id}">${category.name}</option>`);
                        });
                    },
                    error: function(error) {
                        console.error('Error:', error);
                    }
                });
            }

            $('#zoneSelect').on('change', fetchCategoriesByZone);
            fetchCategoriesByZone();
        });
    </script>
@endsection
