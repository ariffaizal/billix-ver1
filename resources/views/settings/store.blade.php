@extends('layouts.main')

@section('title', $title)

@section('content')
    <div class="row">
        <div class="col-lg-6 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Store Information </h4>
                </div>
                <div class="card-body">
                    <form action="#" id="formStore">
                        <div class="mb-3">
                            <label for="store_name" class="form-label">Store Name*</label>
                            <input type="text" name="store_name" id="store_name" class="form-control"
                                value="{{ $data->store_name }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <input type="text" name="description" id="description" class="form-control"
                                value="{{ $data->store_desc }}">
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" name="address" id="address" class="form-control"
                                value="{{ $data->store_address_1 }}">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" name="email" id="email" class="form-control"
                                value="{{ $data->store_email }}">
                        </div>
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <input type="text" name="phone_number" id="phone_number" class="form-control"
                                value="{{ $data->store_phone }}">
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')

    @vite(['resources/js/settings/store.js'])

@endsection
