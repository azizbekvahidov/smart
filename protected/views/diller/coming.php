<input type="text" placeholder="SN или IMEI код телефона" id="value" style="margin-bottom: 20px" />
<form action="/diller/comings" method="post" id="comingForm">
    <div id="data">
        <table class="hl-table">
            <thead>
                <tr>
                    <th>№</th>
                    <th>SN</th>
                    <th>IMEI1</th>
                    <th>IMEI2</th>
                    <th>Дата производства</th>
                    <th>Наименование</th>
                    <th>Цвет</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="tableBody">

            </tbody>
        </table>
        <input type="submit" value="Сохранить">
    </div>
</form>
<script>
    $(document).ready(function(){
                $("#value").val("").focus();
        $("#value").keypress(function( event){
            if ( event.which == 13 ) {
                getPhone();
            }
        });

    });
    $(document).on("click",".deleteRow", function(){
        $(this).parent().parent().remove();
    });
    function getPhone(){
        var cnt = $("#tableBody tr").length;
        var value = $("#value").val();
        var str = "";
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('diller/ajaxComing'); ?>",
            data: "val="+value,
            success: function(data){
                var write = true;
                data = JSON.parse(data);
                $('#tableBody > tr').each(function() {
                    if($(this).children("td:nth-child(2)").text() == data.SN){
                        write = false;
                    }
                });
                if(!write){
                     alert("Этот телефон уже в списке");
                }
                else{
                    str = "<tr>" +
                            "<td><input type='text' name='printId[]' value='" + data.printId + "' hidden='hidden' > " + (parseInt(cnt)+1) +"</td>" +
                            "<td>" + data.SN + "</td>" +
                            "<td>" + data.IMEI1 + "</td>" +
                            "<td>" + data.IMEI2 + "</td>" +
                            "<td>" + data.printDate + "</td>" +
                            "<td>" + data.model + "</td>" +
                            "<td>" + data.color + "</td>" +
                            "<td><a href='#' class='deleteRow'>Удалить</a></td>" +
                        "</tr>";
                    if(data.hasPhone){
                        alert("Этот телефон уже приходован")
                    }
                    else {
    
                        $("#tableBody").append(str);
                    }
                }
                $("#value").val("").focus();
            }
        });
    }
</script>