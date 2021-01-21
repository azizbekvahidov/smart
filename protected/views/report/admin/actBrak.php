<script src="<?php echo Yii::app()->request->baseUrl; ?>/resources/js/jquery-1.11.3.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/resources/js/highcharts/highcharts.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/resources/js/highcharts/modules/exporting.js"></script>
<h1>Актовые браки</h1>
<? $func = new Functions()?>
<div class="">
    <form action="sendForm" autocomplete="off">
        <div class="col-sm-2">
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
        <div class="col-sm-2">
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

        <div class="col-sm-2">
            <select name="cause" id="cause" class="form-control">
                <option value="0">Все</option>
                <option value="Ishlab chiqarish brak">Ishlab chiqarish brak</option>
                <option value="SKD brak">SKD brak</option>
            </select>
        </div>

        <div class="col-sm-2">
            <select name="cause" id="place" class="form-control">
                <option value="0">Все</option>
                <option value="1">Ishlab chiqarish brak</option>
                <option value="2">Сервис</option>
            </select>
        </div>


        <input type="button" id="view" class="btn" value="Показать">
        &nbsp; <a href="javascript:;" id="export" class="btn btn-success">Экспорт</a>
    </form>
</div>
<div style="margin-top: 20px;">
    <div id="data">

    </div>

</div>

<script>


    $('#export').click(function(){
        $('#dataTable').table2excel({
            name: "Excel Document Name"
        });
    });

    $(document).ready(function(){

        var from,
            to,
            type;
        $('#view').click(function(){
            from = $('#start').val();
            to = $('#end').val();
            type = $('#cause').val();
            place = $('#place').val();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('report/getActBrak'); ?>",
                data: "from="+from+'&till='+to+'&cause='+type+'&place='+place,
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