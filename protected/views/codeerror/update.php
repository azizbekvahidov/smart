<?php
/* @var $this CodeerrorController */
/* @var $model Codeerror */

$this->breadcrumbs=array(
	'Codeerrors'=>array('index'),
	$model->name=>array('view','id'=>$model->codeErrorId),
	'Update',
);

$this->menu=array(
	array('label'=>'List Codeerror', 'url'=>array('index')),
	array('label'=>'Create Codeerror', 'url'=>array('create')),
	array('label'=>'View Codeerror', 'url'=>array('view', 'id'=>$model->codeErrorId)),
	array('label'=>'Manage Codeerror', 'url'=>array('admin')),
);
?>

<h1>Update Codeerror <?php echo $model->codeErrorId; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>