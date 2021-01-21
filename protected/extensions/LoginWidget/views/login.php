<div class="form form-vertical">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'login-form',
        //'enableAjaxValidation'=>true,
        'clientOptions'=>array('validateOnSubmit'=>true),
        'htmlOptions'=>array('class'=>'form-horizontal'),
        'action' => array('/cabinet/login'), // this is is the action that's going to process the data
    )); ?>

        <?php echo $form->labelEx($model,'username', array('label'=>'Пользователь')); ?>
        <?php echo $form->textField($model,'username', array('class'=>"form-control" ,'id'=>"username", 'placeholder'=>"имя пользователя" ,'style'=>"margin-bottom:.5em",'autocomplete'=>'off')); ?>
        <?php echo $form->error($model,'username'); ?>

        <?php echo $form->labelEx($model,'password', array('label'=>'Пароль')); ?>
        <?php echo $form->passwordField($model,'password',array('class'=>"form-control" ,'id'=>"password", 'placeholder'=>"пароль" ,'style'=>"margin-bottom:.5em")); ?>
        <?php echo $form->error($model,'password'); ?>

    <br/><?php echo CHtml::submitButton('Войти!', array('class'=>"btn btn-primary", 'style'=>"margin-top:.75em;width: 100%; height: 32px; font-size: 13px;", 'name'=>"login" )); ?>

    <?php $this->endWidget(); ?>
</div>