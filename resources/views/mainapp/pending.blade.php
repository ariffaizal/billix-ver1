@extends('layouts.main')

@section('title', $title)

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    {{-- <span>Today's order data ({{ date('d-m-Y') }})</span> --}}
                    <table class="table table-hover" id="tabelData">
                        <thead>
                            <tr>
                                <th>ID#</th>
                                <th>Time</th>
                                <th>Bill To</th>
                                <th>Subtotal</th>
                                <th>Discount</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $i)
                                <tr>
                                    <td>{{ $i->id_order }}</td>
                                    <td>{{ $i->order_time }}</td>
                                    <td class="text-end">{{ $i->bill_name }}</td>
                                    <td class="text-end">{{ $i->price_subtotal }}</td>
                                    <td class="text-end">{{ $i->price_discount }}</td>
                                    <td class="text-end">{{ $i->price_total }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('orders.pay', ['id_order' => $i->id_order]) }}"
                                            class="btn btn-sm btn-info" id="btnDetail">Pay</a>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

{{-- <!-- @section('script') -->

    <!-- @vite(['resources/js/orders/list.js']) -->

<!-- @endsection --> --}}
