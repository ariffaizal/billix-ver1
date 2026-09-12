$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    const baseUrl = "/orders";

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
                data: "cstable",
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

    // Hapus
    $("#tabelData").on("click", "#btnDelete", function () {
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
                    url: baseUrl + "/delete",
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
                        table.ajax.reload();
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
