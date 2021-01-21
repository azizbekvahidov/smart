<h1>Редактировать позицию</h1>


<div class="span-5 last">
    <div class="portlet-content">
        <ul class="operations" id="yw1">
            <li><a href="/position/positionCreate">Добавить Позицию</a></li>
        </ul>
    </div>
</div>

<div class="col-sm-3">
    <form action="" class=" " method="post" id="mainForm">
        <div class="col-sm-12">
            <div class="form-group col-sm-12">
                <div class="col-sm-8">
                    <select name="pos[phoneId]" id="" class="form-control ">
                        <? foreach ($model as $val){
                            if($val["phoneId"] == $pos["phoneId"]){?>
                                <option selected value="<?=$val["phoneId"]?>"><?=$val["model"]?></option>
                            <?} else ?>
                                <option value="<?=$val["phoneId"]?>"><?=$val["model"]?></option>
                        <?}?>
                    </select>
                </div>
            </div>
            <div class="form-group col-sm-12">
                <div class="col-sm-8">
                    <input type="text" name="pos[name]" class="form-control" placeholder="Наименование" value="<?=$pos["name"]?>" />
                </div>
            </div>
            <div class="form-group col-sm-12">
                <div class="col-sm-8">
                    <input type="text" name="pos[people]" class="form-control" placeholder="" value="<?=$pos["people"]?>" />
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
    <table class="table table-bordered">
        <form action="" class=" " method="post" id="structForm">
            <thead>
                <tr>
                    <th class="form-group">
                        <div class="col-sm-8">
                            <select name="error" id="errors" class="form-control ">
                                <? foreach ($error as $val){?>
                                    <option value="<?=$val["errorId"]?>"><?=$val["description"]?></option>
                                <?}?>
                            </select>
                        </div>
                    </th>
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

<script>
    $(document).ready( function () {

        $("#errors").chosen();
    });

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

    $(document).on("click","#addStruct", function () {
        var formData = $("#structForm").serialize();
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('position/addStruct'); ?>",
            data: formData+"&id=<?=$pos["positionId"]?>",
            success: function(data){
                var res = JSON.parse(data);
                var text = "<tr id='"+res.posConnId+"'><td>"+res.description+"</td><td><a href='javascript:;' onclick='deleteStruct("+res.posConnId+")'><i class='glyphicon glyphicon-remove'></i></a></td>";
                $("#data").append(text);
            }
        });
    });

    $(document).on("click","#btnSave", function () {
        var formData = $("#mainForm").serialize();
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('position/positionUpdateAjax'); ?>",
            data: formData+"&id=<?=$pos["positionId"]?>",
            success: function(data){
            }
        });
    });
</script>
