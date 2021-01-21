<link rel="stylesheet" href="/css/bootstrap.min.css">
<script src="/js/jquery.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<style>
    #cartBody{
        overflow-y: scroll;
        height: 400px;
    }
    .panel-body{
        padding: 7px;
    }
</style>
<div class=" " style="position: fixed; width: 100%; z-index: 2">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="pull-left">Ваш балл: <span id="ballDiv"><?=$user["ball"]?></span></div>
            <div class="pull-right">
                <button data-toggle="modal" data-target="#cartModal" class="btn btn-success">Корзина <i class="glyphicon glyphicon-shopping-cart"></i> <?=$cart?> (<span id="cart">2</span>)</button>
            </div>
        </div>
    </div>
</div>
<? $cnt = 1;
foreach ($model as $val){?>
    <div class="" style="background: powderblue; margin: 0 10px 10px; padding: 15px 15px 0 15px;">
        <div class="col-sm-12">
        <h3><?=$val["name"]?></h3>
        </div>
        <hr style="margin-top: 5px;margin-bottom: 5px;border-top: 1px solid #000;">
        <div class="" style="float: left; width: 40%"><img src="/upload/shop/<?= $val["photo"] ?>" style="width: 100%" alt=""></div>
        <div class="" style="float: right; width: 60%">
            <div class="panel panel-success" style="font-size: 70%">
                <div class="panel-heading">Описание</div>
                <div class="panel-body">
                    <div class="panel panel-danger"><div class="panel-body">Цена: <?=$val["ball"]?> балл</div></div>
                    <div class="panel panel-info"><div class="panel-body"><?=nl2br($val["content"])?></div></div>
                    <div><button class="btn btn-danger pull-right addCart" onclick="addCart(<?=$val["shopproductId"]?>,<?=$val["ball"]?>)">В корзину</button></div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="clearfix"></div>
<?}?>

<script>
    $(document).ready(function () {
        getCart("minus");
    });
    function getCart(action,ball = 0) {
        var curBall = parseFloat($("#ballDiv").text());
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('action/getCart'); ?>",
            data: "id=" +<?=$user["userId"]?>,
            success: function (data) {
                data = JSON.parse(data);
                var text = "";
                var cntBall = 0;
                $.each(data, function (index, val) {
                    cntBall = parseFloat(cntBall) + parseFloat(val.ball);
                    text += '<div class="detail_' + val.shopdetailId + '" style="background: powderblue; margin: 0 10px 10px; padding: 15px 15px 0 15px;">\
                    <div class="" style="float: left; width: 40%"><img src="/upload/shop/' + val.photo + '" style="width: 100%" alt=""></div>\
                    <div class="" style="float: right; width: 60%">\
                    <div class="panel panel-success" style="">\
                    <div class="panel-body">\
                    <div class="panel panel-info"><div class="panel-body">' + val.name + '</div></div>\
                    <div><button class="btn btn-danger pull-right deleteCart" onclick="deleteCart(' + val.shopdetailId + ',' + val.ball + ')">Удалить <i class="glyphicon glyphicon-trash"></i></button></div>\
                </div>\
                </div>\
                </div>\
                <div class="clearfix"></div>\
                    </div>';
                });
                $("#cartBody").html(text);
                if (data.length == 0) {
                    $("#cartBody").html("<h1 style='text-align: center'>Корзина пуста</h1>");
                }
                if (ball != 0) {
                    setCurBall(curBall, ball, action);
                }
                else {
                    setCurBall(curBall, cntBall, action);
                }
                $("#cart").text(data.length);
            }
        });
    }

    function setCurBall(curBall,cntBall,action) {
        if(action == "plus"){
            $("#ballDiv").text(curBall+cntBall);
        }
        if(action == "minus"){
            $("#ballDiv").text(curBall-cntBall);
        }
    }

    function addCart(prodId,ball){
        var curBall = parseFloat($("#ballDiv").text());
        if(curBall - ball <= 0){
            $("#alertModal").modal("show");
        }
        else {
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('action/addCart'); ?>",
                data: "id="+<?=$user["userId"]?>+"&prodId="+prodId,
                success: function (data) {
                    getCart("minus", ball);
                }
            });
        }
    }

    function deleteCart(prodId,ball){
        var name = ".detail_"+prodId;
        console.log(ball);
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('action/deleteCart'); ?>",
            data: "id="+prodId,
            success: function(data){
                getCart("plus",ball);
                $(name).remove();
            }
        });
    }

    $(document).on("click","#closeCart", function () {
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('action/cartClose'); ?>",
            data: "id="+<?=$user["userId"]?>,
            success: function(data){
                getCart("minus");
            }
        });
    });
</script>

<div class="modal fade" id="cartModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Корзина</h4>
            </div>
            <div class="modal-body" id="cartBody">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                <button type="button" class="btn btn-primary" id="closeCart" data-dismiss="modal">Оформить заказ</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" id="alertModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body" id="slertBody">
                Незватает баллов
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Ок</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
