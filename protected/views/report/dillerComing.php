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
        <input type="button" id="view" value="Показать">
&nbsp; <a href="javascript:;" id="export" class="btn btn-success">Экспорт</a>
    </form>
</div>
<div id="data" style="margin-top: 20px;"></div>
<script>
    $(document).ready(function(){
        var from,
            to;
        $('#view').click(function(){
            from = $('#start').val();
            to = $('#end').val();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('report/AjaxComing'); ?>",
                data: "from="+from+'&to='+to,
                success: function(data){
                    var date;
                    data = JSON.parse(data);
                    str = "<table id='dataTable' class='span-15'><thead>" +
                            "<tr>" +
                                "<th>№</th>" +
                                "<th>SN</th>" +
                                "<th>IMEI1</th>" +
                                "<th>IMEI2</th>" +
                                "<th>Модель</th>" +
                                "<th>Цвет</th>" +
                                "<th>Дата</th>" +
                            "</tr>" +
                        "</thead><tbody>";
                    $.each(data.model, function(key,val){

                        str += "<tr>" +
                            "<td>" + parseInt(key+1) + "</td>" +
                            "<td>" + val.SN + "</td>" +
                            "<td>" + val.IMEI1 + "</td>" +
                            "<td>" + val.IMEI2 + "</td>" +
                            "<td>" + val.model + "</td>" +
                            "<td>" + val.color + "</td>" +
                            "<td>" + val.createAt + "</td>" +
                            "</tr>";
                    });
                    str += "</tbody></table>";
                    str += "<table id='' class='span-6' style='border: 1px solid #000;'><thead>" +
                        "<tr>" +
                        "<th colspan='3' style=' text-align: center'>Количесто по моделям</th>" +
                        "</tr>" +
                        "<tr>" +
                        "<td>№</td>" +
                        "<td>Модель</td>" +
                        "<td>Кол-во</td>" +
                        "</tr>" +
                        "</thead><tbody>";
                    $.each(data.cnt, function(key,val){

                        str += "<tr>" +
                            "<td>" + parseInt(key+1) + "</td>" +
                            "<td>" + val.model + "</td>" +
                            "<td>" + val.cnt + "</td>" +
                            "</tr>"
                    });
                    str += "</tbody></table>";
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