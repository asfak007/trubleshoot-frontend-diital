@extends('admin.layouts.master')
@section('style')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <!-- include summernote css/js -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
@endsection
@section('title')
    <title>Add Service Page</title>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
    <li class="breadcrumb-item active" aria-current="page">Service Inputs</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="card">
                <div class="card-body p-4">
                    <form class="row g-3" id="categoryForm" method="Post" action="{{ route('service.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="col-md-4">
                            <label for="input1" class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" id="input1" placeholder="Name">
                        </div>
                        <div class="col-md-4">
                            <label for="zoneSelect" class="form-label">Select Zone</label>
                            <select name="zone_id" id="zoneSelect" class="form-select">
                                <option disabled selected >Choose...</option>
                                @foreach($zones as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="categorySelect" class="form-label">Select Category</label>
                            <select name="category_id" id="categorySelect" class="form-select">

                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="subcategorySelect" class="form-label">Sub Category</label>
                            <select name="subcategory_id" id="subcategorySelect" class="form-select">

                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="input4" class="form-label">Assign Service To Provider</label>
                            <select id="input4" name="provider_id" class="form-select">

                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="input5" class="form-label">Price Type</label>
                            <select id="input5" name="price_type" class="form-select">
                                <option selected value="fixed">Fixed</option>
                                <option value="hourly">Hourly</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="input1" class="form-label">Price</label>
                            <input type="number" name="price" class="form-control" id="input1" placeholder="First Name">
                        </div>
                        <div class="col-md-4">
                            <label for="input1" class="form-label">Discount %</label>
                            <input type="number" name="discount" class="form-control" id="input1" placeholder="First Name">
                        </div>
                        <div class="col-md-4">
                            <label for="input1" class="form-label">Duration (Hours:Minutes)</label>
                            <input type="text" class="form-control" name="duration" id="duration" placeholder="1:00">
                        </div>
                        <div class="col-md-4">
                            <label for="input5" class="form-label">Status</label>
                            <select id="input5" class="form-select" name="status">
                                <option value="1" selected>Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="input11" class="form-label">Short description</label>
                            <textarea class="form-control" name="short_description" id="input11" placeholder="Address ..." rows="5"></textarea>
                        </div>
                        <div class="col-md-12">
                            <label for="input11" class="form-label">Long description</label>
                            <textarea class="form-control" name="long_description" id="editor" placeholder="Address ..." rows="5"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Image</label>
                            <input class="form-control" type="file" name="image" id="formFile">
                        </div>
                        <div class="col-md-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_featured" id="input12">
                                <label class="form-check-label" for="input12">Set as featured</label>
                            </div>
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
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        ClassicEditor
            .create( document.querySelector( '#editor' ) )
            .catch( error => {
                console.error( error );
            } );
    </script>
    <script>
        $(document).ready(function() {
            $('#duration').on('input', function() {
                var input = $(this).val();
                var isValid = /^([01]?[0-9]|2[0-3]):([0-5][0-9])$/.test(input);
                $(this).toggleClass('is-invalid', !isValid);
            });

            // Set the base URL for your Laravel project here
            const BASE_URL = "{{ url('/') }}";

            // Function to fetch categories based on the selected zone using AJAX
            function fetchCategoriesByZone() {
                const zoneId = $('#zoneSelect').val();

                if (!zoneId) {
                    // If zoneId is null, clear the category select dropdown
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

            // Function to fetch providers based on the selected zone using AJAX
            function fetchProvidersByZone() {
                const zoneId = $('#zoneSelect').val();

                if (!zoneId) {
                    // If zoneId is null, clear the provider select dropdown
                    $('#input4').empty().append('<option selected>Choose...</option>');
                    return;
                }

                $.ajax({
                    url: `${BASE_URL}/api/zones/${zoneId}/providers`,
                    type: 'GET',
                    success: function(providers) {
                        const providerSelect = $('#input4');
                        providerSelect.empty();
                        providerSelect.append('<option selected>Choose...</option>');

                        $.each(providers, function(index, provider) {
                            providerSelect.append(`<option value="${provider.id}">${provider.company_name}</option>`);
                        });
                    },
                    error: function(error) {
                        console.error('Error:', error);
                    }
                });
            }

            // Attach the event listener to the zone select dropdown
            $('#zoneSelect').on('change', function() {
                fetchCategoriesByZone();
                fetchProvidersByZone();
            });

            // Trigger the initial fetch of categories and providers when the page loads
            fetchCategoriesByZone();
            fetchProvidersByZone();
        });
    </script>
    <script>
        // Function to update the subcategory options based on the selected category
        function updateSubcategories() {
            // Get the selected category ID
            const categoryId = $('#categorySelect').val();

            if (!categoryId) {
                // If categoryId is null, clear the subcategory select dropdown
                $('#subcategorySelect').empty().append('<option disabled selected>Open this select menu</option>');
                return;
            }

            // Set the base URL for your Laravel project here
            const BASE_URL = "{{ url('/') }}";

            // Send an AJAX request to fetch the subcategories based on the selected category
            $.ajax({
                url: `${BASE_URL}/api/categories/${categoryId}/subcategories`,
                type: 'GET',
                success: function(subcategories) {
                    const subcategorySelect = $('#subcategorySelect');
                    subcategorySelect.empty();
                    subcategorySelect.append('<option disabled selected>Open this select menu</option>');

                    $.each(subcategories, function(index, subcategory) {
                        subcategorySelect.append(`<option value="${subcategory.id}">${subcategory.name}</option>`);
                    });
                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });
        }

        // Attach the event listener to the category select dropdown
        $('#categorySelect').on('change', function() {
            updateSubcategories();
        });

        // Trigger the initial update of subcategories when the page loads
        updateSubcategories();
    </script>
    <script>

        function submitForm() {
            const BASE_URL = "{{ url('/') }}";
            const form = document.getElementById('categoryForm');

            // Perform AJAX form submission
            $.ajax({
                url: form.getAttribute('action'),
                type: 'POST',
                data: new FormData(form),
                processData: false,
                contentType: false,
                {{--success: function(response) {--}}
                    {{--    console.log('Form submitted successfully!', response);--}}
                    {{--    --}}{{--// Optionally, you can redirect the user to another page after form submission--}}
                    {{--    --}}{{--// window.location.href = "{{ route('success.page') }}";--}}
                    {{--},--}}
                error: function(error) {
                    console.error('Error:', error);
                }
            });
        }

        function resetForm() {
            document.getElementById("categoryForm").reset();
        }
    </script>

@endsection
