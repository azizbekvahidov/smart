<script>

    function outPhone(str){
        console.log(str);
        var cnt = parseInt($("#"+str).children("td:nth-child(5)").text());
        $("#"+str).children("td:nth-child(5)").text(cnt+1);
    }
</script>
<style>
    .btn-default{

        width: 110%;
        margin: 1%;
        font-size: 100%;
        white-space: normal;
    }
    #actContent{
        height:350px;
        overflow-y: scroll;
    }
</style>
<div>
    <div class="col-sm-12 center">
        <div class="col-sm-4">
            <input type="text" id="empKey" class="form-control">
            <div class="col-sm-12 hide alert alert-danger" id="alertCont" style="font-size: 20px; font-weight: bold"></div>
            <div class="col-sm-12" style="height: 75%" id="box"></div>
        </div>

        <div class="col-sm-4" id="model">
            <table class="table table-bordered">
                <tr>
                    <th>№</th>
                    <th>Модель</th>
                    <th>Цвет</th>
                    <th class="hide">Кол-во</th>
                    <th>Кол-во на отгрузку</th>
                </tr>
                <? if(!empty($produce)){
                    foreach ($produce as $key => $value ){?>
                        <tr id="<?=$value["phoneId"]?>-<?=$value["colorId"]?>">
                            <td><?=$key+1?></td>
                            <td><?=$value["model"]?></td>
                            <td><?=$value["name"]?></td>
                            <td class="hide"><?=$value["cnt"]?></td>
                            <td><?=$value["cnt"]?></td>
                        </tr>
                <?}}?>
            </table>
        </div>

        <div class="col-sm-4" id="cont" style="height: 83vh; overflow-y: scroll">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>№</th>
                    <th>SN</th>
                    <th>BOX</th>
                    <th>Модель</th>
                    <th>Цвет</th>
                </tr>
                </thead>
                <tbody>
                <? $cnt = count($out);
                foreach ($out as $key => $val){?>
                    <script>
                        //outPhone("<?=$val["phoneId"]?>-<?=$val["colorId"]?>");
                    </script>
                    <tr>
                        <td><?=$cnt?></td>
                        <td><?=$val["sn"]?></td>
                        <td><?=$val["box"]?></td>
                        <td><?=$val["model"]?></td>
                        <td><?=$val["name"]?></td>
                    </tr>
                    <? $cnt--;}?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    var box = "";


    var cnt = <?=((!empty($out)) ? count($out)+1 : 1)?>;
    var timerId;
    $(document).ready(function () {
        timers();
        $(document).keyboard({
            language: 'russian,us',
            enterKey: function () {
                //alert('Hey there! This is a callback function example.');
            },
            keyboardPosition: 'bottom',
            directEnter: false
        });

        //$("#empKey").focus();
        $("#cencel").click(function () {
            $("#empKey").val("");
            $("#operatorButtons").addClass("hide");
            $("#brigadirButtons").addClass("hide");
            $("#cont").html("");
            $("#imageDiv").addClass("hide");
            $("#empKey").focus();
        });
    });

    function timers() {
        timerId = setInterval(function() {
            $("#empKey").focus();
        }, 500);
    }


    $(document).on("keypress", function (e) {
        if(e.which == 13){
            Identity();
        }
    });

    function Identity(){
        $("#alertCont").addClass("hide");
        $("#cont table tbody tr").css("background-color","white").css("color","black");
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('stock/identyPhone'); ?>",
            data: "val="+$("#empKey").val(),
            success: function(data){
                data = JSON.parse(data);
                if($.isEmptyObject(data.message)) {
//                    var photo = data.photo.image;
                    var str = "<tr style='background-color: green; color: #fff;'><td>"+cnt+"</td><td>"+data.produce.sn+"</td><td>"+data.produce.box+"</td><td>"+data.produce.model+"</td><td>"+data.produce.name+"</td></tr>";
                    $("#cont table tbody").prepend(str);
                    $("#empKey").select();
                    outPhone(data.produce.phoneId+"-"+data.produce.colorId);
//                    $("#"+data.produce.sn).css("color","red");
                    cnt++;
                }
                else{
                    $("#empKey").select();
                    $("#alertCont").text(data.message).removeClass("hide");
                }
                var text = "<table class='table'>";

//                if(box != data.produce.box){
                    $.each(data.box, function (index, val) {
                        if(index%2 == 0){
                            text += "<tr><td id='"+val.sn+"' style='color: "+((val.produce == 1) ? "black" : "red")+"'>"+val.sn+"</td>";
                        }
                        else{
                            text += "<td id='"+val.sn+"' style='color: "+((val.produce == 1) ? "black" : "red")+"'>"+val.sn+"</td></tr>";

                        }
                    });

                    text += "</table>";
                    $("#box").html(text);
//                }
//                if(data.produce != false)  box = data.produce.box : box = "";
            }
        });
    }

</script>
