<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/resources/css/bootstrap.css">
<? $pos = new lineFunc()?>
<style>
    #line1{
        width: 32vh;
        height: 6vh;
        background-color: #1e9880;
        position: absolute;
        top: 10%;
        left: 9%;
        border: 0.2vh solid;
    }
    #line2{
         width: 90vh;
         height: 6vh;
         background-color: #1e9880;
         position: absolute;
         top: 40%;
         left: 9%;
         border: 0.2vh solid;
     }

    #line3{
        width: 84vh;
        height: 6vh;
        background-color: #1e9880;
        position: absolute;
        top: 70%;
        left: 9%;
        border: 0.2vh solid;
    }
    .line div{
        float: left;
        margin: 0;
        padding: 0;
        position: relative;
        border: 0.2vh solid;
        display: inline-block;
        width: 5.8vh;
        height: 5vh;
        background-color: #ccc;
    }
    .line a{
        font-size: 1vh;
        text-decoration: none;
        color: #000;
    }
    .topPos{
        top: -5vh;
        border-radius: 1vh 1vh 0 0;;
    }
    .bottomPos{
        bottom: -0.7vh;
        border-radius: 0 0 1vh 1vh;
    }
</style>
<input type="hidden" value="<?=$id?>" id="posId">
<div style="height: 70vh">
    <div class="row">
        <div id="line1" class="line">
            <?  for ($i = 2; $i <= 10; $i+=2){?>
                <a href="#" class="linePos" data-id="<?=$i?>">
                    <div class="topPos"><?=$pos->getPositionName($i,$modelId)?></div>
                </a>
            <?}?>
            <?  for ($i = 1; $i <= 10; $i+=2){?>
                <a href="#" class="linePos" data-id="<?=$i?>">
                    <div class="bottomPos"><?=$pos->getPositionName($i,$modelId)?></div>
                </a>
            <?}?>
        </div>
    </div>
    <div class="row">
        <div id="line2" class="line">
            <?  for ($i = 12; $i <= 40; $i+=2){?>
                <a href="#" class="linePos" data-id="<?=$i?>">
                    <div class="topPos"><?=$pos->getPositionName($i,$modelId)?></div>
                </a>
            <?}?>
            <?  for ($i = 11; $i <= 40; $i+=2){?>
                <a href="#" class="linePos" data-id="<?=$i?>">
                    <div class="bottomPos"><?=$pos->getPositionName($i,$modelId)?></div>
                </a>
            <?}?>
        </div>
    </div>

    <div class="row">
        <div id="line3" class="line">
            <?  for ($i = 42; $i <= 68; $i+=2){?>
                <a href="#" class="linePos" data-id="<?=$i?>">
                    <div class="topPos"><?=$pos->getPositionName($i,$modelId)?></div>
                </a>
            <?}?>
            <?  for ($i = 41; $i <= 68; $i+=2){?>
                <a href="#" class="linePos" data-id="<?=$i?>">
                    <div class="bottomPos"><?=$pos->getPositionName($i,$modelId)?></div>
                </a>
            <?}?>
        </div>
    </div>
</div>