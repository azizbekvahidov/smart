<? $func = new Functions();?>

<? foreach ($model as $key => $val) {?>
    <tr data-id="<?=$val["positionId"]?>">
        <td><?=(($key)+1)?></td>
        <td><?=$val["lName"]?></td>
        <td class="linePosition"><?=$val["linePosition"]?></td>
        <td><?=$val["people"]?></td>
        <td><?=$val["dName"]?></td>
        <td><pre><?=$func->getPositionError($val["positionId"])?></pre></td>
        <td>
            <a href='javascript:;' class='editPosition' onclick='editPos( <?=$val["positionId"] ?>)'><i class='glyphicon glyphicon-pencil'></i></a>
            <a href='javascript:;' class='delPosition' onclick='delPos( <?=$val["positionId"] ?>,$(this))'><i class='glyphicon glyphicon-remove'></i></a>
        </td>
    </tr>

<?}?>

<script>

    $(document).ready( function () {

        $("#errors").chosen({width: "95%"});
    });

</script>
