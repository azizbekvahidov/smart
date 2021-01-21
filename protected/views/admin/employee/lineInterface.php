<script src="/resources/js/jquery.min.js"></script>
<script src="/resources/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="/resources/css/bootstrap.min.css" >
<link rel="stylesheet" href="/resources/css/keyboard.css" >
<script src="/resources/js/keyboard.js"></script>
<style>
    .btn-default{

        width: 110%;
        margin: 1%;
        font-size: 2.4vh;
        white-space: normal;
    }
    #actContent{
        height:350px;
        overflow-y: scroll;
    }
    body{
        background-image: url(/images/lineBG4.jpg);
        background-repeat: no-repeat;
        background-position: left 0vh top -45vh;
        background-size: cover;

    }
</style>
<div>
    <div class="col-sm-12 center">
        <div class="col-sm-2">
            <input type="text" id="empKey" class="form-control">
        </div>

        <button class="btn btn-danger" id="cencel">Отемна</button>
        <div class="col-sm-6" id="cont"></div>
    </div>
    <div class="" id="content">
        <div class="col-sm-9 ">
            <div id="operatorButtons" class="col-sm-12 hide">
                <? foreach ($phone as $val){?>
                    <div class="col-sm-4" >
                        <button class="btn btn-default operation" id="<?=$val["phoneId"]?>" style="font-size: 7vh"><?=$val["model"]?></button>
                    </div>
                <?}?>
            </div>
            <div id="brigadirButtons" class="col-sm-12 hide">
                <div class="col-sm-2">
                    <button class="btn btn-default reason" id="addDetail">Акт</button>
                </div>
                <div class="col-sm-2">
                    <button class="btn btn-default reason">Неисправность <br>на линии</button>
                </div>
                <div class="col-sm-2">
                    <button class="btn btn-default reason">Нехватка <br>деталей</button>
                </div>
                <div class="col-sm-2">
                    <button class="btn btn-default reason" id="callEmp">Вызов <br>ответ. лица</button>
                </div>
                <div class="col-sm-2">
                    <button class="btn btn-default reason " id="produce">Производимая <br>модель</button>
                </div>
                <div class="col-sm-2">
                    <button class="btn btn-default reason" id="produced">Выпущено</button>
                </div>
                <div class="col-sm-2">
                    <button class="btn btn-default reason" id="opertation">Сесть за работу</button>
                </div>
            </div>
        </div>

        <div class="col-sm-3 hide" id="imageDiv">
            <img src="/upload/employee/no-user-image.png" id="imgCont" width="100%" alt="">
        </div>
    </div>
</div>

