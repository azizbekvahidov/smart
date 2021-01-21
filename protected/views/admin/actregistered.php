

<div class="passBlock col-sm-12" style="    position: relative;    left: 41%;    top: 30%;">
    <div class="form-group center-block col-sm-2">
        <input type="password" class="form-control" placeholder="введите пароль" id="pass" >
    </div>
</div>
<div class="col-sm-10 hide actBlock">
    <label class="switch">
        <input type="checkbox" checked="checked" id="actType">
        <span class="slider round"></span>
    </label>
    <h1 id="actNum"></h1>
    <div id="actContent">
    </div>
    </form>
    <div class="form-group"><a href="/admin/actRegistered" class="btn btn-danger" id="saveAct">Закрыть Акт</a></div>
</div>
<script>
    var departmentId = 0;
    var num = 0;
    var actType = "phone";
    $(document).ready(function () {
        $("#pass").focus();
        $("#pass").keypress(function (e) {
            if(e.which == 13){
                $.ajax({
                    type: "POST",
                    url: "<?php echo Yii::app()->createUrl('admin/loginDepartment'); ?>",
                    data: "pass="+$("#pass").val(),
                    success: function(data){
                        data = JSON.parse(data);
                        if(!$.isEmptyObject(data)){
                            $(".passBlock").addClass("hide");
                            $(".actBlock").removeClass("hide");
                            $("#phone").focus();
                            $(this).val("");
                            departmentId = data.departmentId;
                            num = parseInt(data.actNum)+1;
                            $("#actNum").html("Акт № "+num)
                        }
                        else{
                            alert("Пароль не правильный")
                        }
                    }
                });
            }
        });
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('admin/phoneList'); ?>",
            success: function(data){
                var text = createPhone(data,actType);
                $("#actContent").append(text);
            }
        });

    });
    function createPhone(data,actT){
        var text = "";
        if(actT == "phone") {
            data = JSON.parse(data);
            text = '<div class="row panel panel-default"><form action="" id="actForm"><div class="form-group col-sm-2">' +
                '<select name="phoneId" class="form-control">';
            $.each(data, function (index, val) {
                text += "<option value='" + val.phoneId + "'>" + val.model + "</option>"
            });
            text += '</select></div><table class="table "><tbody class="data">';
            text += '</tbody></table>' +
                '<div class="form-group"><button type="button" class="btn btn-success" id="save">Сохранить</button></div>' +
                '<button type="button" class="btn-success btn right addSpare" style="" >Добавить строку</button></div>';
        }
        if(actT == "detail"){
            text = '<div class="row panel panel-default"><form action="" id="actForm">';

            text += '<table class="table "><tbody class="data">';
            text += '</tbody></table>' +
                '<div class="form-group"><button type="button" class="btn btn-success" id="save">Сохранить</button></div>' +
                '<button type="button" class="btn-success btn right addSpare" style="" >Добавить строку</button></div>';
        }
        return text;
    }

    function createCont(data){
        data = JSON.parse(data);
        var text = "";
        text = "<tr><td><select name='act[spare][]' class='form-control'>";
        $.each(data, function(index, val){
            text += "<option value='" + val.spareId + "'>" + val.name + "</option>"
        });
        text += '</select></td><td><input type="text" name="act[cnt][]" placeholder="Кол-во" class="placeholder form-control"></td>'+
            '<td><input type="text" name="act[desc][]" class="form-control" placeholder="Описание"></td>'+
            '<td><select name="act[cause][]" class="form-control">'+
            '<option value="Ishlab chiqarish brak">Ishlab chiqarish brak</option>';

        if(departmentId == 11){
            text += '<option value="SKD brak">Пред продажа</option>';
        }
        else{
            text += '<option value="SKD brak">SKD brak</option>';
        }
        text += '</select></td><td><a class="btn remove"><i class="glyphicon glyphicon-remove"></i></a></td></tr>';
        return text;
    }
    $(document).on("click",".remove", function () {
        $(this).parent().parent().remove();
    });
    $(document).on("click",".addSpare", function(){
        var element = $(this).parent().children("table").children("tbody");
        $.ajax({
            type: "POST",
            data: "actType="+actType,
            url: "<?php echo Yii::app()->createUrl('admin/SpareListTest'); ?>",
            success: function(data){
                var text = createCont(data);
                element.append(text);
            }
        });
    });
    $(document).on("keypress", function (e) {
        if(e.which == 43){
            $(".addSpare").click();
        }
    });
    $(document).on("click","#save", function(){
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('admin/saveAct'); ?>",
            data: $("#actForm").serialize()+"&departmentId="+departmentId+"&regId="+num+"&actType="+actType ,
            success: function(data){
                var text = createPhone( data,actType);
                $("#actContent").html(text);
//                $("#pass").focus();
//                $(".passBlock").removeClass("hide");
//                $(".actBlock").addClass("hide");
            }
        });
    });
    $(document).on("click", "#actType", function () {
        if($(this).is(':checked')){
            actType = "phone";
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('admin/phoneList'); ?>",
                success: function(data){
                    var text = createPhone(data,actType);
                    $("#actContent").html(text);
                }
            });
        }
        else{
            actType = "detail";
            var text = createPhone("",actType);
            $("#actContent").html(text);
        }
    })
</script>

<div class="modal fade modals " tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Структура</h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->