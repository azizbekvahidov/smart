<table>
    <tr>
        <th>Модель</th>
        <th>Кол-во</th>
    </tr>
    <?foreach ($model as $val){?>
        <tr>
            <td><?=$val["model"]?></td>
            <td><?=$val["cnt"]?></td>
        </tr>
    <?}?>
</table>