<script>
    var timerId;
    var userID = 0;
    var depId = 0;
    var actNum = 0;
    var produce = false;
    $(document).ready(function () {
        timers();
        $(document).keyboard({
            language: 'russian,us',
            enterKey: function () {
                //alert('Hey there! This is a callback function example.');
            },
            keyboardPosition: 'bottom',
            directEnter: false
        });

        //$("#empKey").focus();
        $("#cencel").click(function () {
            $("#empKey").val("");
            $("#operatorButtons").addClass("hide");
            $("#brigadirButtons").addClass("hide");
            $("#cont").html("");
            $("#imageDiv").addClass("hide");
            $("#empKey").focus();
        });
    });

    function timers() {
        timerId = setInterval(function() {
            $("#empKey").focus();
        }, 500);
    }

    $(document).on("keypress", function (e) {
        if(e.which == 13){
            Identity();
        }
    });

    $(document).on("change","#department", function () {
        depId = $(this).val();
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('action/getPosition'); ?>",
            data: "id=0-0-"+depId+"&phoneId="+phoneId,
            success: function(data){
                var res = JSON.parse(data);
                $("#positionModal").modal("show");
                var text = '<div id="operatorButtons" class="col-sm-12">';
                $.each(res, function (key, value) {
                    text += '<div class="col-sm-4" >\
                            <button class="btn btn-default position" id="" style="font-size: 2.5vh; height: 14vh;">'+value.name+'</button>\
                            </div>';
                });
                text += '</div>';
                $("#positions").html(text);
            }
        });
    });

    var phoneId;
    $(document).on("click",".operation", function(){
        phoneId = $(this).attr("id");
        $("#department").val(depId);
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('action/getPosition'); ?>",
            data: "id="+$("#empKey").val()+"&phoneId="+$(this).attr("id"),
            success: function(data){
                var res = JSON.parse(data);
                $("#positionModal").modal("show");
                var text = '<div id="operatorButtons" class="col-sm-12">';
                    $.each(res, function (key, value) {
                        let str = value.linePosition;
                        if (str.search(',') != -1) {
                            let list = str.split(',');
                            console.log()
                            for (let j of list) {
                                text += '<div class="col-sm-4" >\
                                    <button class="btn btn-default position" id="' + j + '" style="font-size: 2.5vh; height: 14vh;">' + value.name + '</button>\
                                    </div>';
                            }
                        }
                        else {
                            text += '<div class="col-sm-4" >\
                                <button class="btn btn-default position" id="' + str + '" style="font-size: 2.5vh; height: 14vh;">' + value.name +'</button>\
                                </div>';
                        }
                    });
                    text += '</div>';
                    $("#positions").html(text);
            }
        });
    });
    $(document).on("click", "#opertation", function () {
        $("#operatorButtons").removeClass("hide");
        $("#brigadirButtons").addClass("hide");
    });

    $(document).on("click",".position", function () {

        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('action/lineOperation'); ?>",
            data: "id="+$("#empKey").val()+"&reason="+$(this).attr("id")+"&phoneId="+phoneId,
            success: function(data){
                $("#empKey").val("");
                $("#operatorButtons").addClass("hide");
                $("#imageDiv").addClass("hide");
                $("#cont").html("");
                $("#positionModal").modal("hide");
                $("#positions").html("");
                $("#empKey").focus();
            }
        });
    });
    function Identity(){
        var img = "no-user-image.png";
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('admin/getEmployees'); ?>",
            data: "id="+$("#empKey").val()+"&reason="+$(this).text()+"&reason=line",
            success: function(data){
                data = JSON.parse(data);
                if(data.stat == "true") {
                    userID = data.employeeId;
                    depId = data.departmentId;
                    if (data.photo != "")
                        img = data.photo;
                    $("#cont").html("<h2>"+data.surname + " " + data.name + " " + data.lastname +"</h2>");
                    $("#imgCont").attr("src","/upload/employee/" + img);
                    if(data.positionId == 1){
                        $("#operatorButtons").removeClass("hide");
                        $("#imageDiv").removeClass("hide");
                    }
                    else if(data.positionId == 2){
                        $("#brigadirButtons").removeClass("hide");
                        $("#imageDiv").removeClass("hide");
                    }

                }
                else if(data.stat == "false"){
                    $("#empKey").val("");
                    $("#cont").html("");
                    $("#empKey").focus();
                }
            }
        });
    }

    $(document).on("click","#addDetail", function(){
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('action/checkAct'); ?>",
            data: "id="+depId,
            success: function (obj) {
                var data = JSON.parse(obj);
                if(data.id != 0){
                    actNum = data.id;
                    var text = '<table class="table-bordered table">\
                        <thead>\
                            <tr>\
                                <td class="text-center">№</td>\
                                <td class="text-center">Наименование сырья и материалов</td>\
                                <td class="text-center">ед.изм.</td>\
                                <td class="text-center">Кол-во</td>\
                                <td class="text-center">Причина</td>\
                                <td class="text-center">Завод/Произ</td>\
                                <td class="text-center"></td>\
                            </tr>\
                        </thead>\
                        <tbody>';
                    $.each(data.info, function(i, b) {
                        text += '<tr id="'+b.actdetailId+'">\
                            <td class="text-center">'+(parseInt(i)+1)+'</td>\
                            <td>'+b.model+' '+b.name+'</td>\
                            <td class="text-center">шт</td>\
                            <td class="text-center skdCnt">'+b.cnt+'</td>\
                            <td class="text-center produceCnt">'+b.desc+'</td>\
                            <td class="text-center produceCnt">'+b.cause+'</td>\
                            <td><a class="btn removeDetail"><i class="glyphicon glyphicon-remove"></i></a></td>\
                        </tr>';
                        });
                        text += '</tbody></table>';
                        $("#actContent").html(text);
                }
                else{
                    actNum = 0;
                    var text = "<button id='createAct' class='btn-success btn pull-right'>Создать Акт</button>";
                    $("#actContent").html(text);
                }
            }
        });
        $("#addModal").modal("show");
    });

    $(document).on("click","#produce", function(){
        produce = false;
        $("#modelCnt").addClass("hide");
        $("#modelModal").modal("show");
    });

    $(document).on("click","#produced", function(){
        produce = true;
        $("#modelCnt").removeClass("hide");
        $("#modelModal").modal("show");
    });

    $(document).on("click","#createAct", function () {
        var el = $(this);
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('action/createAct'); ?>",
            data: "id="+depId,
            success: function (data) {
                actNum = data;
                el.remove();
            }
        });
    });

    $(document).on("click",".removeDetail", function () {
        var el = $(this).parent().parent();
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('admin/DeleteActDetail'); ?>",
            data: "id="+el.attr("id"),
            success: function (data) {
                el.remove();
            }
        });
    });

    $(document).on("click",".detailSave", function () {
        if(actNum != 0) {
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('action/addActDetail'); ?>",
                data: $(".detailForm").serialize() + "&id=" + actNum,
                success: function (data) {
                    data = JSON.parse(data);
                    //location.reload();
                    $("#addModal").modal("hide");
                    showAlert(data.alert,data.alertType);
                }
            });
        }
    });

    $(document).on('hide.bs.modal','.modal', function () {
        timers();
    });
    $(document).on('show.bs.modal','.modal', function () {
        clearInterval(timerId);
    });

    $(document).on('click','.modelSave', function () {
        console.log(depId);
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('action/produce'); ?>",
            data: $(".modelForm").serialize() + "&produce=" + produce+"&depId="+depId,
            success: function (data) {
                data = JSON.parse(data);
                //location.reload();
                $("#modelModal").modal("hide");
                showAlert(data.alert,data.alertType);
            }
        });
    });

    $(document).on("click","#callEmp",function () {
        $("#callModal").modal("show");
    });

    function showAlert(text,alertType){
        var alertClass = "";
        $("#alertCont").removeClass("alert-success").removeClass("alert-danger");
        if(alertType == "danger"){
            alertClass = "alert-danger";
        }
        if(alertType == "success"){
            alertClass = "alert-success";
        }
        $("#alertModal").modal("show");
        $("#alertCont").addClass(alertClass).text(text);
        setTimeout(function () {
            $("#alertModal").modal("hide");
        },1500);
    }

    function callEmp(id) {
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('action/callEmp'); ?>",
            data: "id="+id+"&userId="+userID,
            success: function (data) {
                //data = JSON.parse(data);
                //location.reload();
                $("#callModal").modal("hide");
                //showAlert(data.alert,data.alertType);
            }
        });
    }
