
<h1>FTQ</h1>
<div class="">
    <form action="/site/FtqData" autocomplete="off" method="post" >
        <div class="col-sm-2">
            <?php Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
            $this->widget('CJuiDateTimePicker',array(
                'name'=>"month", //Model object
                'attribute'=>'eventDate', //attribute name
                'mode'=>'date', //use "time","date" or "datetime" (default)
                'options'=>array(
                ),
                'htmlOptions' => array(
                    "class" => "form-control col-lg-4",
                    "placeHolder" => "С"
                ) // jquery plugin options
            ));
            ?>
        </div>
        <input type="button" id="view" class="btn" value="Показать">
        <input type="submit" id="print" class="btn btn-success"  value="Напечатать">
    </form>
</div>
<div id="data" style="margin-top: 20px;"></div>
<script>
    $(document).ready(function(){
        var month;
        $('#view').click(function(){
            month = $('#month').val();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('site/FtqData'); ?>",
                data: "month="+month,
                success: function(data){
                    $("#data").html(data);

                }
            });
        });
    });

</script>
