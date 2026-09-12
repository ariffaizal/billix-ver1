$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    const baseUrl = "/orders/history";

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
        order: [[1, "desc"]],
        columns: [
            {
                data: "id",
                render: DataTable.render.text(),
                orderable: false,
            },
            {
                data: "time",
                type: "string",
                render: DataTable.render.text(),
            },
            {
                data: "bill_name",
                render: DataTable.render.text(),
                orderable: false,
            },
            {
                data: "subtotal",
                render: DataTable.render.number(".", ",", 0),
            },
            {
                data: "discount",
                render: DataTable.render.number(".", ",", 0),
            },
            {
                data: "total",
                render: DataTable.render.number(".", ",", 0),
            },
            {
                data: "status",
                orderable: false,
            },
            {
                data: "by",
                render: DataTable.render.text(),
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
        function (ev, picker) {
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
        function (ev, picker) {
            $(this).val("");
            table.clear().draw();
        }
    );
});
