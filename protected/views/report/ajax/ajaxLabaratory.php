<? $func = new Functions(); $allFullCnt = 0; $allSkdCnt = 0; $allFacCnt = 0; $plan = 0; $produce = 0; $checked = 0;?>

<div class="col-sm-8">
    <table class="table table-bordered ">
        <thead>
        <tr class="bg-success" style="text-align: center">
            <td rowspan="2">№</td>
            <td rowspan="2">Sana</td>
            <td rowspan="2">Model</td>
            <td rowspan="2">Status</td>
            <td rowspan="2">Nuqsonlar topilgan bo'lim</td>
            <td colspan="3">Nuqsonlar soni</td>
            <td rowspan="2">Nuqson izohi</td>
            <td rowspan="2">sabab</td>
            <td rowspan="2">Nuqson sababi </td>
            <td rowspan="2">O'zgartirilgan ehtiyot qismlari</td>

        </tr>
        <tr class="bg-success">
            <td >Ishlab chiqarishdagi nuqsonlar </td>
            <td >SKD nuqsonlari</td>
            <td >Umumiy soni</td>

        </tr>
        </thead>
        <tbody>
        <? foreach ($model as $keys => $value){
            $allFacCnt = 0;
            $allSkdCnt = 0;
            $allFullCnt = 0;
            $otk1 = 0;
            $otk2 = 0;
            foreach ($value as $key => $val){
                if($val["login"] == "OTK1")
                    $otk1++;
                if($val["login"] == "OTK2")
                    $otk2++;
                $facCnt = $func->getfacError($val['registerId']);
                $skdCnt = $func->getSkdError($val['registerId']);
                $fullCnt = $facCnt + $skdCnt;

                $allFacCnt = $allFacCnt + $facCnt;
                $allSkdCnt = $allSkdCnt + $skdCnt;
                $allFullCnt = $allFullCnt + $fullCnt;
                ?>
                <tr  class="<?=$val["registerId"]?>">
                    <td><?=$key+1?></td>
                    <td><?=date("Y-m-d", strtotime($val['errorOtkDate']))?></td>
                    <td><?=$keys?></td>
                    <td><?=(($val["status"] == 1) ? "Bitgan" : "Bitmagan")?></td>
                    <td><?=$val["login"]?></td>

                    <td><?=$facCnt?></td>
                    <td><?=$skdCnt?></td>
                    <td><?=$fullCnt?></td>

                    <td><?=$val["ceName"]?></td>
                    <td><?=$val["name"]?></td>
                    <td class="solve"><?=($val["solve"] != "") ? $val["solve"] : $func->getSpare($val["spareId"])." almashtirildi"?></td>
                    <td><?=(($val["spareId"] == 0) ? "Zapchast ishlatilmadi" : "Zapchast ishlatildi")?></td>
                </tr>
            <?}?>
            <tr class="bg-danger">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>OTK1 - <?=$otk1?>, OTK2 - <?=$otk2?></td>
                <td><?=$allFacCnt?></td>
                <td><?=$allSkdCnt?></td>
                <td><?=$allFullCnt?></td>

                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        <?}?>
    </table>
</div>

<div class="col-sm-4">
    <table class="table table-bordered ">
        <thead>
        <tr class="bg-success">
            <td >Ishlab chiqarishdagi nuqsonlar </td>
            <td >Uchastok</td>
            <td >Umumiy soni</td>
        </tr>
        </thead>
        <tbody>
        <? foreach ($model1 as $keys => $value){?>
            <tr class="bg-success">
                <td colspan="3"><?=$keys?></td>
            </tr>
            <?foreach ($value as $key => $val){
                ?>
                <tr  class="<?=$val["registerId"]?>">
                    <td><?=$val["name"]?></td>
                    <td><?=$val["login"]?></td>
                    <td><?=$val["cnt"]?></td>
                </tr>
            <?}?>
        <?}?>
    </table>
</div>

<div class="col-sm-4">
    <table class="table table-bordered ">
        <thead>
        <tr class="bg-success">
            <td >Ishlab chiqarishdagi nuqsonlar </td>
            <td >Umumiy soni</td>
        </tr>
        </thead>
        <tbody>
            <?foreach ($model2 as $key => $val){
                ?>
                <tr  class="<?=$val["registerId"]?>">
                    <td><?=$val["name"]?></td>
                    <td><?=$val["cnt"]?></td>
                </tr>
            <?}?>
    </table>
</div>
<script>
    $(document).ready(function(){
        $('#exportProduce').click(function(){
            $('#produceTable').table2excel({
                name: "Excel Document Name"
            });
        });
    });

</script>
</script>
