<h1>Структура телефона</h1>
<div class="col-lg-7">
    <form action="" method="post">
        <div class="form-group">
            <select name="struct[departmentId]" class="form-control" id="department">
                <? foreach ($model as $val){?>
                    <option value="<?=$val["departmentId"]?>"><?=$val["name"]?></option>
                <?}?>
            </select>
        </div>

        <div class="bg-success col-lg-6">
            <div id="spare"></div>
            <button type="button" style="position: fixed" href="" class="btn btn-success" id="addSpare">
                <i class="glyphicon glyphicon-plus"></i>
            </button>
        </div>
        <div class="bg-success col-lg-6">
            <div id="semiSpare"></div>
            <button type="button" style="position: fixed" href="" class="btn btn-success" id="addSemiSpare">
                <i class="glyphicon glyphicon-plus"></i>
            </button>
        </div>

        <br>
        <br>
        <br><div class="clear"></div>
        <div class="form-group">
            <button type="submit" class="btn btn-success">Сохранить</button>
        </div>
    </form>
</div>
<div class="col-lg-5">
    <div id="departmentStruct">

    </div>
</div>

<script>
    $(document).ready(function () {
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('spare/SpareList'); ?>",
            success: function(data){
                data = JSON.parse(data);
                var text = "<div class='form-group col-sm-8'><select name='struct[spare][]' class='form-control'>";
                $.each(data, function(index, val){
                    text += "<option value='" + val.spareId + "'>" + val.name + "</option>"
                });
                text += "</select> </div> <div class='form-group col-sm-2'><input name='struct[cnt][]' placeholder='Количество' type='number' class='form-control' value='1' /></div>";
                $("#spare").html(text);
            }
        });
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('spare/semiSpareList'); ?>",
            success: function(data){
                data = JSON.parse(data);
                var text = "<div class='form-group col-sm-8'><select name='struct[semiSpare][]' class='form-control'>";
                $.each(data, function(index, val){
                    text += "<option value='" + val.semispareId + "'>" + val.name + "</option>"
                });
                text += "</select> </div> <div class='form-group col-sm-2'><input name='struct[semiCnt][]' placeholder='Количество' type='number' class='form-control' value='1' /></div>";
                $("#semiSpare").html(text);
            }
        });
        var id = $("#department").val();
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('admin/departmentStruct'); ?>",
            data: "id="+id,
            success: function(data){
                $("#departmentStruct").html(data);
            }
        });
    });
    $(document).on("change","#department", function () {
        var id = $(this).val();
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('admin/departmentStruct'); ?>",
            data: "id="+id,
            success: function(data){
                $("#departmentStruct").html(data);
            }
        });
    });
    $(document).on("click","#addSpare", function(){
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('spare/SpareList'); ?>",
            success: function(data){
                data = JSON.parse(data);
                var text = "<div class='form-group col-sm-8'><select name='struct[spare][]' class='form-control'>";
                $.each(data, function(index, val){
                    text += "<option value='" + val.spareId + "'>" + val.name + "</option>"
                });
                text += "</select> </div> <div class='form-group col-sm-2'> <input name='struct[cnt][]' placeholder='Количество' type='number' class='form-control' value='1' /></div>";
                $("#spare").append(text);
            }
        });
    });

    $(document).on("click","#addSemiSpare", function(){
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('spare/semiSpareList'); ?>",
            success: function(data){
                data = JSON.parse(data);
                var text = "<div class='form-group col-sm-8'><select name='struct[semiSpare][]' class='form-control'>";
                $.each(data, function(index, val){
                    text += "<option value='" + val.semispareId + "'>" + val.name + "</option>"
                });
                text += "</select> </div> <div class='form-group col-sm-2'> <input name='struct[semiCnt][]' placeholder='Количество' type='number' class='form-control' value='1' /></div>";
                $("#semiSpare").append(text);
            }
        });
    });
</script>