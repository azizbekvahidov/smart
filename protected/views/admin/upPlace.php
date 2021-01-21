<form action="" method="post">
    <div>
        <label for="">Наименование</label>
        <input type="text" name="place[name]" value="<?=$model["name"]?>" />
    </div>
    <!--<br>
    <div>
        <label for="">Тип точки</label>
        <?=CHtml::dropDownList("place[pType]",$model["place"],$list,array())?>
    </div>
    <br>
    <div id="data" style="">
        <? if($model["parent"] != null){?>
            <label for="">относится к</label>
            <?=CHtml::dropDownList("place[parent]",$model["parent"],$list,array())?>
        <?}?>
    </div>-->
    <br>
    <div>
        <button type="submit">Сохранить</button>
    </div>
</form>
<script>
    $(document).ready(function(){
        var month,
            seller;
        $('#place_pType').change(function(){
            place = $('#pType').val();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('admin/AjaxGetPlace'); ?>",
                data: "place="+place,
                success: function(data){
                    $("#data").html(data)
                }
            });
        });
    });

</script>