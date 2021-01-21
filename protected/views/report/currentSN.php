<table class="table table-bordered" id="dataTable">
    <thead>
        <tr>
            <th>#</th>
            <th>Наименование</th>
            <th>Цвет</th>
            <th>SAP код</th>
            <th>Последний SN</th>
        </tr>
    </thead>
    <tbody>
    <? foreach($model as $key => $val){?>
        <tr>
            <td><?=$key+1?></td>
            <td><?=$val["model"]?></td>
            <td><?=$val["name"]?></td>
            <td><?=$val["SAPCode"]?></td>
            <td><?=$val["lastNum"]?></td>
        </tr>
    <?}?>
    </tbody>
</table>