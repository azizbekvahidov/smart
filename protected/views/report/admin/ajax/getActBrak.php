
<div class="col-sm-5">
    <table class="table table-bordered" id="dataTable">
        <tr>
            <th>Модель</th>
            <th>Запчасти</th>
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
<div class="col-sm-7" >
    <div class="col-sm-12" id="Col"></div>
    <div class="col-sm-12" id="spare"></div>
    <div class="col-sm-12" id="phone"></div>
</div>
<script>
    var spoptions = {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Соотношение деталей по отношению к их общему количеству'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} % - {point.y}',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black',
                    }
                }
            }
        },
        series: [{
            name: 'Кол-во',
            colorByPoint: true,
            data: <?=json_encode($res["spare"])?>
        }]
    };

    Highcharts.chart('spare', spoptions);

    var phoptions = {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Соотношение деталей по отношению к моделям'
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
            data: <?=json_encode($res["phone"])?>
        }]
    };

    Highcharts.chart('phone', phoptions);

    var Coloptopns = {
        chart:{
            type: "column"
        },
        title: {
            text: 'Обшие произведенная продукция'
        },
        xAxis: {
            categories: <?=json_encode($res["chart"]["categories"])?>,
            crosshair: true
        },
        legend: {
//            layout: 'vertical',
//            align: 'right',
//            verticalAlign: 'middle'
        },
        yAxis: {
            min: 0,
            title: {
                text: ''
            }
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            },
            series: {
                dataLabels: {
                    enabled: true,
                }
            }
        },

        series: <?=json_encode($res["chart"]["series"])?>,

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

    var colchart = Highcharts.chart('Col', Coloptopns);

</script>