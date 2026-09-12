$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    const pathName = window.location.pathname.split("/");
    const idOrder = pathName[3];

    console.log(idOrder);
    // Refund Table Order
    $("#btnRefund").on("click", function () {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            confirmButtonText: "Yes, Process to Refund!",
            cancelButtonText: "Cancel",
            reverseButtons: true,
        }).then((yes) => {
            if (yes.isConfirmed) {
                $(this).html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...'
                );
                $(this).prop("disabled", true);
                $.ajax({
                    url: "/orders/refund_process",
                    method: "POST",
                    data: {
                        id: idOrder,
                    },
                    dataType: "JSON",
                    success: function (data) {
                        Swal.fire({
                            icon: "success",
                            title: "Success!",
                            text: "Order has been refund.",
                            // showConfirmButton: false,
                            // timer: 1000,
                        }).then(function () {
                            window.location.href =
                                "/orders/view/" + data.new_order_id;
                        });
                    },
                    error: function (e) {
                        Swal.fire({
                            icon: "error",
                            title: "Error!",
                            text: e.responseJSON.message,
                        });
                        $("#btnRefund").html(
                            '<i class="bi bi-arrow-counterclockwise"></i> Refund'
                        );
                        $("#btnRefund").prop("disabled", false);
                    },
                });
            }
        });
    });
});
