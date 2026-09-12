@extends('layouts.main')

@section('title', $title)

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="mb-2">
                        @if (!empty($shift))
                            <button class="btn btn-primary" type="button" id="btnSelectOrder">
                                <i class="bi bi-check2-square"></i> Select Order
                            </button>
                        @endif
                        @if (auth()->user()->role == 'owner')
                            <a href="{{ route('orders') }}" class="btn btn-outline-primary">
                                <i class="bi bi-arrow-left"></i>
                                Orders List
                            </a>
                            <a href="{{ route('orders.history') }}" class="btn btn-outline-primary">
                                <i class="bi bi-clock-history"></i>
                                Orders History
                            </a>
                        @endif
                    </div>
                    <table class="table table-hover" id="tabelData">
                        <thead>
                            <tr>
                                <th>ID#</th>
                                <th>Time</th>
                                <th>Bill To</th>
                                <th>Subtotal</th>
                                <th>Discount</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>By</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')

    @vite(['resources/js/orders/refund.js'])

@endsection
