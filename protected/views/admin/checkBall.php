<div class="alert alert-success alert-dismissible hide" id="alert" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    Балл успешно изменен!!!
</div>
<table class="table table-bordered" id="dataTable">
    <thead>
        <tr>
            <th>№</th>
            <th>Ф.И.О</th>
            <th>Балл</th>
        </tr>
    </thead>
    <tbody>
        <?foreach ($model as $key => $val){?>
            <tr>
                <td><?=$key+1?></td>
                <td><?=$val["surname"]?> <?=$val["name"]?> <?=$val["lastname"]?></td>
                <td><input type="number" value="<?=$val["ball"]?>" onchange="changeBall(<?=$val["ballId"]?>,this.value)" class="form-control" ></td>
            </tr>
        <?}?>
    </tbody>
</table>

<script>
    $(document).ready(function(){
        $('#export').click(function(){
            $('#dataTable').table2excel({
                name: "Excel Document Name"
            });
        });
        $('#dataTable').DataTable();
    });

    function changeBall(id,val) {
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('admin/changeBall'); ?>",
            data: "id="+id+"&val="+val,
            success: function(data){
                $("#alert").removeClass("hide");
                setTimeout(function() {
                    $("#alert").addClass("hide");
                }, 1000);
            }
        });
    }

</script>