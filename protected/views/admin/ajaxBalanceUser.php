<? $func = new Functions(); $pCnt = 0; $sCnt = 0; $planCnt = 0; $ballCnt = 0; $difference = 0?>
<div style="background-color: #fff">
<table>
    <thead>
        <tr>
            <th>Модель</th>
            <th>Прод.</th>
            <th>План</th>
            <th>Балл</th>
            <th>Разн.</th>
        </tr>
    </thead>
    <tbody>
    <?foreach ($model as $val){
        $plan = $func->getPlan($userId,$val["phoneId"],$month);
        $sold = $func->getSold($userId,$val["phoneId"],$month);
        $ball = $func->getBall($val["phoneId"],$sold,$plan,$userId);
        ?>
        <tr>
            <td><?=$val["model"]?></td>
            <td><?=$sold?></td>
            <td><?=$plan?></td>
            <td><?=$ball?></td>
            <td style="color:red;"><?=$sold-$plan?></td>
        </tr>
    <? 
    $sCnt = $sCnt + $sold;
    $planCnt = $planCnt + $plan;
    $ballCnt = $ballCnt + $ball;
    $difference = $difference + ($sold-$plan);
    
    }?>
    </tbody>
    <tfoot>
        <tr>
            <th>Итого</th>
            <th><?=$sCnt?></th>
            <th><?=$planCnt?></th>
            <th><?=$ballCnt?></th>
            <th style="color:red;"><?=$difference?></th>
        </tr>
    </tfoot>
</table></div>