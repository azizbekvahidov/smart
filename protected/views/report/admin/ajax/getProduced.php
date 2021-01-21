<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/highcharts/highcharts.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/highcharts/modules/exporting.js"></script>
<div class="col-sm-5">
    <table class="table table-bordered">
        <tr>
            <th>Модель</th>
            <th>Цвет</th>
            <th>Кол-во</th>
        </tr>
        <? foreach ($res["model"] as $val){?>
            <tr>
                <td><?=$val["model"]?></td>
                <td><?=$val["name"]?></td>
                <td><?=$val["cnt"]?></td>
            </tr>
        <?}?>
    </table>
</div>
<div class="col-sm-5" id="chart">
</div>
<script>
    var options = {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: ''
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} % - {point.y}',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        },
        series: [{
            name: 'Кол-во',
            colorByPoint: true,
            data: <?=json_encode($res["chart"])?>
        }]
    };

    Highcharts.chart('chart', options);
</script>