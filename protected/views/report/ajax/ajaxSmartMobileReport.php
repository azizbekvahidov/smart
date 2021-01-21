<? $func = new Functions();?>
<div class="col-sm-6">
    &nbsp; <a href="javascript:;" id="export1" class="btn btn-success">Экспорт</a>
    <table class="table-bordered table" id="dataTable">
        <thead>
            <tr>
                <th>№</th>
                <th>Viloyat</th>
                <th>Diler</th>
                <th>Model</th>
                <th>Soni</th>
                <th>Umumiy tarqatilgan telefonlar soni</th>
            </tr>
        </thead>
        <tbody>
        <? foreach ($model as $key => $val){
            $cnt = 0;
            $outPhones = $func->getDillerOutPhones($val["userId"],$from,$to);
            $arrCnt = count($outPhones["data"]);
            if($arrCnt == 0){
                $arrCnt = 1;
            }
            ?>
            <tr>
                <td rowspan="<?=$arrCnt?>"><?=$key+1?></td>
                <td rowspan="<?=$arrCnt?>"><?=$func->getCity($val["point"])?></td>
                <td rowspan="<?=$arrCnt?>"><?=$val["name"]?></td>
                <td><?=$outPhones["data"][0]["model"]?></td>
                <td><?=$outPhones["data"][0]["cnt"]?></td>
                <td rowspan="<?=$arrCnt?>"><?=$outPhones["sum"]?></td>
            </tr>
            <? for($i = 1; $i < $arrCnt; $i++){?>
                <tr>
                    <td><?=$outPhones["data"][$i]["model"]?></td>
                    <td><?=$outPhones["data"][$i]["cnt"]?></td>
                </tr>
            <?}?>
        <?}?>
        </tbody>
    </table>
</div>

<div class="col-sm-6">
    &nbsp; <a href="javascript:;" id="export2" class="btn btn-success">Экспорт</a>
    <table class="table-bordered table" id="dataTable2">
        <thead>
        <tr>
            <th>№</th>
            <th>Viloyat</th>
            <th>Diler</th>
            <th>Model</th>
            <th>Soni</th>
            <th>Umumiy sotilgan telefonlar soni</th>
        </tr>
        </thead>
        <tbody>
        <? foreach ($model as $key => $val){
            $cnt = 0;
            $outPhones = $func->getDillerSellPhones($val["userId"],$from,$to);
            $arrCnt = count($outPhones["data"]);
            if($arrCnt == 0){
                $arrCnt = 1;
            }
            ?>
            <tr>
                <td rowspan="<?=$arrCnt?>"><?=$key+1?></td>
                <td rowspan="<?=$arrCnt?>"><?=$func->getCity($val["point"])?></td>
                <td rowspan="<?=$arrCnt?>"><?=$val["name"]?></td>
                <td><?=$outPhones["data"][0]["model"]?></td>
                <td><?=$outPhones["data"][0]["cnt"]?></td>
                <td rowspan="<?=$arrCnt?>"><?=$outPhones["sum"]?></td>
            </tr>
            <? for($i = 1; $i < $arrCnt; $i++){?>
                <tr>
                    <td><?=$outPhones["data"][$i]["model"]?></td>
                    <td><?=$outPhones["data"][$i]["cnt"]?></td>
                </tr>
            <?}?>
        <?}?>
        </tbody>
    </table>
</div>