<div class="col-sm-4">
    <h1>Добавить телефон</h1>
    <form action="" method="post" class="forms">
        <div class="form-group">
            <input type="text" name="phone[sn]" class="form-control input" id="sn" placeholder="SN">
        </div>
        <div class="form-group">
            <input type="number" name="phone[imei1]" class="form-control input" id="imei1" placeholder="IMEI1">
        </div>
        <div class="form-group">
            <input type="number" name="phone[imei2]" class="form-control input" id="imei2" placeholder="IMEI2">
        </div>
        <div class="form-group">
            <?php Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
            $this->widget('CJuiDateTimePicker',array(
                'name'=>"phone[date]", //Model object
                'attribute'=>'eventDate', //attribute name
                'mode'=>'date', //use "time","date" or "datetime" (default)
                'options'=>array(
                    'dateFormat'=>'yy-mm-dd',
                ),
                'htmlOptions'=>array(
                    'class'=>'form-control',
                    'id'=>'date'
                )
            ));
            ?>
        </div>
        <div class="form-group">
            <input type="button" class="btn btn-success save" value="Сохранить">
        </div>

    </form>
</div>
<script>
    $(document).ready(function(){
        $("#sn").focus();
        $(".input").keypress(function (e) {
            if(e.which == 13){
                var name = $(this).attr("name");
                switch (name){
                    case "phone[sn]":
                        $("#imei1").focus();
                        break;
                    case "phone[imei1]":
                        $("#imei2").focus();
                        break;
                    case "phone[imei2]":
                        $("#date").focus();
                        break;
                }
            }
        });
        $(".save").click(function () {
            $(".forms").submit();
        })
    });
</script>