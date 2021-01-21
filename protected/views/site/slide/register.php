
<script src="<?php echo Yii::app()->request->baseUrl; ?>/resources/js/highcharts/highcharts.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/resources/js/highcharts/modules/exporting.js"></script>
<div id="regLine">

</div>
<div id="regCol">

</div>

<script>
    var optopns = {
        title: {
            text: 'Итоговая статистика браков за неделю'
        },
        xAxis: {
            categories: <?=json_encode($repairStat["dateArray"])?>,
            crosshair: true,
            reversed: true
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

        series: <?=json_encode($repairStat["model"])?>,

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
        chart:{
            type: "column"
        },
        title: {
            text: ''
        },
        xAxis: {
            categories: <?=json_encode($repairStat["dateArray"])?>,
            crosshair: true,
            reversed: true
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

        series: <?=json_encode($repairStat["model"])?>,

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

    var chart = Highcharts.chart('regLine', optopns);
    var colchart = Highcharts.chart('regCol', Coloptopns);
</script>
