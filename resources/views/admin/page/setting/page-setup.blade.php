@extends('admin.layouts.master')
@section('style')

    <!-- include summernote css/js -->
    <link href='https://cdn.jsdelivr.net/npm/froala-editor@latest/css/froala_editor.pkgd.min.css' rel='stylesheet'
          type='text/css'/>

@endsection
@section('title')
    <title>Add Service Page</title>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
    <li class="breadcrumb-item active" aria-current="page">Service Inputs</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('page-settings.store') }}" method="post">
                @csrf

                <textarea id="editor" class="mt-5" name="{{$slug}}" required>{!! $data->{$slug} ?? '' !!}</textarea>


                <div class="col-md-12 mt-5">
                    <div class="d-md-flex d-grid justify-content-end gap-3">
                        <button type="submit" class="btn btn-primary px-4">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script type='text/javascript'
            src='https://cdn.jsdelivr.net/npm/froala-editor@latest/js/froala_editor.pkgd.min.js'></script>
    <script>
        // init Froala Editor
        new FroalaEditor('#editor');
    </script>
@endsection
