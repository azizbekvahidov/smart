<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/resources/css/bootstrap.css">
<script src="<?php echo Yii::app()->request->baseUrl; ?>/resources/js/jquery.min.js"></script>
<div  id="reception" style="width: 100%">
</div>

<script>
    getData();
    function getData(){
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('site/receptionReport'); ?>",
            data: 'from=<?=date("Y-m-d")?>',
            success: function(data){
                $("#reception").html(data);
            }
        });
    }
    setInterval(getData, 10000);
</script>