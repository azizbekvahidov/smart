    <? $func = new Functions()?>
    <div class="col-sm-12">

        <table class="table table-bordered" id="dataTable">
            <thead>
                <tr>
                    <td></td>
                    <td colspan="<?=count($phone)?>"><h1>Остатки</h1></td>
                    <td colspan="<?=count($phone)?>"><h1>Приходы</h1></td>
                    <td colspan="<?=count($phone)?>"><h1>Расходы</h1></td>
                </tr>
                <tr>
                    <th>Диллеры</th>
                    <? foreach ($phone as $value){?>
                    <th><?=$value["model"]?></th>
                    <?}?>
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
                        <td><?=$val["surname"]?> <?=$val["name"]?> <?=$val["lastname"]?></td>
                        <? foreach ($phone as $value){?>
                            <td><?=$func->getDealerBalance($to,$value["phoneId"],$val["userId"])?></td>
                        <?}?>
                        <? foreach ($phone as $value){?>
                            <td><?=$func->getDealerIn($from,$to,$value["phoneId"],$val["userId"])?></td>
                        <?}?>
                        <? foreach ($phone as $value){?>
                            <td><?=$func->getDealerOut($from,$to,$value["phoneId"],$val["userId"])?></td>
                        <?}?>
                    </tr>
                <?}?>
            </tbody>
        </table>
    </div>