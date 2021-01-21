<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/resources/css/bootstrap.min.css">
<script src="<?php echo Yii::app()->request->baseUrl; ?>/resources/js/jquery.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/resources/js/bootstrap.min.js"></script>
<!--<script src="--><?php //echo Yii::app()->request->baseUrl; ?><!--/resources/js/jquery.fireworks.js"></script>-->
<style>
    @media (min-width:100px) {
        .disp{
            line-height: 114%;
            font-size: 10vh;
            color: #fff;
            text-align: center;
        }
        .font{
             font-size: 11vh;text-align: center;vertical-align: middle;line-height: 112%;
         }
    }
    @media (min-width:1300px) {
        .disp{
            line-height: 114%;
            font-size: 21vh;
            color: #fff;
            text-align: center;
        }
        .font{
            font-size: 16vh;text-align: center;vertical-align: middle;line-height: 112%;
        }
    }
    body{
        background-color: lightcyan;
    }
    .model{
        background-color: lawngreen;
        border-radius: 50px;
        line-height: 109%!important;
    }
    .boldSize{
        font-weight: bold;
    }

    .result{
        width: 100%;
        text-align: center;
        position: absolute;
        bottom:0%;
        z-index: 10000;
        font-size: 38vh;
        color: gold;
        font-weight: bold;
        background: #000;
    }

    .firework { margin:0 auto; width:100%; height:100%;}

    .font2{
        font-size: 6vh;text-align: center;vertical-align: middle;line-height: 112%;
    }
    .cont div{
    }canvas {
         display: block;
         top:-30%;
         background: transparent;
         position: absolute;
     }
</style>
<!--<div class="result hide">150000</div>-->
<!--<div class="cont">-->
    <div class="col-xs-12" style="height: 7%; ">
        <div class="col-xs-4 font2">Model</div>
        <div class="col-xs-4 font2">Plan</div>
        <div class="col-xs-4 font2">Produce</div>
    </div>
    <div class="col-xs-12" style="height: 36%; overflow-y: scroll" id="produceCont">

    </div>
    <div class="col-xs-12" style="height: 24%;border-bottom: 2px solid #000">
        <div class="col-xs-4 font" style="line-height: 150%">ALL</div>
        <div class="col-xs-4 disp boldSize" id="allPlan" style="background-color: forestgreen;"></div>
        <div class="col-xs-4 disp boldSize" id="allProduce" style="background-color: royalblue;"></div>
    </div>
    <div class="col-xs-12" style="height: 25%;">
        <div class="col-xs-8 " style="font-size: 8vh;text-align: center;vertical-align: middle;line-height: 150%;">

            <div class="col-xs-6 font2">Balance</div>
            <div class="col-xs-6 font2">Tact</div>
            <div class="col-xs-6 disp  boldSize" style="background-color: red; " id="balance"></div>
            <div class="col-xs-6 disp  boldSize" style="background-color: navy;" id="tact"></div>
        </div>
        <div class="col-xs-4 " style="font-size: 9vh;text-align: center;vertical-align: middle;line-height: 200%;">
            <div class="font2" style="line-height: 150%"><?=date("d F Y")?> </div>
            <div class="font2 clock" style="line-height: 100%"><?=date("H:i:s")?> </div>
            <div class="font2" style="line-height: 100%"><img style="width: 52%" src="/images/LOGO.png" alt=""></div>
        </div>
    </div>
</div>



