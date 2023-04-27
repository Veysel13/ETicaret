$(document).on('click','.selectAll',function (){
    const target=$(this).data('target');
    $('#'+target).append(
        $('<option/>', {
            selected: true,
            text: 'All',
            value: ''
        })
    );
});

const ajaxForm = (context) => {
    const datatableTarget = context.data('datatabletarget');
    const dataUpdate = context.data('dataupdate');
    const modalClose = context.data('modalclose');

    context.find('.form-error').html('');
    context.find('BUTTON').hide();

    let formData;
    let contentType;
    let processData;
    if (context.prop('enctype') === 'multipart/form-data') {
        formData = new FormData(context[0]);
        contentType = false;
        processData = false;
    } else {
        formData = context.serialize();
        contentType = 'application/x-www-form-urlencoded; charset=UTF-8';
        processData = false;
    }

    const successCallback = context.data('successcallback') ? eval(context.data('successcallback')) : false;

    const defaultSuccessCallback = (response) => {
        if (datatableTarget) {
            if (response.message !==undefined){

                toastr.success(response.message,'Success');
            }else if(response.redirectUrl !==undefined) {
                location.href = response.redirectUrl;
                return;
            }

            $(datatableTarget).DataTable().ajax.reload(null, false);
        }else {
            location.href = response.redirectUrl;
            return false;
        }

        context.find('BUTTON').show();
        if (modalClose) {
            $(modalClose).modal('hide');
        }
    }

    $.ajax({
        url: context.prop('action'),
        method: context.prop('method'),
        contentType: contentType,
        processData: processData,
        data: formData,
        success: successCallback ? (response) => successCallback(response, context) : defaultSuccessCallback,
        error: function (xhr, status, error) {

            context.find('BUTTON').show();

            const response = $.parseJSON(xhr.responseText);
            const errors = Object.keys(response.errors).map(function (k) {
                return response.errors[k]
            });
            const errorHtml = `
                <div class="alert alert-warning">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                    ${errors.map(err => `${Object(err) === err ? err[0] : err}`).join('<br />')}
                </div>
                `;
            context.find('.form-error').html(errorHtml);
        }
    });
};

const ajaxAlertForm = (context) => {
    const datatableTarget = context.data('datatabletarget');
    const dataRemove = context.data('dataremove');

    $.ajax({
        url: context.prop('href'),
        method: context.prop('method') ? context.prop('method') : 'POST',
        success: function (response) {
            if (datatableTarget) {
                $(datatableTarget).DataTable().ajax.reload(null, false);
            }else {
                location.href = response.redirectUrl;
                return;
            }

            if (response.status === true) {
                toastr.success(response.message,'Success');
            }

            if (response.status === false) {
                toastr.error(response.message,'Error');
            }
        },
        error: function (xhr, status, error) {
            const response = $.parseJSON(xhr.responseText);

            const errors = Object.keys(response.errors).map(function (k) {
                return response.errors[k]
            });

            swal({
                title: 'Error !',
                text: errors.map(err => `${err}`).join('<br />'),
                type: "error",
                showCancelButton: false,
                confirmButtonText: 'Ok!'
            });
        }
    });
}

const ajaxEditForm = (context) => {

    const editModal = $('#editModal');
    editModal.modal('show');
    editModal.find('.body').html(`<div class="d-flex justify-content-center">
                                          <div class="spinner-border" role="status">
                                            <span class="sr-only">Yükleniyor...</span>
                                          </div>
                                   </div>`);

    $.ajax({
        url: context.prop('href'),
        method: context.prop('method') ? context.prop('method') : 'GET',
        success: function (response) {
            if (response.status === true) {
                editModal.find('.body').html(response.content);
            }
        },
        error: function (xhr, status, error) {
            const response = $.parseJSON(xhr.responseText);

            const errors = Object.keys(response.errors).map(function (k) {
                return response.errors[k]
            });

            editModal.modal('hide');

            swal({
                title: 'Error!',
                text: errors.map(err => `${err}`).join('<br />'),
                type: "error",
                showCancelButton: false,
                confirmButtonText: 'Ok!'
            });
        }
    });
};

const datePicker = (context) => {
    $(context).daterangepicker({
        timePicker: true,
        buttonClasses: ['btn', 'btn-sm'],
        applyClass: 'btn-danger',
        cancelClass: 'btn-inverse',
        singleDatePicker: true,
        timePicker24Hour: true,
        locale: {
            cancelLabel: 'CANCEL',
            applyLabel: 'APPLY',
            format: 'MM-DD-YYYY HH:mm:ss'
        }
    });
}

