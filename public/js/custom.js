$('.select2').select2();

$('#tanggal').datetimepicker({
    format: 'DD/MM/YYYY'
});

$('#tanggaljam').datetimepicker({
    format: 'DD/MM/YYYY HH:mm'
});

$('#filterTanggal').daterangepicker({
    locale: {
        format: 'DD/MM/YYYY'
    }
});

function setModalContent(modal, element, type, url) {
    $(modal).find(element).attr(type, url);
}

function previewImage(e) {
    const image = e;
    const imgPreview = document.querySelector('.img-preview');

    imgPreview.style.display = 'block';

    const oFReader = new FileReader();
    oFReader.readAsDataURL(image.files[0]);

    oFReader.onload = oFREvent => {
        imgPreview.src = oFREvent.target.result;
    }
}

function previewFile(input, previewClass, previewName) {
    const file = input.files[0];
    const preview = document.querySelector(`.${previewClass}`);

    const reader = new FileReader();

    reader.onload = function (event) {
        if (file.type === 'application/pdf') {
            preview.innerHTML = `<embed src="${event.target.result}" width="100%" height="400px" class="mb-3 col-sm-6" />`;
        } else {
            preview.innerHTML = `<img class="card-img p-1" src="${event.target.result}" style="width:100%; height: 300px; object-fit: contain;"/>`;
        }
    }
    reader.readAsDataURL(file);
    $(previewName).val(file.name);
    preview.style.display = 'block';
}

function previewFiles(input, previewClass) {
    const files = input.files;
    const preview = document.querySelector(`.${previewClass}`);

    preview.innerHTML = '';

    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const reader = new FileReader();

        reader.onload = event => {
            if (file.type === 'application/pdf') {
                preview.innerHTML += `<embed src="${event.target.result}" width="100%" height="400px" class="mb-3 col-sm-6" />`;
            } else {
                preview.innerHTML += `<img class="img-fluid mb-3 col-sm-6" src="${event.target.result}" />`;
            }
        }

        reader.readAsDataURL(file);
    }

    preview.style.display = 'block';
}

function isValidUUID(uuid) {
    var uuidPattern = /^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i;
    return uuidPattern.test(uuid);
}


function validasiInputTabel(table, input, cell) {
    let jmlBaris = $(table).find('tbody tr');
    let baris = 0;
    jmlBaris.each(function (i) {
        let cellElement = $(this).find('td').eq(cell);
        let data = cellElement.find('span').data('values');
        // let data = cellElement.find('button').attr('values');
        if (data === input) {
            baris++;
        }
    });
    return baris;
}

const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
        confirmButton: 'btn btn-success ml-3',
        cancelButton: 'btn btn-default'
    },
    buttonsStyling: false
});

const swalDelete = Swal.mixin({
    customClass: {
        confirmButton: 'btn btn-danger ml-3',
        cancelButton: 'btn btn-default'
    },
    buttonsStyling: false
});


$(function () {
    $(window).keydown(function (event) {
        if (event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });
});

toastr.options = {
    "closeButton": false,
    "debug": false,
    "newestOnTop": false,
    "progressBar": true,
    "positionClass": "toast-bottom-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "10000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
}

function exportAction(e, dt, button, config) {
    var self = this;
    var oldStart = dt.settings()[0]._iDisplayStart;
    dt.one('preXhr', function (e, s, data) {
        // Just this once, load all data from the server...
        data.start = 0;
        data.length = 2147483647;
        dt.one('preDraw', function (e, settings) {
            // Call the original action function
            if (button[0].className.indexOf('buttons-copy') >= 0) {
                $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf('buttons-excel') >= 0) {
                $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                    $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf('buttons-csv') >= 0) {
                $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
                    $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
                    $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
                $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
                    $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf('buttons-print') >= 0) {
                $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
            }
            dt.one('preXhr', function (e, s, data) {
                // DataTables thinks the first item displayed is index 0, but we're not drawing that.
                // Set the property to what it was before exporting.
                settings._iDisplayStart = oldStart;
                data.start = oldStart;
            });
            // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
            setTimeout(dt.ajax.reload, 0);
            // Prevent rendering of the full data to the DOM
            return false;
        });
    });
    // Requery the server with the new one-time export settings
    dt.ajax.reload();
}