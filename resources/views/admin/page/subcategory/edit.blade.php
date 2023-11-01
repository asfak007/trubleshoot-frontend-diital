@extends('admin.layouts.master')
@section('title')
    <title> Sub Category Edit Page</title>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
    <li class="breadcrumb-item active" aria-current="page">Category Inputs</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-8 mx-auto">
            <div class="card">
                <div class="card-body p-4">
                    <form class="row g-3" id="categoryForm" method="POST" action="{{route('subcategory.update',$category->id)}}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') <!-- Use the PUT method for updating -->
                        <div class="col-md-12">
                            <label for="input1" class="form-label">Category Name</label>
                            <input type="text" class="form-control" id="input1" name="name" placeholder="Category Name" required value="{{ $category->name }}">
                        </div>
                            <div class="col-md-12">
                                <label for="input7" class="form-label">Category</label>
                                <select id="input7" class="form-select" name="category_id" required>

                                     @foreach($categories as $item)
                                         @if($item->id == $category->parent_id)
                                            <option value="{{$item->id}}" selected>{{$item->name}}</option>
                                         @endif
                                             @if($item->id != $category->parent_id)
                                                 <option value="{{$item->id}}" >{{$item->name}}</option>
                                             @endif
                                     @endforeach
                                </select>
                            </div>

                        <div class="col-md-12">
                            @if ($category->image)
                                <label class="form-label">Existing Image</label>
                                <div class="mb-3">
                                    <img id="existingImage" src="{{ config('app.url'). $category->image }}" alt="Category Image" class="img-thumbnail" style="width: 200px; height: 200px;">
                                </div>
                            @endif
                            <label for="input3" class="form-label">Category Image</label>
                            <input type="file" class="form-control" id="input3" name="image" onchange="previewImage(event)">
                        </div>
                        <div class="col-md-12">
                            <div class="d-md-flex d-grid justify-content-end gap-3">
                                <button type="submit" class="btn btn-primary px-4">Submit</button>
                                <a href="{{ route('list.subcategory') }}" class="btn btn-light px-4">Cancel</a>
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
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function () {
                const imgElement = document.getElementById('existingImage');
                imgElement.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }


    </script>
@endsection

