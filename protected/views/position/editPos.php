<style>
    .chosen-drop{
        width: 311px!important;
    }
</style>
<div class="row">
    <form action="" id="mainForm">
        <div class="col-sm-12">
            <div class="form-group col-sm-12">
                <label for="" class="col-sm-2 control-label">
                    Позиция
                </label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" value="<?=$model["name"]?>">
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group col-sm-12">
                <label for="" class="col-sm-2 control-label">
                    Кол-во
                </label>
                <div class="col-sm-2">
                    <input type="number" class="form-control" name="people" value="<?=$model["people"]?>">
                </div>
                <label for="" class="col-sm-1 control-label">
                    Отдел
                </label>
                <div class="col-sm-3">
                    <select name="departmentId" class="form-control ">
                        <? foreach ($department as $val){?>
                            <option <?=($val["departmentId"] == $model["departmentId"]) ? "selected" : ""?> value="<?=$val["departmentId"]?>"><?=$val["name"]?></option>
                        <?}?>
                    </select>
                </div>
                <div class="col-sm-4">
                    <button class="btn btn-success" id="saveBtn" type="button">Сохранить</button>
                </div>
            </div>
        </div>
    </form>

    <div class="col-sm-12">
        <table class="table table-bordered">
            <form action="" class=" " method="post" id="structForm">
                <thead>
                <tr>
                    <td class="form-group">
                        <div class="col-sm-8">
                            <select name="error" id="errors" class="form-control ">
                                <? foreach ($error as $val){?>
                                    <option value="<?=$val["errorId"]?>"><?=$val["description"]?></option>
                                <?}?>
                            </select>
                        </div>
                    </td>
                    <td class="form-group">
                        <div class="col-sm-8">
                            <button class="btn btn-success" id="addStruct" type="button">Добавить</button>
                        </div>
                    </td>
                </tr>
                </thead>
                <tbody id="data">
                <? foreach ($struct as $val){?>
                    <tr id="<?=$val["posConnId"]?>">
                        <td><?=$val["description"]?></td>
                        <td><a href="javascript:;" onclick="deleteStruct(<?=$val["posConnId"]?>)"><i class="glyphicon glyphicon-remove"></i></a></td>
                    </tr>
                <?}?>
                </tbody>
            </form>
        </table>
    </div>
</div>
<script>

    $(document).ready( function () {

        $("#errors").chosen({width: "95%"});
    });

    $("#errors").chosen({width: "95%"});

    function deleteStruct(id) {
        var obj = $("#"+id);
        console.log(obj);
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('position/deleteStruct'); ?>",
            data: "id="+id,
            success: function(){
                obj.remove();
            }
        });
    }
    $("#saveBtn").click(function () {
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('position/PositionUpdate'); ?>",
            data: "id=<?=$model["positionId"]?>&"+$("#mainForm").serialize(),
            success: function(data){

            }
        });
    });
    $("#addStruct").click(function () {
        var formData = $("#structForm").serialize();
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('position/addStruct'); ?>",
            data: "id=<?=$model["positionId"]?>&errorId="+$("#errors").val(),
            success: function(data){
                var res = JSON.parse(data);
                var text = "<tr id='"+res.posConnId+"'><td>"+res.description+"</td><td><a href='javascript:;' onclick='deleteStruct("+res.posConnId+")'><i class='glyphicon glyphicon-remove'></i></a></td>";
                $("#data").append(text);
            }
        });
    });
</script>

