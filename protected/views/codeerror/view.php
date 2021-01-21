<?php
/* @var $this CodeerrorController */
/* @var $model Codeerror */

$this->breadcrumbs=array(
	'Codeerrors'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Codeerror', 'url'=>array('index')),
	array('label'=>'Create Codeerror', 'url'=>array('create')),
	array('label'=>'Update Codeerror', 'url'=>array('update', 'id'=>$model->codeErrorId)),
	array('label'=>'Delete Codeerror', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->codeErrorId),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Codeerror', 'url'=>array('admin')),
);
?>

<h1>View Codeerror #<?php echo $model->codeErrorId; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'codeErrorId',
		'name',
	),
)); ?>
