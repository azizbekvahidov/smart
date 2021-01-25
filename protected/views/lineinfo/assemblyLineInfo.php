<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/resources/css/bootstrap.css">
<script src="<?php echo Yii::app()->request->baseUrl; ?>/resources/js/jquery.min.js"></script>
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
        width: 170vh;
        height: 15vh;
        background-color: #1e9880;
        position: absolute;
        top: 32%;
        left: 2%;
        border: 0.2vh solid;
    }
    #model{

        left: 3%;
        position: relative;
        font-size: 10vh;
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
    .line a > div{
        float: left;
        margin: 0;
        padding: 0;
        position: relative;
        border: 0.2vh solid;
        display: inline-block;
        width: 11.2vh;
        height: 18vh;
        background-color: #ccc;
        padding: 5px;
    }
    .line a{
        font-size: 1.2vh;
        text-decoration: none;
        color: #000;
    }
    .topPos{
        top: -18vh;
        border-radius: 1vh 1vh 0 0;
    }
    .bottomPos{
        bottom: 3.3vh;
        border-radius: 0 0 1vh 1vh;
    }
    .active{
        background-color: green!important;
    }
    .deactive{
        background-color: red!important;
    }
    .posEmp{
        position: absolute;
        bottom: 0;
    }
    .font2{
        font-size: 25vh;
    }
    .font3{
        font-size: 8vh;
    }
    .clock{
        position: absolute;
        bottom: 6vh;
        right: 1vh;
    }
    .assemblyCnt{
        position: absolute;
        bottom: 1vh;
        left: 1vh;
    }
    .temperature{
        position: absolute;
        top: 1vh;
        left: 1vh;
    }
</style>
<div class="constainer">
    <div class="row">
        <h1 id="model">

        </h1>
    </div>
    <div style="height: 70vh">
        <div class="row">
            <div id="line2" class="line">
                <?  for ($i = 12; $i <= 40; $i+=2){?>
                    <a href="#" class="linePos" id="pos-<?=$i?>">
                        <div    class="topPos ">
                            <div></div>
                            <div class="posEmp"></div>
                        </div>
                    </a>
                <?}?>
                <?  for ($i = 11; $i <= 40; $i+=2){ ?>
                    <a href="#" class="linePos" id="pos-<?=$i?>">
                        <div class="bottomPos ">
                            <div></div>
                            <div class="posEmp"></div>
                        </div>
                    </a>
                <?}?>
            </div>
        </div>

    </div>
</div>
<div class="font2 assemblyCnt">0</div>
<div class="font3 temperature">Temperature: <span id="temp">-</span> &#186;C &nbsp; Humidity: <span id="hum">-</span>%</div>
<div class="font2 clock" style="line-height: 100%"><?=date("H:i:s")?> </div>
<script>
    var curPhone = 0;
    function getEmpPos() {
        $.ajax({
            type: "GET",
            url: "<?php echo Yii::app()->createUrl('lineinfo/getEmpPos'); ?>",
            success: function (data) {
                data = JSON.parse(data);
                if(curPhone != data.phone.model){

                    $(".linePos>div div").text("");
                    $(".linePos>div").removeClass("active");
                    $(".linePos>div").removeClass("deactive");
                    curPhone = data.phone.model;
                }
                for (let i of data.linePos){
                    let str = i.linePosition;
                    if(str.search(',') != -1){
                        let list = str.split(',');
                        for (let j of list){
                        let id = "#pos-"+j+" div";
                        $(id).addClass("deactive");
                        $(id).children("div:first-child").text(i.name);

                        }

                    }
                    else{
                        let id = "#pos-"+str+" div";
                        $(id).addClass("deactive");
                        $(id).children("div:first-child").text(i.name);
                    }
                }
                for (let i of data.model){
                    let id = "#pos-"+i.linePosition+" div";
                    $(id).children("div.posEmp").text("");
                    $(id).removeClass("deactive");
                    $(id).addClass("active");
                    $(id).children("div.posEmp").text(i.surname+" "+i.eName);
                }
                $("#model").text(data.phone.model);
                $(".assemblyCnt").text(data.count);
                $("#temp").text(data.temp);
                $("#hum").text(data.hum);


            }
        });
    }

    function clock() {// We create a new Date object and assign it to a variable called "time".
        var time = new Date(),

            // Access the "getHours" method on the Date object with the dot accessor.
            hours = time.getHours(),

            // Access the "getMinutes" method with the dot accessor.
            minutes = time.getMinutes(),


            seconds = time.getSeconds();

        document.querySelectorAll('.clock')[0].innerHTML = "|  " + harold(hours) + ":" + harold(minutes) + ":" + harold(seconds);

        function harold(standIn) {
            if (standIn < 10) {
                standIn = '0' + standIn
            }
            return standIn;
        }
    }
    setInterval(clock, 1000);

    $(document).ready(function () {
        getEmpPos();
        setInterval(function(){
           getEmpPos();
       },2000);
        // setTimeout(getEmpPos(),2000);
    });
</script>