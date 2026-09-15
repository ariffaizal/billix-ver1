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
                    <div class="row mb-3">
                        <div class="col-6">
                            <table class="table table-sm" id="tabelTop5">
                                <thead>
                                    <th>Top 5 Items</th>
                                    <th>Gross sales</th>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <table class="table table-hover" id="tabelData">
                        <thead>
                            <th>Items</th>
                            <th>Category</th>
                            <th>Items sold</th>
                            <th>Gross sales</th>
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

            const baseUrl = "/reports/byitems";

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
                        data: "fnb_name",
                        render: DataTable.render.text(),
                    },
                    {
                        data: "category_name",
                        render: DataTable.render.text(),
                    },
                    {
                        data: "items_sold",
                        render: DataTable.render.number(".", ",", 0),
                    },
                    {
                        data: "items_amount",
                        render: DataTable.render.number(".", ",", 0),
                    },
                ],
            });

            const tableTop5 = $("#tabelTop5").DataTable({
                autoWidth: true,
                info: false,
                searching: false,
                paging: false,
                ordering: false,
                // scrollY: "60vh",
                // scrollX: true,
                processing: true,
                // lengthMenu: [
                //     [20, 50, 100, -1],
                //     [20, 50, 100, "All"],
                // ],
                ajax: {
                    url: baseUrl + "/chart",
                    method: "GET",
                },
                order: [
                    [1, "desc"]
                ],
                columns: [{
                        data: "fnb_name",
                        render: DataTable.render.text(),
                    },
                    {
                        data: "items_amount",
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
                        picker.startDate.format("DD-MM-YYYY") +
                        " - " +
                        picker.endDate.format("DD-MM-YYYY")
                    );
                    let url =
                        baseUrl +
                        "/data?start_date=" +
                        picker.startDate.format("YYYY-MM-DD") +
                        "&end_date=" +
                        picker.endDate.format("YYYY-MM-DD");
                    table.ajax.url(url).load();

                    let urlTop5 =
                        baseUrl +
                        "/chart?start_date=" +
                        picker.startDate.format("YYYY-MM-DD") +
                        "&end_date=" +
                        picker.endDate.format("YYYY-MM-DD");
                    tableTop5.ajax.url(urlTop5).load();
                }
            );
            $('input[name="tanggal"]').on(
                "cancel.daterangepicker",
                function(ev, picker) {
                    $(this).val("");
                    table.clear().draw();
                    tableTop5.clear().draw();
                }
            );


        });
    </script>


@endsection
