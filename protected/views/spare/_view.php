<?php
/* @var $this SpareController */
/* @var $data Spare */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('spareId')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->spareId), array('view', 'id'=>$data->spareId)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('spareType')); ?>:</b>
	<?php echo CHtml::encode($data->spareType); ?>
	<br />


</div>