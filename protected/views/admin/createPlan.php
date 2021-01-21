<form method="post">
    <input type="text" value="<?=$seller?>" hidden name="userId">
    <input type="text" value="<?=$month?>" hidden name="month">
    <div class="grid-view span-10">
        <table class="items">
            <? foreach ($model as $value){?>
                <tr class="odd">
                    <td><?=$value["model"]?></td>
                    <td><input type="text" value="" name="model[<?=$value["phoneId"]?>]"></td>
                </tr>
            <?}?>
            <tr>
                <td></td>
                <td><button type="submit">Сохранить</button></td>
            </tr>
        </table>
    </div>
</form>