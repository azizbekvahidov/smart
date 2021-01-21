<? $func = new Functions()?>
<div class="col-sm-12">

    <table class="table table-bordered" id="dataTable">
        <thead>

        <tr>
            <th>Модели</th>
            <th>Продажа</th>
            <th>Остаток диллеров</th>
            <th>Остаток продовцов</th>
        </tr>
        </thead>
        <tbody>
        <?foreach ($phone as $val){?>
            <tr>
                <td><?=$val["model"]?></td>
                <td><?=$func->getRetailerSold($from,$to,$val["phoneId"])?></td>
                <td><?=$func->getDealerBalance($to,$val["phoneId"])?></td>
                <td><?=$func->getRetailerBalance($to,$val["phoneId"])?></td>
            </tr>
        <?}?>
        </tbody>
    </table>
</div>