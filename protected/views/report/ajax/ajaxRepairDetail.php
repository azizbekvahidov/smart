<div class="col-lg-6">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>№</th>
                <th>Точка</th>
                <th>Кол-во</th>
            </tr>
        </thead>
        <tbody>
            <?foreach ($repair as $key => $value){?>
                <tr>
                    <td><?=$key+1?></td>
                    <td><?=$value["login"]?></td>
                    <td><?=$value["cnt"]?></td>
                </tr>
            <?}?>
        </tbody>
    </table>
</div>
<? $spare = new Spare();?>
<div class="col-lg-6">
    <table class="table table-bordered">
        <thead>
        <tr>
            <td rowspan="" class="text-center">№</td>
            <td rowspan="" class="text-center">Наименование сырья и материалов</td>
            <td class="text-center">Ошибка</td>
            <td class="text-center">Решение</td>
            <td class="text-center">Кол-во</td>
            <td class="text-center">Причина</td>
        </tr>
        <tr>
        </tr>
        </thead>
        <tbody>
        <?foreach ($detail as $key => $value){?>
            <tr>
                <td><?=$key+1?></td>
                <td><?=$value["model"]?></td>
                <td><?=$value["errorname"]?></td>
                <td><?=($value["spareId"] == 0) ? $value["solve"] : $spare->getName($value["spareId"])." (заменена) "?></td>
                <td><?=$value["cnt"]?></td>
                <td><?=$value["cause"]?></td>
            </tr>
        <?}?>
        </tbody>
    </table>
</div>