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
                            Input Tax
                        </button>
                    </div>
                    <table class="table table-hover" id="tabelData">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Rate (%)</th>
                                <th>Remarks</th>
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
                    <h1 class="modal-title fs-5" id="modalAddLabel">Tax Rate</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="#" id="formAdd">
                        <div class="mb-3">
                            <label for="name" class="form-label">Rate (%)*</label>
                            <input type="text" name="rate" class="form-control" id="rate" required>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Remarks*</label>
                            <input type="text" name="remarks" class="form-control" id="remarks" required>
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

    @vite(['resources/js/settings/tax.js'])

@endsection