<script>
    var toId = "";
    getData();
    function getData(){
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('site/getData'); ?>",
            success: function(data){
                data = JSON.parse(data);

                var text = "";
                $.each(data.model, function (index,value) {
                    text +='<div class="col-xs-12" style="height: 50%;border-bottom: 2px solid #000" id="'+index+'">'+
                        '<div class="col-xs-4 font model boldSize">'+index+'</div>'+
                        '<div class="col-xs-4 font boldSize" style="">'+((data.plan[index] == undefined) ? "0" : data.plan[index])+'</div>'+
                        '<div class="col-xs-4 font boldSize " style="">'+data.produce[index]+'</div>'+
                        '</div>';
                });
                $("#produceCont").html(text);
                $("#allPlan").text(data.all.plan);
                $("#allProduce").text(data.all.produce);
                var now = new Date();
                var range = (Date.now() - (new Date(now.getMonth()+1+"/"+now.getDate()+"/"+now.getFullYear()+' 8:00:00').getTime()))/1000;
                if($("#produceCont").is($("#"+data.scroll))) {
                    if(toId != data.scroll) {
                        $('#produceCont').animate({
                            scrollTop: ($("#" + data.scroll).offset().top)
                        }, 500);
                        console.log(data.scroll);
                    }
                }
                toId = data.scroll;
                console.log(range);
                var tact = parseInt(data.all.produce) - parseInt(parseInt(range/60)*data.all.plan/600);
                $("#tact").text(tact);
                $("#balance").text(parseInt(data.all.plan) - parseInt(data.all.produce));

            }
        });
    }
    setInterval(getData, 5000);

    function clock() {// We create a new Date object and assign it to a variable called "time".
        var time = new Date(),

            // Access the "getHours" method on the Date object with the dot accessor.
            hours = time.getHours(),

            // Access the "getMinutes" method with the dot accessor.
            minutes = time.getMinutes(),


            seconds = time.getSeconds();

        document.querySelectorAll('.clock')[0].innerHTML = harold(hours) + ":" + harold(minutes) + ":" + harold(seconds);

        function harold(standIn) {
            if (standIn < 10) {
                standIn = '0' + standIn
            }
            return standIn;
        }
    }
    setInterval(clock, 1000);
    var start = new Date;

    setInterval(function() {
        $('.Timer').text((new Date - start) / 1000 + " Seconds");
    }, 1000);
    var produce, balance, takt, all;
    var timerId = setInterval(function fireworks() {
        produce = $(".produce").text();
        takt = $(".takt").text();
        all = $(".all").text();
        balance = $(".balance").text();
        if(parseInt(all) == 1500000){

        }
        /*if(parseInt(all)+1 == 1500000){
         fire1();
         fire2();
         $(".result").text(parseInt(all)+1).removeClass("hide");
         setTimeout(function () {
         $(".firework").addClass("hide");
         $(".result").addClass("hide");
         $("canvas").addClass("hide");
         }, 10000);
         }*/

        $(".produce").text(parseInt(produce)+1);
        $(".takt").text(parseInt(takt)+1);
        $(".all").text(parseInt(all)+1);
        $(".balance").text(parseInt(balance)+1);

    }, 2000);


</script>

<script type="application/x-javascript">
    function fireworks(){
    }

