<link rel="stylesheet" href="/css/bootstrap.min.css">
<table class="table table-bordered">
    <tr>
        <th>Код</th>
        <th>Время</th>
        <th>OTK</th>
        <th>Модель</th>
        <th>Проблема</th>
        <th>Причина</th>
        <th>Решение</th>
        <th></th>
    </tr>
    <? foreach ($model as $val){?>
        <tr>
            <td><?=$val["registerCode"]?></td>
            <td><?=$val["errorOtkDate"]?></td>
            <td><?=$val["login"]?></td>
            <td><?=$val["model"]?></td>
            <td><?=$val["descUz"]?></td>
            <td><?=$val["cause"]?></td>
            <td><?=$val["solve"]?></td>
            <td><?=($val["status"] == 1) ? "Закрыт" : "Открыт"?></td>
        </tr>
    <?}?>
</table>