
    <?php echo CHtml::label($name,''); ?>
    <?=CHtml::dropDownList($ctype,"",$model,array("empty"=>"Выберите",))?>

<script>
    $(document).ready(function(){
        var id = "#Users_<?=$id?>";
        $(id).change(function(){
            var id = $(this).val();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('users/getPlace'); ?>",
                data: "id="+id+"&ctype=<?=$ctypeNum?>",
                success: function(data){
                    $('#<?=$next?>').html(data);
                }
            });
        });

    })
</script>