const onlyDatePicker = (context) => {
    $(context).daterangepicker({
        timePicker: false,
        buttonClasses: ['btn', 'btn-sm'],
        applyClass: 'btn-danger',
        cancelClass: 'btn-inverse',
        singleDatePicker: true,
        timePicker24Hour: false,
        locale: {
            cancelLabel: 'CANCEL',
            applyLabel: 'APPLY',
            format: 'MM-DD-YYYY'
        }
    });
}

const dateRangePicker = (context) => {

    const inputName = $(context).data('name');
    const inputName2 = `${inputName}2`;
    $(context).daterangepicker({
        autoUpdateInput: false,
        buttonClasses: ['btn', 'btn-sm'],
        applyClass: 'btn-danger',
        cancelClass: 'btn-inverse',
        ranges: {
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 15 Days': [moment().subtract(14, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        locale: {
            cancelLabel: 'CANCEL',
            applyLabel: 'APPLY',
            format: 'MM-DD-YYYY'
        }
    });

    $(context).on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('MM-DD-YYYY') + ' - ' + picker.endDate.format('MM-DD-YYYY'));
        $(context).parent().find('#' + inputName).find('input:first').val(picker.startDate.format('MM-DD-YYYY'));
        $(context).parent().find('#' + inputName).find('input:last').val(picker.endDate.format('MM-DD-YYYY'));
    });

    $(context).on('cancel.daterangepicker', function (ev, picker) {
        $(context).val('');
        $(context).parent().find('#' + inputName).find('input').val('');
    });
}

const tableSelectStatusChange = (context) => {
    const url = $(context).data('url');
    const refId = $(context).data('refid');
    const value = $(context).val();

    $(context).hide();
    $.ajax({
        url: url,
        method: 'POST',
        data: {
            'refId': refId,
            'newId': value
        },
        success: function (response) {
            if (response.status === true) {
                //todo: success
                $.toast({
                    heading: '',
                    text: response.message,
                    position: 'top-right',
                    loaderBg:'#ff6849',
                    icon: 'success',
                    hideAfter: 3500,
                    stack: 6
                });
            } else {
                Swal.fire({
                    title: 'Hata!',
                    text: response.message,
                    icon: 'error',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Tamam'
                })
            }
            $(context).show();
        },
        error: function (xhr, status, error) {
            const response = $.parseJSON(xhr.responseText);
            const errors = Object.keys(response.errors).map(function (k) {
                return response.errors[k]
            });
            const errorHtml = `${errors.map(err => `${Object(err) === err ? err[0] : err}`).join("\n")}`;
            Swal.fire({
                title: 'Hata!',
                text: errorHtml,
                icon: 'error',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Tamam'
            });
            $(context).show();
        }
    });
}

const tableSelectStatusChangeWithSwitch = (context) => {
    const url = $(context).data('url');
    const refId = $(context).data('refid');
    const value = $(context).is(':checked')?1:0;
    const datatableTarget = context.data('datatabletarget');

    $(context).hide();
    $.ajax({
        url: url,
        method: $(context).data('method')?$(context).data('method'):'POST',
        data: {
            'refId': refId,
            'newId': value
        },
        success: function (response) {

            if (datatableTarget) {
                $(datatableTarget).DataTable().ajax.reload(null, false);
            }

            if (response.status === true) {
                toastr.success(response.message,'Success');
            } else {
                toastr.error(response.message,'Error');
            }
            $(context).show();
        },
        error: function (xhr, status, error) {
            const response = $.parseJSON(xhr.responseText);
            const errors = Object.keys(response.errors).map(function (k) {
                return response.errors[k]
            });
            const errorHtml = `${errors.map(err => `${Object(err) === err ? err[0] : err}`).join("\n")}`;
            toastr.error(errorHtml,'Error');
            $(context).show();
        }
    });
}

const tableSelectPriceChange = (context) => {
    const url = $(context).data('url');
    const refId = $(context).data('refid');
    const value = $(context).val();

    $(context).hide();
    $.ajax({
        url: url,
        method: $(context).data('method')?$(context).data('method'):'POST',
        data: {
            'refId': refId,
            'newId': value
        },
        success: function (response) {
            if (response.status === true) {
                toastr.success(response.message,'Success');
            } else {
                toastr.error(response.message,'Error');
            }
            $(context).show();
        },
        error: function (xhr, status, error) {
            const response = $.parseJSON(xhr.responseText);
            const errors = Object.keys(response.errors).map(function (k) {
                return response.errors[k]
            });
            const errorHtml = `${errors.map(err => `${Object(err) === err ? err[0] : err}`).join("\n")}`;
            toastr.error(errorHtml,'Error');
            $(context).show();
        }
    });
}

