<table class="table table-bordered">
    <thead>
    <tr>
        <th>№</th>
        <th>Наименование детали</th>
        <th>Кол-во</th>
        <th>Код</th>
    </tr>
    </thead>
    <tbody>
    <? $i = 1;?>
    <?foreach ($model2 as $val){?>
        <tr>
            <td><?=$i?></td>
            <td><?=$val["name"]?></td>
            <td><?=$val["cnt"]?></td>
            <td>Полуготовая запчасть</td>
        </tr>
    <?$i++;}?>
    <?foreach ($model as $val){?>
        <tr>
            <td><?=$i?></td>
            <td><?=$val["name"]?></td>
            <td><?=$val["cnt"]?></td>
            <td>Запчасть</td>
        </tr>
    <?$i++;}?>

    </tbody>
</table>