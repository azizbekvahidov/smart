<div class="">
    <h2>Точка</h2>
    <div class="row">
        <div class="span-4">
            <?php echo CHtml::label('Город','login'); ?>
            <?php echo CHtml::dropDownList('city',"",CHtml::listData(Point::model()->findAll("place = 'city' ORDER BY name"),'pointId','name'),array("empty"=>"Выберите",)); ?>
        </div>
        <div id="listData">
            <div class="span-4" id="district">
            </div>
            <div class="span-4" id="market">
            </div>
            <div class="span-5" id="shop">
            </div>
        </div>
        <div class="span-4"><button type="button" id="refresh">обновить</button></div>
        <script>
            $(document).ready(function(){
                $('#city').change(function(){
                    var id = $(this).val();
                    $.ajax({
                        type: "POST",
                        url: "<?php echo Yii::app()->createUrl('users/getPlace'); ?>",
                        data: "id="+id+"&ctype=1",
                        success: function(data){
                            $('#district').html(data);
                        }
                    });
                });
            });
            $(document).on("click","#refresh", function(){
                var city = $("#city").val(),
                    district = $("#Users_district").val(),
                    market = $("#Users_market").val(),
                    shop = $("#Users_shop").val();
                $.ajax({
                    type: "POST",
                    url: "<?php echo Yii::app()->createUrl('users/getUsers'); ?>",
                    data: "city="+city+"&district="+district+"&market="+market+"&shop="+shop,
                    success: function(data){
                        data = JSON.parse(data);
                        var str = "<option value=''>Все</option>";
                        $.each(data, function(key,val){
                            str += "<option value='" + val.userId + "'>" + val.login + " - " + val.name + " " + val.surname + " " + val.lastname + "</option>";
                        });
                        $("#seller").html(str);
                    }
                });
            });
        </script>
    </div>
    <br>
    <br>
    <br>
    <form action="sendForm" class="row span-20">
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
            <option value="">Все</option>
            <? foreach ($list as $val){?>
                <option value="<?=$val["userId"]?>"><?=$val["login"]?> - <?=$val["name"]?> <?=$val["surname"]?> <?=$val["lastname"]?></option>
            <?}?>
        </select>
        <input type="button" id="view" value="Показать">
        &nbsp; <a href="javascript:;" id="export" class="btn btn-success">Экспорт</a>
    </form>
    <br>
    <br>
    <div id="data" class="col-sm-4" style="margin-top: 20px;"></div>
    <script>
        $(document).ready(function(){
            var month,
                seller;
            $('#view').click(function(){
                var city = $("#city").val(),
                    district = $("#Users_district").val(),
                    market = $("#Users_market").val(),
                    shop = $("#Users_shop").val();
                month = $('#month').val();
                seller = $("#seller").val();
                $.ajax({
                    type: "POST",
                    url: "<?php echo Yii::app()->createUrl('admin/AjaxBalanceUser'); ?>",
                    data: "userId="+seller+"&month="+month+"&city="+city+"&district="+district+"&market="+market+"&shop="+shop,
                    success: function(data){
                        $("#data").html(data)
                    }
                });
            });
        });

    </script>

    <script>
        $(document).ready(function(){
            $('#export').click(function(){
                    $('#dataTable').table2excel({
                    name: "Excel Document Name"
                });
            });
        });

    </script>
