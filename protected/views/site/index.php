<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
if(Yii::app()->user->isGuest){
    //$this->redirect("/site/login");
}
else {
    switch (Yii::app()->user->getRole()) {
        case "admin":
            break;
        case "selAdmin":
            break;
        case "diller":
            break;
        case "report":
            ?>
<!--<style>-->
<!--    .carousel-inner{-->
<!--    overflow-y: scroll!important;-->
<!--        height: 100%;-->
<!--    }-->
<!--</style>-->
<!--<!--            -->
<!--<div id="carousel-example-generic" class="carousel slide " data-ride="carousel">-->
<!--     Indicators-->
<!--    <ol class="carousel-indicators">-->
<!--        <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>-->
<!--        <li data-target="#carousel-example-generic" data-slide-to="1"></li>-->
<!--    </ol>-->
<!---->
<!--    <!-- Wrapper for slides -->
<!--    <div class="carousel-inner" role="listbox">-->
<!--        <div class="item active" id="registerBlock">-->
<!--        </div>-->
<!--        <div class="item">-->
<!--            --><?// Yii::app()->runController('site/produceError');?>
<!--    </div>-->
<!---->
<!--    <div class="item">-->
<!--        --><?// Yii::app()->runController('site/skdError');?>
<!--    </div>-->
<!---->
<!--    <div class="item active ftqBlock">-->
<!--        --><?// Yii::app()->runController('site/ftqData');?>
<!--    </div>-->
<!--        <div class="item">-->
<!--            <div class="text-center " >-->
<!--                <img src="/images/Hisobot1.jpg" alt="" style="width: 100%; height: 100%">-->
<!--            </div>-->
<!--            <div class="carousel-caption">-->
<!---->
<!--            </div>-->
<!--        </div>-->
<!--		-->
<!--        <div class="item">-->
<!--            <div class="text-center " >-->
<!--                <img src="/images/Hisobot2.jpg" alt="" style="width: 100%; height: 100%">-->
<!--            </div>-->
<!--            <div class="carousel-caption">-->
<!---->
<!--            </div>-->
<!--        </div>-->
<!--		-->
<!--        <div class="item">-->
<!--            <div class="text-center " >-->
<!--                <img src="/images/Hisobot3.jpg" alt="" style="width: 100%; height: 100%">-->
<!--            </div>-->
<!--            <div class="carousel-caption">-->
<!---->
<!--            </div>-->
<!--        </div>-->
<!--    <div class="item active ">-->
<!--        --><?// Yii::app()->runController('site/statistic');?>
<!--    </div>-->
<!--    <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev" style="width: 0%">-->
<!--        <span class="glyphicon glyphicon-chevron-left" style="color: red" aria-hidden="true"></span>-->
<!--        <span class="sr-only">Previous</span>-->
<!--    </a>-->
<!--    <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next" style="width: 0%">-->
<!--        <span class="glyphicon glyphicon-chevron-right" style="color: red" aria-hidden="true"></span>-->
<!--        <span class="sr-only">Next</span>-->
<!--    </a>-->
    <script>
//        $(document).ready(function(){
//            $('.carousel').carousel({
//                interval: 30000
//            });
//
//        });
//        getRegister();
//        function getRegister(){
//            $.ajax({
//                type: "POST",
//                url: "<?php //echo Yii::app()->createUrl('site/register'); ?>//",
//                success: function(data){
//                    $("#registerBlock").html(data);
//
//                }
//            });
//        }
//        setInterval(getRegister, 10000);
    </script>

<!--    </div>-->
<?
            break;
    }
}
?>