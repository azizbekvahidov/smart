<table class="table table-bordered" id="dataTable">
    <thead>
        <tr>
            <th>#</th>
            <th>Сотрудник</th>
            <th>Болезнь</th>
            <th>Кол-во</th>
            <th>Дата</th>
        </tr>
    </thead>
    <tbody>
    <? $cnt = 1;
    foreach ($model as $val){?>
        <tr>
            <td><?=$cnt?></td>
            <td><?=$val["surname"]?> <?=$val["eName"]?></td>
            <td><?=$val["iName"]?></td>
            <td><?=$val["cnt"]?></td>
            <td><?=date("d.m.Y H:i:s",strtotime($val["useDate"]))?></td>
        </tr>
    <?$cnt++;} ?>
    </tbody>
</table>
<script>
    $(document).ready(function () {

        $("#dataTable").dataTable();
    })
</script>

