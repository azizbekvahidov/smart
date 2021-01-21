<?php
/* @var $this UsersController */
/* @var $model Users */


?>

<h1><?php echo $model->surname." ".$model->name." ".$model->lastname; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'login',
		'name',
		'surname',
		'lastname',
	),
)); ?>
<h2>Пароль: <?=$pass?></h2>
