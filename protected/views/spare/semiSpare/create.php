<h1>Создать Полуготовые запчасти</h1>
<div class="col-lg-7">
    <form action="" method="post">
        <div class="form-group">
            <input name="semiSpare[name]" class="form-control" id="phone">
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
                var text = "<div class='form-group col-sm-6'><select name='semiSpare[spare][]' class='form-control'>";
                $.each(data, function(index, val){
                    text += "<option value='" + val.spareId + "'>" + val.name + "</option>"
                });
                text += "</select> </div> <div class='form-group col-sm-2'><input name='semiSpare[cnt][]' placeholder='Количество' type='number' class='form-control' value='1' /></div>";
                $("#data").html(text);
            }
        });
//        var id = $("#phone").val();
//        $.ajax({
//            type: "POST",
//            url: "<?php //echo Yii::app()->createUrl('admin/phoneStruct'); ?>//",
//            data: "id="+id,
//            success: function(data){
//                $("#phoneStruct").html(data);
//            }
//        });
    });
    //    $(document).on("change","#phone", function () {
    //        var id = $(this).val();
    //        $.ajax({
    //            type: "POST",
    //            url: "<?php //echo Yii::app()->createUrl('admin/phoneStruct'); ?>//",
    //            data: "id="+id,
    //            success: function(data){
    //                $("#phoneStruct").html(data);
    //            }
    //        });
    //    });
    $(document).on("click","#addSpare", function(){
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('spare/SpareList'); ?>",
            success: function(data){
                data = JSON.parse(data);
                var text = "<div class='form-group col-sm-6'><select name='semiSpare[spare][]' class='form-control'>";
                $.each(data, function(index, val){
                    text += "<option value='" + val.spareId + "'>" + val.name + "</option>"
                });
                text += "</select> </div> <div class='form-group col-sm-2'> <input name='semiSpare[cnt][]' placeholder='Количество' type='number' class='form-control' value='1' /></div>";
                $("#data").append(text);
            }
        });
    })
</script>