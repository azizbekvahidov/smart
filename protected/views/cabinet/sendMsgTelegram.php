<form action="" id="telegramMsgForm">
    <? foreach ($emp as $val){?>
        <div class="md-checkbox">
            <input type="checkbox" id="emp<?=$val["employeeId"]?>" name="emp[<?=$val["chatId"]?>]"  class="md-check">
            <label for="emp<?=$val["employeeId"]?>">
                <span class="inc"></span>
                <span class="check"></span>
                <span class="box"></span> <?=$val["surname"]?> <?=$val["name"]?> </label>
        </div>
    <?}?>
    <div class="form-group form-md-line-input has-success">
        <input type="text" name="message" class="form-control"  placeholder="Введите сообщение">
        <label for="form_control_1">Сообщения для телеграм</label>
    </div>
    <div>
        <button type="button" class="btn btn-success" id="sendMsg">Отправить</button>
    </div>
</form>

<script>
    $(document).on('click','#sendMsg', function () {
        $.ajax({
            url:'sendTelegram',
            data: $("#telegramMsgForm").serialize(),
            method: 'POST',
            success: function (res) {
                $('#telegramMsgForm')[0].reset();
            }
        })
    })
</script>