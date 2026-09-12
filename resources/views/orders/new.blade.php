@extends('layouts.main')

@section('title', $title)

@section('script_tag')
    <link rel="stylesheet" href="{{ asset('assets/ext/choices.js/choices.min.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-8 col-sm-12">
            <div class="card">
                <div class="card-body">
                    @php
                        $orderType = $data['orders']->order_type;
                    @endphp
                    <div class="mb-4">
                        @if ($orderType == 'open_bill' || $orderType == 'packages')
                            <button class="btn btn-lg btn-primary" id="btnAddTable">
                                <i class="bi bi-plus"></i> Add Tables
                            </button>
                        @endif
                        <button class="btn btn-lg btn-primary" id="btnAddFnB">
                            <i class="bi bi-plus"></i> Add FnB Items
                        </button>

                        <a href="{{ route('orders') }}" class="btn btn-lg btn-outline-primary"><i
                                class="bi bi-arrow-left"></i>
                            Orders List</a>
                    </div>
                    <table class="table" id="tabelFnBItems">
                        <thead>
                            <th>Items</th>
                            <th>Package / Qty</th>
                            <th>Price</th>
                            <th>Amount</th>
                            <th></th>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <th></th>
                            <th></th>
                            <th class="text-end">Subtotal</th>
                            <th></th>
                            <th></th>
                        </tfoot>
                    </table>

                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12">
            <div class="card">
                <div class="card-body">
                    <form id="formProcess">
                        @csrf
                        <input type="hidden" name="countItems" id="countItems">
                        <input type="hidden" name="total" id="input_total" value="{{ $data['orders']->order_subtotal }}">
                        <input type="hidden" name="id_order" value="{{ $data['orders']->id_order }}">
                        @if ($orderType == 'open_bill')
                            <div class="mb-3">
                                <label for="bill_name" class="form-label">Bill To*</label>
                                <input type="text" class="form-control" name="bill_name" id="bill_name" required
                                    value="{{ $data['orders']->bill_name }}">
                            </div>
                            <div class="mb-3">
                                <label for="member" class="form-label">Member</label>
                                <select name="member" id="member" class="form-select">
                                    <option value="" selected>None</option>
                                    @foreach ($data['member'] as $member)
                                        <option value="{{ $member->id_member }}">{{ $member->member_name }}
                                            [{{ $member->member_no }}]</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="mb-3">
                            <table class="table">
                                <tr>
                                    <td class="font-bold">Subtotal</td>
                                    <td class="font-bold text-end">
                                        <span id="showTotal"></span>
                                    </td>
                                </tr>
                            </table>

                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-lg btn-block btn-success" id="submitFormProses" disabled>
                                <i class="bi bi-check2-circle"></i> Process</button>
                        </div>
                        <div>
                            <button type="button" class="btn btn-lg btn-block btn-outline-danger" id="cancelOrders">
                                <i class="bi bi-x-circle"></i> Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="modalAddTable"
        aria-labelledby="modalAddTableLabel">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalAddTableLabel">Table List</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <form action="#" id="formAddTable">
                        @csrf
                        <table class="table" id="tableAvailable">
                            <thead>
                                <th>Name</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <input type="hidden" name="id_order" value="">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" form="formAddTable" id="submitAddTable">
                        Add Selected
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="modalAddFnB"
        aria-labelledby="modalAddFnBLabel">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalAddFnBLabel">FnB Menus</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <form action="#" id="formAddFnB">
                        @csrf
                        <table class="table" id="tableFnbMenus">
                            <thead>
                                <th>Category</th>
                                <th>Name</th>
                                <th>Price</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <input type="hidden" name="id_order" value="">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" form="formAddFnB" id="submitAddFnB">
                        Add Selected
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')

    <script src="{{ asset('assets/ext/datatables/dataTables.rowGroup.min.js') }}"></script>
    <script src="{{ asset('assets/ext/choices.js/choices.js') }}"></script>
    @vite(['resources/js/orders/new.js'])
    <script>
        $(function() {
            $("#member").change(function() {
                let memberVal = $(this).val();
                if (memberVal != "") {
                    $("#bill_name").prop("disabled", true).prop("required", false);
                } else {
                    $("#bill_name").prop("disabled", false).prop("required", true);
                }
            });
            const choices = new Choices($("#member")[0]);
        });
    </script>

@endsection
