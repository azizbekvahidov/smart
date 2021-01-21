<?php
/* @var $this CodeerrorController */
/* @var $model Codeerror */

$this->breadcrumbs=array(
	'Codeerrors'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Codeerror', 'url'=>array('index')),
	array('label'=>'Manage Codeerror', 'url'=>array('admin')),
);
?>

<h1>Create Codeerror</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>