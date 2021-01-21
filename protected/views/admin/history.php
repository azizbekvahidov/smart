<div class="row">
    <input type="text" id="phone">
    <button type="button" id="show">Показать</button>
</div>
<div class="row" id="data">

</div>
<script>
    $(document).ready(function(){
        $("#phone").val("").focus();
        $("#phone").keypress(function( event){
            if ( event.which == 13 ) {
                getPhone();
            }
        });
        var seller;
        $('#show').click(function(){
            getPhone();
        });

    });

    function getPhone(){
        var phone = $('#phone').val();
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('admin/AjaxHistory'); ?>",
            data: "phone="+phone,
            success: function(data){
                data = JSON.parse(data);
                var str = "<div class='row'>" +
                    "<table class='table table-bordered col-lg-4'>" +
                        "<tr><th>Модель: </th><th>"+data.phone.model+"</th></tr>" +
                        "<tr><th>Цвет: </th><th>"+data.phone.color+"</th></tr>" +
                        "<tr><th>SN: </th><th>"+data.phone.SN+"</th></tr>" +
                        "<tr><th>IMEI1: </th><th>"+data.phone.IMEI1+"</th></tr>" +
                        "<tr><th>IMEI2: </th><th>"+data.phone.IMEI2+"</th></tr>" +
                    "</table></div>" +
                    "<table class='table table-bordered col-lg-12'>" +
                        "<tr>" +
                            "<th>№</th>" +
                            "<th>Действие</th>" +
                            "<th>Дата</th>" +
                            "<th>Пользователь</th>" +
                        "</tr>";
                    $.each(data.logs, function(key,val){
                        var actions = "";
                        switch (val.actions){
                            case "prixod":
                                actions = "Приход";
                                break;
                            case "rasxod":
                                actions = "Расход";
                                break;
                            case "sold":
                                actions = "Продано";
                                break;
                            case "delete":
                                actions = "Цепочка действий удалена(Возврат)";
                                break;
                        }
                        str += "<tr>" +
                            "<th>" + (key + 1) + "</th>" +
                            "<th>" + actions + "</th>" +
                            "<th>" + val.logDate + "</th>" +
                            "<th>" + val.login  + " - " + val.name + " " + val.surname + " " + val.lastname + "</th>" +
                        "</tr>";
                    });
                    str += "</table>";
                $("#data").html(str);
                $("#phone").val("").focus();
            }
        });
    }
</script>