<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/resources/css/bootstrap.css">

<script src="<?php echo Yii::app()->request->baseUrl; ?>/resources/js/jquery.min.js"></script>
<table class="table-bordered " id="skdTable" style="width: 100%">
</table>


<script>
    getData();
    function getData(){
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('site/getSkdError'); ?>",
            success: function(data){
                data = JSON.parse(data);
                var sum = [];
                var str = '<tr>'+
                    '<th class="text-center" colspan="'+(parseInt(data.lastDay)+1)+'">SKD</th>'+
                    '</tr>';
                str += '<tr>'+
                    '<th class="text-center">Aniqlangan nuqsonlar</th>';
                for (var i = 1; i <= parseInt(data.lastDay); i++){
                    str += '<th>'+i+'</th>';
                    sum[i] = 0;
                }
                str += '</tr>';
                $.each(data.skdError, function (index, value) {
                    str += '<tr>'+
                        '<th>'+index+'</th>';
                    for (var i = 1; i <= parseInt(data.lastDay); i++){
                        if(data.skdDetail[i] === undefined || data.skdDetail[i][index] === undefined){
                            str += "<td></td>";
                            sum[i] = sum[i] + 0;
                        }
                        else {
                            str += "<td>"+data.skdDetail[i][index]+"</td>";
                            sum[i] = sum[i] + parseInt(data.skdDetail[i][index]);
                        }
                    }
                });
                str += '<tr>'+
                    '<th></th>';
                for (var i = 1; i <= parseInt(data.lastDay); i++){
                    str += '<th>'+sum[i]+'</th>';
                    sum[i] = 0;
                }
                str += '</tr>';
                $("#skdTable").html(str);

            }
        });
    }
    setInterval(getData, 10000);
</script>