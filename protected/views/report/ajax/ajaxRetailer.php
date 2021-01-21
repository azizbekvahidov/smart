<? $func = new Functions()?>
<div class="col-sm-12">
    <table class="table table-bordered" id="retailerTable">
        <thead>
        <tr>
            <td colspan="4"></td>
            <td colspan="<?=count($phone)?>"><h1>Приходы</h1></td>
            <td colspan="<?=count($phone)?>"><h1>Расходы</h1></td>
        </tr>
        <tr>
            <th>Город</th>
            <th>регион</th>
            <th>Диллер</th>
            <th>Продовец</th>
            <? foreach ($phone as $value){?>
                <th><?=$value["model"]?></th>
            <?}?>
            <? foreach ($phone as $value){?>
                <th><?=$value["model"]?></th>
            <?}?>
        </tr>
        </thead>
        <tbody>
        <?foreach ($model as $val){?>
            <tr>
                <td><?=$func->getCity($val["point"])?></td>
                <td><?=$func->getRegion($val["point"])?></td>
                <td><?=$val["dsurname"]?> <?=$val["dname"]?> <?=$val["dlastname"]?></td>
                <td><?=$val["surname"]?> <?=$val["name"]?> <?=$val["lastname"]?></td>

                <? foreach ($phone as $value){?>
                    <td><?=$func->getRetailerIn($from,$to,$value["phoneId"],$val["userId"])?></td>
                <?}?>
                <? foreach ($phone as $value){?>
                    <td><?=$func->getRetailerSold($from,$to,$value["phoneId"],$val["userId"])?></td>
                <?}?>
            </tr>
        <?}?>
        </tbody>
    </table>
</div>