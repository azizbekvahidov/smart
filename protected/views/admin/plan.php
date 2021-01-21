<? $func = new Functions()?>
<div class="">
    <form action="sendForm">
        <?php Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
        $this->widget('CJuiDateTimePicker',array(
            'name'=>"month", //Model object
            'attribute'=>'eventDate', //attribute name
            'mode'=>'date', //use "time","date" or "datetime" (default)
            'options'=>array(
                'dateFormat'=>'mm.yy',
            ),
        ));
        ?>
        <select name="seller" id="seller" style="margin-bottom: 10px">
            <? foreach ($list as $val){?>
                <option value="<?=$val["userId"]?>"><?=$val["login"]?> - <?=$func->getPoint($val["point"])?></option>
            <?}?>
        </select>
        <input type="button" id="view" value="Показать">
    </form>
 <a  href="createPlan" class="hide" id="addPlan">Добавить план</a>
</div>
<div id="data" class="span-6" style="margin-top: 20px;"></div>
<script>
    $(document).ready(function(){
        var month,
            seller;
        $('#view').click(function(){
            month = $('#month').val();
            seller = $("#seller").val();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('admin/AjaxPlan'); ?>",
                data: "month="+month+"&seller="+seller,
                success: function(data){
                    data = JSON.parse(data);
                    if(data.hasPlan) {
                        str = "<table><thead>" +
                            "<tr>" +
                            "<td>№</td>" +
                            "<td>Модель</td>" +
                            "<td>План</td>" +
                            "</tr>" +
                            "</thead><tbody>";
                        $.each(data.data, function (key, val) {

                            str += "<tr>" +
                                "<td>" + parseInt(key + 1) + "</td>" +
                                "<td>" + val.model + "</td>" +
                                "<td>" + val.plan + "</td>" +
                                "</tr>";
                            $("#tableBody").append(str);
                            $("#value").val("").focus();
                        });
                        str += "</tbody>";
                        $("#data").html(str);
                        $("#addPlan").removeClass("show");
                        $("#addPlan").addClass("hide")
                    }
                    else{
                        $("#data").html("");
                        $("#addPlan").attr("href","createPlan?seller="+seller+"&month="+month);
                        $("#addPlan").removeClass("hide");
                        $("#addPlan").addClass("show")
                    }

                }
            });
        });
    });

</script>