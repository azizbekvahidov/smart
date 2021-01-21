<form action="" method="post">
    <input type="text" placeholder="Кол-во" class="hide" name="plan[action]" value="insert">
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
                "id" => "planDate",
                "name" => "plan[planDate]",
                "autocomplete" => "off"
            ) // jquery plugin options
        ));
        ?>
    </div>
    <div class="col-sm-2">
        <select name="plan[model]" id="model" class="form-control">
            <? foreach ($model as $val){?>
                <option value="<?=$val["phoneId"] ?>"><?=$val["model"]?></option>
            <?}?>
        </select>
    </div>

    <div class="col-sm-2">
        <input type="text" placeholder="Кол-во" class="form-control" name="plan[cnt]">
    </div>

    <div class="col-sm-2">
        <button type="submit" class="btn btn-success">Сохранить</button>
    </div>
</form>

<script>
    $(document).on("chane", "#model" function () {
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('admin/getPlan'); ?>",
            data: "planDate="+$("#planDate").val()+"&model="+$(this).val(),
            success: function(data){
                data = JSON.parse(data);
                if(!$.isEmptyObject(data)){
                    console.log("yes");
                }
                else{
                    console.log("no");
                }
            }
        });
    })
</script>
