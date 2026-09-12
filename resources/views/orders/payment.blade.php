@extends('layouts.main')

@section('title', $title)

@section('script_tag')
    <link rel="stylesheet" href="{{ asset('assets/ext/choices.js/choices.min.css') }}">
@endsection

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
                            @if ($data['order']->order_type != 'open_bill')
                                <form action="{{ route('orders.statustodraft') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id_order" value="{{ $data['order']->id_order }}">
                                    <button type="submit" class="btn btn-outline-primary">
                                        <i class="bi bi-pencil-square"></i>
                                        Edit Items</button>
                                </form>
                            @endif
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
                                    <td>{{ $i['items'] }}</td>
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
                    <form action="#" id="formBilling">
                        @csrf
                        <div class="mb-3">
                            <label for="bill_name" class="form-label">Bill To*</label>
                            <input type="text" class="form-control" name="bill_name" id="bill_name" required
                                value="{{ $data['order']->bill_name }}">
                        </div>
                        <div class="mb-3">
                            <label for="member" class="form-label">Member</label>
                            <select name="member" id="member" class="form-select">
                                <option value="">None</option>
                                @foreach ($data['member'] as $member)
                                    @php
                                        $selected = $member->id_member == $data['order']->id_member ? 'selected' : '';
                                    @endphp
                                    <option value="{{ $member->id_member }}" {{ $selected }}>
                                        {{ $member->member_name }}
                                        [{{ $member->member_no }}]</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Payment Method*</label>
                            <select name="payment_method" id="payment_method" class="form-select" required>
                                <option value="Cash">Cash</option>
                                <option value="Transfer">Transfer</option>
                                <option value="QRIS">QRIS</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <table class="table">
                                <tr>
                                    <td>Subtotal</td>
                                    <td class="text-end">
                                        <input type="hidden" name="subtotal" id="subtotal"
                                            value="{{ $data['order']->price_subtotal }}">
                                        {{ number_format($data['order']->price_subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Discount</td>
                                    <td class="text-end">
                                        <select name="discount" id="discount" class="form-select">
                                            <option value="0" selected>None</option>
                                            @foreach ($data['discount'] as $disc)
                                                <option value="{{ $disc->price }}">{{ $disc->name }} |
                                                    {{ $disc->price }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>VAT</td>
                                    <td class="text-end">
                                        @php
                                            $vat = $data['order']->price_subtotal * ($data['tax']->rate / 100);
                                            $tmpTotal = $data['order']->price_total + $vat;
                                        @endphp
                                        <input type="hidden" name="tax_rate" id="tax_rate"
                                            value="{{ $data['tax']->rate }}">
                                        <input type="hidden" name="vat" id="vat" value="{{ $vat }}">
                                        <span id="showVAT">
                                            {{ number_format($vat, 0, ',', '.') }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-extrabold">Total</td>
                                    <td class="font-extrabold text-end">
                                        <input type="hidden" name="total" id="total" value="{{ $tmpTotal }}">
                                        <span id="showTotal">
                                            {{ number_format($tmpTotal, 0, ',', '.') }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Cash</td>
                                    <td class="text-end">
                                        <input type="number" class="form-control text-end" name="cash" id="cashTendered"
                                            required>
                                        <span id="showCash"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Change</td>
                                    <td class="text-end">
                                        <input type="hidden" name="change" id="change" required>
                                        <span id="changeText"></span>
                                    </td>
                                </tr>
                            </table>

                        </div>
                        <div class="mb-3">
                            <input type="hidden" name="id_order" value="{{ $data['order']->id_order }}">
                            <button type="submit" class="btn btn-lg btn-block btn-success" id="submitFormBilling">
                                <i class="bi bi-cash-stack"></i> Confirm Payment</button>
                        </div>
                        @if ($data['order']->order_type != 'open_bill')
                            <div>
                                <button type="button" class="btn btn-lg btn-block btn-outline-danger" id="cancelOrder">
                                    <i class="bi bi-x-circle"></i> Cancel </button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>

    </div>


@endsection

@section('script')

    <script src="{{ asset('assets/ext/choices.js/choices.js') }}"></script>
    @vite(['resources/js/orders/payment.js'])

@endsection
