$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    const pathName = window.location.pathname.split("/");
    const idOrder = pathName[3];

    $("#member").change(function () {
        let memberVal = $(this).val();
        if (memberVal != "") {
            $("#bill_name").prop("disabled", true).prop("required", false);
        } else {
            $("#bill_name").prop("disabled", false).prop("required", true);
        }
    });
    const choices = new Choices($("#member")[0]);

    //fungsi untuk menampilkan format Rupiah
    function formatRupiah(angka) {
        var number_string = angka.replace(/[^,\d]/g, "").toString(),
            split = number_string.split(","),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        // tambahkan titik setiap 3 angka
        if (ribuan) {
            let separator = sisa ? "." : "";
            rupiah += separator + ribuan.join(".");
        }

        rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
        return rupiah;
    }

    $("#discount").change(function () {
        let priceDiscount = $(this).val();
        calculateTotal(priceDiscount);
        calculateChange();
    });

    function calculateTotal(discount) {
        let subtotal = document.getElementById("subtotal").value;
        let taxRate = document.getElementById("tax_rate").value;
        let totalBeforeVat = subtotal - discount;
        let vat = totalBeforeVat * (taxRate / 100);
        let total = totalBeforeVat + vat;
        let vatText = vat.toLocaleString("id-ID", {
            style: "decimal",
        });
        let totalText = total.toLocaleString("id-ID", {
            style: "decimal",
        });
        document.getElementById("vat").value = vat;
        document.getElementById("showVAT").textContent = vatText;
        document.getElementById("total").value = total;
        document.getElementById("showTotal").textContent = totalText;
    }

    function showCash() {
        let inputCash = document.getElementById("cashTendered").value;
        let cashText = formatRupiah(inputCash);
        document.getElementById("showCash").textContent = cashText;
    }

    // Fungsi untuk menghitung kembalian
    function calculateChange() {
        let cashTendered = document.getElementById("cashTendered").value;
        let total = document.getElementById("total").value;
        let change = cashTendered - total;
        let changeText = change.toLocaleString("id-ID", {
            style: "decimal",
        });
        document.getElementById("change").value = change;
        document.getElementById("changeText").textContent = changeText;
    }

    // Memanggil fungsi saat nilai cashTendered berubah
    document
        .getElementById("cashTendered")
        .addEventListener("input", calculateChange);
    document.getElementById("cashTendered").addEventListener("input", showCash);

    $("#formBilling").submit(function (e) {
        e.preventDefault();
        Swal.fire({
            title: "Confirm Payment?",
            text: "Orders that have been confirmed for payment cannot be changed!",
            icon: "warning",
            showCancelButton: true,
            // confirmButtonColor: "#d33",
            confirmButtonText: "Yes, Confirm",
            cancelButtonText: "Back",
            reverseButtons: true,
        }).then((yes) => {
            if (yes.isConfirmed) {
                let form = $("#formBilling")[0];
                let data = new FormData(form);
                $.ajax({
                    method: "POST",
                    url: "/orders/pay_confirm",
                    enctype: "multipart/form-data",
                    data: data,
                    contentType: false,
                    cache: false,
                    processData: false,
                    async: false,
                    success: function (respon) {
                        Swal.fire({
                            icon: "success",
                            title: "Payment Success!",
                            text: "",
                            showConfirmButton: false,
                            timer: 1000,
                        }).then(function () {
                            window.location.assign("/orders/view/" + idOrder);
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

    // Batalkan Order
    $("#formBilling").on("click", "#cancelOrder", function () {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            confirmButtonText: "Yes, Cancel",
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
