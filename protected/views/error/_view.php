<?php
/* @var $this ErrorController */
/* @var $data Error */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('errorId')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->errorId), array('view', 'id'=>$data->errorId)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('codeId')); ?>:</b>
	<?php echo CHtml::encode($data->codeId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('descUz')); ?>:</b>
	<?php echo CHtml::encode($data->descUz); ?>
	<br />


</div>