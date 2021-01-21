<?php
/* @var $this UsersController */
/* @var $model Users */
$func = new Functions();
$cnt = 1;
$this->breadcrumbs=array(
	'Пользователь'
);

/*$this->menu=array( 
	array('label'=>'Добавить Пользователя', 'url'=>array('create')),
);*/?>


<h1>Пользователи</h1>

<div class="span-5 last">
    <div class="portlet-content">
        <ul class="operations" id="yw1">
            <li><a href="/users/create">Добавить Пользователя</a></li>
        </ul>
    </div>
</div>

&nbsp; <a href="javascript:;" id="export" class="btn btn-success">Экспорт</a>
<div  class="col-sm-12">
    <table class="table table-bordered" id="dataTable">
        <thead>
            <tr>
                <th>№</th>
                <th>Имя</th>
                <th>Фамилия</th>
                <th>Отчество</th>
                <th>Точка</th>
                <th>Статус</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <? foreach ($modelNew as $value){?>
            <tr class="<?=($cnt%2) ? "odd" : "even"?>">
                <td><?=$value["userId"]?></td>
                <td><?=$value["name"]?></td>
                <td><?=$value["surname"]?></td>
                <td><?=$value["lastname"]?></td>
                <td><?=$func->getPoint($value["point"])?></td>
                <td><?=($value["status"] == 1) ? "Остановлен" : "Активен"?></td>
                <td class="button-column">
                    <a class="update glyphicon glyphicon-pencil" title="Редактировать" href="/users/update/<?=$value["userId"]?>">
                    </a>
                    <a class="delete glyphicon glyphicon-trash" title="Удалить" href="/users/delete/<?=$value["userId"]?>">
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
