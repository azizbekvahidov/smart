<?php
/* @var $this UsersController */
/* @var $model Users */
$func = new Functions();
$cnt = 1;
$this->breadcrumbs=array(
    'Новости'
);
?>

<h1>Новости</h1>

    <a class="btn btn-success" href="/news/create">Добавить Новость</a>
&nbsp; <a href="javascript:;" id="export" class="btn btn-success">Экспорт</a>
<div class="col-sm-12" >
    <table class="table-bordered table"  id="dataTable">
        <thead>
        <tr>
            <th>№</th>
            <th>Дата</th>
            <th>Заголовок</th>
            <th>Новость</th>
            <th>Дата</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?  foreach ($model as $value){?>
            <tr class="<?=($cnt%2) ? "odd" : "even"?>">
                <td><?=$cnt?></td>
                <td><img src="/upload/news/<?= $value["foto"] ?>" width="150" height="100" alt=""></td>
                <td><?=$value["header"]?></td>
                <td><?=nl2br($value["content"])?></td>
                <td><?=$value["newsDate"]?></td>
                <td class="button-column">
                    <a class="update glyphicon glyphicon-pencil " title="Редактировать" href="/news/update/<?=$value["newsId"]?>">
                    </a>
                    <a class="delete glyphicon glyphicon-trash" title="Удалить" href="/news/delete/<?=$value["newsId"]?>">
                    </a>
                </td>
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

</script>

