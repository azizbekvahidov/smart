<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/resources/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/resources/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/resources/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/resources/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/resources/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/resources/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/resources/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/resources/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/resources/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/resources/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/resources/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/resources/plugins/morris/morris.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/resources/plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/resources/plugins/jqvmap/jqvmap/jqvmap.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/resources/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/resources/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL STYLES -->
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/resources/css/chosen.css" />

    <link href="<?php echo Yii::app()->request->baseUrl; ?>/resources/css/cabinet/components-md.min.css" rel="stylesheet" id="style_components" type="text/css" />
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/resources/css/cabinet/plugins-md.min.css" rel="stylesheet" type="text/css" />
    <!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN THEME LAYOUT STYLES -->
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/resources/css/cabinet/profile.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/resources/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/resources/layouts/layout/css/themes/darkblue.min.css" rel="stylesheet" type="text/css" id="style_color" />
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/resources/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css" />
    <!-- END THEME LAYOUT STYLES -->
    <link rel="shortcut icon" href="favicon.ico" />


	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/resources/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<![endif]-->

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>
<body>
<? $cabinet = new Cabinet();?>

<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white page-md">
<!-- BEGIN HEADER -->
<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner ">
        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <a href="/">
                <img src="/images/logo_mini.png" alt="logo" class="logo-default" /> </a>
            <div class="menu-toggler sidebar-toggler"> </div>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"> </a>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <!-- BEGIN TOP NAVIGATION MENU -->
        <div class="top-menu">
            <ul class="nav navbar-nav pull-right">
                <!-- BEGIN NOTIFICATION DROPDOWN -->
                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->

                <li class="dropdown dropdown-extended dropdown-inbox" id="">
                    <a class="dropdown-toggle" data-toggle="modal" data-toggle="modal"  href="#addTask">
                        <i class="fa fa-calendar-plus-o"></i>
                    </a>
                    <i></i>
                </li>
                <li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <i class="icon-bell"></i>
                        <span></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="external">
                            <h3>
                                <span class="bold">0 </span>   уведомления</h3>
                        </li>
                        <li>
                            <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 250px;">
                                <ul id="notify" class="dropdown-menu-list scroller" style="height: 250px; overflow: hidden; width: auto;" data-handle-color="#637283" data-initialized="1">

                                </ul>
                                <div class="slimScrollBar" style="background: rgb(99, 114, 131); width: 7px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 121.359px;"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(234, 234, 234); opacity: 0.2; z-index: 90; right: 1px;"></div>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- END TODO DROPDOWN -->
                <!-- BEGIN USER LOGIN DROPDOWN -->
                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                <li class="dropdown dropdown-user">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <img alt="" class="img-circle" src="/upload/employee/<?=$cabinet->getUserImage(Yii::app()->user->getId())?>" />
                        <span class="username username-hide-on-mobile"> <?=Yii::app()->user->getLogin()?> </span>
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-default">
                        <li>
                            <a href="/cabinet/profile">
                                <i class="icon-user"></i> Мой профиль </a>
                        </li>
                        <li>
                            <a href="/cabinet/calendar">
                                <i class="icon-calendar"></i> Мой календарь </a>
                        </li>
                        <li id="signDocs">
                            <a href="/cabinet/signDoc">
                                <i class="icon-doc"></i> Для подписи
                                <span></span>
                            </a>
                        </li>
                        <li id="profileTask">
                            <a href="/cabinet/task">
                                <i class="icon-rocket"></i> Мои задачи
                                <span></span>
                            </a>
                        </li>
                        <li class="divider"> </li>

                        <li>
                            <a href="/site/logout">
                                <i class="icon-key"></i> Log Out </a>
                        </li>
                    </ul>
                </li>
                <!-- END USER LOGIN DROPDOWN -->
                <!-- BEGIN QUICK SIDEBAR TOGGLER -->
                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->

                <!-- END QUICK SIDEBAR TOGGLER -->
            </ul>
        </div>
        <!-- END TOP NAVIGATION MENU -->
    </div>
    <!-- END HEADER INNER -->
