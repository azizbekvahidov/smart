<table class="table table-bordered">
    <tr>
        <th>#</th>
        <th>Оператор</th>
        <th>Время</th>
        <th></th>
        <th>на обед и полдник</th>
        <th>всего</th>
    </tr>
    <? $cnt = 1; foreach ($res as $key => $val){
        $time = 0;
        $timeLunch = 0;

        $last = 0;
        $first = 0;
        $other = 0;
        ?>
        <tr>
            <td><?=$cnt?></td>
            <td><?=$key?></td>
            <td><? foreach ($val as $k => $temp) {
                if($k != 0) {
                    if (($k % 2) == 0) {
                        $last = strtotime($temp["actionTime"]);
                        if($other == 1){
                            $timeLunch = $timeLunch + ($last - $first);
                        }
                        else{
                            $time = $time + ($last - $first);
                        }

                    } else if (($k % 2) == 1) {
                        if($temp["reason"] == 'Обед'){
                            $other = 1;
                        }
                        else{
                            $other = 0;
                        }
                        $first = strtotime($temp["actionTime"]);

                    }

                }
                $s = ($temp["action"] == 'in' ) ? "<i style='color: green' class='glyphicon glyphicon-chevron-up'></i> ," : "<i style='color: red' class='glyphicon glyphicon-chevron-down'></i> ".$temp["reason"].",";
                echo date("H:i:s",strtotime($temp["actionTime"]))." ".$s;
                }?>
            </td>
            <td><?=intval($time/60)?> мин. <?=$time - intval($time/60)*60?> сек.</td>
            <td><?=intval($timeLunch/60)?> мин. <?=$timeLunch - intval($timeLunch/60)*60?> сек.</td>
            <td><?=intval(($time+$timeLunch)/60)?> мин. <?=($time+$timeLunch) - intval(($time+$timeLunch)/60)*60?> сек.</td>
        </tr>
    <? $cnt++;}?>
</table>