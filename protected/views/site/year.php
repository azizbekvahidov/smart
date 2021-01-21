<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/resources/css/styles.css" />
<link rel="stylesheet" href="/resources/css/bootstrap.css">
<script src="/resources/js/jquery.min.js"></script>
<style>
    body{
        background-image: url("/images/new yearcopy.jpg");
        background-repeat: no-repeat;
        background-size: cover;
    }
    #number{

        font-size: 16vh;
        font-weight: bold;
        position: absolute;
        left: 49%;
        width: 27vh;
        height: 27vh;
        top: 1%;
        color: red;
        background-color: lawngreen;
        border-radius: 17vh;
        padding: 2vh 0;
    }
    #cnt{

        position: absolute;
        left: 3vh;
        font-size: 7vh;
        bottom: 8vh;
        color: white;
    }
    #cnt > span{
        font-weight: bold;
        color: white;
        font-size: 14vh;
    }
    #photo{
        margin-top: 5vh;
        right: 8vh;
        position: absolute;
    }
    #photo > div{
        width: 50vh;
        position: relative;
        left: 0vh;
        text-align: center;
        top: 17vh;
        background: rgba(255,255,255,0.7);
        border-radius: 5px;
        color: black;
    }
    #photo img{
        width: 40vh;
        position: relative;
        top: 13vh;
        right: 0vh;
    }
</style>
<audio id="myAudio">
    <source src="/upload/zvuk.mp3" type="audio/mpeg">
    Your browser does not support the audio element.
</audio>
<div id="cnt">Всего участников <span><?=$cnt?></span></div>
<div style="width: 50%" class="text-center">
    <div id="number"></div>
    <div id="photo"></div>
</div>
<div class="text-center" style="width: 50%; position: relative; left: 50vh; bottom: 2vh; visibility: hidden" >
    <button class="btn btn-success" style="font-size: 10vh" id="generate">Сгенерировать</button>
</div>
<div class="hide" >
    <ul id="generated"></ul>
</div>
<script>
    var x = document.getElementById("myAudio");
    var users = [];
    $(document).on("click","body", function () {
        generated();
    });

    function generated(){
        x.play();
        $("#photo").html("");
        var res = 0;
        var emp = [0];
        var last = 80000;
        for (var i = 0; i <= last; i++) {
            res = getRand();
            if(i === last){
                if($.inArray(res,users) !== -1){
                    res = getRand();
                }
                else {
                    getWinner(res);
                    users[res] = res;
                    $("#generated").append("<li id='"+res+"'>"+res+"</li>");
                }
            }
        }
    }

    function getRand(){
        var numb = Math.floor(Math.random() * (<?=$last?> - <?=$first?>)) + <?=$first?>;
        setTimeout(function () {

            $("#number").html(numb);
        },5);

        return numb
    }
    function getWinner(id) {
        console.log(users);
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('site/getWinner'); ?>",
            data: "id="+id,
            success: function(data) {
                data = JSON.parse(data);
                if(Object.keys(data).length === 0){
                    var res = getRand();

                    getWinner(res);
                    users[res] = res;
                    $("#generated").append("<li id='"+res+"'>"+res+"</li>");
//                    setTimeout(function () {
//                        $("#photo").html("<h1>Этот сотрудник уволен</h1>");
//                    },1500);
                }
                else{
                    setTimeout(function () {
                        $("#photo").html("<div><span style=' font-weight: bold; font-size: 6vh; padding: 1vh 2vh; border-radius: 3vh; display: inline-block'>"+data.surname+" </span><br><span style=' font-weight: bold; font-size: 6vh; padding: 1vh 2vh; border-radius: 3vh; display: inline-block'>"+data.name+"</span></div><img  src='/upload/employee/"+data.photo+"' />");
                    },2000);
                }
            }
        });
    }
</script>