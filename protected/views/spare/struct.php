<h1>Структура телефона</h1>
<div class="col-lg-7">
    <form action="" method="post">
        <div class="form-group">
            <select name="struct[phoneId]" class="form-control" id="phone">
                <? foreach ($model as $val){?>
                    <option value="<?=$val["phoneId"]?>"><?=$val["model"]?></option>
                <?}?>
            </select>
        </div>

        <div id="data" class="bg-success col-lg-11">

        </div>

        <br>
        <button type="button" style="position: fixed" href="" class="btn btn-success" id="addSpare">
            <i class="glyphicon glyphicon-plus"></i>
        </button>
        <br>
        <br>
        <div class="form-group">
            <button type="submit" class="btn btn-success">Сохранить</button>
        </div>
    </form>
</div>
<div class="col-lg-5">
    <div id="phoneStruct">

    </div>
</div>

<script>
    $(document).ready(function () {
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('spare/SpareList'); ?>",
            success: function(data){
                data = JSON.parse(data);
                var text = "<div><div class='form-group col-sm-4'><select name='struct[spare][]' class='form-control'>";
                $.each(data, function(index, val){
                    text += "<option value='" + val.spareId + "'>" + val.name + "</option>"
                });
                text += "</select> </div> <div class='form-group col-sm-1'><input name='struct[cnt][]' placeholder='Количество' type='text' class='form-control' value='1' /></div>" +
                    " <div class='form-group col-sm-3'><input name='struct[code][]' placeholder='SAP' class='form-control' /></div>"+
                    " <div class='form-group col-sm-3'><input name='struct[buxName][]' placeholder='Бух. название' class='form-control' /></div>"+
                    "<div class='form-group col-sm-1'><a class='btn remove'><i class='glyphicon glyphicon-remove'></i></a></div> </div>";
                $("#data").html(text);
            }
        });
        var id = $("#phone").val();
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('admin/phoneStruct'); ?>",
            data: "id="+id,
            success: function(data){
                $("#phoneStruct").html(data);
            }
        });
    });
    $(document).on("change","#phone", function () {
        var id = $(this).val();
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('admin/phoneStruct'); ?>",
            data: "id="+id,
            success: function(data){
                $("#phoneStruct").html(data);
            }
        });
    });

    $(document).on("click",".remove", function () {
        $(this).parent().parent().remove();
    });
    $(document).on("click","#addSpare", function(){
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('spare/SpareList'); ?>",
            success: function(data){
                data = JSON.parse(data);
                var text = "<div><div class='form-group col-sm-4'><select name='struct[spare][]' class='form-control'>";
                $.each(data, function(index, val){
                    text += "<option value='" + val.spareId + "'>" + val.name + "</option>"
                });
                text += "</select> </div> <div class='form-group col-sm-1'> <input name='struct[cnt][]' placeholder='Количество' type='text' class='form-control' value='1' /></div>" +
                    " <div class='form-group col-sm-3'><input name='struct[code][]' placeholder='SAP' class='form-control' /></div> " +
                    " <div class='form-group col-sm-3'><input name='struct[buxName][]' placeholder='Бух. название' class='form-control' /></div>"+
                    "<div class='form-group col-sm-1'><a class='btn remove'><i class='glyphicon glyphicon-remove'></i></a></div> </div>";
                $("#data").append(text);
            }
        });
    })
</script>