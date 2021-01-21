<table class="table table-bordered">
    <thead>
        <tr>
            <th>№</th>
            <th>Модель</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <? $cnt = 1;
        foreach ($model as $value){?>
            <tr>
                <td><?=$cnt?></td>
                <td><?=$value["model"]?></td>
                <td><a href="#" onclick="viewModal(<?=$value["phoneId"]?>)" class="view" ><i class="glyphicon glyphicon-search"></i></a></td>
            </tr>
        <? $cnt++;}?>
    </tbody>
</table>

<script>
    function viewModal(id){
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('admin/phoneStruct'); ?>",
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