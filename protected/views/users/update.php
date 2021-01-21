<?php
/* @var $this UsersController */
/* @var $model Users */

$this->breadcrumbs=array(
    $title=>array('admin'),
	'Редактирование',
);
?>

<h1>Редактирование <?=$title?> <br>"<?php echo $model["surname"]." ".$model["name"]." ".$model["lastname"]; ?>"</h1>

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
        <?php echo CHtml::label('Логин','Users_login'); ?>
        <?php echo CHtml::textField('Users[login]',$model["login"],array('size'=>60,'maxlength'=>100)); ?>
    </div>

    <div class="row">
        <?php echo CHtml::label('Имя','Users_name'); ?>
        <?php echo CHtml::textField('Users[name]',$model["name"],array('size'=>60,'maxlength'=>100)); ?>
    </div>

    <div class="row">
        <?php echo CHtml::label('Фамилия','Users_surname'); ?>
        <?php echo CHtml::textField('Users[surname]',$model["surname"],array('size'=>60,'maxlength'=>100)); ?>
    </div>

    <div class="row">
        <?php echo CHtml::label('Отчество','Users_lastname'); ?>
        <?php echo CHtml::textField('Users[lastname]',$model["lastname"],array('size'=>60,'maxlength'=>100)); ?>
    </div>

    <div class="row">
        <?php echo CHtml::label('Телефон (97-320-31-71)','phone'); ?>
        <?php echo CHtml::textField('Users[phone]',$model["phone"],array('size'=>60,'maxlength'=>100)); ?>
    </div>

    <div class="row">
        <?php echo CHtml::label('Новый пароль','Users_pass'); ?>
        <?php echo CHtml::checkBox('Users[pass]',false); ?>
    </div>
    <div class="row">
        <?php echo CHtml::label('Диллер','login'); ?>
        <?php echo CHtml::dropDownList('Users[parent]',$model["parent"],$list); ?>
    </div>
	<h2>Тип</h2>
    <div class="row">
        <?php echo CHtml::radioButtonList('Users[uType]',$model["uType"],array(0=>"Продавцы",1=>"Промоутеры")); ?>
    </div>
    <h2>Точка</h2>
    <div class="row">
        <?php echo CHtml::label('Город','Users_city'); ?>
        <?php echo CHtml::dropDownList('Users[city]',$model["city"],CHtml::listData(Point::model()->findAll("place = 'city'",array(":id"=>Yii::app()->user->getId())),'pointId','name'),array("empty"=>"Выберите",)); ?>
    </div>
    <div id="listData">
        <div class="row" id="district">
            <?php echo CHtml::label("Район",''); ?>
            <?=CHtml::dropDownList('Users[district]',$model["district"],CHtml::listData(Point::model()->findAll("place = 'district'"),'pointId','name'),array("empty"=>"Выберите",))?>
        </div>
        <div class="row" id="market">
            <?php echo CHtml::label("Рынок",''); ?>
            <?=CHtml::dropDownList('Users[market]',$model["market"],CHtml::listData(Point::model()->findAll("place = 'market'"),'pointId','name'),array("empty"=>"Выберите",))?>
        </div>
        <div class="row" id="shop">
            <?php echo CHtml::label("Магазин",''); ?>
            <?=CHtml::dropDownList('Users[shop]',$model["shop"],CHtml::listData(Point::model()->findAll("place = 'shop'"),'pointId','name'),array("empty"=>"Выберите",))?>
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