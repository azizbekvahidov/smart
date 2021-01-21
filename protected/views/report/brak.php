<? $func = new Functions()?>
<h1>Отчет по бракам</h1>
<div class="">
    <form action="sendForm">
        <div class="col-sm-1">
            <?php Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
            $this->widget('CJuiDateTimePicker',array(
                'name'=>"start", //Model object
                'attribute'=>'eventDate', //attribute name
                'mode'=>'date', //use "time","date" or "datetime" (default)
                'options'=>array(),
                'htmlOptions' => array(
                    "class" => "form-control col-lg-4",
                    "placeHolder" => "С"
                ) // jquery plugin options
            ));
            ?>
        </div>
        <div class="col-sm-1">
            <?php Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
            $this->widget('CJuiDateTimePicker',array(
                'name'=>"end", //Model object
                'attribute'=>'eventDate', //attribute name
                'mode'=>'date', //use "time","date" or "datetime" (default)
                'options'=>array(),
                'htmlOptions' => array(
                    "class" => "form-control col-lg-4",
                    "placeHolder" => "По"
                )// jquery plugin options

            ));
            ?>
        </div>
        <input type="button" id="view" class="btn" value="Показать">
        &nbsp; <a href="javascript:;" id="export" class="btn btn-success">Экспорт</a>
    </form>
</div>
<div id="data" style="margin-top: 20px;"></div>
<script>
    $(document).ready(function(){
        var from,
            seller,
            to;
        $('#view').click(function(){
            from = $('#start').val();
            to = $('#end').val();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('report/AjaxBrak'); ?>",
                data: "from="+from+'&to='+to,
                success: function(data){
                    data = JSON.parse(data);
                    str = "<table  id='dataTable' class='table-bordered table'><thead>" +
                        "<tr>" +
                        "<td>№</td>" +
                        "<td>Модель</td>" +
                        "<td>Причина</td>" +
                        "<td>Кол-во</td>" +
                        "<td></td>" +
                        "</tr>" +
                        "</thead><tbody>";
                    $.each(data, function(key,val){

                        str += "<tr>" +
                            "<td>" + parseInt(key+1) + "</td>" +
                            "<td>" + val.model + "</td>" +
                            "<td>" + val.cause + "</td>" +
                            "<td>" + val.cnt + "</td>" +
                            "<td><a href='#' onclick='viewModal(\"" + val.cause + "\"," + val.phoneId + ")' class='view' ><i class='glyphicon glyphicon-search'></i></a></td>" +
                            "</tr>";
                        $("#tableBody").append(str);
                    });
                    str += "</tbody>";
                    $("#data").html(str);

                }
            });
        });
        $('#export').click(function(){
            $('#dataTable').table2excel({
                name: "Excel Document Name"
            });
        });
    });

</script>

<script>
    function viewModal(cause, id){
        var from = $('#start').val(),
        to = $('#end').val();
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('report/ajaxBrakDetail'); ?>",
            data: "from="+from+'&to='+to+"&id="+id+"&cause="+cause,
            success: function(data){
                data = JSON.parse(data);
                str = "<table  id='dataTable' class='table-bordered table'><thead>" +
                    "<tr>" +
                    "<td>№</td>" +
                    "<td>Наименование</td>" +
                    "<td>Кол-во</td>" +
                    "</tr>" +
                    "</thead><tbody>";
                $.each(data, function(key,val){

                    str += "<tr>" +
                        "<td>" + parseInt(key+1) + "</td>" +
                        "<td>" + val.name + "</td>" +
                        "<td>" + val.cnt + "</td>" +
                        "</tr>";
                    $("#tableBody").append(str);
                });
                str += "</tbody>";
                $(".modal-body").html(str);
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
                <h4 class="modal-title">Детально</h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->