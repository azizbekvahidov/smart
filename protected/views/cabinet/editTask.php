<form action="/cabinet/editTask" method="GET" id="taskModalForm"  enctype="multipart/form-data">
    <div class="row">
        <div class="col-sm-8">
            <div class="form-group form-md-line-input has-success">
                <div class="input-icon">
                    <input type="text" name="id" hidden value="<?=$model["taskId"]?>">
                    <textarea type="text" name="edit[task]" rows="4" cols="20" placeholder="Введите задачу" class="form-control" value="" ><?=$model["task"]?></textarea>
                    <span class="help-block">Введите задачу здесь</span>
                    <i class="fa fa-tasks"></i>
                </div>
            </div>
        </div>

    </div>
    <div class="row">

        <div class="col-sm-4">
            <div class="form-group form-md-line-input has-success">
                <div class="input-icon">
                    <input type="date" name="edit[deadline]" id="deadline" class="form-control" value="<?=$model["taskDeadline"]?>" />
                    <label for="deadline">Срок выполнения</label>
                    <span class="help-block">Срок выполнения задачи</span>
                    <i class="fa fa-calendar-times-o"></i>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <button type="submit" class="btn btn-success">Сохранить</button>
        </div>

    </div>
</form>