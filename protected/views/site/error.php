
<!DOCTYPE html>

<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->

<head>
    <meta charset="utf-8" />
    <title><?=Yii::app()->name . ' - Error 404'?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    <link href="/resources/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="/resources/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
    <link href="/resources/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/resources/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />
    <link href="/resources/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="/resources/css/cabinet/components-md.min.css" rel="stylesheet" id="style_components" type="text/css" />
    <link href="/resources/css/cabinet/plugins-md.min.css" rel="stylesheet" type="text/css" />
    <!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link href="/resources/css/cabinet/error.min.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL STYLES -->
    <!-- BEGIN THEME LAYOUT STYLES -->
    <!-- END THEME LAYOUT STYLES -->
    <link rel="shortcut icon" href="favicon.ico" /> </head>
<!-- END HEAD -->

    <body class=" page-404-3">
        <div class="page-inner">
            <img src="/resources/media/earth.jpg" class="img-responsive" alt=""> </div>
        <div class="container error-404">
            <h1>Ошибка кода <?php echo $code; ?></h1>
            <h2>Houston, we have a problem.</h2>
            <p> <?php echo CHtml::encode($message); ?> </p>
            <p>
                <a href="/" class="btn red btn-outline"> Return home </a>
                <br> </p>
        </div>
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
        <script sr  c="/resources/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="/resources/js/cabinet/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <!-- END THEME LAYOUT SCRIPTS -->
    </body>

</html>
