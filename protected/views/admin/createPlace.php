<form action="" method="post">
    <div>
        <label for="">Наименование</label>
        <input type="text" name="place[name]" />
    </div>
    <br>
    <div>
        <label for="">Тип точки</label>
        <select name="place[pType]" id="pType">
            <option value="city">Город</option>
            <option value="district">Район</option>
            <option value="market">Рынок</option>
            <option value="shop">Магазин</option>
        </select>
    </div>
    <br>
    <div id="data" ></div>
    <br>
    <br>
    <br>
    <div>
        <button type="submit">Сохранить</button>
    </div>
</form>
<script>
    $(document).ready(function(){
        var month,
            seller;
        $('#pType').change(function(){
            place = $('#pType').val();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('admin/AjaxGetPlace'); ?>",
                data: "place="+place,
                success: function(data){
                    $("#data").html(data)
                }
            });
        });
    });

</script>