<form  id="pages">
    <div class="form-group">
        <input type="text" class="form-control" name="lastPage" placeholder="Кол-во напечатанных страниц">
    </div>
    <div class="form-group">
        <input type="text" class="form-control" name="pages" placeholder="Вставьте страницы из PrintKnij">
    </div>
    <div class="form-group">
        <button type="button" class="btn btn-success" id="send"  >Просчитать</button>
    </div>
</form>

<h1>Резудьтат</h1>
<div id="data"></div>

<script>

    $(document).on('click','#send', function () {
        $.ajax({
            url:'ajaxGetPage',
            data: $("#pages").serialize(),
            method: 'POST',
            success: function (data) {
                $("#data").text(data);
            }
        })
    })

</script>