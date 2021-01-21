<div class="">
    <form action="sendForm">
        <?php Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
        $this->widget('CJuiDateTimePicker',array(
            'name'=>"start", //Model object
            'attribute'=>'eventDate', //attribute name
            'mode'=>'date', //use "time","date" or "datetime" (default)
            'options'=>array() // jquery plugin options
        ));
        ?>
        <?php Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
        $this->widget('CJuiDateTimePicker',array(
            'name'=>"end", //Model object
            'attribute'=>'eventDate', //attribute name
            'mode'=>'date', //use "time","date" or "datetime" (default)
            'options'=>array() // jquery plugin options
        ));
        ?>
        <input type="button" id="view" value="Показать">
    </form>
</div>
<div id="data" style="margin-top: 20px;"></div>
<script>
    $(document).ready(function(){
        var from,
            to;
        $('#view').click(function(){
            from = $('#start').val();
            to = $('#end').val();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('report/Sellreport'); ?>",
                data: "from="+from+'&to='+to,
                success: function(data){
                    $('#data').html(data);
                }
            });
        });
    });
</script>