</script>

<!-- Modal window for act -->

<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form class="detailForm">
                    <table class="table table-bordered">
                        <tr>
                            <td>
                                <select name='phone' class='form-control'>
                                    <? foreach ($list as $val){?>
                                        <option value='<?=$val["phoneId"]?>'><?=$val["model"]?></option>
                                    <?}?>
                                </select>
                            </td>
                            <td>
                                <select name='spare' class='form-control'>
                                    <? foreach ($spare as $val){?>
                                        <option value='<?=$val["spareId"]?>'><?=$val["name"]?></option>
                                    <?}?>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="cnt" placeholder="Кол-во" class="placeholder form-control">
                            </td>
                            <td>
                                <input type="text" name="desc" class="form-control" placeholder="Описание">
                            </td>
                            <td>
                                <select name="cause" class="form-control">
                                    <option value="Ishlab chiqarish brak">Ishlab chiqarish brak</option>
                                    <option value="SKD brak">Пред продажа</option>
                                    <option value="SKD brak">SKD brak</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </form>
                <div id="actContent"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary closeBtn"  data-dismiss="modal">Выход</button>
                <button type="button" class="btn btn-success detailSave">Сохранить</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal window for  -->

<div class="modal fade" id="modelModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form class="modelForm">
                    <div class="form-group">
                        <select name="modelId" id="" class="form-control" >
                            <?foreach ($phone as $val){?>
                                <option value="<?= $val["phoneId"] ?>"><?=$val["model"]?></option>
                            <?}?>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="text" placeholder="Кол-во" name="modelCnt" id="modelCnt" class="form-control">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary closeBtn"  data-dismiss="modal">Выход</button>
                <button type="button" class="btn btn-success modelSave">Сохранить</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="callModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-group">
                <?foreach ($callEmp as $val){?>
                    <div class="col-sm-3" >
                        <a href="javascript:;" onclick="callEmp(<?=$val["employeeId"]?>)"><img width="200" height="250" src="/upload/employee/<?= $val["photo"] ?>" alt=""></a>
                    </div>
                <?}?>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="alert" role="alert" id="alertCont">...</div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="positionModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-group">
                    <select name="" id="department" class="form-control">
                        <? foreach ($dep as $val){?>
                            <option value="<?=$val["departmentId"]?>"><?=$val["name"]?></option>
                        <?}?>
                    </select>
                </div>
                <div class="form-group" id="positions">

                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>