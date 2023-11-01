<!doctype html>
<html lang="en" class="light-theme">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--favicon-->
    <link rel="icon" href="{{asset('assets/images/favicon-32x32.png')}}" type="image/png" />

    <!--plugins-->
     @include('admin.layouts.css')
   @yield('style')
    @yield('title')

</head>

<body >

<!--wrapper-->
<div class="wrapper">
    <!--sidebar wrapper -->
    @include('admin.layouts.sidebar')
    <!--end sidebar wrapper -->
    <!--start header -->
    @include('admin.layouts.header')
    <!--end header -->
    <!--start page wrapper -->

    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                @yield('breadcrumb-title')

                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            @yield('breadcrumb-items')
                        </ol>
                    </nav>
                </div>

            </div>
            <!--end breadcrumb-->
            @yield('content')
        </div>
    </div>
    <!--end page wrapper -->
    <!--start overlay-->
    <div class="overlay toggle-icon"></div>
    <!--end overlay-->
    <!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
    <!--End Back To Top Button-->
    @include('admin.layouts.footer')
</div>
<!--end wrapper-->

<!-- search modal -->

<!-- end search modal -->



<!--start switcher-->

<!--end switcher-->

<!-- Bootstrap JS -->
@include('admin.layouts.script')
@yield('script')
</body>

</html>


