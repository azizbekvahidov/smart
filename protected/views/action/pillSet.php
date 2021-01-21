<link rel="stylesheet" href="/resources/css/bootstrap.min.css">
<script src="/resources/js/jquery.min.js"></script>
<style>
    .submitBtn{
        width: 30%;
        margin-right: 3%;
        height: 10vh;
        font-size: 3.5vh;
        margin-bottom: 2vh;
    }
    #illForm{
        margin-left: 4%;
        margin-top: 4vh;
    }
    input{align-content: ;
        display: inline!important;
        width: 85%!important;
    }
    button{
        margin: 0 2.5%;
    }
</style>
<form action="" id="illForm">
    <div class="col-sm-7">
        <div class="form-group">
            <select name="illId" id="ill" class="form-control">
                <option value=""></option>
                <? foreach ($model as $val){?>
                    <option value="<?=$val["illId"]?>"><?=$val["name"]?></option>
                <?}?>
            </select>
        </div>
        <div id="data"  >

        </div>
    </div>
    <div class="col-sm-5">
        <table class="table table-bordered " id="pillTable">
            <thead>
            <tr>
                <th>№</th>
                <th>Сотрудник</th>
                <th>Лекарство</th>
                <th>Кол-во</th>
                <th>Время</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>

</form>

<script>
    $(document).ready(function () {
        $.ajax({
            url: "<?php echo Yii::app()->createUrl('action/setPill'); ?>",
            type:"get",
            success: function (data) {
                data = JSON.parse(data);
                var txt = "";
                $.each(data, function (index, value) {
                    txt += "<tr>" +
                        "<td>" + (index + 1) + "</td>"+
                        "<td>" + (value.surname + value.name) + "</td>"+
                        "<td>" + value.Pillname + "</td>"+
                        "<td>" + value.cnt + "</td>"+
                        "<td>" + value.useTime + "</td>"+
                        "</tr>";
                });
                $("#pillTable tbody").html(txt);
            }
        });
    });
    $(document).on("change","#ill", function () {
        $.ajax({
            type: "POST",
            data: "ill=" + $(this).val(),
            url: "<?php echo Yii::app()->createUrl('action/getPill'); ?>",
            success: function (data) {
                var text = "<div class='form-group'>" +
                    "<button type='button' class='btn btn-success' id='remove'>-</button>" +
                    "<input name='cnt' id='cnt'  type='number' value='1' class='form-control row' />" +
                    "<button type='button' class='btn btn-success' id='add'>+</button>" +

                    "</div>" +
                    "<div class='form-group'>";
                data = JSON.parse(data);
                $.each(data, function( index, value )
                {
                    text += "<button type='button' class='btn btn-info submitBtn' data-id='" + value.pillId + "'>" + value.name + "</button>"
                });
                text += "</div>";
                $("#data").html(text);
            }
        });
    });
    $(document).on('click','.submitBtn', function () {
        var pill = $(this).attr('data-id');
        var formData = $("#illForm").serialize();
        $.ajax({
            url: "<?php echo Yii::app()->createUrl('action/setPill'); ?>",
            type:"post",
            data: formData+"&pillId="+pill+"&id=<?=$id?>",
            success: function (data) {
                $("#illForm")[0].reset();
                $("#data").html("");
                data = JSON.parse(data);
                var txt = "";
                $.each(data, function (index, value) {
                    txt += "<tr>" +
                        "<td>" + (index + 1) + "</td>"+
                        "<td>" + (value.surname + value.name) + "</td>"+
                        "<td>" + value.Pillname + "</td>"+
                        "<td>" + value.cnt + "</td>"+
                        "<td>" + value.useTime + "</td>"+
                        "</tr>";
                });
                $("#pillTable tbody").html(txt);
            }
        });
    });
    $(document).on('click','#add', function () {
        var cnt = parseInt($("#cnt").val());
        cnt++;
        $("#cnt").val(cnt);
    });
    $(document).on('click','#remove', function () {
        var cnt = parseInt($("#cnt").val());
        if(cnt > 1) {
            cnt--;
            $("#cnt").val(cnt);
        }
    });
</script>