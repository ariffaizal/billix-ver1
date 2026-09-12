@extends('layouts.main')

@section('title', $title)

@section('content')
    <div class="row mb-3">
        <div class="col-12">
            <nav class="nav nav-pills nav-fill">
                <a class="nav-link active" href="#">Daftar Order</a>
            </nav>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row mb-3">
                        <div class="col-lg-6 col-md-12">
                            <label for="tanggal" class="form-label">Pilih tanggal untuk melihat daftar Order</label>
                            <input type="text" name="tanggal" id="tanggal" class="form-control">
                        </div>
                    </div>
                    <table class="table table-lg table-hover" id="tabelData">
                        <thead>
                            <tr>
                                <th>ID#</th>
                                <th>Waktu</th>
                                <th>Nama Billing</th>
                                <th>Sub Total</th>
                                <th>Diskon</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>By</th>
                                <th>action</th>
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

    @vite(['resources/js/mainapp/report.js'])

@endsection
