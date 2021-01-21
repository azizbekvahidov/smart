<?php
/* @var $this UsersController */
/* @var $model Users */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'users-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>


	<div class="row">
		<?php echo CHtml::label('Логин','login'); ?>
		<?php echo CHtml::textField('Users[login]',"",array('size'=>60,'maxlength'=>100)); ?>
	</div>

    <div class="row">
        <?php echo CHtml::label('Имя','login'); ?>
        <?php echo CHtml::textField('Users[name]',"",array('size'=>60,'maxlength'=>100)); ?>
    </div>

    <div class="row">
        <?php echo CHtml::label('Фамилия','login'); ?>
        <?php echo CHtml::textField('Users[surname]',"",array('size'=>60,'maxlength'=>100)); ?>
    </div>

    <div class="row">
        <?php echo CHtml::label('Отчество','login'); ?>
        <?php echo CHtml::textField('Users[lastname]',"",array('size'=>60,'maxlength'=>100)); ?>
    </div>
    <div class="row">
        <?php echo CHtml::label('Телефон (97-320-31-71)','phone'); ?>
        <?php echo CHtml::textField('Users[phone]',"",array('size'=>60,'maxlength'=>100)); ?>
    </div>
    <h2>Точка</h2>
    <div class="row">
        <?php echo CHtml::label('Город','login'); ?>
        <?php echo CHtml::dropDownList('Users[city]',"",CHtml::listData(Point::model()->findAll("place = 'city'"),'pointId','name'),array("empty"=>"Выберите",)); ?>
    </div>
    <div id="listData">
        <div class="row" id="district">
        </div>
        <div class="row" id="market">
        </div>
        <div class="row" id="shop">
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $('#Users_city').change(function(){
                var id = $(this).val();
                $.ajax({
                    type: "POST",
                    url: "<?php echo Yii::app()->createUrl('users/getPlace'); ?>",
                    data: "id="+id+"&ctype=1",
                    success: function(data){
                        $('#district').html(data);
                    }
                });
            });

        });
    </script>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Сохраить'); ?>
	</div>
<?php $this->endWidget(); ?>

</div><!-- form -->