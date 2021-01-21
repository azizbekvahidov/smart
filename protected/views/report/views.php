<? $func = new Functions(); ?>

<div>
    <table>
        <tbody>
        <tr>
            <td>Модель:</td>
            <td><?=$phones["phone"]?></td>
        </tr>
        <tr>
            <td>Цвет:</td>
            <td><?=$phones["color"]?></td>
        </tr>
        <tr>
            <td>SN:</td>
            <td><?=$print["SN"]?></td>
        </tr>
        <tr>
            <td>IMEI1:</td>
            <td><?=$print["IMEI1"]?></td>
        </tr>
        <tr>
            <td>IMEI2:</td>
            <td><?=$print["IMEI2"]?></td>
        </tr>
        <tr>
            <td>Дата выпуска:</td>
            <td><?=date("d.m.Y",strtotime($print["printDate"]))?></td>
        </tr>
        <?if($print["sell"] == 1){?>
            <tr>
                <td>Статус:</td>
                <td>Продано</td>
            </tr>
            <tr>
                <td>Дата продажи:</td>
                <td><?=date("d.m.Y",strtotime($print["sellDay"]))?></td>
            </tr>
        <?}else{?>
            <tr>
                <td>Статус:</td>
                <td>Не продано</td>
            </tr>
            <tr>
                <td>Дата продажи:</td>
                <td><?=date("d.m.Y",strtotime($print["backDay"]))?></td>
            </tr>
        <?}?>
        </tbody>
    </table>
</div>
<div style="float: left; width: 350px;">
    <h3>Продажи</h3>
    <?$cnt = 1; if(!empty($sell)){?>
    <table>
        <thead>
        <tr>
            <th>№</th>
            <th>Пользователь</th>
            <th>Точка</th>
            <th>Дата</th>
        </tr>
        </thead>
        <tbody>
        <?foreach ($sell as $value){ $res = $func->getPoint($value["userId"]);?>
                <tr>
                    <td><?=$cnt?></td>
                    <td><?=$res["name"]?><br><?=$res["surname"]?><br><?=$res["lastname"]?></td>
                    <td><?=$res["region"]?><br><?=$res["district"]?><br><?=$res["desc"]?></td>
                    <td><?=date("d.m.Y H:i:s",strtotime($value["sellDate"]))?></td>
                </tr>
                <?$cnt++;}?>
        </tbody>
    </table>
    <?}?>
</div>
<div style="float: right; width: 350px;">
    <h3>Возвраты</h3>
    <?$cnt = 1; if(!empty($back)){ ?>
    <table>
        <thead>
        <tr>
            <th>№</th>
            <th>Пользователь</th>
            <th>Точка</th>
            <th>Дата</th>
        </tr>
        </thead>
        <tbody>
        <?foreach ($back as $value){ $res = $func->getPoint($value["userId"]);?>
            <tr>
                <td><?=$cnt?></td>
                <td><?=$res["name"]?><br><?=$res["surname"]?><br><?=$res["lastname"]?></td>
                <td><?=$res["region"]?><br><?=$res["district"]?><br><?=$res["desc"]?></td>
                <td><?=date("d.m.Y H:i:s",strtotime($value["backDate"]))?></td>
            </tr>
            <?$cnt++;}?>
        </tbody>
    </table>
    <?}?>
</div>