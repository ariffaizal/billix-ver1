@extends('layouts.main')

@section('title', $title)

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="mb-2">
                        <a href="{{ route('orders') }}" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-left"></i>
                            Orders List
                        </a>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-6 col-md-12">
                            <label for="tanggal" class="form-label">Pilih tanggal untuk melihat daftar Order</label>
                            <input type="text" name="tanggal" id="tanggal" class="form-control">
                        </div>
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

    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    @vite(['resources/js/orders/history.js'])

@endsection
