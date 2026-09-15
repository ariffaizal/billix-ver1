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
                            <input type="hidden" name="titleText" id="titleText">
                        </div>
                    </div>
                    <table class="table table-striped table-hover" id="tabelData">
                        <thead>
                            <th>ID</th>
                            <th>Operator</th>
                            <th>Opening time</th>
                            <th>Closing time</th>
                            <th>Opening cash</th>
                            <th>Cash</th>
                            <th>Transfer</th>
                            <th>QRIS</th>
                            <th>Other</th>
                            <th>Total</th>
                            <th>Refunds</th>
                            <th>Net</th>
                            <th></th>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>Total</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- mulai modal detail --}}
    <div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Transaction Details - Shift #<span id="detailShiftId"></span></h5>
                    <button type="button" id="btnDownloadCSV" class="btn btn-sm btn-success ms-auto me-2">
                        <i class="bi bi-file-earmark-spreadsheet"></i> Download CSV
                    </button>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered" id="tableDetail">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    {{-- <th>Invoice</th> --}}
                                    <th>Items / Table</th>
                                    <th>Qty</th>
                                    <th>Method</th>
                                    <th>Status</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody id="listDetail">
                            </tbody>
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

        <script type="module">
            $(function() {
                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                });

                const baseUrl = "/reports/byshift";
                let currentShiftData = []; // Variabel penampung data untuk download CSV

                // 1. Inisialisasi DataTable Utama
                const table = $("#tabelData").DataTable({
                    autoWidth: false,
                    scrollX: true,
                    processing: true,
                    serverSide: false,
                    ajax: {
                        url: baseUrl + "/data",
                        method: "GET",
                        data: function(d) {
                            // Mengirim parameter tanggal jika daterangepicker sudah diisi
                            let range = $('#tanggal').val().split(' - ');
                            if (range.length === 2) {
                                d.start_date = moment(range[0], "DD-MM-YYYY").format("YYYY-MM-DD");
                                d.end_date = moment(range[1], "DD-MM-YYYY").format("YYYY-MM-DD");
                            }
                        }
                    },
                    columns: [{
                            data: "id",
                            render: (data) =>
                                `<a href="javascript:void(0)" class="fw-bold btn-detail text-primary" data-id="${data}">${data}</a>`
                        },
                        {
                            data: "operator"
                        },
                        {
                            data: "opening_time"
                        },
                        {
                            data: "closed_time"
                        },
                        {
                            data: "opening_cash",
                            render: $.fn.dataTable.render.number('.', ',', 0)
                        },
                        {
                            data: "cash",
                            render: $.fn.dataTable.render.number('.', ',', 0)
                        },
                        {
                            data: "transfer",
                            render: $.fn.dataTable.render.number('.', ',', 0)
                        },
                        {
                            data: "qris",
                            render: $.fn.dataTable.render.number('.', ',', 0)
                        },
                        {
                            data: "other",
                            render: $.fn.dataTable.render.number('.', ',', 0)
                        },
                        {
                            data: "total",
                            render: $.fn.dataTable.render.number('.', ',', 0)
                        },
                        {
                            data: "refund",
                            render: $.fn.dataTable.render.number('.', ',', 0)
                        },
                        {
                            data: "net",
                            render: $.fn.dataTable.render.number('.', ',', 0)
                        },
                        {
                            data: "action"
                        }
                    ]
                });

                // 2. Inisialisasi Daterangepicker
                $('#tanggal').daterangepicker({
                    autoUpdateInput: false,
                    locale: {
                        format: "DD-MM-YYYY",
                        cancelLabel: 'Clear'
                    },
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                    }
                });

                $('#tanggal').on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format(
                        'DD-MM-YYYY'));
                    table.ajax.reload();
                });

                $('#tanggal').on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                    table.ajax.reload();
                });

                // Event Handler Klik ID untuk Detail Per Item
                $('#tabelData').on('click', '.btn-detail', function() {
                    const id = $(this).data('id');
                    $('#detailShiftId').text(id);
                    $('#listDetail').html('<tr><td colspan="6" class="text-center">Loading data...</td></tr>');
                    $('#modalDetail').modal('show');

                    $.ajax({
                        url: baseUrl + "/details",
                        method: "GET",
                        data: {
                            id: id
                        },
                        success: function(data) {
                            currentShiftData = data; // Simpan untuk CSV
                            let html = '';
                            if (Array.isArray(data) && data.length > 0) {
                                data.forEach(order => {
                                    const status = order.order_status == 9 ?
                                        '<span class="badge bg-success">Success</span>' :
                                        '<span class="badge bg-danger">Refund</span>';

                                    (order.order_items || order.orderItems || []).forEach(item => {
                                        const itemName = item.fnb_name || item.table_name || item.package_name ||
                                            item.regular_name || item.openbill_name || '-';
                                        const qty = item.fnb_qty || 1;
                                        const amount = item.fnb_amount || item.regular_totalprice ||
                                            item.openbill_totalprice || item.items_amount || 0;

                                        html += `<tr>
                                            <td>${moment(order.created_at).format('HH:mm:ss')}</td>
                                            <td>${itemName}</td>
                                            <td class="text-center">${qty}</td>
                                            <td>${order.pay_method || '-'}</td>
                                            <td>${status}</td>
                                            <td class="text-end">${new Intl.NumberFormat('id-ID').format(amount)}</td>
                                        </tr>`;
                                    });
                                });
                            }
            
                            if (html === '') {
                                html = '<tr><td colspan="6" class="text-center">No items found</td></tr>';
                            }
            
                            $('#listDetail').html(html);
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText);
                            $('#listDetail').html('<tr><td colspan="6" class="text-center text-danger">Failed to load data. Check console for details.</td></tr>');
                        }
                    });
                });

                // 4. Fungsi Download CSV
                $('#btnDownloadCSV').click(function() {
                    if (currentShiftData.length === 0) return alert('No data to download');

                    const shiftId = $('#detailShiftId').text();
                    let csv = 'Time,Item Name,Qty,Method,Status,Amount\n';

                    currentShiftData.forEach(order => {
                        const status = order.order_status == 9 ? 'Success' : 'Refund';
                        (order.items || []).forEach(item => {
                            const itemName = (item.fnb_name || item.table_name || item
                                .package_name ||
                                item.regular_name || item.openbill_name || '-').replace(
                                /,/g, ' ');
                            const qty = item.fnb_qty || 1;
                            const amount = item.fnb_amount || item.regular_totalprice ||
                                item.openbill_totalprice || item.items_amount || 0;

                            csv +=
                                `${moment(order.created_at).format('HH:mm:ss')},${itemName},${qty},${order.pay_method},${status},${amount}\n`;
                        });
                    });

                    const blob = new Blob([csv], {
                        type: 'text/csv;charset=utf-8;'
                    });
                    const link = document.createElement("a");
                    link.href = URL.createObjectURL(blob);
                    link.download = `Detail_Shift_${shiftId}.csv`;
                    link.click();
                });
            });
        </script>


    @endsection
