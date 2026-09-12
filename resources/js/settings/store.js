$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    const baseUrl = "/settings/store";

    $("#formStore").submit(function (e) {
        e.preventDefault();
        let form = $("#formStore")[0];
        let data = new FormData(form);
        $.ajax({
            method: "POST",
            url: baseUrl,
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
});