</div>
<!-- END HEADER -->
<!-- BEGIN HEADER & CONTENT DIVIDER -->
<div class="clearfix"> </div>
<!-- END HEADER & CONTENT DIVIDER -->
<!-- BEGIN CONTAINER -->
<div class="page-container">
    <!-- BEGIN SIDEBAR -->
    <div class="page-sidebar-wrapper">
        <!-- BEGIN SIDEBAR -->
        <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
        <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
        <div class="page-sidebar navbar-collapse collapse">
            <!-- BEGIN SIDEBAR MENU -->
            <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
            <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
            <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
            <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
            <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
            <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
            <?php

            $this->menu=array(
                array(
                    'label'=>'<i class="icon-note"></i> <span class="title">Действия</span> <span class="arrow"></span>',
                    'url'=> '#',
                    'linkOptions'=> array(
                        'class' => 'nav-link nav-toggle',
                        'data-toggle' => 'dropdown',
                    ),
                    'itemOptions' => array('class'=>'nav-item'),
                    'items'=> array(
                        array('label'=>'<span class="title"><i class="icon-doc"></i> Создать протокол </span>', 'url'=>array('/protokol/protokol')),
                        array('label'=>'<span class="title"><i class="icon-doc"></i> Отправить сообщение </span>', 'url'=>array('/cabinet/sendMsgTelegram')),

                    ),
                ),
                array(
                    'label'=>'<i class="icon-bar-chart"></i> <span class="title"> Отчеты</span> <span class="arrow"></span>',
                    'url'=> '#',
                    'linkOptions'=> array(
                        'class' => 'nav-link nav-toggle',
                        'data-toggle' => 'dropdown',
                    ),
                    'itemOptions' => array('class'=>'nav-item'),
                    'items'=> array(
                        array('label'=>'<span class="title"><i class="icon-flag"></i> Решенные задачи</span>', 'url'=>array('/cabinet/solvedTask')),
                        array('label'=>'<span class="title"><i class="icon-flag"></i> Мои выставленные задачи</span>', 'url'=>array('/cabinet/mySetTask')),

                    ),
                ),
            );
            $this->widget('zii.widgets.CMenu', array(
                'items' => $this->menu,
                'encodeLabel' => false,
                'htmlOptions' => array(
                    'class'=>'page-sidebar-menu  page-header-fixed ',
                    'data-keep-expanded'=>'false',
                    'data-auto-scroll'=>"true",
                    'data-slide-speed'=>"200",
                    'style'=>"padding-top: 20px"
                ),
                'activateParents'=>true,
                'submenuHtmlOptions' => array(
                    'class' => 'sub-menu ',
                )
            ));

            ?>
            <!-- END SIDEBAR MENU -->
            <!-- END SIDEBAR MENU -->
        </div>
        <!-- END SIDEBAR -->
    </div>
    <!-- END SIDEBAR -->
    <!-- BEGIN CONTENT -->
    <div class="page-content-wrapper">
        <!-- BEGIN CONTENT BODY -->
        <div class="page-content" id="mainContent">
            <?php echo $content; ?>

        </div>
        <!-- END CONTENT BODY -->
    </div>
    <!-- END CONTENT -->
</div>
<!-- END CONTAINER -->
<!-- BEGIN FOOTER -->
<div class="page-footer">
    <div class="page-footer-inner">
        Copyright &copy; <?php echo date('Y'); ?> Artel.<br/>
        Все права защищены.<br/>

    </div>
    <div class="scroll-to-top">
        <i class="icon-arrow-up"></i>
    </div>
</div>
<? $emp = new Employee()?>
<div class="modal fade" id="addTask" tabindex="-1" role="task" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Решение</h4>
            </div>
            <div class="modal-body">
                <form id="taskModalForm"  enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="form-group form-md-line-input has-success">
                                <div class="input-icon">
                                    <textarea type="text" name="task" rows="4" cols="20" placeholder="Введите задачу" class="form-control" ></textarea>
                                    <span class="help-block">Введите задачу здесь</span>
                                    <i class="fa fa-tasks"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-md-line-input has-success">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail" style="width: 70px; height: 50px;">
                                        <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" alt="" /> </div>
                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 70px; max-height: 50px;"> </div>
                                    <div>
                                        <span class="btn default btn-file">
                                            <span class="fileinput-new"> Выберите файл</span>
                                            <span class="fileinput-exists"> Изменить </span>
                                            <input type="file" id="uploadedFile" name="file">
                                        </span>
                                        <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput"> Удалить </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group form-md-line-input form-md-floating-label has-success">
                                <select name="response" id="" class="form-control">
                                    <option value="">Выберите ответственного</option>
                                    <? foreach ($emp->getEmployeeList() as $val){?>
                                        <option value="<?= $val["employeeId"] ?>"><?=$val["surname"]?> <?=$val["name"]?></option>
                                    <?}?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-md-line-input  has-success ">
                                <div class="input-icon">
                                    <input type="date" name="taskDate" id="taskDate" value="<?=date("Y-m-d")?>" class="form-control" />
                                    <label for="taskDate">Дата начало</label>
                                    <span class="help-block">Дата начало задачи</span>
                                    <i class="fa fa-calendar-check-o"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group form-md-line-input has-success">
                                <div class="input-icon">
                                    <input type="date" name="deadline" id="deadline" class="form-control" />
                                    <label for="deadline">Срок выполнения</label>
                                    <span class="help-block">Срок выполнения задачи</span>
                                    <i class="fa fa-calendar-times-o"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Закрыть</button>
                <button type="button" id="saveTask" class="btn green">Выставить задачу</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- END FOOTER -->
