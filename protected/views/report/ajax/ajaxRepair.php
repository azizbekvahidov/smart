<? $func = new Functions(); $allFullCnt = 0; $allSkdCnt = 0; $allFacCnt = 0; $plan = 0; $produce = 0; $checked = 0;?>

<? if(empty($plans)){?>
<form class="form-group col-sm-2" id="produceForm">
    <input type="text" id="plan" class="form-control" placeholder="План">
    <input type="text" id="produce" class="form-control" placeholder="Производства">
    <input type="text" id="checked" class="form-control" placeholder="Проверенные">
    <input type="button" id="addProd" value="Добавить">
</form>
<?} else{
    $plan = $plans["plan"];
    $produce = $plans["produce"];
    $checked = $plans["checked"];
}  ?>

<table class="table table-bordered ">
    <thead>
        <tr class="bg-success" style="text-align: center">
            <td rowspan="2">№</td>
            <td rowspan="2">код</td>
            <td rowspan="2">Sana</td>
            <td rowspan="2">Model</td>
            <td colspan="2">Telefonlar soni</td>
            <td rowspan="2">Kategoriya</td>
            <td rowspan="2">Status</td>
            <td rowspan="2">Nuqsonlar topilgan bo'lim</td>
            <td colspan="3">Nuqsonlar soni</td>
            <td rowspan="2">%</td>
            <td rowspan="2">Nuqson izohi</td>
            <td rowspan="2">sabab</td>
            <td rowspan="2">Nuqson sababi </td>
            <td rowspan="2">Nuqson takrorlanmasligi uchun bajarilgan ish</td>
            <td rowspan="2">Telefon xolati</td>
            <td rowspan="2">O'zgartirilgan ehtiyot qismlari</td>
            <td rowspan="2">Rasm </td>
            <td rowspan="2"> </td>

        </tr>
        <tr class="bg-success">
            <td >Yig'ilgan telefonlar soni</td>
            <td >Tekshirilgan telefonlar soni</td>
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
            foreach ($value as $key => $val){
                $repairError = $func->getRepairError($val["errorIdRepair"]);
                $facCnt = $func->getfacError($val['registerId']);
                $skdCnt = $func->getSkdError($val['registerId']);
                $fullCnt = $facCnt + $skdCnt;

                $allFacCnt = $allFacCnt + $facCnt;
                $allSkdCnt = $allSkdCnt + $skdCnt;
                $allFullCnt = $allFullCnt + $fullCnt;
                ?>
                <tr  class="<?=$val["registerId"]?>">
                    <td><?=$key+1?></td>
                    <td><?=$val["registerCode"]?></td>
                    <td><?=date("Y-m-d", strtotime($val['errorOtkDate']))?></td>
                    <td><?=$keys?></td>
                    <td><?=$produce?></td>
                    <td><?=$checked?></td>
                    <td>Ish jarayoni</td>
                    <td><?=(($val["status"] == 1) ? "Bitgan" : "Bitmagan")?></td>
                    <td><?=$val["login"]?></td>

                    <td><?=$facCnt?></td>
                    <td><?=$skdCnt?></td>
                    <td><?=$fullCnt?></td>
                    <td><?=number_format($fullCnt*100/(($produce == 0) ? 1 : $produce),2,","," ")?>%</td>

                    <td><?=($repairError["desc"] == "") ? $val["ceName"] : $repairError["name"]?></td>
                    <td><?=($repairError["desc"] == "") ? $val["name"] : $repairError["desc"]?></td>
                    <td class="solve"><?=($val["solve"] != "") ? $val["solve"] : $func->getSpare($val["spareId"])." almashtirildi"?></td>
                    <td class="todo"><?=$val["todo"]?></td>
                    <td>Liniyaga to'g'irlab qaytarildi</td>
                    <td><?=(($val["spareId"] == 0) ? "Zapchast ishlatilmadi" : "Zapchast ishlatildi")?></td>
                    <td class="photo col-sm-3">
                        <div class="photoCont">
                            <? foreach ($func->getRepairPhoto($val["registerId"]) as $photo){ ?>
                                <img src="/upload/ftqImages/compress/compress_<?=$photo["name"]?>" alt="">
                            <?}?>
                        </div>
                        <button class="btn addPhoto">+</button>
                    </td>
                    <td class="photo">
                        <button class="btn deleteRow">X</button>
                    </td>
                </tr>
            <?}?>
            <tr class="bg-danger">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>Ish jarayoni</td>
                <td></td>
                <td>OTK</td>

                <td><?=$allFacCnt?></td>
                <td><?=$allSkdCnt?></td>
                <td><?=$allFullCnt?></td>
                <td><?=number_format($allFullCnt*100/(($produce == 0) ? 1 : $produce),2,","," ")?>%</td>

                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
    <?}?>
</table>
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
