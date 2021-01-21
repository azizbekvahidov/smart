<? $func = new Functions();
?>
<div class="col-sm-5 ">
    <table class="table table-bordered " style="font-size: 11px;">
        <tr>
            <th>#</th>
            <th>sn</th>
            <th>model</th>
        </tr>
        <?
        foreach ($list as $key => $val){
            $res = $func->getPhone($val);
            if(!empty($res)){
            ?>
            <tr>
                <td><?=$key+1?></td>
                <td><?=$val?></td>
                <td><?=$res["model"]?></td>
            </tr>

            <?}
			else{
				?>
				<tr>
                <td><?=$key+1?></td>
                <td><?=$val?></td>
                <td></td>
            </tr>
				<?
			}
        }
        ?>
    </table>
</div>
<div class="col-sm-3 ">
    <table class="col-sm-3 table table-bordered " style="font-size: 11px;">
        <tr>
            <th>IMEI1</th>
            <th>IMEI2</th>
        </tr>
    <?
    foreach ($list as $val){
        $res = $func->getPhone($val);
    if(!empty($res)){
        ?>
        <tr>
            <td><?=$res["IMEI1"]?></td>
            <td><?=$res["IMEI2"]?></td>
        </tr>

        <?}
		else{
			?><tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr><?
		}
    }
    ?>
    </table>
</div>
