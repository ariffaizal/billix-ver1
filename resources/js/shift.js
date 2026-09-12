$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $("#btnOpenShift").click(function () {
        $("#modalAddShift").modal("show");
    });

    $("#formAddShift").submit(function (e) {
        e.preventDefault();
        let form = $("#formAddShift")[0];
        let data = new FormData(form);
        $.ajax({
            method: "POST",
            url: "/shift/open",
            enctype: "multipart/form-data",
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            // async: false,
            success: function (respon) {
                Swal.fire({
                    icon: "success",
                    title: "Your shift has been started!",
                    text: "",
                    showConfirmButton: false,
                    timer: 1000,
                }).then(function () {
                    window.location.reload();
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
    });

    // Close Shift
    $("#btnCloseShift").click(function () {
        let id = $(this).attr("data-id");
        $("#modalCloseShift").modal("show");
        $('#modalCloseShift [name="id_shift"]').val(id);
    });

    $("#formCloseShift").submit(function (e) {
        e.preventDefault();
        let form = $("#formCloseShift")[0];
        let data = new FormData(form);
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            confirmButtonText: "Yes, Close Shift!",
            cancelButtonText: "Cancel",
            reverseButtons: true,
        }).then((yes) => {
            if (yes.isConfirmed) {
                $.ajax({
                    url: "/shift/close",
                    method: "POST",
                    data: data,
                    enctype: "multipart/form-data",
                    data: data,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (m) {
                        Swal.fire({
                            icon: "success",
                            // title: "Shift Closed!",
                            text: "Your Shift has been closed.",
                            showConfirmButton: false,
                            timer: 1000,
                        }).then(function () {
                            console.log(m);
                            window.location.replace(
                                "/reports/closeshift?id=" + m.id_user_shift
                            );
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
