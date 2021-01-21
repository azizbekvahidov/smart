<form action="saveProtokol" method="post" class="form-horizontal"  enctype="multipart/form-data">
    <div class="panel panel-default">
        <div class="panel-body">
                <div class="form-group row">
                    <label for="num" class="col-sm-1 control-label">№</label>
                    <div class="col-sm-1">
                        <input type="text" name="num" value="<?=$protokolNum?>" id="num" class="form-control" readonly />
                    </div>
                    <label for="stens" class="col-sm-1 control-label">Стенографист</label>
                    <div class="col-sm-2">
                        <input type="text" name="stens" value="<?=Yii::app()->user->getName()?>" id="stens" class="form-control" readonly />
                    </div>
                    <label for="start" class="col-sm-2 control-label">Начало собрания</label>
                    <div class="col-sm-1">
                        <input type="text" name="start" value="<?=date('H:i:s')?>" id="start" class="form-control" readonly />
                    </div>
                    <label for="meetingType" class="col-sm-2 control-label">тип собрания</label>
                    <div class="col-sm-2">
                        <select name="meetingType" id="meetingType" class="form-control">
                            <option value="Каждый день">Каждый день</option>
                            <option value="Через день">Через день</option>
                            <option value="Каждый недедлю">Каждую недедлю</option>
                            <option value="Каждый  месяц">Каждый месяц</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="theme" class="col-sm-2 control-label">Тема собрания</label>
                    <div class="col-sm-3">
                        <input type="text" name="theme" value="" id="theme" class="form-control"  />
                    </div>
                    <label for="place" class="col-sm-2 control-label">Место собрания</label>
                    <div class="col-sm-3">
                        <input type="text" name="place" value="" id="place" class="form-control"  />
                    </div>
                    <div class="">
                        <button type="button" class="btn blue" id="addQuestion"><i class="fa fa-plus"></i>Добавить вопрос</button>
                    </div>
                </div>
                <div class="form-group">
                    <label for="participants" class="col-sm-1 control-label">Участники</label>
                    <div class="col-sm-11">
                        <select id="participants" name="participants[]" class="form-control" data-placeholder="Выберите участника" multiple class="chosen-select">

                            <? foreach($model as $val){?>
                            <option value='<?=$val["employeeId"]?>'><?=$val["surname"]." ".$val["name"]?></option>
                            <?}?>
                        </select>
                    </div>
                    <div class="col-sm-9">

                    </div>
                </div>
                <div class="clearfix"></div>
        </div>
    </div>
    <div class="panel panel-primary">
        <div class="panel-heading">Вопросы на повестке дня</div>
        <div class="panel-body">
            <div id="data"></div>
        </div>
    </div>
    <button type="submit" class="btn green-meadow">Сохранить протокол</button>
</form>
<script>
    $(document).ready(function () {
        $("#participants").chosen({no_results_text: "Oops, nothing found!"});
    });
    $(document).on('click','.removeRow', function () {
        $(this).parent().parent().parent().parent().remove();
    });
    var cnt = 1;
    $("#addQuestion").click(function () {
        $.ajax({
            url: 'getResponse',
            method: 'POST',
            success:function(res){
                var data = JSON.parse(res);
                var str = "";
                $.each(data, function (index,val) {
                    str += "<option value='"+val.employeeId+"'>"+val.surname+" "+val.name+"</option>"
                });
                var text = '<div class="panel panel-default">\
                            <div class="panel-body">\
                                <div class="form-group">\
                                    <label for="ques" class="col-sm-1 control-label ">Вопрос '+cnt+'</label>\
                                    <div class="col-sm-6">\
                                        <input type="text" name="question['+cnt+']" value="" id="ques" class="form-control"  />\
                                    </div>\
                                    <label for="response" class="col-sm-1 control-label">Ответсвенный</label>\
                                    <div class="col-sm-4">\
                                        <select name="response['+cnt+'][]" multiple  class="form-control response">'+str+'</select>\
                                    </div>\
                                    </div>\
                                <div class="form-group">\
                                    <label for="deadline" class=e"col-sm-1 control-label">Срок выполнения</label>\
                                    <div class="col-sm-2">\
                                        <input type="date" name="deadline['+cnt+']" value=""  class="form-control deadline"  />\
                                    </div>\
                                    <div class="fileinput fileinput-new" data-provides="fileinput">\
                                        <div class="fileinput-new thumbnail col-sm-2" style="width: 200px; height: 150px;">\
                                            <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" alt="" />\
                                        </div>\
                                        <div class="fileinput-preview fileinput-exists thumbnail col-sm-5" style="max-width: 200px; max-height: 150px;"> </div>\
                                        <div class="col-sm-3">\
                                            <span class="btn default btn-file">\
                                                <span class="fileinput-new"> Выберите файл</span>\
                                                <span class="fileinput-exists"> Изменить </span>\
                                                <input type="file" id="uploadedFile" name="file['+cnt+']">\
                                            </span>\
                                            <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput"> Удалить </a>\
                                        </div>\
                                    </div>\
                                    <div class="col-sm-1"><a class="btn removeRow red-mint" href="javascript:;"><i class="glyphicon glyphicon-remove"></i></a></div>\
                                </div>\
                            </div>\
                        </div>\
                    <div class="clearfix"></div>';
                $("#data").append(text);
                $(".response").chosen({no_results_text: "Oops, nothing found!"})
                cnt++;
            }
        });
    });

</script>