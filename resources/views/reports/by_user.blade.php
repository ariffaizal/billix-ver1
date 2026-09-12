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
                            <th>Name</th>
                            <th>Gross sales</th>
                            <th>Refund</th>
                            <th>Discounts</th>
                            <th>Net sales</th>
                            <th>Tax</th>
                            <th>Receipts</th>
                            <th>Average sales</th>
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

            const baseUrl = "/reports/byuser";

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
                        data: "name",
                        render: DataTable.render.text(),
                    },
                    {
                        data: "gross_sales",
                        render: DataTable.render.number(".", ",", 0),
                    },
                    {
                        data: "total_refund",
                        render: DataTable.render.number(".", ",", 0),
                    },
                    {
                        data: "total_discount",
                        render: DataTable.render.number(".", ",", 0),
                    },
                    {
                        data: "total_net",
                        render: DataTable.render.number(".", ",", 0),
                    },
                    {
                        data: "total_vat",
                        render: DataTable.render.number(".", ",", 0),
                    },
                    {
                        data: "total_order",
                        render: DataTable.render.number(".", ",", 0),
                    },
                    {
                        data: "average",
                        render: DataTable.render.number(".", ",", 0),
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
