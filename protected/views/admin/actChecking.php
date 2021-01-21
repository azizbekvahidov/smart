<h1>Список актов</h1>
<table class="table table-bordered" id="dataTable">
    <thead>
        <tr>
            <th>№</th>
            <th>Дата регистрации</th>
            <th>Отдел</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <? foreach ($model as $val){?>
            <tr>
                <td><?=$val["actNum"]?></td>
                <td><?=$val["actDate"]?></td>
                <td><?=$val["name"]?></td>
                <td>
                    <a href="actDetail?id=<?=$val["actregisterId"]?>" class="view" target="_blank" ><i class="glyphicon glyphicon-search"></i></a>
                </td>
            </tr>
        <?}?>
    </tbody>
</table>

<script>
    $(document).ready(function(){
        $('#dataTable').DataTable();
    });

</script>
