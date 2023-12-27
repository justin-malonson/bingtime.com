$.validator.setDefaults({
    highlight: function (element) {
        $(element).closest('.form-group').addClass('has-error');
    },
    unhighlight: function (element) {
        $(element).closest('.form-group').removeClass('has-error');
    },
    errorElement: 'span',
    errorClass: 'help-block',
    errorPlacement: function (error, element) {
        if (element.parent('.input-group').length) {
            error.insertAfter(element.parent());
        } else {
            error.insertAfter(element);
        }
    }
});

function ajaxRequest(url, postData) {
    return new Promise((resolve, reject) => {
        let request = {
            type: "POST",
            url: url,
            dataType: 'json',
            data: postData,
            success: function (data) {
                if (data.redirect) {
                    document.location.href = data.redirect;
                } else if (data.error) {
                    reject(data.error);
                } else {
                    resolve(data.result);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                reject(errorThrown);
            }
        };
        if (postData instanceof FormData) {
            request.contentType = false;
            request.processData = false;
        }
        $.ajax(request);
    });
}

function disableInput(checkBoxId, input) {
    if ($(checkBoxId).is(':checked')) {
        $(input).prop("disabled", false);
    } else {
        $(input).prop("disabled", true);
    }
}