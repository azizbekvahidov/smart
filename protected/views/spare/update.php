<?php
/* @var $this SpareController */
/* @var $model Spare */

$this->breadcrumbs=array(
	'Spares'=>array('index'),
	$model->name=>array('view','id'=>$model->spareId),
	'Update',
);

$this->menu=array(
	array('label'=>'List Spare', 'url'=>array('index')),
	array('label'=>'Create Spare', 'url'=>array('create')),
	array('label'=>'View Spare', 'url'=>array('view', 'id'=>$model->spareId)),
	array('label'=>'Manage Spare', 'url'=>array('admin')),
);
?>

<h1>Update Spare <?php echo $model->spareId; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>