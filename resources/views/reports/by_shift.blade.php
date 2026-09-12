@extends('layouts.main')

@section('title', $title)

@section('content')
    <div class="row">
        <div class="col-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-lg-6 col-md-12">
                            <label for="tanggal" class="form-label">please select date</label>
                            <input type="text" name="tanggal" id="tanggal" class="form-control">
                        </div>
                    </div>
                    <table class="table table-hover" id="tabelData">
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

    <script>
        $(function() {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            const baseUrl = "/reports/byshift";

            const table = $("#tabelData").DataTable({
                autoWidth: true,
                // scrollY: "60vh",
                scrollX: true,
                processing: true,
                lengthMenu: [
                    [20, 50, 100, -1],
                    [20, 50, 100, "All"],
                ],
                ajax: {
                    url: baseUrl + "/data",
                    method: "GET",
                },
                order: [
                    [0, "desc"]
                ],
                columns: [{
                        data: "id",
                        render: DataTable.render.text(),
                    },
                    {
                        data: "opened_by",
                        type: "string",
                        render: DataTable.render.text(),
                        orderable: false,
                    },
                    {
                        data: "opening_time",
                        type: "string",
                        render: DataTable.render.text(),
                        orderable: false,
                    },
                    {
                        data: "closed_time",
                        render: DataTable.render.text(),
                        orderable: false,
                    },
                    {
                        data: "expected_cash",
                        render: DataTable.render.number(".", ",", 0),
                    },
                    {
                        data: "actual_cash",
                        render: DataTable.render.number(".", ",", 0),
                    },
                    {
                        data: "diff",
                        render: DataTable.render.number(".", ",", 0),
                    },
                    {
                        data: "action",
                        orderable: false,
                    },
                ],
            });

            $('input[name="tanggal"]').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: "Clear",
                },
                opens: "right",
                ranges: {
                    Today: [moment(), moment()],
                    // Yesterday: [
                    //     moment().subtract(1, "days"),
                    //     moment().subtract(1, "days"),
                    // ],
                    "Last 7 Days": [moment().subtract(6, "days"), moment()],
                    "Last 30 Days": [moment().subtract(29, "days"), moment()],
                    "This Month": [moment().startOf("month"), moment().endOf("month")],
                    "Last Month": [
                        moment().subtract(1, "month").startOf("month"),
                        moment().subtract(1, "month").endOf("month"),
                    ],
                },
                alwaysShowCalendars: true,
            });
            $('input[name="tanggal"]').on(
                "apply.daterangepicker",
                function(ev, picker) {
                    $(this).val(
                        picker.startDate.format("MM/DD/YYYY") +
                        " - " +
                        picker.endDate.format("MM/DD/YYYY")
                    );
                    let url =
                        baseUrl +
                        "/data?start_date=" +
                        picker.startDate.format("YYYY-MM-DD") +
                        "&end_date=" +
                        picker.endDate.format("YYYY-MM-DD");
                    table.ajax.url(url).load();
                }
            );
            $('input[name="tanggal"]').on(
                "cancel.daterangepicker",
                function(ev, picker) {
                    $(this).val("");
                    table.clear().draw();
                }
            );
        });
    </script>


@endsection
