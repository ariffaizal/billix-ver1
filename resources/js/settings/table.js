$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    const baseUrl = "/settings/table";

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
        columns: [
            {
                data: "no",
            },
            {
                data: "name",
                render: DataTable.render.text(),
            },
            {
                data: "relay",
                render: DataTable.render.text(),
            },
            {
                data: "active",
            },
            { data: "testing" },
            { data: "action" },
        ],
    });

    $("#btnAdd").click(function () {
        $("#submitAdd").val("tambah");
        $("#formAdd").trigger("reset");
        $("#modalAdd").modal("show");
    });

    //tambah
    $("#formAdd").submit(function (e) {
        e.preventDefault();
        let state = $("#submitAdd").val();
        let form = $("#formAdd")[0];
        let data = new FormData(form);
        let link = state == "tambah" ? baseUrl : baseUrl + "/update";
        $.ajax({
            method: "POST",
            url: link,
            enctype: "multipart/form-data",
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            async: false,
            success: function (respon) {
                Swal.fire({
                    icon: "success",
                    title: "Saved!",
                    text: "",
                    showConfirmButton: false,
                    timer: 1000,
                });
                $("#formAdd")[0].reset();
                $("#modalAdd").modal("hide");
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
    });

    // Form Edit
    $("#tabelData").on("click", "#btnEdit", function () {
        let id = $(this).attr("data-id");
        $("#submitAdd").val("update");

        $.ajax({
            url: baseUrl + "/show",
            data: {
                id: id,
            },
            method: "POST",
            dataType: "JSON",
            success: function (data) {
                $("#modalAdd").modal("show");
                $.each(data, function () {
                    $('[name="id"]').val(id);
                    $('[name="table_name"]').val(data.table_name);
                    $('[name="relay"]').val(data.http_relay);
                    if (data.is_active == 1) {
                        $('[name="is_active"]').prop("checked", true);
                    } else {
                        $('[name="is_active"]').prop("checked", false);
                    }
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
        return false;
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
                    url: baseUrl,
                    method: "DELETE",
                    data: {
                        id: id,
                    },
                    dataType: "JSON",
                    success: function (data) {
                        Swal.fire({
                            icon: "success",
                            title: "Sukses!",
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

    // Form Edit
    $("#tabelData").on("click", ".btnTestRelay", function () {
        let id = $(this).attr("data-id");
        let state = $(this).attr("data-state");
        Swal.fire({
            title: "Loading...",
            text: "Please wait...",
            allowEscapeKey: false,
            allowOutsideClick: false,
            showConfirmButton: false,
        });
        $.ajax({
            url: baseUrl + "/testrelay",
            data: {
                id_table: id,
                state: state,
            },
            method: "POST",
            dataType: "JSON",
            success: function (data) {
                Swal.fire({
                    icon: "success",
                    title: "",
                    text: "command " + state + " has been sent.",
                    showConfirmButton: false,
                    timer: 1000,
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
        return false;
    });
});
