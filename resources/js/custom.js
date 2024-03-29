var tabpertable;

$.fn.Custom = function( opts ) {
    var maintable = $(this);
    tabpertable = maintable.DataTable({
        columns: opts.Columns,
        language: {
            decimal: "",
            emptyTable: "Нет данных в таблице",
            info: "Показать _START_ до _END_ из _TOTAL_ записей",
            infoEmpty: "Показать от 0 до 0 из 0 записей",
            infoFiltered: "(фильтровать по _MAX_)",
            infoPostFix: "",
            thousands: ",",
            lengthMenu: "Показать _MENU_ ",
            loadingRecords: "Загрузка...",
            processing: "Процесс...",
            search: "Поиск:",
            zeroRecords: "Нет соответствующих данных",
            paginate: {
                first: "Первый",
                last: "Конец",
                next: "След.",
                previous: "Пред."
            },
            aria: {
                sortAscending: ": Задать по нарастающему",
                sortDescending: ": Задать по убывающему"
            }
        },
        pageLength: "125",
        "lengthMenu": [ 50, 100, 500, 1000 ],

        ajax: {
            url: opts.refreshUrl,
            dataSrc: 'datas'
        },
        columnDefs:opts.columnDefs,
        order: opts.order,
        createdRow: function (row, data, dataIndex, cells) {
            if(opts.OnCreatedRow !== null)
                opts.OnCreatedRow(row, data, dataIndex, cells);
            else {
                if (opts.rowClass === null)
                    $(row).addClass('tableRow');
                else
                    $(row).addClass(opts.rowClass);
            }


        },
        initComplete: function (settings, json) {
            if(opts.init!==null)
            {
                opts.init();
            }
            $('#searchInput').keyup(function(){
                $('#mainTable').DataTable().search($(this).val()).draw() ;
            })
            $('#searchInput').change(function(){
                $('#mainTable').DataTable().search($(this).val()).draw() ;
            })

        },


    });
     






var yesFunc = function () {$(this).dialog("close");};   
var noFunc = function () {$(this).dialog("close");};

    $.ConfirmDialog =  function (message, yesFunction){
        $('<div></div>').appendTo('body')
                    .html('<div><h6>'+message+'?</h6></div>')
                    .dialog({

                        modal: true, title: 'Сообщение', zIndex: 10000, autoOpen: true,
                        width: '250px', resizable: false,
                        buttons: {
                            ДА: yesFunction,
                            Нет: function () {  
                                //$('body').append('<h1>Confirm Dialog Result: <i>No</i></h1>');
                            
                                $(this).dialog("close");
                            }
                        },
                        close: function (event, ui) {
                            $(this).remove();
                        }
                    });
    };    
    
    
    $.DeleteRecord = function(elem){
        
        eId = $(elem).attr('id');
        eId= eId.replace('delRecord', '');
        yesFunc = function () {
            $.ajax({
                url: opts.deleteUrl,
                type: 'POST',
                data:{'id':eId},
                success: function(res){
                    var tableRow = $("td").filter(function() {
                        return $(this).text() == eId;
                    }).closest("tr");
                    $(tableRow).remove();
                    $('#mainTable').DataTable().ajax.reload();
                    console.log(res+ " record deleted");
                    $.emptyValues();
					$("#formBlock").collapse("hide");
                },
                error: function(xhr){
                    console.log(xhr.responseText);
                }
            });
            $(this).dialog("close");
        };  
        $.ConfirmDialog('Удалить запись', yesFunc);
        
    };
    $.rowClick = function(elem) {
		$("#formBlock").collapse("show");
		        $("html,body").animate({
		            scrollTop: $("#formBlock").offset().top
		        }, 500);
        var tableData = $(elem).children("td").map(function() {
            return $(this).text();
        }).get();

		var $inputs = $(".mainForm :input");

		var values = {};
        var i=0;
        $inputs.each(function() {
            var tableVal = tableData[i];
            if(tableVal === 'Перечисление'){
                tableVal = 'transfer';
            }
            else if(tableVal === "Наличные"){
                tableVal = 'cash';
            }

            if($(this).attr("type") === 'date'){
                var from = tableVal.split(".");
                var f = new Date(from[2], from[1], from[0]);
                tableVal = f.getFullYear() + "-" + (f.getMonth() < 9 ? "0" + (f.getMonth()) : f.getMonth()) + "-" + f.getDate();
            }
            //alert($(this).attr('name')+''+tableData[i]);
            $(this).val(tableVal);
            i++;
            $(this).trigger("chosen:updated");
        });
    };
    
    $.emptyValues = function(){
        $("#alertBox").remove();
        $("#mainForm1 input").attr("style","");
         $("#mainForm1")[0].reset();
        $("#formID").val(0);
		$('select').prop("selectedIndex", -1);
        $("select").trigger("chosen:updated");
    };
    $('#btnNew').on('click', function(){
        $("#formBlock").collapse("show");
        $.emptyValues();
    });
    $('#btnCencel').on('click', function(){
        $("#formBlock").collapse("hide");
        $.emptyValues();
    });
    $('#mainForm1').on('submit', function(){
        var formId = $('#formID').val();
        var url1 = opts.saveUrl;
		var form = $(this);
        if(formId == 0)
        {
            url1 = opts.newUrl;
        
        }
        
        var data = $(this).serialize();
        $.ajax({
            url: url1,
            type: 'POST',
            data: data,
            success: function(res){
            if(Object.keys(res).length != 0){
                    $("#alertBox").remove();
                    var str = '<div class="alert alert-danger alert-dismissible fade show" role="alert" id="alertBox">';
                    $.each(res, function (index,val) {
                        str += val[0]+"<br>";
                        $("[name='"+index+"']").css("border-color","#dc3545");
                    });
                    str += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
                        '<span aria-hidden="true">&times;</span>'+
                        '</button>'+
                        '</div>';
                    form.prepend(str);
                }
                else {
                    $("#formBlock").collapse("hide");
                    $('#mainTable').DataTable().ajax.reload();
                    $.emptyValues();
                    $("#alertBox").remove();
                }    
            },
            error: function(xhr){
                console.log(xhr.responseText);
            }
        });
        
        return false; 
    });    
      
    $.DeleteRecordAll = function(elem, delurl, isConfirmDialog){
        
        eId = $(elem).attr('id');
        eId= eId.replace('delRecord', '');
        alert(eId);
        yesFunc = function () {
                $.ajax({
                url: delurl,
                type: 'POST',
                data:{'id':eId},
                success: function(res){
                    var tableRow = $("td").filter(function() {
                        return $(this).text() == eId;
                    }).closest("tr");
                    $(tableRow).remove();
                    //$('#mainTable').DataTable().ajax.reload();
                    console.log(res+ " record deleted");
                    //$.emptyValues();
                },
                error: function(xhr){
                    console.log(xhr.responseText);
                }
            });
            if(isConfirmDialog)
            $(this).dialog("close");
        };  
        if(isConfirmDialog)
        $.ConfirmDialog('Удалить запись', yesFunc);
        else
            yesFunc();
        
    };

            
}
var Timer;

function Start() {

    $('#searchInput').keyup(function () {

        clearTimeout(Timer);
        Timer = setTimeout(SendRequest, 1000);
    });
}
//$('#saveSearch').on('click', function(){
function SendRequest() {

    var key = $("#searchInput").val();
    //alert(key);
    $.ajax({
        url: '/sold/expense/setsearch',
        type: 'POST',
        data:{'searchset':{'url':window.location.href, 'value':key}},
        success: function(res){

            console.log(res);

        },
        error: function(xhr){
            console.log(xhr.responseText);
        }

    });
//    });
}


$('#selectYearMain').on('change', function(){
    cyear = $(this).val();
    $.ajax({
        url: '/sold/expense/setyear',
        type: 'POST',
        data:{'current_year':cyear},
        success: function(res){
            location.reload();
        },
        error: function(xhr){
            console.log(xhr.responseText);
        }

    });
});
$(Start);
