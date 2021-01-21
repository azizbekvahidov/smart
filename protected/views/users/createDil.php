<?php
/* @var $this UsersController */
/* @var $model Users */

$this->breadcrumbs=array(
    $title.'ы'=>array('admin'),
    'Добавить',
);

?>

    <h1>Добавить <?=$title?>а</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>