<? $func = new Functions()?>
<a class="btn btn-success" href="exportEmployee">Скачать в Еxcel</a>
<h1></h1>
<div class="right">
    <div class="btn-group" role="group" aria-label="...">
        <a  href="shopCreate" class="btn btn-success">Добавить</a>
    </div>
</div>
<table class="table-bordered table" id="dataTable">
    <thead>
    <tr>
        <th>№</th>
        <th>Ф.И.О.</th>
        <th>Общий балл</th>
        <th>Список товаров</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <? foreach ($model as $key => $val){?>
        <tr>
            <td><?=$key + 1?></td>
            <td><?=$val["surname"]?> <?=$val["name"]?> <?=$val["lastname"]?></td>
            <td><?=$val["ball"]?></td>
            <td>
                <ol class="list-group">
                <?foreach ($func->getUserProd($val["userId"]) as $keys => $value){?>
                    <li class="list-group-item"><?=$keys+1?>. <?=$value["name"]?> <img width="50" src="/upload/shop/<?=$value["photo"]?>" alt=""></li>
                <?}?>
                </ol>
            </td>
            <td>
                <a class="update glyphicon glyphicon-ok" title="Редактировать" href="/admin/okOrder/<?=$val["userId"]?>">
                </a>
                <a class="delete glyphicon glyphicon-remove" title="Удалить" href="/admin/cencelOrder/<?=$val["userId"]?>">
                </a>
            </td>
        </tr>
    <?}?>
    </tbody>
</table>

<script>
    $(document).ready(function () {
        $('#export').click(function(){
            $('#dataTable').table2excel({
                name: "dealer report"
            });
        });
    })
</script>
<script>
    $(document).ready(function(){
        $('#dataTable').DataTable();
    });

</script>
