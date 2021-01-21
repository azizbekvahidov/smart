<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/resources/css/bootstrap.css">

<script src="<?php echo Yii::app()->request->baseUrl; ?>/resources/js/highcharts/highcharts.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/resources/js/highcharts/modules/exporting.js"></script>
<div id="ftqLine">

</div>
<div id="ftqCol">

</div>

<script>
    var optopns = {
        title: {
            text: 'Показатель выполнения плана / Reja bajarilishi ko`rsatgichi (%) (M/R*100)'
        },
        xAxis: {
            categories: <?=json_encode($repairStat["days"])?>,
            crosshair: true
        },
        legend: {
//            layout: 'vertical',
//            align: 'center',
//            verticalAlign: 'middle'
        },
        yAxis: {
            min: 0,
            title: {
                text: ''
            },
            plotLines:[{
                value: 98,
                color: 'red',
                width: 2
                }
            ]
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            },
            series: {
                dataLabels: {
                    enabled: true,
                    format: '{point.y}%'
                }
            }
        },

        series: <?=json_encode($repairStat["ftq"]["producePercentDiag"])?>,

        responsive: {
            rules: [{
                condition: {
                    maxWidth: 500
                },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    }
                }
            }]
        }
    };

    var Coloptopns = {
        title: {
            text: 'Показатель выполнения плана / Reja bajarilishi ko`rsatgichi (%) (M/R*100)'
        },
        xAxis: {
            categories: <?=json_encode($repairStat["days"])?>,
            crosshair: true
        },
        legend: {
    //            layout: 'vertical',
    //            align: 'center',
    //            verticalAlign: 'middle'
        },
        yAxis: {
            min: 0,
                title: {
                text: ''
            },
            plotLines:[{
                value: 98,
                color: 'red',
                width: 2
            }
            ]
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                    borderWidth: 0
            },
            series: {
                dataLabels: {
                    enabled: true,
                        format: '{point.y}%'
                }
            }
        },

        series: <?=json_encode($repairStat["ftq"]["repairPercentDiag"])?>,

        responsive: {
            rules: [{
                condition: {
                    maxWidth: 500
                },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    }
                }
            }]
        }
    };

    var chart = Highcharts.chart('ftqLine', optopns);
    var colchart = Highcharts.chart('ftqCol', Coloptopns);
</script>

<table class="table-bordered " id="ftqTable" style="width: 100%">
    <tr>
        <th class="text-center">Календарные дни / Kalendar kun</th>
        <? for ($i = 1; $i <= $lastday; $i++){?>
            <th><?=$i?></th>
        <? }?>
    </tr>
    <tr>
        <th class="text-center">Модел / Model</th>
        <? for ($i = 1; $i <= $lastday; $i++){?>
            <th><?=$repairStat["ftq"]["phoneModel"][$i]?></th>
        <? }?>
    </tr>
    <tr>
        <th class="text-center">"Официальный план производства / Rasmiy ishlab chiqarish rejasi (R)"</th>
        <? for ($i = 1; $i <= $lastday; $i++){?>
            <td><?=$repairStat["ftq"]["officialplan"][$i]?></td>
        <? }?>
    </tr>
    <tr>
        <th class="text-center">"Текущий план производства / Joriy ishlab chiqarish rejasi (R)"</th>
        <? for ($i = 1; $i <= $lastday; $i++){?>
            <td><?=$repairStat["ftq"]["currentplan"][$i]?></td>
        <? }?>
    </tr>
    <tr>
        <th class="text-center">"Произведено продукции / Ishlab chiqarilgan maxsulot (M)" </th>
        <? for ($i = 1; $i <= $lastday; $i++){?>
            <td><?=$repairStat["ftq"]["produce"][$i]?></td>
        <? }?>
    </tr>
    <tr>
        <th class="text-center">Показатель выполнения официального плана / Rasmiy reja bajarilishi ko`rsatgichi (%) (M/R*100)</th>
        <? for ($i = 1; $i <= $lastday; $i++){?>
            <td><?=$repairStat["ftq"]["oficcialproducePercent"][$i]?>%</td>
        <? }?>
    </tr>
    <tr>
        <th class="text-center">Показатель выполнения текущего плана / Joriy reja bajarilishi ko`rsatgichi (%) (M/R*100)</th>
        <? for ($i = 1; $i <= $lastday; $i++){?>
            <td><?=$repairStat["ftq"]["currentproducePercent"][$i]?>%</td>
        <? }?>
    </tr>
    <tr>
        <th class="text-center">"Количество несоответствий / Nomuvofiqlik soni (N)"</th>
        <? for ($i = 1; $i <= $lastday; $i++){?>
            <td><?=$repairStat["ftq"]["repairCnt"][$i]?></td>
        <? }?>
    </tr>
    <tr>
        <th class="text-center">"Показатель качества продукции Sifat darajasi ko'rsatgichi SDK=(1-N/M)"</th>
        <? for ($i = 1; $i <= $lastday; $i++){?>
            <td><?=$repairStat["ftq"]["repairPercent"][$i]?>%</td>
        <? }?>
    </tr>
</table>