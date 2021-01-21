
<div class="col-sm-5">
    <h1>Потраченное время для выхода</h1>
    <table class="table table-bordered" id="dataTable">
        <tr>
            <th>#</th>
            <th>Оператор</th>
            <th>Время</th>
        </tr>
        <? $cnt = 1; foreach ($res as $key => $val){
            $time = 0;
            $timeLunch = 0;

            $last = 0;
            $first = 0;
            $other = 0;
            foreach ($val as $k => $temp) {
                if($k != 0) {
                    if ($temp["action"] == 'in') {
                        $last = strtotime($temp["actionTime"]);
                        if($other == 0){
                            $time = $time + ($last - $first);
                        }
                    } else if ($temp["action"] == 'out') {
                        if ($temp["reason"] == 'Обед') {
                            $other = 1;
                        } else if ($temp["reason"] == 'Полдник') {
                            $other = 1;
                        } else {
                            $other = 0;

                        }
                        $first = strtotime($temp["actionTime"]);
                    }
                }
                $s = ($temp["action"] == 'in' ) ? "<i style='color: green' class='glyphicon glyphicon-chevron-up'></i> ," : "<i style='color: red' class='glyphicon glyphicon-chevron-down'></i> ".$temp["reason"].",";
                //echo date("H:i:s",strtotime($temp["actionTime"]))." ".$s;
            }
            if($time >= 1500){
                ?>
                <tr>
                    <td><?=$cnt?></td>
                    <td><?=$key?></td>
                    <td><?=intval($time/60)?> мин. <?=$time - intval($time/60)*60?> сек.</td>
                </tr>
                <? $cnt++;}}?>
    </table>
</div>
<div class="col-sm-6">
    <h1>Потраченное время для обеда</h1>
    <table class="table table-bordered" id="dataTable">
        <tr>
            <th>#</th>
            <th>Оператор</th>
            <th>на обед и полдник</th>
        </tr>
        <? $cnt = 1; foreach ($res as $key => $val){
            $time = 0;
            $timeLunch = 0;

            $last = 0;
            $first = 0;
            $other = 0;
            foreach ($val as $k => $temp) {
                if($k != 0) {
                    if (($k % 2) == 0) {
                        $last = strtotime($temp["actionTime"]);
                        if($other == 1){
                            $timeLunch = $timeLunch + ($last - $first);
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
                //echo date("H:i:s",strtotime($temp["actionTime"]))." ".$s;
            }
            if($timeLunch >= 2400){
                ?>
                <tr>
                    <td><?=$cnt?></td>
                    <td><?=$key?></td>
                    <td><?=intval($timeLunch/60)?> мин. <?=$timeLunch - intval($timeLunch/60)*60?> сек.</td>
                </tr>
                <? $cnt++;}}?>
    </table>
</div>