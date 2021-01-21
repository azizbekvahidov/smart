function getTask(){
    var cntTask = 0;
    var cntDoc = 0;
    $.ajax({
        method: 'POST',
        url: '/cabinet/getTask',
        success: function (res) {
            var data = JSON.parse(res);
            var tasks = "";
            $.each(data, function (index, val) {
                switch (index){
                    case 'protokol':
                        cntTask += val.length;
                        if(cntTask !== 0) {
                            $.each(val, function (ind, value) {
                                tasks += '<li>\
                                        <a href="javascript:;">\
                                           <span class="details">\
                                               <span class="label label-sm label-icon label-success">\
                                                   <i class="fa fa-rocket"></i>\
                                               </span> \
                                                   ' + value.meetQuestion +'\
                                           </span>\
                                       </a>\
                                   </li>';
                            });
                        }
                        break;
                    case 'task':
                        cntTask += val.length;
                        if(cntTask !== 0) {
                            $.each(val, function (ind, value) {
                                tasks += '<li>\
                                        <a href="javascript:;">\
                                           <span class="details">\
                                               <span class="label label-sm label-icon label-success">\
                                                   <i class="fa fa-tasks"></i>\
                                               </span> \
                                                   ' + value.task +'\
                                           </span>\
                                       </a>\
                                   </li>';
                            });
                        }
                        break;
                    case 'sign':
                        cntDoc += val.length;
                        break;
                }

            });
            if(cntTask != 0) {
                $("#header_notification_bar > a span").addClass('badge badge-default').html(' ' + cntTask + ' ');
                $("#profileTask a span").addClass('badge badge-success').html(' ' + cntTask + ' ');
                $(".external span").html(cntTask)
            }
            else{
                $("#header_notification_bar > a span").removeClass('badge badge-default').html(' ');
                $("#profileTask a span").removeClass('badge badge-success').html(' ');
                $(".external span").html('0')
            }

            if(cntDoc != 0) {
                console.log(cntDoc);
                $("#signDocs a span").addClass('badge badge-success').html(' ' + cntDoc + ' ');
                $(".external span").html(cntTask)
            }
            else{
                $("#header_notification_bar > a span").removeClass('badge badge-default').html(' ');
                $("#signDocs a span").removeClass('badge badge-success').html(' ');
                $(".external span").html('0')
            }
            $("#notify").html(tasks);
        }
    });


}

getTask();
$(document).on("click",'#saveTask', function () {
    // var data = $("#taskModalForm").serialize();
    var form = $('#taskModalForm')[0];

    var data = new FormData(form);
    console.log(data);
    $.ajax({
        method: 'POST',
        enctype: 'multipart/form-data',
        url: '/cabinet/taskSave',
        processData: false,
        contentType: false,
        cache: false,
        data: data,
        success: function (res) {
            if(res === '1'){
                App.alert({ container: $('#mainContent'), // alerts parent container
                    place: 'prepend', // append or prepent in container
                    message: 'Задача выставлена', // alert's message
                    close: false, // make alert closable
                    reset: true, // close all previouse alerts first
                    focus: true, // auto scroll to the alert after shown
                    closeInSeconds: 5000, // auto close after defined seconds
                    icon: 'fa fa-check', // put icon class before the message
                    type: 'success'
                });
            }
            else{
                App.alert({ container: $('#mainContent'), // alerts parent container
                    place: 'prepend', // append or prepent in container
                    message: 'Задача не выставлена', // alert's message
                    close: false, // make alert closable
                    reset: true, // close all previouse alerts first
                    focus: true, // auto scroll to the alert after shown
                    closeInSeconds: 5000, // auto close after defined seconds
                    icon: 'fa fa-close', // put icon class before the message
                    type: 'danger'
                });
            }
            $("#addTask").modal("hide");
            $('#taskModalForm')[0].reset();
        }

    });
});
setInterval(getTask, 5000);
