<?$update = false; if(!empty($model)) $update = true;?>
<div class="col-sm-4">
    <h1><?=($update) ? "Редактирование новости " : "Добавить новость"?></h1>
    <form action="" method="post" class="forms"  enctype="multipart/form-data">
        <div class="form-group">
            <input type="text" name="news[header]" class="form-control input" value="<?=($update) ? $model["header"] : ""?>" placeholder="Заголовок">
        </div>
        <div class="form-group">
            <textarea type="number" name="news[content]" class="form-control input" placeholder="Новость"><?=($update) ? $model["content"] : ""?></textarea>
        </div>
        <div class="form-group">
            <input type="file" class="form-control" name="file" id="file">
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-success save" value="Сохранить">
        </div>

    </form>
</div>