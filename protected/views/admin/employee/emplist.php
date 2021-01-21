
<a style="padding: 3px 5px;" href="/admin/emplistPrint" type="button" name="button" class="btn btn-info expCheck" data-dismiss="modal">Напечатать</a>
<div class="col-lg-12" style="margin: 0 auto">
    <table>
    <? $i = 1; foreach ($model as $val){?>
        <?if($i%2 != 0){
            ?><tr><?
        }?>
        <td class="col-lg-5">
            <div class="col-lg-5"><img width="150" height="100" src="/barcodegenerator/generatebarcode?code=<?=$val["employeeId"]?>-<?=$val["sex"]?>-<?=$val["departmentId"]?>"></div>
            <div class="col-lg-5">
                <h4><?=$val["surname"]?> <?=$val["name"]?></h4>
                <img width="100" src="/upload/employee/<?= $val["photo"] ?>" alt="">
            </div>
        </td>
        <?if($i%2 == 0){
            ?></tr><?
        }?>
    <?$i++;}?>
    </table>
</div>
<script>
    $(document).ready(function () {

        $(".expCheck").printPage();
    })
</script>
