<form action="sendForm">
    <div class="col-sm-1">
    <?php Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
    $this->widget('CJuiDateTimePicker',array(
        'name'=>"start", //Model object
        'attribute'=>'eventDate', //attribute name
        'mode'=>'date', //use "time","date" or "datetime" (default)
        'options'=>array(), // jquery plugin options
        'htmlOptions' => array(
            "class" => "form-control col-lg-4",
            "placeHolder" => "С"
        ) // jquery plugin options
    ));
    ?>
    </div>
    <div class="col-sm-1">
    <?php Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
    $this->widget('CJuiDateTimePicker',array(
        'name'=>"end", //Model object
        'attribute'=>'eventDate', //attribute name
        'mode'=>'date', //use "time","date" or "datetime" (default)
        'options'=>array(), // jquery plugin options
        'htmlOptions' => array(
            "class" => "form-control col-lg-4",
            "placeHolder" => "По"
        ) // jquery plugin options
    ));
    ?>
    </div>
    <input type="button" id="view" value="Показать" class="btn">
</form>

<div id="data" style="margin-top: 20px;"></div>
<script>
    $(document).ready(function(){
        var from;
        var end;
        $('#view').click(function(){
            from = $('#start').val();
            end = $('#end').val();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('report/ajaxLabaratory'); ?>",
                data: "from="+from+"&end="+end,
                success: function(data){
                    $('#data').html(data);
                }
            });
        });
    });
</script>