<!--<script src="/resources/js/jquery-3.3.1.js" ></script>-->
<!--[if lt IE 9]>
<script src="/resources/plugins/respond.min.js"></script>
<script src="/resources/plugins/excanvas.min.js"></script>
<![endif]-->
<!-- BEGIN CORE PLUGINS -->
<script src="/resources/plugins/jquery.min.js" type="text/javascript"></script>
<script src="/resources/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="/resources/plugins/js.cookie.min.js" type="text/javascript"></script>
<script src="/resources/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="/resources/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="/resources/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="/resources/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="/resources/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<script src="/resources/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="/resources/js/cabinet/app.min.js" type="text/javascript"></script>
<script src="/resources/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="/resources/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="/resources/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
<script src="/resources/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<script src="/resources/plugins/moment.min.js" type="text/javascript"></script>
<script src="/resources/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
<script src="/resources/plugins/morris/morris.min.js" type="text/javascript"></script>
<script src="/resources/plugins/morris/raphael-min.js" type="text/javascript"></script>
<script src="/resources/plugins/counterup/jquery.waypoints.min.js" type="text/javascript"></script>
<script src="/resources/plugins/counterup/jquery.counterup.min.js" type="text/javascript"></script>
<script src="/resources/plugins/amcharts/amcharts/amcharts.js" type="text/javascript"></script>
<script src="/resources/plugins/amcharts/amcharts/serial.js" type="text/javascript"></script>
<script src="/resources/plugins/amcharts/amcharts/pie.js" type="text/javascript"></script>
<script src="/resources/plugins/amcharts/amcharts/radar.js" type="text/javascript"></script>
<script src="/resources/plugins/amcharts/amcharts/themes/light.js" type="text/javascript"></script>
<script src="/resources/plugins/amcharts/amcharts/themes/patterns.js" type="text/javascript"></script>
<script src="/resources/plugins/amcharts/amcharts/themes/chalk.js" type="text/javascript"></script>
<script src="/resources/plugins/amcharts/ammap/ammap.js" type="text/javascript"></script>
<script src="/resources/plugins/amcharts/ammap/maps/js/worldLow.js" type="text/javascript"></script>
<script src="/resources/plugins/amcharts/amstockcharts/amstock.js" type="text/javascript"></script>
<script src="/resources/plugins/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
<script src="/resources/plugins/flot/jquery.flot.min.js" type="text/javascript"></script>
<script src="/resources/plugins/flot/jquery.flot.resize.min.js" type="text/javascript"></script>
<script src="/resources/plugins/flot/jquery.flot.categories.min.js" type="text/javascript"></script>
<script src="/resources/plugins/jquery-easypiechart/jquery.easypiechart.min.js" type="text/javascript"></script>
<script src="/resources/plugins/jquery.sparkline.min.js" type="text/javascript"></script>
<script src="/resources/plugins/jqvmap/jqvmap/jquery.vmap.js" type="text/javascript"></script>
<script src="/resources/plugins/jqvmap/jqvmap/maps/jquery.vmap.russia.js" type="text/javascript"></script>
<script src="/resources/plugins/jqvmap/jqvmap/maps/jquery.vmap.world.js" type="text/javascript"></script>
<script src="/resources/plugins/jqvmap/jqvmap/maps/jquery.vmap.europe.js" type="text/javascript"></script>
<script src="/resources/plugins/jqvmap/jqvmap/maps/jquery.vmap.germany.js" type="text/javascript"></script>
<script src="/resources/plugins/jqvmap/jqvmap/maps/jquery.vmap.usa.js" type="text/javascript"></script>
<script src="/resources/plugins/jqvmap/jqvmap/data/jquery.vmap.sampledata.js" type="text/javascript"></script>
<script src="/resources/js/cabinet/portlet-draggable.min.js" type="text/javascript"></script>
<script src="/resources/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="/resources/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
<script src="/resources/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
<script src="/resources/plugins/jquery.sparkline.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/resources/js/chosen.jquery.js" type="text/javascript" ></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/resources/js/cabinet/cabinet.js" type="text/javascript" ></script>

<script src="/resources/js/custom.js" type="text/javascript"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/resources/js/datatables.min.js" type="text/javascript" ></script>
<!-- END THEME GLOBAL SCRIPTS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="/resources/js/cabinet/form-validation-md.min.js" type="text/javascript"></script>
<script src="/resources/js/cabinet/dashboard.min.js" type="text/javascript"></script>
<script src="/resources/js/cabinet/datatable.js" type="text/javascript"></script>
<script src="/resources/plugins/datatables/datatables.min.js" type="text/javascript"></script>
<script src="/resources/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
<script src="/resources/js/cabinet/table-datatables-editable.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME LAYOUT SCRIPTS -->
<script src="/resources/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
<script src="/resources/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
<script src="/resources/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>

</body>
</html>
