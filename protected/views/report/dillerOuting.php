<? $func = new Functions()?>
<div class="">
    <form action="sendForm">
        <?php Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
        $this->widget('CJuiDateTimePicker',array(
            'name'=>"start", //Model object
            'attribute'=>'eventDate', //attribute name
            'mode'=>'date', //use "time","date" or "datetime" (default)
            'options'=>array() // jquery plugin options
        ));
        ?>
        <?php Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
        $this->widget('CJuiDateTimePicker',array(
            'name'=>"end", //Model object
            'attribute'=>'eventDate', //attribute name
            'mode'=>'date', //use "time","date" or "datetime" (default)
            'options'=>array() // jquery plugin options
        ));
        ?>
        <select name="seller" id="seller" style="margin-bottom: 10px">
            <option value="0">Все</option>
            <? foreach ($list as $val){?>
                <option value="<?=$val["userId"]?>"><?=$val["login"]?> - <?=$func->getPoint($val["point"])?></option>
            <?}?>
        </select>
        <input type="button" id="view" value="Показать">
&nbsp; <a href="javascript:;" id="export" class="btn btn-success">Экспорт</a>
    </form>
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
            seller = $("#seller").val();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('report/AjaxOuting'); ?>",
                data: "from="+from+'&to='+to+"&seller="+seller,
                success: function(data){
                    data = JSON.parse(data);
                    str = "<table id='dataTable' class='table table-bordered'><thead>" +
                        "<tr>" +
                        "<td>№</td>" +
                        "<td>SN</td>" +
                        "<td>IMEI1</td>" +
                        "<td>IMEI2</td>" +
                        "<td>Модель</td>" +
                        "<td>Цвет</td>" +
                        "<td>Дата</td>" +
                        "<td>Промоутер</td>" +
                        "</tr>" +
                        "</thead><tbody>";
                    $.each(data, function(key,val){

                        str += "<tr>" +
                            "<td>" + parseInt(key+1) + "</td>" +
                            "<td>" + val.SN + "</td>" +
                            "<td>" + val.IMEI1 + "</td>" +
                            "<td>" + val.IMEI2 + "</td>" +
                            "<td>" + val.model + "</td>" +
                            "<td>" + val.color + "</td>" +
                            "<td>" + val.createAt + "</td>" +
                            "<td>" + val.surname + " " + val.name + " "+ val.lastname + " - " +  val.point+ "</td>" +
                            "</tr>";
                        $("#tableBody").append(str);
                        $("#value").val("").focus();
                    });
                    str += "</tbody>";
                    $("#data").html(str);

                }
            });
        });
        
        $('#export').click(function(){
            $('#dataTable').table2excel({
                name: "Excel Document Name"
            });
        });
    });

</script>