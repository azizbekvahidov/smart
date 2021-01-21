<? if($res == "false"){?>
<div class="alert alert-warning alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <strong>Внимание!</strong> Этот телефон либо продан либо отсутсвует в базе.
</div>
<?}?>
<div class="col-sm-4">
    <h1>Добавить проданный телефон</h1>
    <form action="" method="post" class="forms">
        <div class="form-group">
            <input type="text" name="phone[login]" class="form-control input" id="imei1" placeholder="Логин продавца">
        </div>
        <div class="form-group">
            <input type="password" name="phone[pass]" class="form-control input" id="imei2" placeholder="Пароль продавца">
        </div>
        <div class="form-group">
            <input type="text" name="phone[sn]" class="form-control input" id="sn" placeholder="SN или IMEI">
            <button class="btn btn-info check" type="button">Проверить телефон</button>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-success save" value="Сохранить">
        </div>
    </form>
</div>
<div class="col-sm-4">
    <div class="text-center hide" id="alertDiv" role="alert">
        <i class="" id="alert" style="font-size: 100px"></i>
        <div id="alertCont"></div>
    </div>
</div>
<script>
    $(document).ready(function(){
        var good = "text-success glyphicon glyphicon-thumbs-up",
            bad = "text-danger glyphicon glyphicon-thumbs-down";
        $(".input").keypress(function (e) {
        });
        $(".check").click(function () {
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('admin/AjaxCkeckPhone'); ?>",
                data: "data="+$("#sn").val(),
                success: function(data){

                    data = JSON.parse(data);
                    console.log(data);
                    if(data.bool){
                        if(data.model.sell == 1){
                            $("#alert").removeClass(good).addClass(bad);
                            $("#alertCont").text("Телефона продан продовцом под логином \"" + data.user.login + "\"");
                        }
                        else{
                            $("#alert").removeClass(bad).addClass(good);
                            $("#alertCont").text("Телефона не продан");
                        }
                        alert();
                    }else{
                        $("#alert").removeClass(good).addClass(bad);
                        $("#alertCont").text("Телефона нет в Базе");
                        alert();
                    }
                }
            });
        })
    });
    function alert(){
        $('#alertDiv').removeClass("hide");
        setInterval(function(){
            $('#alertDiv').addClass("hide");
        },10000);
    }
</script>