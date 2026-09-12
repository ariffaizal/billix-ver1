$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    const pathName = window.location.pathname.split("/");
    const idOrder = pathName[3];

    const tableDetail = $("#tabelFnBItems").DataTable({
        autoWidth: true,
        scrollX: true,
        paging: false,
        searching: false,
        info: false,
        ordering: false,
        // processing: true,
        ajax: {
            url: "/orders/items/" + idOrder + "/list",
            method: "GET",
        },
        columns: [
            {
                data: "items",
                render: DataTable.render.text(),
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
            $("#input_total").val(total);
            // $("#countItems").val(data.length);
            $("#showTotal").html(numFormat(total));
            let btnSubmit = document.getElementById("submitFormProses");
            if (data.length > 0) {
                btnSubmit.disabled = false;
            } else {
                btnSubmit.disabled = true;
            }
        },
    });

    const tableFnbMenus = $("#tableFnbMenus").DataTable({
        autoWidth: true,
        paging: false,
        // searching: false,
        info: false,
        ordering: false,
        processing: true,
        rowGroup: true,
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

    $("#btnAddFnB").click(function () {
        $("#formAddFnB")[0].reset();
        $("#modalAddFnB").modal("show");
        tableFnbMenus.ajax.reload();
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

    $("#tabelFnBItems").on("change", ".fnbQty", function () {
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
        // }
    });

    // Hapus Items
    $("#tabelFnBItems").on("click", "#btnDelete", function () {
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

    $("#btnAddTable").click(function () {
        $("#formAddTable")[0].reset();
        $("#modalAddTable").modal("show");
        tableAvailable.ajax.reload();
    });

    const tableAvailable = $("#tableAvailable").DataTable({
        autoWidth: true,
        paging: false,
        searching: false,
        info: false,
        ordering: false,
        processing: true,
        ajax: {
            url: "/settings/table/available?id_order=" + idOrder,
            method: "GET",
        },
        columns: [
            {
                data: "tables",
            },
        ],
    });

    $("#formAddTable").submit(function (e) {
        e.preventDefault();
        let form = $("#formAddTable")[0];
        let data = new FormData(form);
        $.ajax({
            method: "POST",
            url: "/orders/items/" + idOrder + "/addTables",
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
                $("#formAddTable")[0].reset();
                $("#modalAddTable").modal("hide");
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

    $("#tabelFnBItems").on("change", ".pilihpaket", function () {
        let packageSelected = $(this).val();
        if (packageSelected != "") {
            $.ajax({
                url: "/orders/items/" + idOrder + "/updateTables",
                method: "POST",
                data: {
                    package: packageSelected,
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
        }
    });

    // Process Order
    $("#formProcess").submit(function (e) {
        e.preventDefault();
        $("#submitFormProses").html(
            '<span class="spinner-border spinner-border-sm"></span> Processing...'
        );
        $("#submitFormProses").prop("disabled", true);
        $("#cancelOrders").addClass("d-none");

        let form = $("#formProcess")[0];
        let data = new FormData(form);
        $.ajax({
            method: "POST",
            url: "/orders/process",
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            success: function (respon) {
                Swal.fire({
                    icon: "success",
                    title: "Saved!",
                    text: "",
                    showConfirmButton: false,
                    timer: 1000,
                }).then(function () {
                    window.location.href = respon.url;
                });
            },
            error: function (e) {
                Swal.fire({
                    icon: "error",
                    title: "Error!",
                    text: e.responseJSON.message,
                });
                $("#submitFormProses").html(
                    '<i class="bi bi-check2-circle"></i> Process'
                );
                $("#submitFormProses").prop("disabled", false);
                $("#cancelOrders").removeClass("d-none");
            },
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
