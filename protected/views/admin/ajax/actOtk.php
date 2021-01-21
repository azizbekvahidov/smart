<div id="data">

</div>

<script>
    $(document).ready(function () {
        $.ajax({
            type: "GET",
            url: "<?php echo Yii::app()->createUrl('admin/actLabDetail'); ?>",
            data: "id="+<?=$regId?>,
            success: function(data){
                $("#data").html(data);
            }
        });
    });
    var detailedId = 0;
    function change(id,cnt){
        detailedId = id;
        $("#actCnt").val(cnt);
        $(".modals").modal("show");
    }

    $(document).on("click", "#ok", function () {
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('admin/change'); ?>",
            data: "&id="+detailedId+"&cnt="+$("#actCnt").val(),
            success: function(data){
                $.ajax({
                    type: "GET",
                    url: "<?php echo Yii::app()->createUrl('admin/actLabDetail'); ?>",
                    data: "id="+<?=$regId?>,
                    success: function(data){
                        $("#data").html(data);
                    }
                });
            }
        });
    })

</script>

<div class="modal fade modals " tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <input type="text" class="form-control" id="actCnt">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" id="ok" data-dismiss="modal">OK</button>
                <button type="button" class="btn btn-success" data-dismiss="modal">отмена</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->