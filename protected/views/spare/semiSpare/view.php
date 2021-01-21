<table class="table table-bordered">
    <thead>
        <tr>
            <th>№</th>
            <th>Наименование запчасти</th>
            <th>Кол-во</th>
        </tr>
    </thead>
    <tbody>
        <?foreach ($struct as $key => $val){?>
            <tr>
                <td><?=$key+1?></td>
                <th><?=$val["name"]?></th>
                <th><?=$val["cnt"]?></th>
            </tr>
        <?}?>
    </tbody>
</table>