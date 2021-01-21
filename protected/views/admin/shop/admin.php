
<a class="btn btn-success" href="exportEmployee">Скачать в Еxcel</a>
<h1>Список продуктов для магазина</h1>
<div class="right">
    <div class="btn-group" role="group" aria-label="...">
        <a  href="shopCreate" class="btn btn-success">Добавить</a>
    </div>
</div>
<table class="table-bordered table" id="dataTable">
    <thead>
    <tr>
        <th>№</th>
        <th>Наименование</th>
        <th>Описание</th>
        <th>Фото</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <? foreach ($model as $val){?>
        <tr>
            <td><?=$val["shopproductId"]?></td>
            <td><?=$val["name"]?></td>
            <td><?=$val["content"]?></td>
            <td><img width="100" src="/upload/shop/<?= $val["photo"] ?>" alt=""></td>
            <td>
                <a class="update glyphicon glyphicon-pencil" title="Редактировать" href="/admin/shopUpdate/<?=$val["shopproductId"]?>">
                </a>
                <a class="delete glyphicon glyphicon-trash" title="Удалить" href="/admin/shopDelete/<?=$val["shopproductId"]?>">
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
