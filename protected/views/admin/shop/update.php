<h1>Рудактирование продукта для магазина</h1>
<form action="" method="post" enctype="multipart/form-data" id="shopForm">
    <div class="col-sm-7">
        <div class="form-group col-sm-12">
            <div class="col-sm-4">
                <input type="text" placeholder="Наименование" name="name" class="form-control" value="<?=$model["name"]?>">
            </div>
            <div class="col-sm-4">
                <input type="text" placeholder="Балл" name="ball" class="form-control" value="<?=$model["ball"]?>">
            </div>
        </div>
        <div class="form-group col-sm-12">
            <div class="col-sm-4">
                <textarea rows="10" class="form-control" name="content" ><?=$model["content"]?></textarea>
            </div>
            <div class="col-sm-4">
                <input type="file" class="form-control" name="file" id="file" >
            </div>
        </div>
        <div class="radio col-sm-7">
            <div class="col-sm-4">
                <input type="submit" class="btn-success btn" value="Сохранить">
            </div>
        </div>
    </div>
    <div class="col-sm-5">
        <div id="previewImage">
            <img src="/upload/shop/<?=$model["photo"]?>" width="450"/>
        </div>
    </div>
</form>

<script>
    $(document).ready(function () {
        $("#file").change(function () {
            filePreview(this);
        });
    });
    function filePreview(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#previewImage + img').remove();
                $('#previewImage').html('<img src="'+e.target.result+'" width="450"/>');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>