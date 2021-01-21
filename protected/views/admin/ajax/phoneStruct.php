<table class="table table-bordered">
    <thead>
        <tr>
            <th>№</th>
            <th>Наименование детали</th>
            <th>Кол-во</th>
            <th>SAP</th>
            <th>Бух. название</th>
        </tr>
    </thead>
    <tbody>
        <?foreach ($model as $i => $val){?>
            <tr>
                <td><?=$i+1?></td>
                <td><?=$val["name"]?></td>
                <td><?=$val["cnt"]?></td>
                <td><?=$val["SAPCode"]?></td>
                <td><?=$val["buxName"]?></td>
            </tr>
        <?}?>
    </tbody>
</table>