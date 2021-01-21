<div>
    <div class="col-sm-12 center">
        <div class="col-sm-4">
            <div class="col-sm-12" id="model">
                <h2>Телефоны в складе</h2>
                <table class="table table-bordered">
                    <tr>
                        <th>№</th>
                        <th>Модель</th>
                        <th>Цвет</th>
                        <th>Кол-во</th>
                    </tr>
                    <? if(!empty($produceCompress)){
                        foreach ($produceCompress as $key => $value ){?>
                            <tr id="<?=$value["phoneId"]?>-<?=$value["name"]?>">
                                <td><?=$key+1?></td>
                                <td><?=$value["model"]?></td>
                                <td><?=$value["name"]?></td>
                                <td><?=$value["cnt"]?></td>
                            </tr>
                        <?}}?>
                </table>
            </div>
                <div class="col-sm-12" id="model">
                    <h2>Телефоны в складе(Рассыпные)</h2>
                <table class="table table-bordered">
                    <tr>
                        <th>№</th>
                        <th>Модель</th>
                        <th>Цвет</th>
                        <th>Кол-во</th>
                    </tr>
                    <? if(!empty($outCompress)){
                        foreach ($outCompress as $key => $value ){?>
                            <tr id="<?=$value["phoneId"]?>-<?=$value["name"]?>">
                                <td><?=$key+1?></td>
                                <td><?=$value["model"]?></td>
                                <td><?=$value["name"]?></td>
                                <td><?=$value["cnt"]?></td>
                            </tr>
                        <?}}?>
                </table>
            </div>
        </div>
        <div class="col-sm-4" id="cont" style="height: 100%; overflow-y: scroll">
            <h2>Телефоны в складе</h2>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>№</th>
                    <th>SN</th>
                    <th>BOX</th>
                    <th>Модель</th>
                    <th>Цвет</th>
                </tr>
                </thead>
                <tbody>
                <? $cnt = 1;
                foreach ($produce as $key => $val){?>
                    <script>
                        outPhone("<?=$val["phoneId"]?>-<?=$val["name"]?>");
                    </script>
                    <tr>
                        <td><?=$cnt?></td>
                        <td><?=$val["sn"]?></td>
                        <td><?=$val["box"]?></td>
                        <td><?=$val["model"]?></td>
                        <td><?=$val["name"]?></td>
                    </tr>
                    <? $cnt++;}?>
                </tbody>
            </table>
        </div>
        <div class="col-sm-4" id="cont" style="height: 100%; overflow-y: scroll">
            <h2>Телефоны в складе(Рассыпные)</h2>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>№</th>
                    <th>SN</th>
                    <th>BOX</th>
                    <th>Модель</th>
                    <th>Цвет</th>
                </tr>
                </thead>
                <tbody>
                <? $cnt = count($out);
                foreach ($out as $key => $val){?>
                    <script>
                        outPhone("<?=$val["phoneId"]?>-<?=$val["name"]?>");
                    </script>
                    <tr>
                        <td><?=$cnt?></td>
                        <td><?=$val["sn"]?></td>
                        <td><?=$val["box"]?></td>
                        <td><?=$val["model"]?></td>
                        <td><?=$val["name"]?></td>
                    </tr>
                    <? $cnt--;}?>
                </tbody>
            </table>
        </div>
    </div>
</div>
