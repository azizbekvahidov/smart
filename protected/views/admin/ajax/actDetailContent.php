<? $cnt = 1; $func = new Functions();?>
<h1 class="col-lg-7">Акт № <?=$act["code"]?> - <?=$act["actNum"]?> от <?=date("d.m.Y",strtotime($act["actDate"]))?></h1>
<h1 class="col-lg-5">
    <button class="btn btn-success right" id="addDetail">Добавить</button>
</h1>
<table class="table-bordered table">
    <thead>
    <tr>
        <td rowspan="2" class="text-center">№</td>
        <td rowspan="2" class="text-center">Наименование сырья и материалов</td>
        <td rowspan="2" class="text-center">ед.изм.</td>
        <td colspan="2" class="text-center">Кол-во</td>
        <td rowspan="2" class="text-center">Причина</td>
        <td rowspan="2" class="text-center">Сервис</td>
        <td rowspan="2" class="text-center">Утилизация</td>
        <td rowspan="2" class="text-center"></td>
    </tr>
    <tr>
        <td class="text-center"><?=($act["departmentId"] == 11) ? "Перед прод." : "Завод. брак"?></td>
        <td class="text-center">Произ. брак</td>
    </tr>
    </thead>
    <tbody>
    <? $skd = 0; $produce = 0; ?>
    <?foreach ($model as $val){
        $skdCnt = ($val["cause"] == "SKD brak") ? $val["cnt"] : 0;//$func->getSKDCnt($val["spareId"],$val["phoneId"],$val["actregisterId"]);
        $skd = $skd + $skdCnt;
        $produceCnt = ($val["cause"] == "Ishlab chiqarish brak") ? $val["cnt"] : 0;//$func->getProduceCnt($val["spareId"],$val["phoneId"],$val["actregisterId"]);
        $produce = $produce + $produceCnt;
        ?>

        <tr id="<?=$val["actdetailId"]?>">
            <td class="text-center"><?=$cnt?></td>
            <td><?=$val["model"]?> <?=$val["name"]?></td>
            <td class="text-center">шт</td>
            <td class="text-center skdCnt">
                <?=($skdCnt != 0) ? $skdCnt : 0?>
            </td>
            <td class="text-center produceCnt">
                <?=($produceCnt!= 0) ? $produceCnt : 0?>
            </td>
            <td ><?=$val["desc"]?><?//=$func->getActDesc($val["spareId"],$val["phoneId"],$val["actregisterId"])?></td>
            <td>
                <input type="text" class="form-control service" value="<?=$val["service"]?>" >
            </td>
            <td>
                <input type="text" class="form-control util" value="<?=$val["util"]?>">
            </td>
            <td><a class="btn remove"><i class="glyphicon glyphicon-remove"></i></a></td>
        </tr>
        <?$cnt++;}?>
        <tr>
            <th colspan="3">Итого</th>
            <th><?=$skd?></th>
            <th><?=$produce?></th>
            <th colspan="3"></th>
        </tr>
    </tbody>
</table>
<a class="btn btn-success" href="test?id=<?=$act["actregisterId"]?>">Скачать в Еxcel</a>
<? if ($act['signed'] == 0){?><a href="closeAct?id=<?=$act["actregisterId"]?>" class="closeAct btn btn-danger right" onclick="javascript:;" ><i class="glyphicon glyphicon-remove"> </i> Закрыть и подписать акт</a><?}?>
<script>
    $(document).on("change",".service", function(){
        var id = $(this).parent().parent().attr("id");
        var cnt = parseInt($(this).parent().parent().children(".produceCnt").text()) + parseInt($(this).parent().parent().children(".skdCnt").text());
        //var otherCnt = parseInt($(this).parent().parent().children().children("input.util").val());
        var curCnt = parseInt($(this).val());
        var resCnt = cnt - curCnt;
        $(this).parent().parent().children().children("input.util").val(resCnt);
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('admin/updateServiceUtil'); ?>",
            data: "id="+id+"&util="+resCnt+"&service="+curCnt,
            success: function(data){
                console.log(resCnt);
            }
        });
    });

    $(document).on("change",".util", function(){
        var id = $(this).parent().parent().attr("id");
        var cnt = parseInt($(this).parent().parent().children(".produceCnt").text()) + parseInt($(this).parent().parent().children(".skdCnt").text());
        //var otherCnt = $(this).parent().parent().children().children("input.service").val();
        var curCnt = parseInt($(this).val());
        var resCnt = cnt - curCnt;
        $(this).parent().parent().children().children("input.service").val(resCnt);
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('admin/updateServiceUtil'); ?>",
            data: "id="+id+"&service="+resCnt+"&util="+curCnt,
            success: function(data){
            }
        });
    });

    $(document).on("click",".remove", function(){
        if(window.confirm('Вы уверены ?')) {
            var id = $(this).parent().parent().attr("id");
            var element =  $(this).parent().parent();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('admin/deleteActDetail'); ?>",
                data: "id=" + id,
                success: function (data) {
                    element.remove();
                }
            });
        }
    });

    $(document).on("click","#addDetail", function(){
        $("#addModal").modal("show");
    });
    $(document).on("click",".detailSave", function () {
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('admin/addActDetail'); ?>",
            data: $(".detailForm").serialize() + "&id=<?=$act['actregisterId']?>",
            success: function (data) {
                location.reload();
            }
        });
    })
</script>

<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Добавить деталь</h4>
            </div>
            <div class="modal-body">
                <form action="" class="detailForm">
                    <table class="table table-bordered">
                        <tr>
                            <td>
                                <select name='phone' class='form-control'>
                                    <? foreach ($list as $val){?>
                                        <option value='<?=$val["phoneId"]?>'><?=$val["model"]?></option>
                                    <?}?>
                                </select>
                            </td>
                            <td>
                                <select name='spare' class='form-control'>
                                    <? foreach ($spare as $val){?>
                                    <option value='<?=$val["spareId"]?>'><?=$val["name"]?></option>
                                    <?}?>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="cnt" placeholder="Кол-во" class="placeholder form-control">
                            </td>
                            <td>
                                <input type="text" name="desc" class="form-control" placeholder="Описание">
                            </td>
                            <td>
                                <select name="cause" class="form-control">
                                    <option value="Ishlab chiqarish brak">Ishlab chiqarish brak</option>
                                    <option value="SKD brak">Пред продажа</option>
                                    <option value="SKD brak">SKD brak</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Выход</button>
                <button type="button" class="btn btn-primary detailSave">Сохранить</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Добавить деталь</h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Выход</button>
                <button type="button" class="btn btn-primary detailSave">Сохранить</button>
            </div>
        </div>
    </div>
</div>