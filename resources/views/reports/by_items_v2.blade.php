@extends('layouts.main')

@section('title', $title)

@section('script_tag')
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.2/css/buttons.dataTables.css">
@endsection

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
                    <div class="mb-3">
                        <h4 class="h4">Summary</h4>
                    </div>
                    <table class="table table-hover" id="tabelDataSummary" style="width: 100%">
                        <thead>
                            <th>Items</th>
                            <th>Amount</th>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="h5">Tables</h5>
                    <table class="table table-hover" id="tabelDataTables">
                        <thead>
                            <th>Items</th>
                            <th>Amount</th>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>

                                <th>
                                    <p>
                                        Total
                                    </p>

                                </th>
                                <th></th>
                            </tr>
                            <tr>
                                <td><i>note : the total does not include discounts and final tax
                                        calculations</i></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>

                </div>
            </div>
        </div>
        <div class="col-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="h5">FnB</h5>
                    <table class="table table-hover" id="tabelDataFnb">
                        <thead>
                            <th>Items</th>
                            <th>Category</th>
                            <th>Items Sold</th>
                            <th>Amount</th>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>

                                <th></th>
                                <th></th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                            <tr>
                                <td></td>
                                <td><i>note : the total does not include discounts, final tax
                                        calculations and refund</i></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tfoot>
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

    <script src="https://cdn.datatables.net/buttons/3.2.2/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.print.min.js"></script>

    <script>
        $(function() {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            const baseUrl = "/reports/byitems";

            const tabelDataSummary = $("#tabelDataSummary").DataTable({
                autoWidth: true,
                scrollX: true,
                processing: true,
                ordering: false,
                searching: false,
                paging: false,
                info: false,
                ajax: {
                    url: baseUrl + "/data-summary",
                    method: "GET",
                },
                buttons: [{
                        extend: 'print',
                        title: function() {
                            return 'SUMMARY ITEMS REPORTS ' +
                                $('#tanggal').val();
                        },
                    },
                    {
                        extend: 'pdfHtml5',
                        orientation: 'potrait',
                        pageSize: 'A4',
                        title: function() {
                            return 'SUMMARY ITEMS REPORTS ' +
                                $('#tanggal').val();
                        },
                        customize: function(doc) {
                            var netColumnIndex = 1;
                            doc.content[1].table.widths = Array(doc.content[1].table.body[0]
                                .length + 1).join('*').split('');
                            doc.content[1].table.body.forEach(function(row, rowIndex) {
                                if (rowIndex > 0 && row[netColumnIndex]) {
                                    // Apply alignment only to valid cells
                                    row[netColumnIndex].alignment = 'right';
                                }
                            });
                        }
                    }
                ],
                layout: {
                    topStart: 'buttons'
                },
                columns: [{
                        data: "items",
                    },
                    {
                        data: "amount",
                        render: DataTable.render.number(".", ",", 0),
                    },
                ],
            });

            const tabelDataTables = $("#tabelDataTables").DataTable({
                autoWidth: true,
                scrollX: true,
                processing: true,
                ordering: false,
                searching: false,
                paging: false,
                info: false,
                ajax: {
                    url: baseUrl + "/data-tables",
                    method: "GET",
                },
                buttons: [{
                        extend: 'print',
                        title: function() {
                            return 'TABLES ITEMS REPORTS ' +
                                $('#tanggal').val();
                        },
                    },
                    {
                        extend: 'pdfHtml5',
                        orientation: 'potrait',
                        pageSize: 'A4',
                        title: function() {
                            return 'TABLES ITEMS REPORTS ' +
                                $('#tanggal').val();
                        },
                        customize: function(doc) {
                            var netColumnIndex = 1;
                            doc.content[1].table.widths = Array(doc.content[1].table.body[0]
                                .length + 1).join('*').split('');
                            doc.content[1].table.body.forEach(function(row, rowIndex) {
                                if (rowIndex === 0) {
                                    // Skip the header row
                                    return;
                                }
                                row[netColumnIndex].alignment = 'right';
                            });
                        }
                    }
                ],
                layout: {
                    topStart: 'buttons'
                },
                columns: [{
                        data: "items",
                    },
                    {
                        data: "amount",
                        render: DataTable.render.number(".", ",", 0),
                    },
                ],
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();

                    // Calculate the total amount
                    var total = api
                        .column(1, {
                            page: 'current'
                        })
                        .data()
                        .reduce(function(a, b) {
                            return parseFloat(a) + parseFloat(b);
                        }, 0);

                    // Update the footer
                    $(api.column(1).footer()).html(
                        DataTable.render.number('.', ',', 0).display(total)
                    );
                }
            });

            const tabelDataFnb = $("#tabelDataFnb").DataTable({
                autoWidth: true,
                scrollX: true,
                processing: true,
                ordering: false,
                searching: false,
                paging: false,
                info: false,
                ajax: {
                    url: baseUrl + "/data-fnb",
                    method: "GET",
                },
                buttons: [{
                        extend: 'print',
                        title: function() {
                            return 'FNB ITEMS REPORTS ' +
                                $('#tanggal').val();
                        },
                    },
                    {
                        extend: 'pdfHtml5',
                        orientation: 'potrait',
                        pageSize: 'A4',
                        title: function() {
                            return 'FNB ITEMS REPORTS ' +
                                $('#tanggal').val();
                        },
                        customize: function(doc) {
                            var netColumnIndex = 3;
                            doc.content[1].table.widths = Array(doc.content[1].table.body[0]
                                .length + 1).join('*').split('');
                            doc.content[1].table.body.forEach(function(row, rowIndex) {
                                if (rowIndex === 0) {
                                    // Skip the header row
                                    return;
                                }
                                row[2].alignment = 'right';
                                row[netColumnIndex].alignment = 'right';
                            });
                        }
                    }
                ],
                layout: {
                    topStart: 'buttons'
                },
                columns: [{
                        data: "items",
                    },
                    {
                        data: "category",
                    },
                    {
                        data: "sold",
                    },
                    {
                        data: "amount",
                        render: DataTable.render.number(".", ",", 0),
                    },
                ],
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();

                    // Calculate the total amount
                    var total = api
                        .column(3, {
                            page: 'current'
                        })
                        .data()
                        .reduce(function(a, b) {
                            return parseFloat(a) + parseFloat(b);
                        }, 0);

                    // Update the footer
                    $(api.column(3).footer()).html(
                        DataTable.render.number('.', ',', 0).display(total)
                    );
                }
            });



            $('input[name="tanggal"]').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: "Clear",
                },
                opens: "right",
                ranges: {
                    Today: [moment(), moment()],
                    Yesterday: [
                        moment().subtract(1, "days"),
                        moment().subtract(1, "days"),
                    ],
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
                        "/data-summary?start_date=" +
                        picker.startDate.format("YYYY-MM-DD") +
                        "&end_date=" +
                        picker.endDate.format("YYYY-MM-DD");
                    tabelDataSummary.ajax.url(url).load();

                    let urltables =
                        baseUrl +
                        "/data-tables?start_date=" +
                        picker.startDate.format("YYYY-MM-DD") +
                        "&end_date=" +
                        picker.endDate.format("YYYY-MM-DD");
                    tabelDataTables.ajax.url(urltables).load();

                    let urlfnb =
                        baseUrl +
                        "/data-fnb?start_date=" +
                        picker.startDate.format("YYYY-MM-DD") +
                        "&end_date=" +
                        picker.endDate.format("YYYY-MM-DD");
                    tabelDataFnb.ajax.url(urlfnb).load();
                }
            );
            $('input[name="tanggal"]').on(
                "cancel.daterangepicker",
                function(ev, picker) {
                    $(this).val("");
                    tabelDataSummary.clear().draw();
                    tabelDataTables.clear().draw();
                    tabelDataFnb.clear().draw();
                }
            );
        });
    </script>


@endsection
