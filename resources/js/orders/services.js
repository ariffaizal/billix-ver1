$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    const pathName = window.location.pathname.split("/");
    const idOrder = pathName[3];

    // Start Session
    $("#btnStart").on("click", function () {
        Swal.fire({
            title: "Start this session?",
            text: "",
            icon: "warning",
            showCancelButton: true,
            // confirmButtonColor: "#d33",
            confirmButtonText: "Yes, start it!",
            cancelButtonText: "Cancel",
            reverseButtons: true,
        }).then((yes) => {
            if (yes.isConfirmed) {
                $(this).html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...'
                );
                $(this).prop("disabled", true);
                $("#cancelOrders").addClass("d-none");
                $.ajax({
                    url: "/orders/services/start",
                    method: "POST",
                    data: {
                        id_order: idOrder,
                    },
                    dataType: "JSON",
                    success: function (data) {
                        Swal.fire({
                            icon: "success",
                            title: "Success!",
                            text: "Session has been started.",
                            // showConfirmButton: false,
                            // timer: 1000,
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
            }
        });
    });

    // Stop Session
    $("#btnStop").on("click", function () {
        Swal.fire({
            title: "Stop this session?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            confirmButtonText: "Yes, stop it!",
            cancelButtonText: "Cancel",
            reverseButtons: true,
        }).then((yes) => {
            if (yes.isConfirmed) {
                $(this).html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...'
                );
                $(this).prop("disabled", true);
                $.ajax({
                    url: "/orders/services/stop",
                    method: "POST",
                    data: {
                        id_order: idOrder,
                    },
                    dataType: "JSON",
                    success: function (data) {
                        Swal.fire({
                            icon: "success",
                            title: "Success!",
                            text: "Session has been stoped.",
                            // showConfirmButton: false,
                            // timer: 1000,
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
            }
        });
    });
});
