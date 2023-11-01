@extends('admin.layouts.master')

@section('title')
    <title>Edit Extra Service</title>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
    <li class="breadcrumb-item"><a href="{{ route('list-extra') }}">Extra Services</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Extra Service</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="card">
                <div class="card-body p-4">
                    <form class="row g-3" method="POST" action="{{ route('update-extra', $extraService->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="col-md-12">
                            <label for="inputName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="inputName" name="name" value="{{ $extraService->name }}" required>
                        </div>
                        <div class="col-md-12">
                            <label for="inputService" class="form-label">Service</label>
                            <select id="inputService" class="form-select" name="service_id" required>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" {{ $service->id == $extraService->service_id ? 'selected' : '' }}>{{ $service->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="inputMinPrice" class="form-label">Minimum Price</label>
                            <input type="number" class="form-control" id="inputMinPrice" name="min_price" value="{{ $extraService->min_price }}" required>
                        </div>
                        <div class="col-md-12">
                            <div class="d-md-flex d-grid justify-content-end gap-3">
                                <button type="submit" class="btn btn-primary px-4">Update</button>
                                <a href="{{ route('list-extra') }}" class="btn btn-light px-4">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
