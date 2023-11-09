$(function () {
    tampilDataSetAlat();
});

function tampilDataSetAlat() {
    $('#tabelDataSetAlat').DataTable(
        {
            bDestroy: true,
            processing: true,
            serverSide: true,
            ordering: false,
            searching: true,
            bInfo: true,
            autoWidth: false,
            responsive: true,
            deferRender: true,
            ajax: {
                url: url + '/data',
                type: 'POST',
            },
            lengthMenu: [
                [10, 50, 100],
                [10, 50, 100],
            ],
            columns: [
                {
                    "data": "noReferensi",
                    "orderable": false,
                    "className": "text-center align-middle"
                },
                {
                    "data": "namaSetAlat",
                    "orderable": false,
                    "className": "align-middle"
                },
                {
                    "data": "jenis",
                    "orderable": false,
                    "className": "align-middle"
                },
                {
                    "data": "satuan",
                    "orderable": false,
                    "className": "align-middle"
                },
                {
                    "data": "id",
                    "render": function (data, type, row) {
                        let editUrl = url + "/edit/" + data;
                        let hapusUrl = url + "/hapus/" + data;
                        let html = /* html */`
                        <div class="d-flex justify-content-center">
                            <a href="${editUrl}" class="btn btn-warning btn-sm border-0" data-popup='tooltip' title='Edit Data'>
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="${hapusUrl}" method="POST" class="formHapus">
                                <input type="hidden" name="${csrfToken}" value="${csrfHash}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="ml-1 btn btn-danger btn-sm border-0" data-popup='tooltip' title='Hapus Data'>
                                    <i class="far fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>`;

                        return html;
                    },
                    "orderable": false,
                    "className": "text-center align-middle"
                },
            ],
            language: {
                lengthMenu: 'TAMPIL _MENU_ DATA',
                zeroRecords: 'TIDAK ADA DATA',
                info: 'HALAMAN _PAGE_ DARI _PAGES_',
                infoEmpty: '',
                infoFiltered: '(FILTER DARI _MAX_ TOTAL DATA)',
                loadingRecords: "Loading...",
                processing: "Proses menampilkan data...",
            },
        }
    );
}

$('#tabelDataSetAlat tbody').on("click", ".formHapus button[type='submit']", function (e) {
    e.preventDefault();
    swalDelete.fire({
        title: 'Hapus Set Alat',
        text: "Apakah yakin hapus?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: "YAKIN",
        cancelButtonText: "BATAL",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            let form = $(this).closest("form");
            let data = form.serialize();
            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                data: data,
                dataType: "JSON",
                success: function (res) {
                    console.log(res);
                    if (res.sukses) {
                        swalWithBootstrapButtons.fire(
                            res.pesan.judul,
                            res.pesan.teks,
                            "success"
                        ).then(() => $('#tabelDataSetAlat').DataTable().ajax.reload(null, false));
                    } else {
                        swalWithBootstrapButtons.fire(
                            res.pesan.judul,
                            res.pesan.teks,
                            "error"
                        );
                    }
                },
                error: function (xhr, textStatus, thrownError) {
                    console.log(xhr.status + " => " + textStatus);
                    console.log(thrownError);
                    swalWithBootstrapButtons.fire(
                        "Gagal",
                        "Terjadi Masalah,<br>Silahkan Hubungi Tim IT",
                        "error"
                    );
                }
            });
        }
    });
});