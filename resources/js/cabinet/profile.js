$(document).ready(function () {
    profile('view');
    $('.profileLink').click(function () {
        $(this).parent().parent().children('li').removeClass('active');
        $(this).parent().addClass('active');
    });
});
var pass = false;
$(document).on('keyup','#reNewPass', function () {
    if($(this).val() !== $('#newPass').val()){
        pass = false;
        $(this).parent().addClass('has-error');
        $(this).parent().children('span').text('Пароли не совпадают.')
    }
    else{
        pass = true;
        $(this).parent().removeClass('has-error');
        $(this).parent().children('span').text('Введите новый пароль повторно.')
    }
});
$(document).on('click','#savePass', function () {
    if(pass) {
        var data = $("#changePassword").serialize();
        $.ajax({
            url: '/cabinet/changePass',
            data: data,
            method: 'POST',
            success: function (res) {
                res = JSON.parse(res);
                App.alert({ container: $('#mainContent'), // alerts parent container
                    place: 'prepend', // append or prepent in container
                    message: res.text, // alert's message
                    close: true, // make alert closable
                    reset: true, // close all previouse alerts first
                    focus: true, // auto scroll to the alert after shown
                    closeInSeconds: 5000, // auto close after defined seconds
                    icon: 'fa fa-'+res.icon, // put icon class before the message
                    type: res.type
                });
                if(res.type === 'success'){
                    $("#changePassword")[0].reset();
                }
            }
        });
    }
    else{

    }
});
$(document).on('click','#saveFile', function () {
    var data = $("#fileInput").serialize();
    console.log($('#uploadedFile').val());
    console.log(data);
});
function profile(link) {
    $.ajax({
        url: '/cabinet/settings',
        data: {type:link},
        method: 'POST',
        success: function (res) {
            $("#profileContent").html(res);
        }
    })
}