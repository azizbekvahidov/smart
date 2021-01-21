<script>

    function outPhone(str){
        var cnt = parseInt($("#"+str).children("td:nth-child(5)").text());
        $("#"+str).children("td:nth-child(5)").text(cnt-1);
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
            <label for="">В складе</label>
            <input type="text" id="outKey" class="form-control">
            <label for="">рассыпная</label>
            <input type="text" id="looseKey" class="form-control">
            <div class="col-sm-12 hide alert alert-danger" id="alertCont" style="font-size: 20px; font-weight: bold"></div>
            <div class="col-sm-12" style="height: 75%" id="photo"></div>
        </div>

    </div>
</div>

<script>



    var cnt = <?=((!empty($out)) ? count($out)+1 : 1)?>;




    $(document).on("keypress", function (e) {
        if(e.which == 13){
            if($("#outKey").is(":focus")) {
                outPhones();
            }
            if($("#looseKey").is(":focus")){
                loosePhones();
            }
        }
    });

    function outPhones(){
        $("#alertCont").addClass("hide");
        $("#cont table tbody tr").css("background-color","white").css("color","black");
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('stock/outPhones'); ?>",
            data: "val="+$("#outKey").val(),
            success: function(data){
                data = JSON.parse(data);
                $("#outKey").focus().select();
                if(data.res == "notOk") {
                    $("#alertCont").text(data.message).removeClass("hide");
                }

            }
        });
    }

    function loosePhones(){
        $("#alertCont").addClass("hide");
        $("#cont table tbody tr").css("background-color","white").css("color","black");
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('stock/loosePhones'); ?>",
            data: "val="+$("#looseKey").val(),
            success: function(data){
                data = JSON.parse(data);
                $("#looseKey").focus().select();
                if(data.res == "notOk") {
                    $("#alertCont").text(data.message).removeClass("hide");
                }
            }
        });
    }

</script>
