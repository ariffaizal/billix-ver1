@extends('layouts.main')

@section('title', $title)

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="mb-2">
                        @if (!empty($shift))
                            <div class="btn-group">
                                <div class="dropdown">
                                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="bi bi-plus-lg"></i> New Order
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <form action="{{ route('orders.create') }}" method="post">
                                                @csrf
                                                <input type="hidden" name="type" value="packages">
                                                <button type="submit" class="dropdown-item">Packages</button>
                                            </form>
                                        <li>
                                            <form action="{{ route('orders.create') }}" method="post">
                                                @csrf
                                                <input type="hidden" name="type" value="open_bill">
                                                <button type="submit" class="dropdown-item">Open Billing</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('orders.create') }}" method="post">
                                                @csrf
                                                <input type="hidden" name="type" value="fnb_only">
                                                <button type="submit" class="dropdown-item">FnB Only</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        @endif

                        <a href="{{ route('orders.history') }}" class="btn btn-outline-primary">
                            <i class="bi bi-clock-history"></i>
                            Orders History
                        </a>

                    </div>
                    <span>Note: The orders list displayed corresponds to the currently active shift.</span>
                    <table class="table table-hover" id="tabelData">
                        <thead>
                            <tr>
                                <th>ID#</th>
                                <th>Time</th>
                                <th>Bill To</th>
                                <th>Table</th>
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

    @vite(['resources/js/orders/list.js'])

@endsection
