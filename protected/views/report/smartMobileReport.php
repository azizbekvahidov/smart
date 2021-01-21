<h1>Отчет по SmartMobile</h1>
<? $func = new Functions()?>
<div class="">
    <form action="sendForm">
        <div class="col-sm-1">
            <?php Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
            $this->widget('CJuiDateTimePicker',array(
                'name'=>"start", //Model object
                'attribute'=>'eventDate', //attribute name
                'mode'=>'date', //use "time","date" or "datetime" (default)
                'options'=>array(),
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
                'options'=>array(),
                'htmlOptions' => array(
                    "class" => "form-control col-lg-4",
                    "placeHolder" => "По"
                )// jquery plugin options

            ));
            ?>
        </div>
        <input type="button" id="view" class="btn" value="Показать">
    </form>
</div>
<div id="data" style="margin-top: 20px;"></div>
<script>
    $(document).ready(function(){
        var from,
            seller,
            to;
        $('#view').click(function(){
            from = $('#start').val();
            to = $('#end').val();
            seller = $("#seller option:selected").val();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('report/AjaxSmartMobileReport'); ?>",
                data: "from="+from+'&to='+to,
                success: function(data){
                    $("#data").html(data);
                }
            });
        });
    });

    $(document).on('click','#export1', function(){
        $('#dataTable').table2excel({
            name: "dealer report"
        });
    });
    $(document).on('click','#export2', function(){
        $('#dataTable2').table2excel({
            name: "dealer report"
        });
    });

</script>