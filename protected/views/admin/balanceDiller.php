<div class="">
    <form action="sendForm">

        <select name="seller" id="seller" style="margin-bottom: 10px">
            <? foreach ($list as $val){?>
                <option value="<?=$val["userId"]?>"><?=$val["login"]?></option>
            <?}?>
        </select>
        <input type="button" id="view" value="Показать">
    </form>

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
                    url: "<?php echo Yii::app()->createUrl('admin/AjaxBalanceDiller'); ?>",
                    data: "userId="+seller,
                    success: function(data){
                        $("#data").html(data)
                    }
                });
            });
        });

    </script>