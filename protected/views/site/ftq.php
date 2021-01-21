<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/resources/css/styles.css" />
<style>
    .carousel-inner{
        overflow-y: scroll!important;
        height: 100%;
    }
</style>
<a class='btn hide' href='newyear'>Лоторея на новый год</a>
<div id="carousel-example-generic" class="carousel slide " data-ride="carousel">
    <!-- Indicators
    <ol class="carousel-indicators">
        <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
        <li data-target="#carousel-example-generic" data-slide-to="1"></li>
    </ol>
-->
    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">
        <div class="item active" id="registerBlock">
        </div>
        <div class="item">
            <? Yii::app()->runController('site/produceError');?>
        </div>

        <div class="item">
            <? Yii::app()->runController('site/skdError');?>
        </div>

        <div class="item active ftqBlock">
            <? Yii::app()->runController('site/ftqData');?>
        </div>
        <div class="item">
            <div class="text-center " >
                <img src="/images/Hisobot1.jpg" alt="" style="width: 100%;">
            </div>
            <div class="carousel-caption">

            </div>
        </div>
		
        <div class="item">
            <div class="text-center " >
                <img src="/images/Hisobot2.jpg" alt="" style="width: 100%; ">
            </div>
            <div class="carousel-caption">

            </div>
        </div>
		
        <div class="item">
            <div class="text-center " >
                <img src="/images/Hisobot3.jpg" alt="" style="width: 100%; ">
            </div>
            <div class="carousel-caption">

            </div>
        </div>
        <div class="item">
            <div class="text-center " >
                <img src="/images/model.png" alt="" style="height: 100%">
            </div>
            <div class="carousel-caption">

            </div>
        </div>
		
        <div class="item">
            <? Yii::app()->runController('site/statistic');?>
        </div>

        <div class="item">
            <? Yii::app()->runController('site/getReceptionReport');?>
        </div>
        <?foreach ($model as $val){
			?>

        <div class="item">
            <div class="bg">
                <div class="innerDiv">
                    <div class="confetti"></div>
                    <div class="headTitle"></div>
                    <div class="ballons"></div>
                    <div class="convert"></div>
                    <div class="phones">
                        <img src="/images/birthday/phone.png" alt="">
                        <div class="phoneDisplay">
                            <div><img src="/upload/employee/<?=$val["photo2"]?>" alt="" /></div>
                            <div class="some">
                                <div class="texts">
                                    <h2 style="color: #3df93d; font-size: 3vh"><?=$val["name"]?></h2>
                                </div>
                                <div class="icons">
                                    <div class="statusBar">
                                        <img src="/images/birthday/statusBar.png" alt="">
                                    </div>
                                    <div class="circle">
                                        <div class="call">
                                            <img src="/images/birthday/call.png" alt="">
                                        </div>
                                        <div class="message">
                                            <img src="/images/birthday/message.png" alt="">
                                        </div>
                                        <div class="hangout">
                                            <img src="/images/birthday/hangout.png" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?}?>

        <!--<div class="item">
            <div class="text-center " >
                <img src="/images/new_year.jpg" alt="" style="width: 100%; ">
            </div>
            <div class="carousel-caption">

            </div>
        </div>-->
        <!--<div class="item">
            <div class="bg">
                <div class="innerDiv">
                    <div class="confetti"></div>
                    <div class="headTitle"></div>
                    <div class="ballons"></div>
                    <div class="convert"></div>
                    <div class="phones">
                        <img src="/images/birthday/phone.png" alt="">
                        <div class="phoneDisplay">
                            <div class="some">
                                <div class="texts">
                                    <h2>Shaxlo</h2>
                                </div>
                                <div class="icons">
                                    <div class="statusBar">
                                        <img src="/images/birthday/statusBar.png" alt="">
                                    </div>
                                    <div class="circle">
                                        <div class="call">
                                            <img src="/images/birthday/call.png" alt="">
                                        </div>
                                        <div class="message">
                                            <img src="/images/birthday/message.png" alt="">
                                        </div>
                                        <div class="hangout">
                                            <img src="/images/birthday/hangout.png" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>-->
        <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev" style="width: 0%">
            <span class="glyphicon glyphicon-chevron-left" style="color: red" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next" style="width: 0%">
            <span class="glyphicon glyphicon-chevron-right" style="color: red" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
        <script>
            $(document).ready(function(){
                $('.carousel').carousel({
                    interval: 15000
                });

            });
            getRegister();
            function getRegister(){
                $.ajax({
                    type: "POST",
                    url: "<?php echo Yii::app()->createUrl('site/register'); ?>",
                    success: function(data){
                        $("#registerBlock").html(data);

                    }
                });
            }
            setInterval(getRegister, 10000);
        </script>

    </div>
</div>
