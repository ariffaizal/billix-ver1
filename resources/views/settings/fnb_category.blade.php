@extends('layouts.main')

@section('title', $title)

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="mb-4">
                        <a href="{{ route('settings.fnb') }}" class="btn btn-outline-primary">
                            <i class="bi bi-list-columns"></i>
                            List Menus
                        </a>
                        <button type="button" class="btn btn-primary" id="btnAdd">
                            <i class="bi bi-plus-lg"></i>
                            Add New Category
                        </button>

                    </div>
                    <table class="table table-hover" id="tabelData">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Category Name</th>
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
                    <h1 class="modal-title fs-5" id="modalAddLabel">Form FnB</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="#" id="formAdd">
                        <div class="mb-3">
                            <label for="category_name" class="form-label">Category Name*</label>
                            <input type="text" name="category_name" class="form-control" id="category_name" required>
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

    @vite(['resources/js/settings/fnb_category.js'])

@endsection
