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
                        </thead>
                        <tbody>
                            @foreach ($data['items'] as $i)
                                <tr>
                                    <td>{{ $i['items'] }}
                                        @if ($data['order']->order_status == 3 && $i['is_table'] == 1)
                                            <button class="btn btn-sm btn-outline-info btnTransfer"
                                                data-id="{{ $i['id_order_item'] }}">
                                                <i class="bi bi-arrow-left-right"></i> Transfer
                                            </button>
                                        @endif
                                    </td>
                                    <td>{{ $i['qty'] }}</td>
                                    <td class="text-end">{{ number_format($i['price'], 0, ',', '.') }}</td>
                                    <td class="text-end">{{ number_format($i['amount'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <table class="table">
                            <tr>
                                <td>Bill To</td>
                                <td class="text-end">
                                    {{ $data['order']->bill_name }}
                                </td>
                            </tr>
                            {{-- <tr>
                                <td>Payment Method</td>
                                <td class="text-end">
                                    {{ $data['order']->pay_method }}
                                </td>
                            </tr> --}}
                            <tr>
                                <td>Subtotal</td>
                                <td class="text-end">
                                    {{ number_format($data['order']->price_subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <td>Discount</td>
                                <td class="text-end">
                                    {{ number_format($data['order']->price_discount, 0, ',', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <td>VAT</td>
                                <td class="text-end">
                                    {{ number_format($data['order']->price_vat, 0, ',', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <td class="font-extrabold">Total Refund</td>
                                <td class="font-extrabold text-end">
                                    {{ number_format($data['order']->price_total, 0, ',', '.') }}
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="mb-3">
                        <a href="{{ route('orders.print', ['id_order' => $data['order']->id_order]) }}" target="_blank"
                            class="btn btn-lg btn-block btn-primary">
                            <i class="bi bi-printer"></i> Print Receipt</a>
                    </div>

                </div>
            </div>
        </div>
    </div>


@endsection

@section('script')

    {{-- @if ($data['order']->order_type == 'packages' && $data['order']->order_status == 2)
        @vite(['resources/js/orders/services.js'])
    @endif
    @if ($data['order']->order_type == 'packages' && $data['order']->order_status == 3)
        @vite(['resources/js/orders/transfer.js'])
    @endif
    @if ($data['order']->order_type == 'fnb_only' && $data['order']->order_status == 9)
        @vite(['resources/js/orders/view.js'])
    @endif --}}
@endsection
