<? $func = new Functions(); $balances = array();?>
<table  id="dataTable" class="table table-bordered">
    <thead>
    <tr>
        <th>Модель</th>
        <?foreach ($users as $value){?>
            <th><?=$value["login"]?></th>
        <?}?>
    </tr>
    </thead>
    <tbody>
    <?foreach ($model as $val){
        ?>
        <tr>
            <td><?=$val["model"]?></td>
            <? foreach ($users as $value){
                $balance = $func->getBalanceUser($value["userId"],$val["phoneId"],$month);?>
                <td><?=$balance?></td>
            <?
            $balances[$value["userId"]] = $balance[$value["userId"]] + $balance;
            }?>
        </tr>
    <?}?>
    </tbody>
    <tfoot>
    <tr>
        <th>Итого</th>
        <? foreach ($users as $value){?>
            <th><?=$balances[$value["userId"]]?></th>
        <?}?>
    </tr>
    </tfoot>
</table>