@extends('layouts.main')

@section('title', $title)

@section('content')
    <div class="row">
        <div class="col-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <th>ID</th>
                            <th>Opened by</th>
                            <th>Opening time</th>
                            <th>Closing time</th>
                            <th>Expected cash amount</th>
                            <th>Actual cash amount</th>
                            <th>Difference</th>
                            <th>Action</th>
                        </thead>
                        <tbody>

                            <tr>
                                <td>{{ $data['shift']->id_user_shift }}</td>
                                <td>{{ $data['user']->name }}</td>
                                <td>{{ $data['shift']->shift_start }}</td>
                                <td>{{ $data['shift']->shift_end }}</td>
                                <td>{{ number_format($data['expected'], 0, ',', '.') }}</td>
                                <td>{{ number_format($data['shift']->cash_actual, 0, ',', '.') }}</td>
                                <td>{{ number_format($data['diff'], 0, ',', '.') }}</td>
                                <td>
                                    <a href="{{ url('reports/byshift/print?id=' . $data['shift']->id_user_shift) }}"
                                        target="_blank" class="btn btn-sm btn-outline-primary" title="Print this report"><i
                                            class="bi bi-printer"></i> Print</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
