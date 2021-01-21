<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

?>

<h1 class="text-center">Авторизация</h1>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

<div class="row  col-sm-4" style="left: 34%;">
	<div class="form-group row">
		<?php echo $form->labelEx($model,'username',array('class'=>'label-control')); ?>
        <div class="">
		    <?php echo $form->textField($model,'username',array('class'=>'form-control','autocomplete'=>'off')); ?>
        </div>
		<?php echo $form->error($model,'username',array('class'=>'label-control')); ?>
	</div>

	<div class="form-group row">
		<?php echo $form->labelEx($model,'password',array('class'=>'label-control')); ?>
        <div class="">
            <?php echo $form->passwordField($model,'password',array('class'=>'form-control')); ?>
        </div>
		<?php echo $form->error($model,'password',array('class'=>'label-control')); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Войти',array('class'=>'btn btn-success','style'=>'width: 100%;')); ?>
	</div>
</div>
<?php $this->endWidget(); ?>
</div><!-- form -->
