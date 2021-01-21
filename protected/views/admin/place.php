<div class="span-16 ">
    <? $func = new Functions()?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Город</th>
                <th>Район</th>
                <th>Рынок</th>
                <th>Магазин</th>
            </tr>
        </thead>
        <tbody>
        <?foreach ($model as $value){?>
            <tr class="odd">
                <td><?=$value["name"]?>
                    <a class="update" title="Редактировать" href="/admin/upPlace/<?=$value["pointId"]?>">
                        <i class="glyphicon glyphicon-pencil"></i>
                    </a>
<!--                    <a class="delete" title="Удалить" href="/users/upDelete/--><?//=$value["pointId"]?><!--">-->
<!--                        <img src="/assets/ef6f8c38/gridview/delete.png" alt="Удалить">-->
<!--                    </a>-->
                </td>
                <td></td>
                <td></td>
                <td></td>
            </tr>

            <? $dist = $func->getPlace($value["pointId"]);
            if(!empty($dist)){?>
                <?foreach ($dist as $v){?>
                    <tr class="even">
                        <td></td>
                        <td><?=$v["name"]?>
                            <a class="update" title="Редактировать" href="/admin/upPlace/<?=$v["pointId"]?>">
                                <i class="glyphicon glyphicon-pencil"></i>
                            </a>
<!--                            <a class="delete" title="Удалить" href="/users/upDelete/--><?//=$v["pointId"]?><!--">-->
<!--                                <img src="/assets/ef6f8c38/gridview/delete.png" alt="Удалить">-->
<!--                            </a>-->
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                    <? $mark = $func->getPlace($v["pointId"]);
                    if(!empty($mark)){?>
                        <?foreach ($mark as $val){?>
                            <tr class="odd">
                                <td></td>
                                <td></td>
                                <td><?=$val["name"]?>
                                    <a class="update" title="Редактировать" href="/admin/upPlace/<?=$val["pointId"]?>">
                                        <i class="glyphicon glyphicon-pencil"></i>
                                    </a>
<!--                                    <a class="delete" title="Удалить" href="/users/upDelete/--><?//=$val["pointId"]?><!--">-->
<!--                                        <img src="/assets/ef6f8c38/gridview/delete.png" alt="Удалить">-->
<!--                                    </a>-->
                                </td>
                                <td></td>
                            </tr>
                            <? $shop = $func->getPlace($val["pointId"]);
                            if(!empty($shop)){?>
                                <?foreach ($shop as $vals){?>
                                    <tr class="even">
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><?=$vals["name"]?>
                                            <a class="update" title="Редактировать" href="/admin/upPlace/<?=$vals["pointId"]?>">
                                                <i class="glyphicon glyphicon-pencil"></i>
                                            </a>
<!--                                            <a class="delete" title="Удалить" href="/users/upDelete/--><?//=$vals["pointId"]?><!--">-->
<!--                                                <img src="/assets/ef6f8c38/gridview/delete.png" alt="Удалить">-->
<!--                                            </a>-->
                                        </td>
                                    </tr>
                                <?}?>
                            <?}?>
                        <?}?>
                    <?}?>
                <?}?>
            <?}?>

        <?}?>
        </tbody>
    </table>
</div>
<div class="span-2 right">
    <ul class="operations" id="yw1">
        <li><a href="/admin/createPlace">Добавить</a></li>
    </ul>
</div>