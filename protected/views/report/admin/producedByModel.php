
<h1>Список выпущенных телефонов</h1>
<? $func = new Functions()?>
<div class="">
    <form action="sendForm">
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
            <select name="model" id="model" class="form-control">
                <?foreach ($phones as $key => $val){?>
                <option value="<?=$val["phoneId"]?>" <?=($key == 0) ? "selected" : ""?>><?=$val["model"]?></option>
                <?}?>
            </select>
        </div>
        <div class="col-sm-2" id="colorBlock">
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
    getColor();

    function getColor(){

        var model = $('#model').val();
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('report/getModelColor'); ?>",
            data: 'model='+model,
            success: function(data){
                data = JSON.parse(data);
                var str = '<select name="color" id="color" class="form-control">'+
                    '<option value="0" selected>Все</option>';
                $.each(data, function (index, val) {
                    str += '<option value="'+val.colorId+'">'+val.name+'</option>'
                });
                str += '</select>';
                $("#colorBlock").html(str);
            }
        });
    }

    $(document).on("change","#model", function () {
        getColor();
    });

    $(document).ready(function(){

        var from,
            to, model, color;
        $('#view').click(function(){
            from = $('#start').val();
            to = $('#end').val();
            model = $('#model').val();
            color = $('#color').val();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('report/getProducedByModel'); ?>",
                data: "from="+from+'&till='+to+'&model='+model+'&color='+color,
                success: function(data){

                    data = JSON.parse(data);
                    var str = "<table class='table table-bordered' id='dataTable'>"+
                        "<tr>" +
                            "<td>#</td>" +
                            "<td>SN</td>" +
                            "<td>IMEI1</td>" +
                            "<td>IMEI2</td>" +
                        "</tr>";
                    $.each(data,function (index,val) {
                        str += "<tr>" +
                            "<td>"+(index+1)+"</td>" +
                            "<td>"+val.SN+"</td>" +
                            "<td>"+val.IMEI1+"</td>" +
                            "<td>"+val.IMEI2+"</td>" +
                            "</tr>";
                    });

                    str +="</table>";
                    $("#data").html(str);
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