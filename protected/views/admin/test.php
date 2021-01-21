<button id="export">export</button>
<table id="dataTable" class="table">
    <thead>
        <tr>
            <td>kod</td>
            <td>photo</td>
        </tr>
    </thead>
    <tbody>
        <?foreach ($model as $val){?>
            <tr>
                <td><?=$val["employeeId"]?>-<?=$val["sex"]?>-<?=$val["departmentId"]?></td>
                <td><?=$val["photo"]?></td>
            </tr>
        <?}?>
    </tbody>
</table>

<script>
    $(document).ready(function () {
        $('#export').click(function(){
            $('#dataTable').table2excel({
                name: "dealer report"
            });
        });
    })
</script>
<img src="http://sm art  /sampleapps/barcodegenerator/generatebarcode?code=1234567890ABCDEF">