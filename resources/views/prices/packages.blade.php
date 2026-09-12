@extends('layouts.main')

@section('title', $title)

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="mb-4">
                        <button type="button" class="btn btn-primary" id="btnAdd">
                            <i class="bi bi-plus-lg"></i>
                            Create New Package
                        </button>
                    </div>
                    <table class="table table-hover" id="tabelData">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Time Limit</th>
                                <th>Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalAdd" aria-labelledby="modalAddLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalAddLabel">Form Package</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="#" id="formAdd">
                        <div class="mb-3">
                            <label for="package_name" class="form-label">Package Name*</label>
                            <input type="text" name="package_name" class="form-control" id="package_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="time_limit" class="form-label">Time limit*</label>
                            <input type="text" name="time_limit" class="form-control" id="time_limit" required
                                value="00:00:00">
                            <span>hours:minutes:seconds, e.g 01:30:00</span>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price*</label>
                            <input type="number" name="price" class="form-control" id="price" required>
                        </div>
                        <input type="hidden" name="id" id="id">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" form="formAdd" id="submitAdd">Save</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')

    @vite(['resources/js/prices/packages.js'])

@endsection
