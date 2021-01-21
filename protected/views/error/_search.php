<?php
/* @var $this ErrorController */
/* @var $model Error */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'errorId'); ?>
		<?php echo $form->textField($model,'errorId'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'description'); ?>
		<?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'codeId'); ?>
		<?php echo $form->textField($model,'codeId'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'descUz'); ?>
		<?php echo $form->textField($model,'descUz',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->