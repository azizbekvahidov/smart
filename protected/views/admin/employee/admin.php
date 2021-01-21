<? require_once Yii::app()->basePath . '/extensions/barcodeGen/BarcodeGenerator.php';
require_once Yii::app()->basePath . '/extensions/barcodeGen/BarcodeGeneratorPNG.php';
$generator = new Picqer\Barcode\BarcodeGeneratorPNG();
?>
<a class="btn btn-success" href="exportEmployee">Скачать в Еxcel</a>
<h1>Список сотрудников</h1>
<div class="right">
    <div class="btn-group" role="group" aria-label="...">
        <a  href="empCreate" class="btn btn-success">Добавить</a>
    </div>
</div>
<table class="table-bordered table" id="dataTable">
    <thead>
        <tr>
            <th>№</th>
            <th>Ф.И.О.</th>
            <th>Отдел</th>
            <th>День рождения</th>
            <th>Пол</th>
            <th>Фото</th>
            <th>Штрих</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <? foreach ($model as $val){?>
            <tr>
                <td><?=$val["employeeId"]?></td>
                <td><?=$val["surname"]?> <?=$val["name"]?> <?=$val["lastname"]?></td>
                <td><?=$val["dName"]?></td>
                <td><?=$val["birthday"]?></td>
                <td><?=($val["sex"] == 1) ? "Муж" : "Жен"?></td>
                <td><img width="100" src="/upload/employee/<?= $val["photo"] ?>" alt=""></td>
                <td>
                    <a class="update glyphicon glyphicon-pencil" title="Редактировать" href="/admin/empUpdate/<?=$val["employeeId"]?>">
                    </a>
                    <a class="delete glyphicon glyphicon-trash" title="Удалить" href="/admin/empDelete/<?=$val["employeeId"]?>">
                    </a>
                </td>
                <td><?='<img width="100" height="50"  src="data:image/png;base64,' . base64_encode($generator->getBarcode($val["employeeId"]."-".$val["sex"]."-".$val["departmentId"], $generator::TYPE_CODE_128)) . '">'?>
                </td>
            </tr>
        <?}?>
    </tbody>
</table>

<script>
    $(document).ready(function () {
        $('#export').click(function(){
            $('#dataTable').table2excel({
                name: "dealer report"
            });
        });
    })
</script>
<script>
    $(document).ready(function(){
        $('#dataTable').DataTable();
    });

</script>
