<? $emp = new Employee()?>
<div id="taskContainer">
    <h1 class="uppercase"><i class="fa fa-tasks"></i>  Открытые задачи </h1>

    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption font-dark">
                        <span class="caption-subject bold uppercase"> Задачи по протоколу</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="protokolTask">
                        <thead>
                        <tr>
                            <th> № </th>
                            <th> Вопрос </th>
                            <th> Протокол номер </th>
                            <th> Тема протокола </th>
                            <th> Дата протокола </th>
                            <th> Крайний срок </th>
                            <th> Прикрепленный файл </th>
                            <th>  </th>
                        </tr>
                        </thead>
                        <tbody>
                        <? foreach ($model["protokol"] as $key => $val){?>

                            <tr class="odd gradeX">
                                <td><?=$key+1?></td>
                                <td><?=$val["meetQuestion"]?></td>
                                <td><?=$val["protokolNumber"]?></td>
                                <td><?=$val["protokolTheme"]?> <a class="pull-right" href="/protokol/protokolView?id=<?=$val["protokolId"]?>"><i class="icon-doc"></i></a></td>
                                <td><?=$val["protokolDate"]?></td>
                                <td><?=$val["deadline"]?></td>
                                <td><?=($val["file"] != '') ? $val["file"].' <a class="pull-right" href="/upload/files/protokol/'.$val["protokolNumber"].'/'.$val["file"].'"> <i class="fa fa-download"></i></a>' : ''?></td>
                                <td><a class="btn green btn-outline sbold solved" data-toggle="modal" data-send-info="/cabinet/protokolTaskSolve|<?=$val["protListId"]?>|<?=$val["solve"]?>" href="#solve"> Ввести решение</a>
                                    <a class="btn red btn-outline sbold cenceled" data-toggle="modal" data-send-info="/cabinet/protokolTaskCencel|<?=$val["protListId"]?>" href="#cencelModal"> Отказ задания</a></td>
                            </tr>
                        <?}?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption font-dark">
                        <span class="caption-subject bold uppercase"> Выставленные задачи</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="task">
                        <thead>
                        <tr>
                            <th> № </th>
                            <th> Задача </th>
                            <th> Кто выставил </th>
                            <th> Выставленная дата </th>
                            <th> Крайний срок </th>
                            <th> Прикрепленный файл </th>
                            <th>  </th>
                        </tr>
                        </thead>
                        <tbody>
                        <? foreach ($model["task"] as $key => $val){ $employee = $emp->getEmployee($val['taskManager'])?>

                            <tr class="odd gradeX">
                                <td><?=$key+1?></td>
                                <td><?=$val["task"]?></td>
                                <td><?=$employee["surname"]?> <?=$employee["name"]?></td>
                                <td><?=$val["taskDate"]?></td>
                                <td><?=$val["taskDeadline"]?></td>
                                <td><?=($val["file"] != '') ? $val["file"].' <a class="pull-right" href="/upload/files/task/'.$val["file"].'"> <i class="fa fa-download"></i></a>' : ''?></td>
                                <td><a class="btn green btn-outline sbold solved" data-toggle="modal" data-send-info="/cabinet/taskSolve|<?=$val["taskId"]?>|<?=$val["solve"]?>" href="#solve"> Ввести решение</a>
                                <a class="btn red btn-outline sbold cenceled" data-toggle="modal" data-send-info="/cabinet/taskCencel|<?=$val["taskId"]?>" href="#cencelModal"> Отказ задания</a></td>
                            </tr>
                        <?}?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->
        </div>
    </div>


    <div class="modal fade" id="solve" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="/cabinet/protokolTaskSave" enctype="multipart/form-data" method="post" id="modalForm">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title">Решение</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="form-group form-md-line-input has-success">
                                    <div class="input-icon">
                                        <textarea name="solve" class="form-control" placeholder="Введите решение задачи" id="solveArea" cols="75" rows="5"></textarea>
                                        <span class="help-block">Решение задачи ввести здесь</span>
                                        <i class="fa fa-tasks"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-md-line-input has-success">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: 70px; height: 50px;">
                                            <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" alt="" /> </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 70px; max-height: 50px;"> </div>
                                        <div>
                                        <span class="btn default btn-file">
                                            <span class="fileinput-new"> Выберите файл</span>
                                            <span class="fileinput-exists"> Изменить </span>
                                            <input type="file" id="uploadedFile" name="file">
                                        </span>
                                            <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput"> Удалить </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group form-md-line-input">
                                <label class="control-label col-md-3">Статус</label>
                                <div class="col-md-2">
                                    <input type="checkbox" name="status" class="make-switch" checked data-on-color="success" data-off-color="danger" data-off-text="Закрыто" data-on-text="Открыто">
                                    <input type="text" hidden name="id" id="ID">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn dark btn-outline" data-dismiss="modal">Закрыть</button>
                        <button type="submit" class="btn green">Сохранить</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>


    <div class="modal fade" id="cencelModal" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="/cabinet/protokolTaskSave" enctype="multipart/form-data" method="post" id="cencelModalForm">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title">Решение</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="form-group form-md-line-input has-success">
                                    <div class="input-icon">
                                        <textarea name="cencel" class="form-control" placeholder="Введите причину отказа задачи" id="cencelArea" cols="75" rows="5"></textarea>
                                        <span class="help-block">Причина отказа ввести здесь</span>
                                        <i class="fa fa-tasks"></i>
                                        <input type="text" hidden name="id" id="cencelID">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-md-line-input has-success">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: 70px; height: 50px;">
                                            <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" alt="" /> </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 70px; max-height: 50px;"> </div>
                                        <div>
                                        <span class="btn default btn-file">
                                            <span class="fileinput-new"> Выберите файл</span>
                                            <span class="fileinput-exists"> Изменить </span>
                                            <input type="file" id="uploadedFile" name="file">
                                        </span>
                                            <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput"> Удалить </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn dark btn-outline" data-dismiss="modal">Закрыть</button>
                        <button type="submit" class="btn green">Сохранить</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</div>
<script>
    $(document).ready(function () {
        <?if(!empty($message)){  ?>
        console.log("good");
        App.alert({ container: $('#taskContainer'), // alerts parent container
            place: 'prepend', // append or prepent in container
            message: '<?=$message["text"]?>', // alert's message
            close: true, // make alert closable reset: false, // close all previouse alerts first
            focus: true, // auto scroll to the alert after shown
            //closeInSeconds: 10000, // auto close after defined seconds
            icon: 'fa fa-'+('<?=$message["type"]?>' == 'warning' ? 'warning' : 'check'), // put icon class before the message
            type: '<?=$message["type"]?>'
        });
        <?}?>
    });
    $(document).on('click','.solved', function () {

        var res = $(this).attr('data-send-info').split('|');
        console.log(res);
        $("#solveArea").val(res[2]);
        $("#ID").val(res[1]);
        $("#modalForm").attr('action',res[0])
    });
    $(document).on('click','.cenceled', function () {
        var res = $(this).attr('data-send-info').split('|');
        console.log(res);
        $("#cencelID").val(res[1]);
        $("#cencelModalForm").attr('action',res[0])
    });
</script>
