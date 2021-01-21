<script src="/resources/js/jquery.min.js"></script>
<script src="/resources/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="/resources/css/bootstrap.min.css" >
<style>
    .btn-default{

        width: 80%;
        height: 40%;
        margin: 3%;
        font-size: 5vh;
    }
    #signal{
        height: 6vh;
        margin: 1vh;
        font-weight: bold;
        color: #fff;
    }
    body{
        background-image: url(/images/receptionBG.jpg);
        background-repeat: no-repeat;
        background-position: left 0vh top -10vh;
        background-size: cover;

    }
</style>
<div>
    <div style="" class=" center">
        <div style="width: 35vh; display: inline-block; " class="">
            <input type="text" id="empKey" class="form-control">
        </div>
        <div  style="width:20vh; display: inline-block; ">
            <button class="btn btn-danger" id="cencel">Отмена</button>
        </div>
        <div  style="width:100vh; display: inline-block; " id="cont"></div>
        <div  style="width:70vh; display: inline-block; " id="signal" style=""></div>
    </div>
    <div class="hide" id="content">
        <div id="buttons" class="col-sm-9">
        <? foreach ($model as $val){?>
            <div class="col-sm-4">
                <button class="btn btn-default reason"><?=$val["desc"]?></button>
            </div>
        <?}?>
        </div>
        <div class="col-sm-3">
            <img src="/upload/employee/no-user-image.png" id="imgCont" width="100%" alt="">
        </div>
    </div>
</div>

<script>

    function inalert() {
        $("#signal").attr("style","background-color:green;").html("<h1>Вы вошли</h1>");
        setTimeout(function() {
            $("#signal").attr("style","background:none;").html("");
        }, 1000);
    }

    function outalert() {
        $("#signal").attr("style","background-color:red;").html("<h1>Вы вышли</h1>");
        setTimeout(function() {
            $("#signal").attr("style","background:none;").html("");
        }, 1000);
    }

    var timerId;
    var checkOuts;
    var empId = 0;
    $(document).ready(function () {
        timers();
        //$("#empKey").focus();
        $("#cencel").click(function () {
            $("#empKey").val("");
            $("#content").addClass("hide");
            $("#cont").html("");
            $("#empKey").focus();
        });
    });

    $(document).on("keypress", function (e) {
        if(e.which == 13){
            Identity();
        }
    });

    $(document).on("click",".reason", function(){
        var text = $(this).text();
        sendData("out",text);
        outalert();

    });
    function timers() {
        timerId = setInterval(function() {
            $("#empKey").focus();
        }, 500);
    }
    function checkaction() {
        setTimeout(function() {
            }, 6000);
    }

    function sendData(type,reason) {
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('admin/setReason'); ?>",
            data: "id="+$("#empKey").val()+"&type="+type+"&reason="+reason,
            success: function(data){

                $("#empKey").val("");
                $("#content").addClass("hide");
                $("#cont").html("");
                $("#empKey").focus();
            }
        });
    }
    function Identity(){
        var img = "no-user-image.png";
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('admin/testEmp'); ?>",
            data: "id="+$("#empKey").val(),
            success: function(data){
                data = JSON.parse(data);
                if(data.stat == "false") {
                    if (data.photo != "")
                        img = data.photo;
                    $("#cont").html("<h2 style='color: white'>"+data.surname + " " + data.name + " " + data.lastname +"</h2>");
                    $("#imgCont").attr("src","/upload/employee/" + img);
                    $("#content").removeClass("hide");
                    checkaction();
                }
                else if(data.stat == "true"){
                    sendData("in","");
                    inalert();
                }
            }
        });
    }

</script>