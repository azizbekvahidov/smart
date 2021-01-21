<link rel="stylesheet" type="text/css" href="/resources/css/bootstrap.min.css">
<style>
    th,td{
        border:1px solid #000!important;
    }
    th{
        background-color: #ccc;
    }
</style>
<? $func = new Functions();?>
<div>
    <div class="text-center row">
        <h1>Протокол собрания № <?=$model["protokolNumber"]?></h1>
        <h3>Тема: <?=$model["protokolTheme"]?></h3>
    </div>
    <div class="row">
        <div class="col-sm-1" style="float: left"></div>
        <div class="col-sm-2 " style="float: left">Дата: <?=$model["protokolDate"]?></div>
        <div class="col-sm-2" style="float: left">Время: <?=date("H:i",strtotime($model["protokolStart"]))?>-<?=date("H:i",strtotime($model["protokolEnd"]))?></div>
        <div class="col-sm-4" style="float: left"></div>
        <div class="col-sm-2" style="float: left">Место: <?=$model["protokolPlace"]?></div>
    </div>
    <table class="table table-bordered" style="border-collapse: collapse;">
        <tr>
            <th>Тип собрания</th>
            <td><?=$model["protokolType"]?></td>
            <th>Руководитель</th>
            <td>Сагдуллаев Абдулла</td>
            <th>Стенографист</th>
            <td><?=$model["protokolWriter"]?></td>
        </tr>
        <tr>
            <th>Участники</th>
            <td colspan="5">
                <? $participants = explode(',',$model["participants"]);?>
                <?foreach ($participants as $val){
                    if($val == ""){
                        echo "";
                    }else{
                        echo $func->getEmpName($val).";  ";
                    }
                }?>
            </td>
        </tr>
    </table>
    <div style="border-bottom: 1px solid #000;">Повестка дня</div>
    <ol>
        <?foreach ($modelList as $key => $val){?>
            <li><?=$val["meetQuestion"]?></li>
        <?}?>
    </ol>
    <div style="border-bottom: 1px solid #000;"></div>
    <br>
    <?foreach ($modelList as $key => $val){ $emp = explode(',',$val["employeeId"]);?>
        <table class="table table-bordered">
            <tr>
                <th>Вопрос повестки дня:</th>
                <td colspan="4"><?=$key+1?> <?=$val["meetQuestion"]?></td>
            </tr>
            <tr>
                <td colspan="4"></td>
            </tr>
            <tr>
                <th colspan="2" >Решение</th>
                <th>Ответсвенное лицо</th>
                <th>Срок исполнения</th>
            </tr>
            <tr>
                <td colspan="2"><?=$val["solve"]?></td>
                <td><? foreach ($emp as $e){ if($e != "") echo $func->getEmpName($e).","; }?></td>
                <td><?=$val["deadline"]?></td>
            </tr>
        </table>
    <?}?>
</div>
