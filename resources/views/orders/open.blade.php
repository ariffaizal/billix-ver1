@extends('layouts.main')

@section('title', $title)

@section('content')
    <div class="row">
        <div class="col-lg-8 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-row justify-content-between mb-3">
                        <div>
                            <h4>Order ID #{{ $data['order']->id_order }}</h4>
                            <p>Status : {!! $data['status'] !!}</span></p>

                        </div>
                        <div class="hstack gap-2 align-items-start">
                            <button class="btn btn-primary" id="btnAddFnB">
                                <i class="bi bi-plus"></i> Add FnB Items
                            </button>
                            <a href="{{ route('orders') }}" class="btn btn-outline-primary"><i class="bi bi-arrow-left"></i>
                                Order List</a>
                        </div>
                    </div>
                    <table class="table" id="tableItems">
                        <thead>
                            <th>Items</th>
                            <th>Package / Qty</th>
                            <th class="text-end">Price</th>
                            <th class="text-end">Amount</th>
                            <th>Action</th>
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
                    <form action="{{ route('orders.openbill_process') }}" method="POST" id="formProcess">
                        @csrf
                        <div class="mb-3">
                            <label for="bill_name" class="form-label">Bill To*</label>
                            <input type="text" class="form-control" name="bill_name" id="bill_name" required
                                value="{{ $data['order']->bill_name }}">
                        </div>

                        <div class="mb-3">
                            <table class="table">
                                <tr>
                                    <td>Subtotal</td>
                                    <td class="text-end">
                                        <span id="showTotal"></span>
                                    </td>
                                </tr>
                                @if ($data['order']->order_status == 3 && $data['order']->order_type == 'open_bill')
                                    <tr>
                                        <td>Total (current estimate)</td>
                                        <td class="text-end">
                                            <span id="showTotalEstimate"></span>
                                        </td>
                                    </tr>
                                @endif
                            </table>

                        </div>
                        @if ($data['order']->order_status == 3 && $data['order']->order_type == 'open_bill')
                            <div class="mb-3">
                                <button type="button" class="btn btn-lg btn-block btn-danger" id="btnStop">
                                    <i class="bi bi-stop-circle"></i> Stop Session</button>
                            </div>
                        @elseif ($data['order']->order_status == 4 && $data['order']->order_type == 'open_bill')
                            <div class="mb-3">
                                <input type="hidden" name="id_order" value="{{ $data['order']->id_order }}">
                                <button type="submit" class="btn btn-lg btn-block btn-success">
                                    <i class="bi bi-check-circle"></i> Process Payment</button>
                            </div>
                        @else
                            <div class="mb-3">
                                <button type="button" class="btn btn-lg btn-block btn-success" id="btnStart">
                                    <i class="bi bi-play-circle"></i> Start Session</button>
                            </div>
                            <div>
                                <button type="button" class="btn btn-lg btn-block btn-outline-danger" id="cancelOrders">
                                    <i class="bi bi-x-circle"></i> Cancel </button>
                            </div>
                        @endif
                    </form>
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


    <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="modalTransfer"
        aria-labelledby="modalTransferLabel">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalTransferLabel">Transfer Table</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="#" id="formTransfer">
                        @csrf
                        <div class="mb-3">
                            <span>Select New Table</span>
                        </div>
                        <div class="mb-3">
                            <div id="showAvailableTable"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" form="formTransfer" id="submitTransfer">
                        Transfer
                    </button>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('script')

    <script src="{{ asset('assets/ext/datatables/dataTables.rowGroup.min.js') }}"></script>
    @vite(['resources/js/orders/open.js', 'resources/js/orders/services.js'])

    @if ($data['order']->order_status == 3)
        @vite(['resources/js/orders/transfer.js'])

        <script>
            function fetchEstimate() {
                $.ajax({
                    url: "/orders/estimate/{{ $data['order']->id_order }}",
                    method: "GET",
                    dataType: "JSON",
                    success: function(data) {
                        let numFormat = DataTable.render.number(".", ",", 0).display;
                        $("#showTotalEstimate").html(numFormat(data.estimate));
                    },
                    error: function(e) {
                        console.error("Error fetching estimate:", e);
                    },
                });
            }

            // Call fetchEstimate every 1 minute
            setInterval(fetchEstimate, 6000);

            // Initial call to fetchEstimate
            fetchEstimate();
        </script>
    @endif

@endsection
