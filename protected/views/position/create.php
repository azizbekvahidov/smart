<h1>Добавить позицию      <button class="btn btn-success pull-right" id="create">Создать</button></h1>
<div class="row">
    <div class="row">
        <div class="col-sm-3">
            <div class="form-group col-sm-12">
                <div class="col-sm-8">
                    <select name="pos[phoneId]" id="model" class="form-control ">
                        <option value="">Выбрать</option>
                        <? foreach ($model as $val){?>
                            <option value="<?=$val["phoneId"]?>"><?=$val["model"]?></option>
                        <?}?>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <table class="table table-bordered" id="dataTable">
                <thead>
                    <tr>
                        <td>#</td>
                        <td>Наименование позиции</td>
                        <td>Место на линии</td>
                        <td>Кол-во людей</td>
                        <td>Отдел</td>
                        <td>Ошибки</td>
                        <td></td>
                    </tr>
                </thead>
                <tbody >

                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).on("click","#create", function () {
        if($("#model").val() !== "") {
            let txt = ' <form action="" id="newForm">\n' +
                '        <div class="col-sm-12">\n' +
                '            <div class="form-group col-sm-12">\n' +
                '                <label for="" class="col-sm-2 control-label">\n' +
                '                    Позиция\n' +
                '                </label>\n' +
                '                <div class="col-sm-10">\n' +
                '                    <input type="text" class="form-control" name="name" value="">\n' +
                '                </div>\n' +
                '            </div>\n' +
                '        </div>\n' +
                '        <div class="col-sm-12">\n' +
                '            <div class="form-group col-sm-12">\n' +
                '                <label for="" class="col-sm-2 control-label">\n' +
                '                    Кол-во\n' +
                '                </label>\n' +
                '                <div class="col-sm-2">\n' +
                '                    <input type="number" class="form-control" name="people" value="1">\n' +
                '                </div>\n' +
                '                <label for="" class="col-sm-1 control-label">\n' +
                '                    Отдел\n' +
                '                </label>\n' +
                '                <div class="col-sm-3">\n' +
                '                    <select name="departmentId" class="form-control ">\n' +
                '                        <? foreach ($department as $val){?>\n' +
                '                            <option <?=($val["departmentId"] == $model["departmentId"]) ? "selected" : ""?> value="<?=$val["departmentId"]?>"><?=$val["name"]?></option>\n' +
                '                        <?}?>\n' +
                '                    </select>\n' +
                '                </div>' +
                '                <div class="col-sm-4">\n' +
                '                    <button class="btn btn-success" id="newBtn" type="button">Сохранить</button>\n' +
                '                </div>\n' +
                '            </div>\n' +
                '        </div>\n' +
                '    </form>';
            $(".modal-body").html(txt);
            $("#modals").modal("show");
        }
        else{
            alert("выберите модель телефона")
        }

    });
    $(document).on("click","#newBtn",function () {
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('position/PositionStore'); ?>",
            data: "id=" + $("#model").val() + "&" + $("#newForm").serialize(),
            success: function(data){
                loadPos($("#model").val());
                $("#modals").modal("hide");
                // $(".modal-backdrop").remove();
                // editPos(data);

            }
        });
    });
    $(document).on("click",".linePosition",function () {
        let id = $(this).parent().attr("data-id");

        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('Lineinfo/linePos'); ?>",
            data: "id=" + id + "&model=" + $("#model").val() ,
            success: function(data){
                $(".modal-body").html(data);
                $("#modals").modal("show");
                // $(".modal-backdrop").remove();
                // editPos(data);

            }
        });
    });

    $(document).on("click",".linePos",function () {
        let id = $(this).attr("data-id");
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('Lineinfo/setPosition'); ?>",
            data: "id=" + id + "&model=" + $("#model").val() + "&posId=" + $("#posId").val(),
            success: function(data){
                // $(".modal-body").html(data);
                $("#modals").modal("hide");
                loadPos($("#model").val());
                // $(".modal-backdrop").remove();
                // editPos(data);

            }
        });
    });

    function loadPos(id){
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('position/getPosition'); ?>",
            data: "id="+id,
            success: function(data){
                $("#dataTable tbody").html(data);
            }
        });
    }
    $(document).on("change","#model", function () {
        loadPos($(this).val());
    });
    function editPos(id) {
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('position/editPosition'); ?>",
            data: "id="+id,
            success: function(data){
                $(".modal-body").html(data);
                $("#modals").modal("show");

            }
        });
    }
    function delPos(id,elem) {
        if(confirm("Вы уверены что хотите удалить позицию?")) {
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('position/delPosition'); ?>",
                data: "id=" + id,
                success: function (data) {
                    $(elem).parent().parent().remove();
                }
            });
        }
    }
</script>

<div class="modal fade modals " id="modals" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Структура</h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->