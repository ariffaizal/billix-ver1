$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    const pathName = window.location.pathname.split("/");
    const idOrder = pathName[3];

    const tableDetail = $("#tableItems").DataTable({
        autoWidth: true,
        scrollX: true,
        paging: false,
        searching: false,
        info: false,
        ordering: false,
        // processing: true,
        ajax: {
            url: "/orders/items/" + idOrder + "/openbillitems",
            method: "GET",
        },
        columns: [
            {
                data: "items",
            },
            {
                data: "qty",
            },
            {
                data: "price",
                render: DataTable.render.number(".", ",", 0),
            },
            {
                data: "amount",
                render: DataTable.render.number(".", ",", 0),
            },
            {
                data: "action",
            },
        ],
        footerCallback: function (row, data, start, end, display) {
            let api = this.api();
            let total;
            let numFormat = DataTable.render.number(".", ",", 0).display;
            // Remove the formatting to get integer data for summation
            let intVal = function (i) {
                return typeof i === "string"
                    ? i.replace(/[\$,]/g, "") * 1
                    : typeof i === "number"
                    ? i
                    : 0;
            };

            // Total over all pages
            total = api
                .column(3)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);

            // Update footer
            api.column(3).footer().innerHTML = numFormat(total);
            $("#showTotal").html(numFormat(total));
        },
    });

    $("#btnAddFnB").click(function () {
        $("#formAddFnB")[0].reset();
        $("#modalAddFnB").modal("show");
        tableFnbMenus.ajax.reload();
    });

    const tableFnbMenus = $("#tableFnbMenus").DataTable({
        autoWidth: true,
        paging: false,
        // searching: false,
        info: false,
        ordering: false,
        processing: true,
        ajax: {
            url: "/settings/fnb/available",
            method: "GET",
        },
        columns: [
            {
                data: "category",
            },
            {
                data: "name",
            },

            {
                data: "price",
                render: DataTable.render.number(".", ",", 0),
            },
        ],
        columnDefs: [
            { visible: false, targets: 0 },
            // { visible: false, targets: 1 },
        ],
        rowGroup: {
            dataSrc: ["category"],
        },
    });

    $("#formAddFnB").submit(function (e) {
        e.preventDefault();
        let form = $("#formAddFnB")[0];
        let data = new FormData(form);
        $.ajax({
            method: "POST",
            url: "/orders/items/" + idOrder + "/addFnb",
            enctype: "multipart/form-data",
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            async: false,
            success: function (respon) {
                Swal.fire({
                    icon: "success",
                    title: "Added!",
                    text: "",
                    showConfirmButton: false,
                    timer: 1000,
                });
                $("#formAddFnB")[0].reset();
                $("#modalAddFnB").modal("hide");
                tableDetail.ajax.reload();
            },
            error: function (e) {
                Swal.fire({
                    icon: "error",
                    title: "Error!",
                    text: e.responseJSON.message,
                });
            },
        });
    });

    $("#tableItems").on("change", ".fnbQty", function () {
        let idItems = $(this).attr("data-id");
        let qtyVal = $(this).val();
        $.ajax({
            url: "/orders/items/" + idOrder + "/updateFnb",
            method: "POST",
            data: {
                id: idItems,
                fnb_qty: qtyVal,
            },
            dataType: "JSON",
            success: function (data) {
                tableDetail.ajax.reload();
            },
            error: function (e) {
                Swal.fire({
                    icon: "error",
                    title: "Error!",
                    text: e.responseJSON.message,
                });
            },
        });
    });

    // Hapus Items
    $("#tableItems").on("click", "#btnDelete", function () {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "Cancel",
            reverseButtons: true,
        }).then((yes) => {
            if (yes.isConfirmed) {
                const id = $(this).attr("data-id");
                $.ajax({
                    url: "/orders/items/delete",
                    method: "DELETE",
                    data: {
                        id: id,
                    },
                    dataType: "JSON",
                    success: function (data) {
                        Swal.fire({
                            icon: "success",
                            title: "Deleted!",
                            text: "Data has been deleted.",
                            showConfirmButton: false,
                            timer: 1000,
                        });
                        tableDetail.ajax.reload();
                    },
                    error: function (e) {
                        Swal.fire({
                            icon: "error",
                            title: "Error!",
                            text: e.responseJSON.message,
                        });
                    },
                });
            }
        });
    });

    // Cancel Order
    $("#formProcess").on("click", "#cancelOrders", function () {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            confirmButtonText: "Yes, Cancel it!",
            cancelButtonText: "Back",
            reverseButtons: true,
        }).then((yes) => {
            if (yes.isConfirmed) {
                const id = idOrder;
                $.ajax({
                    url: "/orders/cancel",
                    method: "POST",
                    data: {
                        id: id,
                    },
                    dataType: "JSON",
                    success: function (data) {
                        Swal.fire({
                            icon: "success",
                            title: "Canceled!",
                            text: "Order has been canceled.",
                            showConfirmButton: false,
                            timer: 1000,
                        }).then(function () {
                            window.location.assign("/orders");
                        });
                    },
                    error: function (e) {
                        Swal.fire({
                            icon: "error",
                            title: "Error!",
                            text: e.responseJSON.message,
                        });
                    },
                });
            }
        });
    });
});
