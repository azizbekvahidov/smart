
<h1>Информация о телефоне</h1>
<? $func = new Functions()?>
<div class="">
    <form action="sendForm">
        <div class="col-sm-2">
            <input type="text" name="data" id="val" class="form-control" placeholder="SN,IMEI1,IMEI2">
        </div>
        <input type="button" id="view" class="btn" value="Показать">
    </form>
</div>
<br>
    <div id="data" class="col-sm-5">

    </div>

<script>


    $(document).ready(function(){

        $('#view').click(function(){
            var value = $('#val').val();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('report/getPhoneInfo'); ?>",
                data: "data="+value,
                success: function(data){
                    data = JSON.parse(data);
                    var str = "<table class='table table-bordered'>"+
                        "<tr>" +
                            "<td>Модель: "+data.model+"</td>" +
                            "<td>Цвет: "+data.name+"</td>" +
                        "</tr>"+
                        "<tr>" +
                            "<td>SN: "+data.SN+"</td>" +
                            "<td>Коробка: "+data.box+"</td>" +
                        "</tr>"+
                        "<tr>" +
                            "<td>IMEI1: "+data.IMEI1+"</td>" +
                            "<td>Вес: "+data.NW+" г</td>" +
                        "</tr>"+
                        "<tr>" +
                            "<td>IMEI2: "+data.IMEI2+"</td>" +
                            "<td>Дата производства: "+data.produceDate+"</td>" +
                        "</tr>" +
                    "</table>";
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