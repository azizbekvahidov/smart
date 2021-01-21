<?php
/* @var $this SpareController */
/* @var $model Spare */

$this->breadcrumbs=array(
	'Spares'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Spare', 'url'=>array('index')),
	array('label'=>'Manage Spare', 'url'=>array('admin')),
);
?>

<h1>Create Spare</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>