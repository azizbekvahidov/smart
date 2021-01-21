
<div class="col-sm-6">
    <form action="" class=" " method="post" id="mainForm">
        <div class="col-sm-12">
            <div class="form-group col-sm-12">
                <div class="col-sm-8">
                    <select name="department" class="form-control" id="department">
                        <? foreach ($dep as $val){?>
                        <option value="<?=$val["departmentId"]?>"><?=$val["name"]?></option>
                        <?}?>
                    </select>
                </div>
            </div>
            <div class="form-group col-sm-12">
                <div class="col-sm-8">
                    <select name="rateType" class="form-control" id="rateType">
                        <option value="inout">Приход</option>
                        <option value="discipline">дисциплина</option>
                        <option value="error">Ошибки</option>
                    </select>
                </div>
            </div>

            <div class="form-group col-sm-12">
                <div class="col-sm-8" id="">
                    <select name="rateId" class="form-control" id="rateId"></select>
                </div>
            </div>
            <div class="form-group col-sm-12">
                <div class="col-sm-8">
                    <input type="text" name="rate" id="rate" class="form-control" placeholder="" value="<?=$pos["people"]?>" />
                </div>
            </div>
            <div class="form-group col-sm-12">
                <div class="col-sm-8">
                    <button class="btn btn-success" type="button" id="btnSave">Сохранить</button>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="col-sm-6">
    <table class="table-bordered table">
        <thead>
            <tr>
                <td>Тип оценки</td>
                <td>Наименование</td>
                <td>балл</td>
                <td></td>
            </tr>
        </thead>
        <tbody id="linerate">

        </tbody>
    </table>
</div>

<script>

    $(document).on("change","#rateType", function () {
        getRate();
    });
    $(document).on("change","#department", function () {
        getRateList();
    });

    $(document).on("click","#btnSave", function () {
        var formData = $("#mainForm").serialize();
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('position/rateSave'); ?>",
            data: formData,
            success: function(data){
                $("#rate").val("");
                getRateList();
            }
        });
    });
    function getRate() {
        var formData = $("#mainForm").serialize();
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('position/getRateList'); ?>",
            data: formData,
            success: function(data){
                var res = JSON.parse(data);
                var text = "";
                $.each(res,function (index, val) {
                    text += "<option value='"+index+"'>"+val+"</option>";

                });
                    $("#rateId").html(text);
                getRateList();
            }
        });
    }
    function deleteRate(id) {
        if (confirm("Вы уверены ?")) {
            // your deletion code
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('position/deleteRate'); ?>",
                data: "id="+id,
                success: function(data){
                    $("#"+id).remove();
                }
            });
        }
        return false;
    }
    getRate();

    function getRateList() {
        var formData = $("#mainForm").serialize();
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('position/getList'); ?>",
            data: formData,
            success: function(data){
                var res = JSON.parse(data);
                var text = "";
                $.each(res,function (index, val) {
                    console.log(val);
                    text += "<tr id='"+val.rateId+"'>";
                    if(val.ratetype == 'inout'){
                        text +="<td>Посящение</td>";
                    }
                    else if(val.ratetype == 'error'){
                        text +="<td>Ошибки</td>";
                    }
                    else if(val.ratetype == 'discipline'){
                        text +="<td>Дисциплина</td>";
                    }
                     text +="<td>"+val.name+"</td>";
                    text +="<td>"+val.rate+"</td>";
                    text +="<td><a href='javascript:;' onclick='deleteRate("+val.rateId+")'><span class='glyphicon glyphicon-remove'></span></a></td>";
                     text +="</tr>";

                });
                $("#linerate").html(text);
            }
        });
    }
</script>