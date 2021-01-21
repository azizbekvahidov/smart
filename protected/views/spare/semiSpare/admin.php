<h1>Полуготовые запчасти</h1>
<a href="semiSpareCreate" class="btn btn-success right">Создать</a>
<table class="table table-bordered">
    <thead>
    <tr>
        <th>№</th>
        <th>Наименование</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <? $cnt = 1;
    foreach ($model as $value){?>
        <tr>
            <td><?=$cnt?></td>
            <td><?=$value["name"]?></td>
            <td>
                <a href="#" onclick="viewModal(<?=$value["semispareId"]?>)" class="view" ><i class="glyphicon glyphicon-search"></i></a>
                <a href="semiSpareUpdate?id=<?=$value["semispareId"]?>" class="update" ><i class="glyphicon glyphicon-pencil"></i></a>
                <a href="semiSpareDelete?id=<?=$value["semispareId"]?>" class="delete" ><i class="glyphicon glyphicon-trash"></i></a>
            </td>
        </tr>
        <? $cnt++;}?>
    </tbody>
</table>

<script>
    function viewModal(id){
        $.ajax({
            type: "GET",
            url: "<?php echo Yii::app()->createUrl('spare/semiSpareView'); ?>",
            data: "id="+id,
            success: function(data){
                $(".modal-body").html(data);
            }
        });
        $(".modals").modal("show");
    }
</script>

<div class="modal fade modals " tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Структура</h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->