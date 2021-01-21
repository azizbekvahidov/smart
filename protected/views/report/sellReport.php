    <? $cnt = 1; $func = new Functions()?>
<table>
     <thead>
        <tr>
            <th>№</th>
            <th>Модель</th>
            <th>Цвет</th>
            <th>Дата выпуска</th>
            <th>SN</th>
            <th></th>
        </tr>
     </thead>
    <tbody>
        <?foreach ($model as $value){?>
            <tr>
                <td><?=$cnt?></td>
                <td><?=$value["model"]?></td>
                <td><?=$func->getPhoneColor($value["phoneId"],$value["SN"])?></td>
                <td><?=date("d.m.Y",strtotime($value["printDate"]))?></td>
                <td><?=$value["SN"]?></td>
                <td><a type="button" id="view" target="_blank" href="views?id=<?=$value["printId"]?>">детально</a></td>
            </tr>
        <?$cnt++;}?>
    </tbody>
</table>