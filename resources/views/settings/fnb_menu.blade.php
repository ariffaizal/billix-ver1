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
                            Add New Menu
                        </button>
                        <a href="{{ route('settings.fnb.category') }}" class="btn btn-outline-primary">
                            <i class="bi bi-tags"></i>
                            Category
                        </a>
                    </div>
                    <div class="mb-3">
                        <label for="select_category" class="form-label">Select Category :</label>
                        <select name="select_category" id="select_category" class="form-select">
                            <option value="all">All Category</option>
                            @foreach ($data['category'] as $c)
                                <option value="{{ $c->id_fnbcategory }}">{{ $c->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <table class="table table-hover" id="tabelData">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Category</th>
                                <th>Menu Name</th>
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
                    <h1 class="modal-title fs-5" id="modalAddLabel">Form FnB</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="#" id="formAdd">
                        <div class="mb-3">
                            <label for="menu_name" class="form-label">Menu Name*</label>
                            <input type="text" name="menu_name" class="form-control" id="menu_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="category" class="form-label">Category*</label>
                            <select name="category" id="category" class="form-select">
                                <option value="">-- select category --</option>
                                @foreach ($data['category'] as $c)
                                    <option value="{{ $c->id_fnbcategory }}">{{ $c->category_name }}</option>
                                @endforeach
                            </select>
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

    @vite(['resources/js/settings/fnb_menu.js'])

@endsection
