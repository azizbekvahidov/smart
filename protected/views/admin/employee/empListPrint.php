<?// require_once Yii::app()->basePath . '/extensions/barcodeGen/BarcodeGenerator.php';
//require_once Yii::app()->basePath . '/extensions/barcodeGen/BarcodeGeneratorHTML.php';
//require_once Yii::app()->basePath . '/extensions/barcodeGen/BarcodeGeneratorJPG.php';
//require_once Yii::app()->basePath . '/extensions/barcodeGen/BarcodeGeneratorPNG.php';
//$generator = new Picqer\Barcode\BarcodeGeneratorPNG();
?>
<link href="/css/bootstrap3.css" rel="stylesheet">

<div class="col-lg-12" style="margin: 0 auto">
    <table style="border-spacing: 10px 48px;">
        <? $i = 1; foreach ($model as $val){?>
            <?if($i%3 == 1){
                ?><tr><?
            }?>
            <td class="col-lg-5" style="text-align: center; border: 1px solid #000; width: 300px; height: 200px; position: relative;">
<!--                <div class="col-lg-5" style="margin-bottom: 55px;margin-top: 55px">--><?//='<img width="150" height="100"  src="data:image/png;base64,' . base64_encode($generator->getBarcode($val["employeeId"]."-".$val["sex"]."-".$val["departmentId"], $generator::TYPE_CODE_128)) . '">'?><!--</div>-->
                <div class="col-lg-5">
                    <div class=""><img style="width: 16vh;    position: absolute;    top: 10px;    right: 40px;" src="/images/LOGO.png" alt=""></div>
                    <div style="    font-size: 3vh;    width: 28vh;   position: absolute;    top: 88px;    right: 0;    text-align: center;"><?=$val["surname"]?>
                        <br><?=$val["name"]?></div>
                    <img width="100" style="    margin-bottom: 20px;    position: absolute;    top: 0px;    left: 0;    padding: 0 10px;" src="/upload/employee/<?= $val["photo"] ?>" alt="">
                </div>
            </td>
            <?if($i%3 == 0){
                ?></tr><?
            }
            if($i%9 == 0){?>
                <tr style="margin-top: 25px">

                </tr>
            <?}
            //if($i == 18) break;
            ?>
            <?$i++;}?>
    </table>
</div>
