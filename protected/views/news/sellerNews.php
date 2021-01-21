<link rel="stylesheet" href="/css/bootstrap.min.css">
<h1 class="text-center" style="padding: 0; margin: 0;">Новости</h1>

<? $cnt = 1;
foreach ($model as $val){?>
    <div class="" style="background: powderblue;    margin: 0 10px 10px;    padding: 1px 15px 15px;">
        <h3><?=$val["header"]?></h3>
        <div class="clearfix"></div>
        <span class="pull-right"><?=date("Y-m-d",strtotime($val["newsDate"]))?></span><br>
        <hr style="margin-top: 5px;margin-bottom: 5px;border-top: 1px solid #000;">
        <div><img src="/upload/news/<?= $val["foto"] ?>" style="width: 100%" alt=""></div>
        <br>
        <div style=""><?=nl2br($val["content"])?></div>
    </div>
<?}?>
