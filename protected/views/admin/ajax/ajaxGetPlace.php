<? if(!empty($model)){?>
<label for="">относится к</label>
<select name="place[parent]" id="">
    <?foreach ($model as $value){?>
        <option value="<?= $value["pointId"] ?>"><?=$value["name"]?></option>
    <?}?>
</select>
<?}?>