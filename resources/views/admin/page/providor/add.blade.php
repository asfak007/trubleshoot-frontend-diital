@extends('admin.layouts.master')


    @section('style')
        <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
        <style>
            /* Your existing styles here */

            /* Additional styles for image preview */
            #logoImagePreview .image-preview img,
            #identityImagePreview .image-preview img {
                width: 200px;
                height: 200px;
                object-fit: cover;
            }

            /* Add top margin between input field and preview images */
            #identityImagePreview {
                margin-top: 20px;
            }

            /* Clear icon style */
            .clear-icon {
                position: absolute;
                top: 0;
                right: 0;
                cursor: pointer;
                background-color: rgba(255, 255, 255, 0.8);
                border-radius: 50%;
                padding: 5px;
                display: none;
            }

            .image-preview:hover .clear-icon {
                display: block;
            }
        </style>
    @endsection


@section('title')
    <title>Add Provider Page</title>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
    <li class="breadcrumb-item active" aria-current="page">Provider Inputs</li>
@endsection

@section('content')
    <div id="stepper1" class="bs-stepper">
        <div class="card">
            <div class="card-header">
                <div class="d-lg-flex flex-lg-row align-items-lg-center justify-content-lg-between" role="tablist">
                    <div class="step" data-target="#test-l-1">
                        <div class="step-trigger" role="tab" id="stepper1trigger1" aria-controls="test-l-1">
                            <div class="bs-stepper-circle">1</div>
                            <div class="">
                                <h5 class="mb-0 steper-title">Provider Info</h5>
                                <p class="mb-0 steper-sub-title">Enter Your Details</p>
                            </div>
                        </div>
                    </div>
                    <div class="bs-stepper-line"></div>
                    <div class="step" data-target="#test-l-2">
                        <div class="step-trigger" role="tab" id="stepper1trigger2" aria-controls="test-l-2">
                            <div class="bs-stepper-circle">2</div>
                            <div class="">
                                <h5 class="mb-0 steper-title">Business Details</h5>
                                <p class="mb-0 steper-sub-title">Setup Business Details</p>
                            </div>
                        </div>
                    </div>
                    <div class="bs-stepper-line"></div>
                    <div class="step" data-target="#test-l-4">
                        <div class="step-trigger" role="tab" id="stepper1trigger4" aria-controls="test-l-4">
                            <div class="bs-stepper-circle">4</div>
                            <div class="">
                                <h5 class="mb-0 steper-title">Contract Info</h5>
                                <p class="mb-0 steper-sub-title">Contract Details</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="bs-stepper-content">
                    <form id="providerForm" method="post" action="{{ route('provider.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div id="test-l-1" role="tabpanel" class="bs-stepper-pane" aria-labelledby="stepper1trigger1">
                            <h5 class="mb-1">Your Company Information</h5>
                            <p class="mb-4">Enter  information to get closer to companies</p>
                            <div class="row g-3">
                                <div class="col-12 col-lg-6">
                                    <label for="FisrtName" class="form-label">Company Name</label>
                                    <input type="text" class="form-control" name="company_name" id="FisrtName" placeholder="Company / individual Name" required>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <label for="LastName" class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" id="LastName" placeholder="Enter Email Address" required>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <label for="PhoneNumber" class="form-label">Phone Number</label>
                                    <input type="text" class="form-control" name="phone" id="PhoneNumber" placeholder="Phone Number" required>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <label for="InputCountry" class="form-label">Zone</label>
                                    <select class="form-select" name="zone" id="InputCountry" aria-label="Default select example" required>
                                        <option disabled selected>Choose...</option>
                                        @foreach($zones as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <label for="input3" class="form-label">Company Logo</label>
                                    <input type="file" class="form-control" id="input3" name="image" required onchange="previewImage(event)">
                                </div>
                                <div class="col-12 col-lg-6">
                                    <label class="form-label">Starting Time:</label>
                                    <input type="time" name="start" class="form-control">
                                </div>
                                <div class="col-12 col-lg-6">
                                    <label class="form-label">Ending Time:</label>
                                    <input type="time" name="end" class="form-control">
                                </div>
                                <div class="col-12 col-lg-6">
                                    <label class="form-label">Off Day</label>
                                    <select class="form-select mb-3" name="off-day" aria-label="Day select menu">
                                        <option selected disabled>Choose a day</option>
                                        <option value="Monday">Monday</option>
                                        <option value="Tuesday">Tuesday</option>
                                        <option value="Wednesday">Wednesday</option>
                                        <option value="Thursday">Thursday</option>
                                        <option value="Friday">Friday</option>
                                        <option value="Saturday">Saturday</option>
                                        <option value="Sunday">Sunday</option>
                                    </select>

                                </div>
                                <div class="col-12 col-lg-6">
                                    <div id="imagePreview" class="mb-3"></div>
                                </div>

                                <div class="col-12 col-lg-12">
                                    <button type="button" class="btn btn-primary px-4" onclick="goToNextStep()">Next <i class='bx bx-right-arrow-alt ms-2'></i></button>
                                </div>
                            </div><!---end row-->
                        </div>

                        <div id="test-l-2" role="tabpanel" class="bs-stepper-pane" aria-labelledby="stepper1trigger2">
                            <h5 class="mb-1">Business Information</h5>
                            <p class="mb-4">Enter Your Business Details.</p>
                            <div class="row g-3">
                                <div class="col-12 col-lg-6">
                                    <label for="InputLanguage" class="form-label">Select Identity Type</label>
                                    <select class="form-select" name="identity_type" id="InputLanguage" aria-label="Default select example" required>
                                        <option disabled>---</option>
                                        <option value="Passport">Passport</option>
                                        <option value="Driving License">Driving License</option>
                                        <option value="Company Id">Company Id</option>
                                        <option value="NId">NId</option>
                                        <option value="Trade License">Trade License</option>
                                    </select>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <label for="InputEmail2" class="form-label">Identity Number</label>
                                    <input type="text" class="form-control" id="InputEmail2" name="identity_number" placeholder="43524352345" required>
                                </div>
{{--                                <div class="col-12 col-lg-6">--}}
{{--                                    <input id="fancy-file-upload" type="file" name="images" accept=".jpg, .png, image/jpeg, image/png" multiple>--}}
{{--                                </div>--}}
                                <div class="col-12 col-lg-6">
                                    <label for="fileInputIdentity" class="custom-file-upload">
                                        <i class="bx bx-cloud-upload upload-icon"></i> Upload Your Identity Images(front side)
                                    </label>
                                    <input type="file" class="form-control" id="fileInputIdentity" name="identity_image1" accept=".jpg, .png, image/jpeg, image/png"  onchange="previewIdentityImages(event)" required>
                                    <div id="identityImagePreview" class="mb-3 d-flex flex-wrap"></div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <label for="fileInputIdentity" class="custom-file-upload">
                                        <i class="bx bx-cloud-upload upload-icon"></i> Upload Your Identity Images(cover side)
                                    </label>
                                    <input type="file" class="form-control" id="fileInputIdentity" name="identity_image2" accept=".jpg, .png, image/jpeg, image/png"  onchange="previewIdentityImages(event)" required>
                                    <div id="identityImagePreview" class="mb-3 d-flex flex-wrap"></div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <label for="input3" class="form-label">Company  Documents </label>
                                    <input type="file" class="form-control" id="input3" name="document"  required >
                                </div>
                                <div class="col-12">
                                    <div class="d-flex align-items-center gap-3">
                                        <button type="button" class="btn btn-outline-secondary px-4" onclick="stepper1.previous()"><i class='bx bx-left-arrow-alt me-2'></i>Previous</button>
                                        <button type="button" class="btn btn-primary px-4" onclick="goToNextStep()">Next <i class='bx bx-right-arrow-alt ms-2'></i></button>
                                    </div>
                                </div>
                            </div><!---end row-->
                        </div>

                        <div id="test-l-4" role="tabpanel" class="bs-stepper-pane" aria-labelledby="stepper1trigger4">
                            <h6 class="mb-1">Contact Person</h6>
                            <div class="row g-3">
                                <div class="col-12 col-lg-6">
                                    <label for="InputUsername" class="form-label">Name</label>
                                    <input type="text" class="form-control" name="contact_person_name" id="InputUsername" placeholder="Name" required>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <label for="InputUsername" class="form-label">Phone</label>
                                    <input type="text" class="form-control" name="contact_person_phone" id="InputUsername" placeholder="017**********" required>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <label for="InputEmail2" class="form-label">E-mail Address</label>
                                    <input type="email" class="form-control" id="InputEmail2" name="contact_email" placeholder="example@xyz.com" required>
                                </div>
                                <h6 class="mb-1">Account Information</h6>
                                <div class="col-12 col-lg-6">
                                    <label for="InputUsername" class="form-label">First Name</label>
                                    <input type="text" class="form-control" name="first_name" id="InputUsername" placeholder="First Name" required>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <label for="InputUsername" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="InputUsername" name="last_name" placeholder="Last Name" required>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <label for="InputPassword" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="InputPassword" name="Password" value="12345678" required>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <label for="InputConfirmPassword" class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control" id="InputConfirmPassword" value="12345678" required>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex align-items-center gap-3">
                                        <button type="button" class="btn btn-outline-secondary px-4" onclick="stepper1.previous()"><i class='bx bx-left-arrow-alt me-2'></i>Previous</button>
                                        <button type="submit" class="btn btn-success px-4">Submit</button>
                                    </div>
                                </div>
                            </div><!---end row-->
                        </div>
                    </form>
                </div>
            </div>
        </div>


    </div>

    <!--end stepper one-->
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        function previewIdentityImages(event) {
            const previewContainer = document.getElementById("identityImagePreview");
            previewContainer.innerHTML = "";

            if (event.target.files) {
                const files = event.target.files;
                for (let i = 0; i < files.length; i++) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const image = document.createElement("div");
                        image.classList.add("image-preview", "position-relative");
                        const img = document.createElement("img");
                        img.src = e.target.result;
                        image.appendChild(img);
                        previewContainer.appendChild(image);
                    };
                    reader.readAsDataURL(files[i]);
                }
            }
        }

        function goToNextStep() {
            // Code here to handle moving to the next step (if applicable)
            stepper1.next();
        }
        function submitForm() {
            // Add your code here to handle form submission
            // For example, you can use JavaScript or AJAX to submit the form data to the server
        }

    </script>
{{--    <script type="text/javascript">--}}
{{--        @if ($message = Session::get('success'))--}}
{{--        toastr.success('{{ $message }}');--}}
{{--        @if ($message = Session::get('error'))--}}
{{--        toastr.error('{{ $message }}');--}}
{{--        @endif--}}
{{--    </script>--}}
@endsection
