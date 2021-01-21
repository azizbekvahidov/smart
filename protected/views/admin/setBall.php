<form method="post">
    <div class="grid-view span-10">
        <table class="items">
            <? foreach ($model as $value){?>
                <tr class="odd">
                    <td><?=$value["model"]?></td>
                    <td><input type="text" value="<?=$value["ball"]?>" name="model[<?=$value["phoneId"]?>]"></td>
                </tr>
            <?}?>
            <tr>
                <td></td>
                <td><button type="submit">Сохранить</button></td>
            </tr>
        </table>
    </div>
</form>