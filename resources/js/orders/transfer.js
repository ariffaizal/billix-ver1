$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    const pathName = window.location.pathname.split("/");
    const idOrder = pathName[3];

    async function fetchTableAvailable(idItems) {
        try {
            const response = await fetch(
                "/settings/table/showavailable?id_order=" + idOrder
            );
            const data = await response.json();
            const divShowTable = document.getElementById("showAvailableTable");
            divShowTable.innerHTML = ""; // Clear previous content

            data.forEach((table) => {
                const tableElement = document.createElement("div");
                tableElement.classList.add("mb-3"); // Add a class for styling if needed
                tableElement.innerHTML = `
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="new_table" value="${idItems}|${table.id_table}" id="new_table_${table.id_table}">
                        <label class="btn btn-block btn-lg btn-outline-success" for="new_table_${table.id_table}">
                            ${table.table_name}
                        </label>
                    </div>
                    `;
                divShowTable.appendChild(tableElement);
            });
        } catch (error) {
            console.error("Terjadi kesalahan:", error);
            // Tampilkan pesan error jika terjadi kesalahan
            const divShowTable = document.getElementById("showAvailableTable");
            divShowTable.innerHTML = "Terjadi kesalahan saat mengambil data.";
        }
    }

    $("#tableItems").on("click", ".btnTransfer", function () {
        let idItems = $(this).attr("data-id");
        $("#modalTransfer").modal("show");
        fetchTableAvailable(idItems);
    });

    $("#formTransfer").submit(function (e) {
        e.preventDefault();
        // $("#submitTransfer").addClass("disabled");
        $("#submitTransfer")
            .html(
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...'
            )
            .prop("disabled", true);
        let form = $("#formTransfer")[0];
        let data = new FormData(form);
        $.ajax({
            method: "POST",
            url: "/orders/services/changetable",
            enctype: "multipart/form-data",
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            // async: false,
            success: function (respon) {
                Swal.fire({
                    icon: "success",
                    title: "Transfer Success!",
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
