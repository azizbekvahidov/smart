<link rel="stylesheet" href="/resources/css/bootstrap.min.css">
<h1 class="text-center"><?=$day?></h1>
<h3 class="text-center">План</h3>
<table class="table-bordered table">
    <tr>
        <th> Модель: </th>
        <th> Кол-во: </th>
    </tr>
<?php
$model = "";
foreach ($plan as $item) {
    $model = "";
    $allCnt = 0;
    if($model != $item["model"]){
        $allCnt = 0;?>
        <tr>
           <td><?=$item["model"]?></td>  <td><?=$item["cnt"]?></td>
        </tr>
    <?
        $allCnt = $allCnt + $item["cnt"];
    }
    else{
        $allCnt = $allCnt + $item["cnt"];?>
    <tr>
           <td> Кол-во: <?=$item["cnt"]?></td>  <td> Обшее кол-во: <?=$allCnt?></td>
    </tr>

    <?}
    $model = $item["model"];
}
$model = "";?>
</table>
<h3 class="text-center">Произведено</h3>

<table class="table-bordered table">
    <tr>
        <th> Модель: </th>
        <th> Цвет: </th>
        <th> Кол-во: </th>
    </tr>
<?
$allCnt = 0;
$itog = 0;
foreach ($models as $item) {
    if($model != $item["model"]){
        $allCnt = 0;?>
        <tr>
            <td><?=$item["model"]?></td>  <td><?=$item["name"]?></td> <td><?=$item["cnt"]?></td>
        </tr>
        <?
        $allCnt = $allCnt + $item["cnt"];
    }
    else{
        $allCnt = $allCnt + $item["cnt"];?>
        <tr>
            <td><?=$item["model"]?></td>  <td><?=$item["name"]?></td> <td><?=$item["cnt"]?></td>
        </tr>
        <tr>
            <td colspan="2"> Обшее кол-во: </td> <td><?=$allCnt?></td>
        </tr>
        <?
    }
    $itog = $itog + $allCnt;
    $model = $item["model"];
}

$text .= "   Итого: ".$itog."\n";
?>
</table>
<h3 class="text-center">Регистрированные браки</h3>
<table class="table-bordered table">
    <tr>
        <th> Модель: </th>
        <th> Причина: </th>
        <th> Кол-во: </th>
    </tr>
<?
$model = "";
$allCnt = 0;
foreach ($brak as $item) {
    if($model != $item["model"]){
        $allCnt = 0;?>
        <tr>
            <td><?=$item["model"]?></td>  <td><?=$item["cause"]?></td> <td><?=$item["cnt"]?></td>
        </tr>
        <?
        $allCnt = $allCnt + $item["cnt"];
    }
    else{
        $allCnt = $allCnt + $item["cnt"];?>
        <tr>
            <td><?=$item["model"]?></td>  <td><?=$item["cause"]?></td> <td><?=$item["cnt"]?></td>
        </tr>
        <tr>
            <td colspan="2"> Обшее кол-во: </td> <td><?=$allCnt?></td>
        </tr>
        <?

    }
    $model = $item["model"];
}?>
</table>
