<?php
/* @var $this ErrorController */
/* @var $model Error */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'error-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'codeId'); ?>
		<?php echo $form->dropDownList($model,'codeId',CHtml::listData(Codeerror::model()->findAll(),"codeErrorId","name")); ?>
		<?php echo $form->error($model,'codeId'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'descUz'); ?>
		<?php echo $form->textField($model,'descUz',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'descUz'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->