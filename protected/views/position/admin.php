<?php
/* @var $this UsersController */
/* @var $model Users */
$func = new Functions();
$cnt = 1;
?>


<div class="span-5 last">
    <div class="portlet-content">
        <ul class="operations" id="yw1">
            <li><a href="/position/positionCreate">Добавить Позицию</a></li>
        </ul>
    </div>
</div>
&nbsp; <a href="javascript:;" id="export" class="btn btn-success">Экспорт</a>
<div class="col-sm-12" >
    <table class="table-bordered table"  id="dataTable">
        <thead>
        <tr>
            <th>№</th>
            <th>Наименование позиции</th>
            <th>Модель</th>
            <th>Кол-во операторов</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <? foreach ($model as $value){?>
            <tr id="<?=$value["positionId"]?>" class="<?=($cnt%2) ? "odd" : "even"?>">
                <td><?=$cnt?></td>
                <td><?=$value["name"]?></td>
                <td><?=$value["model"]?></td>
                <td><?=$value["people"]?></td>
                <td><a href="positionUpdate?id=<?=$value["positionId"]?>"><i class="glyphicon glyphicon-pencil"></i></a> &nbsp; <a href="javascript:;" onclick="positionDelete(<?=$value["positionId"]?>)"><i class="glyphicon glyphicon-remove"></i></a></td>
            </tr>
            <?$cnt++;}?>
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function(){
        $('#export').click(function(){
            $('#dataTable').table2excel({
                name: "Excel Document Name"
            });
        });
        $('#dataTable').DataTable();
    });

    function positionDelete(id) {
        var obj = $("#"+id);
        console.log(obj);
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('position/positionDelete'); ?>",
            data: "id="+id,
            success: function(){
                obj.remove();
            }
        });
    }

</script>

