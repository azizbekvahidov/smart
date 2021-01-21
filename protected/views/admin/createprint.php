<form action='' method="post">
    <input type="text" name="print[sn]" /><br>
    <input type="text" name="print[imei1]" /><br>
    <input type="text" name="print[imei2]" /><br>
    <select  name="print[sn]" >
        <? foreach($listPhone as $value){?>
            <option value="<?=$value['phoneId']?>">
                <?=$value['model']?>
            </option>
        <?}?>
    </select>    <br>
    <input type="date" name="print[printDate]" /><br>
    <input type="submit" value='Сохранить' /><br>
</form>