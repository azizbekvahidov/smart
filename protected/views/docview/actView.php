<? $cnt = 1; $func = new Functions();?>
<h1 class="col-lg-7">Акт № <?=$act["code"]?> - <?=$act["actNum"]?> от <?=date("d.m.Y",strtotime($act["actDate"]))?></h1>
<h1 class="col-lg-5">
</h1>
<table class="table-bordered table text-center ">
    <thead>
    <tr>
        <th rowspan="2" class="text-center">№</th>
        <th rowspan="2" class="text-center">Наименование сырья и материалов</th>
        <th rowspan="2" class="text-center">ед.изм.</th>
        <th colspan="2" class="text-center">Кол-во</th>
        <th rowspan="2" class="text-center">Причина</th>
        <th rowspan="2" class="text-center">Сервис</th>
        <th rowspan="2" class="text-center">Утилизация</th>
    </tr>
    <tr>
        <th class="text-center"><?=($act["departmentId"] == 11) ? "Перед прод." : "Завод. брак"?></th>
        <th class="text-center">Произ. брак</th>
    </tr>
    </thead>
    <tbody>
    <? $skd = 0; $produce = 0; $util = 0; $service = 0; ?>
    <?foreach ($model as $val){
        $skdCnt = ($val["cause"] == "SKD brak") ? $val["cnt"] : 0;//$func->getSKDCnt($val["spareId"],$val["phoneId"],$val["actregisterId"]);
        $skd = $skd + $skdCnt;
        $produceCnt = ($val["cause"] == "Ishlab chiqarish brak") ? $val["cnt"] : 0;//$func->getProduceCnt($val["spareId"],$val["phoneId"],$val["actregisterId"]);
        $produce = $produce + $produceCnt;
        $util = $util + $val["util"];
        $service = $service + $val["service"];
        ?>
        <tr id="<?=$val["actdetailId"]?>">
            <td class="text-center"><?=$cnt?></td>
            <td><?=$val["model"]?> <?=$val["name"]?></td>
            <td class="text-center">шт</td>
            <td class="text-center skdCnt">
                <?=($skdCnt != 0) ? $skdCnt : 0?>
            </td>
            <td class="text-center produceCnt">
                <?=($produceCnt!= 0) ? $produceCnt : 0?>
            </td>
            <td ><?=$val["desc"]?><?//=$func->getActDesc($val["spareId"],$val["phoneId"],$val["actregisterId"])?></td>
            <td>
                <?=$val["service"]?>
            </td>
            <td>
                <?=$val["util"]?>
            </td>
        </tr>
        <?$cnt++;}?>
    <tr>
        <th colspan="3">Итого</th>
        <th><?=$skd?></th>
        <th><?=$produce?></th>
        <th></th>
        <th><?=$service?></th>
        <th><?=$util?></th>
    </tr>
    </tbody>
</table>
