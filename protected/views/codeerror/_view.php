<?php
/* @var $this CodeerrorController */
/* @var $data Codeerror */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('codeErrorId')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->codeErrorId), array('view', 'id'=>$data->codeErrorId)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />


</div>