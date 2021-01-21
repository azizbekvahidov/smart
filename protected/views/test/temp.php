<div class="col-sm-3">
    <div class="form-group">
        <textarea name="" id="snList" cols="30" rows="20"></textarea>
    </div>
    <button class="btn btn-success" id="getList">Получить список</button>
</div>
<div id="data" class="col-sm-6"></div>
<script>
    $(document).on('click',"#getList", function () {
        $.ajax({
            method:"POST",
            data:"list="+$("#snList").val(),
            url:"ajaxTempTest",
            success: function (data) {
                $("#data").html(data);
            }
        });
    });

</script>