
<? $cabinet = new Cabinet();?>
    <!-- BEGIN PAGE TITLE-->
    <h2 class="page-title"> <i class="fa fa-user"></i> &nbsp;    Профиль пользователя
    </h2>
    <!-- END PAGE TITLE-->
    <!-- END PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PROFILE SIDEBAR -->
            <div class="profile-sidebar">
                <!-- PORTLET MAIN -->
                <div class="portlet light profile-sidebar-portlet ">
                    <!-- SIDEBAR USERPIC -->
                    <div class="profile-userpic">
                        <img src="/upload/employee/<?=$cabinet->getUserImage(Yii::app()->user->getId())?>" class="img-responsive" alt=""> </div>
                    <!-- END SIDEBAR USERPIC -->
                    <!-- SIDEBAR USER TITLE -->
                    <div class="profile-usertitle">
                        <div class="profile-usertitle-name"> <?=Yii::app()->user->getName()?> </div>
                        <div class="profile-usertitle-job"> <?=$cabinet->getUserPosition(Yii::app()->user->getPos())?> </div>
                    </div>
                    <!-- END SIDEBAR USER TITLE -->
                    <!-- SIDEBAR BUTTONS -->
                    <div class="profile-userbuttons">
                        <button type="button" class="btn btn-circle green btn-sm">Follow</button>
                        <button type="button" class="btn btn-circle red btn-sm">Message</button>
                    </div>
                    <!-- END SIDEBAR BUTTONS -->
                    <!-- SIDEBAR MENU -->

                    <div class="profile-usermenu">
                        <ul class="nav">
                            <li class="active">
                                <a href="javascript:;" onclick="profile('view')" class="profileLink">
                                    <i class="icon-home"></i> Просмотр </a>
                            </li>
                            <li>
                                <a href="javascript:;" onclick="profile('settings')" class="profileLink">
                                    <i class="icon-settings"></i> Настройки </a>
                            </li>
                        </ul>
                    </div>
                    <!-- END MENU -->
                </div>
                <!-- END PORTLET MAIN -->
            </div>
            <!-- END BEGIN PROFILE SIDEBAR -->
            <!-- BEGIN PROFILE CONTENT -->
            <div class="profile-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light" id="profileContent">
                        </div>
                    </div>
                </div>
            </div>
            <!-- END PROFILE CONTENT -->
        </div>
    </div>

<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/resources/js/cabinet/profile.js"></script>