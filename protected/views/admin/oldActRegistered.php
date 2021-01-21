<div class="col-sm-2">
    <?php Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
    $this->widget('CJuiDateTimePicker',array(
        'name'=>"start", //Model object
        'attribute'=>'eventDate', //attribute name
        'mode'=>'date', //use "time","date" or "datetime" (default)
        'options'=>array(),
        'htmlOptions' => array(
            "class" => "form-control col-lg-4",
            "placeHolder" => "Дата",
            "id" => "actDate"
        ) // jquery plugin options
    ));
    ?>
</div>
<div class="col-sm-2">
    <input type="text" id="actNum" class="form-control" placeholder="Акт №">
</div>
<div class="col-sm-2">
    <select name="" id="depId" class="form-control">
        <? foreach ($dep as $val){?>
            <option value="<?= $val["departmentId"] ?>"><?=$val["name"]?></option>
        <?}?>
    </select>
</div>
<div class="col-sm-2">
    <input type="text" id="brigadir" class="form-control" placeholder="Бригадир">
</div>
<div class="col-sm-12 actBlock">
    <h1 id="actNum"></h1>
    <div id="actContent">
    </div>
    <div class="form-group"><a href="/admin/oldActRegistered" class="btn btn-danger" id="saveAct">Закрыть Акт</a></div>
</div>
<script>
    var departmentId = 0;
    var num = 0;
    $(document).ready(function () {
        $(".spareLists").chosen({
            no_results_text: "Ничего не найдено"
        });
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
                var text = createPhone(data);
                $("#actContent").append(text);
            }
        });

    });
    function createPhone(data){
        data = JSON.parse(data);
        var text = '<div class="row panel panel-default"><form action="" id="actForm"><div class="form-group col-sm-2">'+
            '<select name="phoneId" class="form-control">';
        $.each(data, function(index, val){
            text += "<option value='" + val.phoneId + "'>" + val.model + "</option>"
        });
        text += '</select></div><table class="table "><tbody class="data">';
        text += '</tbody></table>' +
            '<div class="form-group"><button type="button" class="btn btn-success" id="save">Сохранить</button></div>'+
            '<button type="button" class="btn-success btn right addSpare" style="" >Добавить строку</button></form>'+
            '</div>';
        return text;
    }

    function createCont(data){
        data = JSON.parse(data);
        var text = " <tr><td><select name='act[spare][]' class='form-control spareLists'>";
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
            url: "<?php echo Yii::app()->createUrl('admin/SpareList'); ?>",
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
            url: "<?php echo Yii::app()->createUrl('admin/saveOldAct'); ?>",
            data: $("#actForm").serialize()+"&departmentId="+$("#depId").val()+"&regId="+$("#actNum").val()+"&regDate="+$("#actDate").val()+"&brigadir="+$("#brigadir").val(),
            success: function(data){
                var text = createPhone( data);
                console.log(text);
                $("#actContent").html(text);
//                $("#pass").focus();
//                $(".passBlock").removeClass("hide");
//                $(".actBlock").addClass("hide");
            }
        });
    });

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