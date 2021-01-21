<? $emp = new Employee()?>
<div id="taskContainer">
    <h1 class="uppercase"><i class="fa fa-tasks"></i>  Решенные задачи </h1>

    <div class="row">
        <div class="col-sm-3">
            <div class="form-group form-md-line-input  has-success ">
                <div class="input-icon">
                    <input type="date" name="taskDate" id="start" value="<?=$start?>" class="form-control  form-control-inline input-medium date-picker" />
                    <label for="taskDate">С</label>
                    <i class="fa fa-calendar-check-o"></i>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group form-md-line-input has-success">
                <div class="input-icon">
                    <input type="date" name="deadline" id="end"  value="<?=$end?>" class="form-control  form-control-inline input-medium date-picker" />
                    <label for="deadline">По</label>
                    <i class="fa fa-calendar-times-o"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group form-md-line-input form-md-floating-label has-success">
                <button class="btn btn-circle blue" id="filter"> <i class="fa fa-filter"></i>Фильтровать</button>
            </div>
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
                        </tr>
                        </thead>
                        <tbody id="dataBody">
                        <? foreach ($model as $key => $val){?>

                            <tr class="odd gradeX">
                                <td><?=$key+1?></td>
                                <td><?=$val["task"]?></td>
                                <td><?=$val["taskManager"]?></td>
                                <td><?=$val["taskDate"]?></td>
                                <td><?=$val["solveDate"]?></td>
                            </tr>
                        <?}?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->
        </div>
    </div>

</div>
<script>

    $(document).on("click",'#filter', function () {
        $.ajax({
            method: 'POST',
            url: '/cabinet/ajaxSolvedTask',
            data: {start:$("#start").val(),end:$("#end").val()},
            success: function (res) {
                var data = JSON.parse(res);
                var text = "";
                $.each(data, function (key, val) {
                    text += '<tr class="odd gradeX">\
                        <td>' + (key+1) + '</td>\
                        <td>' + val.task + '</td>\
                        <td>' + val.taskManager + '</td>\
                        <td>' + val.taskDate + '</td>\
                        <td>' + val.solveDate + '</td>\
                        </tr>';
                });
                $("#dataBody").html(text);
            }

        });
    });
</script>