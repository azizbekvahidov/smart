<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/chosen.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/styles.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/fixedHeader.bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/datatables.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/keyboard.css" />


    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>
<body>

<div class="" id="page">

	<!-- header --><?php
    if(Yii::app()->user->isGuest){?>
        <a class="btn btn-success right" href="/site/login">Войти</a>
    <?}?>

        <div id="mainmenu">
            <?php
            if(Yii::app()->user->isGuest){
                $this->widget('zii.widgets.CMenu',array(
                    'items'=>array(
                        array('label'=>'Акт', 'url'=>array('/admin/actRegistered')),
                    )
                ));
            }
            else{
                switch (Yii::app()->user->getRole()){
                    case "admin":
                        $this->widget('zii.widgets.CMenu',array(
                            'items'=>array(
                                array('label'=>'Главная', 'url'=>array('/site/index')),
                                array('label'=>'Телефоны', 'url'=>array('/admin/phone')),
                                array('label'=>'Ошибки', 'url'=>array('/error/admin')),
                                array(
                                    'label'=>'Запчасти', 'url'=>array('#'),
                                    'linkOptions'=> array(
                                        'class' => 'dropdown-toggle',
                                        'data-toggle' => 'dropdown',
                                    ),
                                    'itemOptions' => array('class'=>'nav navbar-nav'),
                                    'items'=> array(
                                        array('label'=>'Запчасти', 'url'=>array('/spare/admin')),
                                        array('label'=>'Полуготовые Запчасти', 'url'=>array('/spare/semiSpare')),
                                        array('label'=>'BOM лист', 'url'=>array('/spare/struct')),
                                        array('label'=>'Запчасти отделов', 'url'=>array('/admin/departmentSpare')),
                                    ),
                                ),
                                array('label'=>'Группы ошибок', 'url'=>array('/codeerror/admin')),
                                array(
                                    'label'=>'Акты', 'url'=>array('#'),
                                    'linkOptions'=> array(
                                        'class' => 'dropdown-toggle',
                                        'data-toggle' => 'dropdown',
                                    ),
                                    'itemOptions' => array('class'=>'nav navbar-nav'),
                                    'items'=> array(
                                        array('label'=>'Открытые акты', 'url'=>array('/admin/actChecking')),
                                        array('label'=>'Все акты', 'url'=>array('/admin/act')),
                                        array('label'=>'Ввод старых актов', 'url'=>array('/admin/oldActRegistered')),
                                    ),
                                ),
                                array('label'=>'Сотрудники', 'url'=>array('/admin/empAdmin')),
                                array(
                                    'label'=>'Отчеты', 'url'=>array('#'),
                                    'linkOptions'=> array(
                                        'class' => 'dropdown-toggle',
                                        'data-toggle' => 'dropdown',
                                    ),
                                    'itemOptions' => array('class'=>'nav navbar-nav'),
                                    'items'=> array(
                                        array('label'=>'Отчет по ремонту', 'url'=>array('/report/repairDetail')),
                                        array('label'=>'Отчет по бракам', 'url'=>array('/report/brak')),
                                    ),
                                ),
                                array('label'=>'Выйти ', 'url'=>array('/site/logout'),'style'=>'text-align: right', 'visible'=>!Yii::app()->user->isGuest)
                            ),
                            'htmlOptions' => array(
                                'class'=>'nav navbar-nav',
                            ),
                            'submenuHtmlOptions' => array(
                                'class' => 'dropdown-menu nav navbar-nav ',
                            )
                        ));
                        break;

                    case "selAdmin":
                        $this->widget('zii.widgets.CMenu',array(
                            'items'=>array(
                                array('label'=>'Главная', 'url'=>array('/site/index')),
                                array('label'=>'Пользователи', 'url'=>array('/users/admin')),
                                array('label'=>'Диллеры', 'url'=>array('/users/adminDil')),
                                //array('label'=>'Отчет по продаже', 'url'=>array('/report/sell')),
                                array(
                                    'label'=>'Баллы', 'url'=>array('#'),
                                    'linkOptions'=> array(
                                        'class' => 'dropdown-toggle',
                                        'data-toggle' => 'dropdown',
                                    ),
                                    'itemOptions' => array('class'=>'nav navbar-nav'),
                                    'items'=> array(
                                        array('label'=>'Установка баллов', 'url'=>array('/admin/setBall')),
                                        array('label'=>'Проверка баллов', 'url'=>array('/admin/checkBall')),
                                    ),
                                ),
                                array('label'=>'Остаток диллеров', 'url'=>array('/admin/balanceDiller')),
                                array('label'=>'Остаток промоутеров', 'url'=>array('/admin/balanceUser')),
                                array('label'=>'Проданные телефоны', 'url'=>array('/report/reportSold')),
                                array('label'=>'План', 'url'=>array('/admin/plan')),
                                array('label'=>'Новости', 'url'=>array('/news/admin')),
                                array('label'=>'Точка', 'url'=>array('/admin/place')),
                                array(
                                    'label'=>'Shop', 'url'=>array('#'),
                                    'linkOptions'=> array(
                                        'class' => 'dropdown-toggle',
                                        'data-toggle' => 'dropdown',
                                    ),
                                    'itemOptions' => array('class'=>'nav navbar-nav'),
                                    'items'=> array(
                                        array('label'=>'Лист продуктов', 'url'=>array('/admin/shopAdmin')),
                                        array('label'=>'Оформленные заказы', 'url'=>array('/admin/shopDetail')),
                                    ),
                                ),
                                array(
                                    'label'=>'Действия', 'url'=>array('#'),
                                    'linkOptions'=> array(
                                        'class' => 'dropdown-toggle',
                                        'data-toggle' => 'dropdown',
                                    ),
                                    'itemOptions' => array('class'=>'nav navbar-nav'),
                                    'items'=> array(
                                        array('label'=>'История телефона', 'url'=>array('/admin/history')),
                                        array('label'=>'Удалить цепочку(Возврат)', 'url'=>array('/admin/deleteAllActions')),
                                        array('label'=>'Добавить телефон', 'url'=>array('/admin/addPhone')),
                                        array('label'=>'Проданные телефоны в ручную', 'url'=>array('/admin/addSoldPhone')),
                                    ),
                                ),
                                array(
                                    'label'=>'Умутбек', 'url'=>array('#'),
                                    'linkOptions'=> array(
                                        'class' => 'dropdown-toggle',
                                        'data-toggle' => 'dropdown',
                                    ),
                                    'itemOptions' => array('class'=>'nav navbar-nav'),
                                    'items'=> array(
                                        array('label'=>'Отчет по диллерам', 'url'=>array('/report/dealer')),
                                        array('label'=>'Отчет по продовцам', 'url'=>array('/report/retailer')),
                                        array('label'=>'Отчет по товарам', 'url'=>array('/report/product')),
                                        array('label'=>'Отчет по SmartMobile', 'url'=>array('/report/smartMobileReport')),
                                    ),
                                ),
                                array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
                                array('label'=>'Выйти ', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
                            ),
                            'htmlOptions' => array(
                                'class'=>'nav navbar-nav',
                            ),
                            'submenuHtmlOptions' => array(
                                'class' => 'dropdown-menu nav navbar-nav ',
                            )
                        ));
                        break;
                    case "diller":
                        $this->widget('zii.widgets.CMenu',array(
                            'items'=>array(
                                array('label'=>'Главная', 'url'=>array('/site/index')),
                                array('label'=>'Приход', 'url'=>array('/diller/coming')),
                                array('label'=>'Расход', 'url'=>array('/diller/out')),
                                array('label'=>'Отчет по приходу', 'url'=>array('/report/dillerComing')),
                                array('label'=>'Отчет по расходу', 'url'=>array('/report/dillerOuting')),
                                array('label'=>'Остаток', 'url'=>array('/report/dillerBalance')),
                                array('label'=>'Проданные телефоны', 'url'=>array('/report/sold')),

                                array('label'=>'Выйти ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'),'style'=>'text-align: right', 'visible'=>!Yii::app()->user->isGuest)
                            ),'htmlOptions' => array(
                            ),
                            'submenuHtmlOptions' => array(
                                'class' => 'dropdown-menu',
                            )
                        ));
                        break;
                    case "OTK":
                        $this->widget('zii.widgets.CMenu',array(
                            'items'=>array(
                                array('label'=>'Главная', 'url'=>array('/site/index')),
                                array('label'=>'FTQ отчет', 'url'=>array('/report/repair')),
                                array('label'=>'Лабаратория', 'url'=>array('/report/labaratory')),
                                array('label'=>'Акт', 'url'=>array('/admin/actOtk')),
                                array(
                                    'label'=>'Отчеты', 'url'=>array('#'),
                                    'linkOptions'=> array(
                                        'class' => 'dropdown-toggle',
                                        'data-toggle' => 'dropdown',
                                    ),
                                    'itemOptions' => array('class'=>'nav navbar-nav'),
                                    'items'=> array(
                                        array('label'=>'Отчет по ремонту', 'url'=>array('/report/repairDetail')),
                                        array('label'=>'Отчет по бракам', 'url'=>array('/report/brak')),
                                    ),
                                ),
                                array('label'=>'Выйти ', 'url'=>array('/site/logout'),'style'=>'text-align: right', 'visible'=>!Yii::app()->user->isGuest)
                            ),
                            'htmlOptions' => array(
                                'class'=>'nav navbar-nav',
                            ),
                            'submenuHtmlOptions' => array(
                                'class' => 'dropdown-menu nav navbar-nav ',
                            )
                        ));
                        break;
                    case "report":
                        $this->widget('zii.widgets.CMenu',array(
                            'items'=>array(
                                array('label'=>'Главная', 'url'=>array('/site/index')),
                                array(
                                    'label'=>'Отчет по продаже', 'url'=>array('/#'),
                                    'linkOptions'=> array(
                                        'class' => 'dropdown-toggle',
                                        'data-toggle' => 'dropdown',
                                    ),
                                    'itemOptions' => array('class'=>'nav navbar-nav'),
                                    'items'=> array(
                                        array('label'=>'Остаток диллеров', 'url'=>array('/admin/balanceDiller')),
                                        array('label'=>'Остаток промоутеров', 'url'=>array('/admin/balanceUser')),
                                        array('label'=>'Проданные телефоны', 'url'=>array('/report/reportSold')),
                                        array('label'=>'Отчет по диллерам', 'url'=>array('/report/dealer')),
                                        array('label'=>'Отчет по продовцам', 'url'=>array('/report/retailer')),
                                        array('label'=>'Отчет по товарам', 'url'=>array('/report/product')),
                                        array('label'=>'Отчет по SmartMobile', 'url'=>array('/report/smartMobileReport')),
                                    ),
                                ),
                                array('label'=>'Брак', 'url'=>array('/report/brak')),
                                array('label'=>'Отчет по ремонту', 'url'=>array('/report/repairDetail')),
                                array('label'=>'Выйти ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'),'style'=>'text-align: right', 'visible'=>!Yii::app()->user->isGuest)
                            ),'htmlOptions' => array(
                                    'class'=>'nav navbar-nav',
                            ),
                            'submenuHtmlOptions' => array(
                                'class' => 'dropdown-menu nav navbar-nav ',
                            )
                        ));
                        break;
                }
            }?>
        </div><!-- mainmenu -->
        <?php if(isset($this->breadcrumbs)):?>
            <?php $this->widget('zii.widgets.CBreadcrumbs', array(
                'links'=>$this->breadcrumbs,
            )); ?><!-- breadcrumbs -->
        <?php endif?>

	    <?php echo $content; ?>

	<div class="clear"></div>

	<div id="footer">
		Copyright &copy; <?php echo date('Y'); ?> Artel.<br/>
		Все права защищены.<br/>
	</div><!-- footer -->

</div><!-- page -->
<script src="/js/jquery-3.3.1.js" ></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/datatables.min.js" type="text/javascript" ></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.printPage.js" type="text/javascript" ></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/dataTables.fixedHeader.min.js" type="text/javascript" ></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/chosen.jquery.js" type="text/javascript" ></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.table2excel.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/keyboard.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/mainKeyboard.js"></script>

</body>
</html>