$('body').find('.date-range-picker').each((i, e) => {
    dateRangePicker(e);
});

$('body').find('.only-date-picker').each((i, e) => {
    onlyDatePicker(e);
});

$('body').on('submit', '.ajaxForm', function (event) {
    event.preventDefault();
    ajaxForm($(this));
});

$('body').on('click', '.ajaxEditForm', function (event) {
    event.preventDefault();
    ajaxEditForm($(this));
});

$('body').on('change', '.tableSelectStatusChange', function (event) {
    event.preventDefault();
    tableSelectStatusChange($(this));
});

$('body').on('change', '.tableSelectStatusChangeWithSwitch', function (event) {
    event.preventDefault();
    tableSelectStatusChangeWithSwitch($(this));
});

$('body').on('change', '.tableSelectPriceChange', function (event) {
    event.preventDefault();
    tableSelectPriceChange($(this));
});

$('body').on('click', '.removeButton', function (event) {
    event.preventDefault();
    const context = $(this);

    swal({
        title: 'Perform the transaction?',
        text: "If you want to perform this operation, press the \"Yes\" button!",
        type: "warning",
        showCancelButton: true,
        cancelButtonClass: 'btn-secondary ',
        confirmButtonColor: "#DD6B55",
        confirmButtonText: 'Yes!',
        cancelButtonText: 'Cancel!',
        closeOnConfirm: true,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            ajaxAlertForm(context);
        }
    });
});

$('body').on('click', '.buttonAlert', function (event) {
    event.preventDefault();
    const context = $(this);
    Swal.fire({
        title: 'İşlem gerçekleştirilsin mi?',
        text: "bu işlemi gerçekleştirmek istiyorsanız \"Evet\" butonuna basın!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Vazgeç!',
        confirmButtonText: 'Evet!'
    }).then((result) => {
        if (result.value) {
            const form = context.data('form')
            $(form).submit();
        }
    });
});


// Default Left Menu Click Cookie
$('#sidebarnav').find('a').click((e) => {
    const href = e.currentTarget.href.replace('#', '');
    if (href !== '#') {
        localStorage.setItem('lastClickLeftMenu', href);
    }
});

if (localStorage.getItem('lastClickLeftMenu')) {
    const lastClickLeftMenu = localStorage.getItem('lastClickLeftMenu');
    $('#sidebarnav').find('a').each((i, e) => {
        if (lastClickLeftMenu == $(e).prop('href')) {
            $(e).parents('ul').removeClass('in');
            $(e).parents('ul').addClass('in');
            $(e).addClass('active');
        }
    })
}
if ($('.file-upload-input-container').length > 0 || true) {
    $(function () {
        $('body').on('click', '.file-upload-input-container .browse-btn', function () {
            var parentContainer = $(this).parent();
            parentContainer.find('input').click();
        });

        $('body').on('click', '.file-upload-input-container .file-info', function () {
            var parentContainer = $(this).parent();
            parentContainer.find('input').click();
        });

        $('body').on('change', '.file-upload-input-container input', function () {
            var name = $(this).val().split(/\\|\//).pop();
            $(this).parent().find('.file-info').text(name);
        });
    });
}
/* Fake Upload End */
/* Header Logo Fixed Start */
var logoDiv = $('.navbar.top-navbar.navbar-expand-md');
$(window).scroll(function () {
    if ($(window).scrollTop() > 5) {
        logoDiv.find('.navbar-header').addClass('mnt-fixed-logo');
        logoDiv.find('.navbar-collapse').css('margin-left', '-' + logoDiv.find('.navbar-header').width() + 'px');
    } else {
        logoDiv.find('.navbar-header').removeClass('mnt-fixed-logo');
        logoDiv.find('.navbar-collapse').css('margin-left', '0px');
    }
});
/* Header Logo Fixed End */

$(function () {
    $('.globalSelect2Tags').each(function (i, e) {
        $(e).select2({
            tags: true
        });
    });
});

/* Tooltip */
$(function() {
    $('[data-toggle="tooltip"]').tooltip();
})

