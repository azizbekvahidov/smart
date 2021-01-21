<div class="alert alert-info alert-dismissible hide" role="alert" id="alertDiv">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <div id="alerts"></div>
</div>
<form action="sendForm">
    <?php Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
    $this->widget('CJuiDateTimePicker',array(
        'name'=>"start", //Model object
        'attribute'=>'eventDate', //attribute name
        'mode'=>'date', //use "time","date" or "datetime" (default)
        'options'=>array() // jquery plugin options
    ));
    ?>
    <input type="button" id="view" value="Показать">
</form>

<div id="data" style="margin-top: 20px;"></div>
<script>
    $(document).ready(function(){
        var from;
        $('#view').click(function(){
            from = $('#start').val();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('report/ajaxRepair'); ?>",
                data: "from="+from,
                success: function(data){
                    $('#data').html(data);
                }
            });
        });
    });
</script>
<script>
    var id;
    $(document).on("click",".todo", function(){
        id = $(this).parent().attr("class");
        $("#values").val($(this).text());
        $("#myModal").modal("show");
        $("#values").focus();
    });
    $(document).on("click",".solve", function(){
        id = $(this).parent().attr("class");
        $("#solveValues").val($(this).text());
        $("#solveModal").modal("show");
        $("#solveValues").focus();
    });
    $(document).on("click",".addPhoto", function(){
        id = $(this).parent().parent().attr("class");
        $("#Modal").modal("show");
    });
        $(document).on("click", ".deleteRow", function () {
            if(confirm("Вы уверены в этом")) {
                var element =$(this).parent().parent();
                id = element.attr("class");
                $.ajax({
                    type: "POST",
                    url: "<?php echo Yii::app()->createUrl('report/deleteRegister'); ?>",
                    data: "id=" + id,
                    success: function () {
                        element.remove();
                    }
                });
            }
        });

    $(document).on("click","#addProd", function(){
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('report/addProduce'); ?>",
            data: "plan="+$("#plan").val()+"&produce="+$("#produce").val()+"&checked="+$("#checked").val()+"&thisDate="+$("#start").val(),
            success: function(){
                $("#view").click();
            }
        });
    });
    $(document).on("click",".save", function(){
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('report/saveToDo'); ?>",
            data: "id="+id+"&val="+$("#values").val(),
            success: function(){
                var el = "."+id;
                $(el).children("td.todo").text($("#values").val());
                $("#myModal").modal("hide");
                $("#values").val("");
            }
        });
    });
    $(document).on("click",".solveSave", function(){
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('report/saveSolve'); ?>",
            data: "id="+id+"&val="+$("#solveValues").val(),
            success: function(){
                var el = "."+id;
                $(el).children("td.solve").text($("#solveValues").val());
                $("#solveModal").modal("hide");
                $("#solveValues").val("");
            }
        });
    });

    $(document).on("click",".savePhoto", function(){
        var formData = new FormData();
        formData.append('file', $('#photoVal')[0].files[0]);
        formData.append("id",id);
        $.ajax({
            url: "<?php echo Yii::app()->createUrl('image/uploadImage?type=register'); ?>",
            type: "POST",
            data:  formData,
            contentType: false,
            processData:false,
            success: function(data)
            {
                $("#Modal").modal("hide");
                $("#alerts").text(data);
                $('#alertDiv').removeClass("hide");
                setInterval(function(){
                    $('#alertDiv').addClass("hide");
                },10000);
            }
        });
//        });
    });

</script>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Установить</h4>
            </div>
            <div class="modal-body">
                <input type="text" class="form-control" id="values">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Выход</button>
                <button type="button" class="btn btn-primary save">Сохранить</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="solveModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Установить</h4>
            </div>
            <div class="modal-body">
                <input type="text" class="form-control" id="solveValues">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Выход</button>
                <button type="button" class="btn btn-primary solveSave">Сохранить</button>
            </div>
        </div>
    </div>
</div>

<form id="photoForm" enctype='multipart/form-data' >
<div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Установить</h4>
            </div>
            <div class="modal-body">
                <input type="file" class="form-control" name="photo" id="photoVal">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Выход</button>
                <button type="button" class="btn btn-primary savePhoto">Сохранить</button>
            </div>
        </div>
    </div>
</div>
</form>