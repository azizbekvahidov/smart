<?php
/* @var $this CodeerrorController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Codeerrors',
);

$this->menu=array(
	array('label'=>'Create Codeerror', 'url'=>array('create')),
	array('label'=>'Manage Codeerror', 'url'=>array('admin')),
);
?>

<h1>Codeerrors</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