//    function fire2() {
//        var b = document.getElementById("div");
//        var c = document.getElementById("canvas");
//        var a = c.getContext("2d");
//        var W = c.width = document.body.clientWidth; //ширина - по размерам клиенской части окна
//        var H = c.height = screen.height; //высота - это не "во весь экран", а больше из-за служебных областей окна
//        var Objects = [];
//        var Colors = "255,0,0;0,255,0;0,0,255;255,255,0;255,0,255;0,255,255;255,255,204;255,204,255;204,255,255".split(";");
//        var timeInterval = 20; //частота обновления, мс
//        var petardColor = "rgb(0,128,0)"; //цвет петарды до взрыва
//        var numRays = 16; //количество лучей <s>чучхе</s> при взрыве
//        var percentAlive = 70; //процент пускаемых, 0-все, 100-никто
//        var petardRadius = 3; //начальный радиус петарды, пикс.
//        var fireRadius = 21; //радиус для вызрыва, пикс.
//        var fireBallRadius = 5; //радиус отдельного огонька при взрыве, пикс.
//
//        DeleteObject = function (obj, t) {
//            if (t) delete Objects[obj];
//            else Objects[Objects.length] = obj;
//        };
//
//        DrawBack = function () {
//            a["globalCompositeOperation"] = "source-over"; //новая фигура визуализируется поверх уже добавленных на холст
//            a.fillStyle = "rgba(0,0,0,.4)";
//            a.fillRect(0, 0, W, H);
//        };
//
//        ColorPath = function (x, y, r, f) {
//            a.beginPath();
//            a.arc(x, y, r, 0, Math.PI * 2, 0);
//            a.fillStyle = f;
//            a.fill();
//        };
//
//        FinalDraw = function (k, x, y, g) {
//            this.k = k;
//            this.x = k ? x : (Math.random() * (W - 200)) + 100;
//            this.y = k ? y : H;
//            this.t = 0;
//            this.j = k ? 20 : 70;
//            this.a = k ? Math.random() * 360 : 240 + Math.random() * 70;
//            this.s = Math.random() * 3 + 2;
//            this.g = g;
//
//            this.thisDraw = function () {
//                this.t++;
//                if (this.k) { //взрыв
//                    f = (Math.PI / 180) * this.a;
//                    this.x += Math.cos(f) * this.s;
//                    this.y += Math.sin(f) * this.s;
//                    a["globalCompositeOperation"] = "lighter";
//                    g = a.createRadialGradient(this.x, this.y, 1, this.x, this.y, fireBallRadius);
//                    g["addColorStop"](0, "rgba(255,255,255,.55)");
//                    g["addColorStop"](1, "rgba(" + this.g + ",.03)");
//                    ColorPath(this.x, this.y, fireRadius, g);
//                }
//                else { //пуск петарды
//                    f = (Math.PI / 180) * this.a;
//                    this.x += Math.cos(f) * 5; //
//                    this.y += Math.sin(f) * 7; //увеличьте для взрывов выше
//                    ColorPath(this.x, this.y, petardRadius, petardColor);
//                }
//            }
//        };
//
//        setInterval(
//            function () {
//                DrawBack();
//                for (q in Objects) {
//                    var obj = Objects[q];
//                    obj.thisDraw();
//                    if (obj.t > obj.j) {
//                        if (!obj.k) {
//                            h = Math.random() * Colors.length | 0;
//                            for (c = 0; c < numRays; c++) DeleteObject(new FinalDraw(1, obj.x, obj.y, Colors[h]));
//                        }
//                        DeleteObject(q, 1);
//                    }
//                }
//                var c = Math.random() * 100;
//                if (c > percentAlive) DeleteObject(new FinalDraw);
//            }, timeInterval);
//    }
</script>
<script>
//    function fire1() {
//        var canvas = $('#canvas')[0];
//        canvas.width = $(window).width();
//        canvas.height = $(window).height();
//        var ctx = canvas.getContext('2d');
//// init
//        ctx.fillStyle = '#000';
//        ctx.fillRect(0, 0, canvas.width, canvas.height);
//// objects
//        var listFire = [];
//        var listFirework = [];
//        var fireNumber = 10;
//        var center = {
//            x: canvas.width / 2,
//            y: canvas.height / 2
//        };
//        var range = 100;
//        for (var i = 0; i < fireNumber; i++) {
//            var fire = {
//                x: Math.random() * range / 2 - range / 4 + center.x,
//                y: Math.random() * range * 2 + canvas.height,
//                size: Math.random() + 0.5,
//                fill: '#fd1',
//                vx: Math.random() - 0.5,
//                vy: -(Math.random() + 4),
//                ax: Math.random() * 0.02 - 0.01,
//                far: Math.random() * range + (center.y - range)
//            };
//            fire.base = {
//                x: fire.x,
//                y: fire.y,
//                vx: fire.vx
//            };
////
//            listFire.push(fire);
//        }
//
//        function randColor() {
//            var r = Math.floor(Math.random() * 256);
//            var g = Math.floor(Math.random() * 256);
//            var b = Math.floor(Math.random() * 256);
//            var color = 'rgb($r, $g, $b)';
//            color = color.replace('$r', r);
//            color = color.replace('$g', g);
//            color = color.replace('$b', b);
//            return color;
//        }
//        (function loop() {
//            requestAnimationFrame(loop);
//            update();
//            draw();
//        })();
//
//        function update() {
//            for (var i = 0; i < listFire.length; i++) {
//                var fire = listFire[i];
////
//                if (fire.y <= fire.far) {
//// case add firework
//                    var color = randColor();
//                    for (var i = 0; i < fireNumber * 5; i++) {
//                        var firework = {
//                            x: fire.x,
//                            y: fire.y,
//                            size: Math.random() + 1.5,
//                            fill: color,
//                            vx: Math.random() * 5 - 2.5,
//                            vy: Math.random() * -5 + 1.5,
//                            ay: 0.05,
//                            alpha: 1,
//                            life: Math.round(Math.random() * range / 2) + range / 2
//                        };
//                        firework.base = {
//                            life: firework.life,
//                            size: firework.size
//                        };
//                        listFirework.push(firework);
//                    }
//// reset
//                    fire.y = fire.base.y;
//                    fire.x = fire.base.x;
//                    fire.vx = fire.base.vx;
//                    fire.ax = Math.random() * 0.02 - 0.01;
//                }
////
//                fire.x += fire.vx;
//                fire.y += fire.vy;
//                fire.vx += fire.ax;
//            }
//            for (var i = listFirework.length - 1; i >= 0; i--) {
//                var firework = listFirework[i];
//                if (firework) {
//                    firework.x += firework.vx;
//                    firework.y += firework.vy;
//                    firework.vy += firework.ay;
//                    firework.alpha = firework.life / firework.base.life;
//                    firework.size = firework.alpha * firework.base.size;
//                    firework.alpha = firework.alpha > 0.6 ? 1 : firework.alpha;
////
//                    firework.life--;
//                    if (firework.life <= 0) {
//                        listFirework.splice(i, 1);
//                    }
//                }
//            }
//        }
//
//        function draw() {
//// clear
//            ctx.globalCompositeOperation = 'source-over';
//            ctx.globalAlpha = 0.18;
//            ctx.fillStyle = '#000';
//            ctx.fillRect(0, 0, canvas.width, canvas.height);
//// re-draw
//            ctx.globalCompositeOperation = 'screen';
//            ctx.globalAlpha = 1;
//            for (var i = 0; i < listFire.length; i++) {
//                var fire = listFire[i];
//                ctx.beginPath();
//                ctx.arc(fire.x, fire.y, fire.size, 0, Math.PI * 2);
//                ctx.closePath();
//                ctx.fillStyle = fire.fill;
//                ctx.fill();
//            }
//            for (var i = 0; i < listFirework.length; i++) {
//                var firework = listFirework[i];
//                ctx.globalAlpha = firework.alpha;
//                ctx.beginPath();
//                ctx.arc(firework.x, firework.y, firework.size, 0, Math.PI * 2);
//                ctx.closePath();
//                ctx.fillStyle = firework.fill;
//                ctx.fill();
//            }
//        }
//    };
</script>