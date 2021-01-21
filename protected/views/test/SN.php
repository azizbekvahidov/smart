
<table class="table table-bordered " style="font-size: 11px;">
    <tr>
        <th>#</th>
        <th>model</th>
        <th>color</th>
        <th>code</th>
    </tr>
    <?
    foreach ($model as $key => $val){
            ?>
            <tr>
                <td><?=$key+1?></td>
                <td><?=$val["model"]?></td>
                <td><?=$val["name"]?></td>
                <td><?=$val["code"]?></td>
            </tr>

        <?
    }
    ?>
</table>