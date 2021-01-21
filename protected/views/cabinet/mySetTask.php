<h1>Мои выставленные задачи</h1>

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
                        <th> Выставил </th>
                        <th> Выставленная дата </th>
                        <th> Крайний срок </th>
                        <th> Прикрепленный файл </th>
                        <th>  </th>
                    </tr>
                    </thead>
                    <tbody>
                    <? foreach ($model as $key => $val){ //$employee = $emp->getEmployee($val['taskManager'])?>

                        <tr class="odd gradeX <?=($val["sts"] == 0 ? 'danger' : 'success')?>">
                            <td><?=$key+1?></td>
                            <td><?=$val["task"]?></td>
                            <td><?=$val["surname"]?> <?=$val["name"]?></td>
                            <td><?=$val["taskDate"]?></td>
                            <td><?=$val["taskDeadline"]?></td>
                            <td><?=($val["file"] != '') ? $val["file"].' <a class="pull-right" href="/upload/files/task/'.$val["file"].'"> <i class="fa fa-download"></i></a>' : ''?></td>
                            <td>
                                <? if($val["sts"] == 0){?>
                                    <a href="/cabinet/editTask?id=<?=$val["taskId"]?>"><i class="fa fa-pencil"></i></a> &nbsp; &nbsp;
                                    <a id="deleteTask" href="javascript:;" data-id="<?=$val["taskId"]?>"><i class="fa fa-trash-o"></i></a>
                                <?}?>
                            </td>
                        </tr>
                    <?}?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
</div>
<script>
    $(document).on('click','#deleteTask', function () {
        if (confirm('Вы уверены ?')) {
            $.ajax({
                method: 'GET',
                url: '/cabinet/deleteTask',
                data: {id:$(this).attr('data-id')},
                success: function (res) {
                    console.log(typeof res);
                    if(res === '1'){
                        location.reload();
//                        $(this).parent().parent().remove()
                    }
                }
            })
        } else {
            // Do nothing!
        }
    })
</script>