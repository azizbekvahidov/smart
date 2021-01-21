<h1>Добавить сотрудника</h1>
<form action="" method="post" enctype="multipart/form-data" id="empForm">
    <div class="col-sm-7">
        <div class="form-group col-sm-12">
            <div class="col-sm-4">
                <input type="text" placeholder="Имя" name="name" class="form-control" value="<?=$model["name"]?>">
            </div>
            <div class="col-sm-4">
                <input type="text" placeholder="Фамилия" name="surname" class="form-control" value="<?=$model["surname"]?>">
            </div>
            <div class="col-sm-4">
                <input type="text" placeholder="Отчество" name="lastname" class="form-control" value="<?=$model["lastname"]?>">
            </div>
        </div>
        <div class="form-group col-sm-12">
            <div class="col-sm-4 radio">
                <label >
                    <input type="radio" name="sex" value="1" <?=($model["sex"] == 1) ? "checked='checked'" : ""?> /> Муж.
                </label>
                <label >
                    <input type="radio" name="sex" value="0" <?=($model["sex"] == 0) ? "checked='checked'" : ""?> /> Жен.
                </label>
            </div>
            <div class="col-sm-4">
                <?php Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                $this->widget('CJuiDateTimePicker',array(
                    'name'=>"start", //Model object
                    'attribute'=>'eventDate', //attribute name
                    'mode'=>'date', //use "time","date" or "datetime" (default)
                    'options'=>array(
                        'dateFormat'=>'dd.mm.yy',
                    ),
                    'htmlOptions' => array(
                        "class" => "form-control col-lg-4",
                        "placeHolder" => "День рождения",
                        "id" => "birthday",
                    ), // jquery plugin options

                    "value" => date("d.m.Y", strtotime($model["birthday"]))
                ));
                ?>
            </div>
            <div class="col-sm-4">
                <select name="depId" id="" class="form-control">
                    <? foreach ($dep as $val){?>
                        <? if($val["departmentId"] == $model["departmentId"]){?>
                            <option value="<?= $val["departmentId"] ?>" selected="selected"><?=$val["name"]?></option>
                        <?} else{?>
                            <option value="<?= $val["departmentId"] ?>"><?=$val["name"]?></option>
                        <?}?>
                    <?}?>
                </select>
            </div>
        </div>
        <div class="form-group col-sm-12">
            <div class="col-sm-4">
                <input type="file" class="form-control" name="file" id="file" >
            </div>
            <div class="col-sm-4">
                <input type="file" class="form-control" name="file2" id="file2">
            </div>

            <div class="col-sm-4">
                <select name="posId" id="" class="form-control">
                    <? foreach ($pos as $val){?>
                        <? if($val["positionId"] == $model["positionId"]){?>
                            <option value="<?= $val["positionId"] ?>" selected="selected"><?=$val["name"]?></option>
                        <?} else{?>
                            <option value="<?= $val["positionId"] ?>"><?=$val["name"]?></option>
                        <?}?>
                    <?}?>
                </select>
            </div>
        </div>

        <div class="form-group col-sm-12">
            <div class="col-sm-4">
                <input type="text" placeholder="Кол карты" name="code" class="form-control" value="<?=$model["code"]?>">
            </div>
        </div>
        <div class="radio col-sm-7">
            <div class="col-sm-4">
                <input type="submit" class="btn-success btn" value="Сохранить">
            </div>
        </div>
    </div>
    <div class="col-sm-5">
        <div id="previewImage">
            <img src="/upload/employee/<?=$model["photo"]?>" width="450"/>
        </div>
    </div>
</form>

<script>
    $(document).ready(function () {
        $("#file").change(function () {
            filePreview(this);
        });
    });
    function filePreview(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#previewImage + img').remove();
                $('#previewImage').html('<img src="'+e.target.result+'" width="450"/>');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>