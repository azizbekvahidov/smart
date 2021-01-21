<h1>Мои выставленные задачи</h1>
<? $doc = new Doc()?>
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <span class="caption-subject bold uppercase"> Выставленные задачи</span>
                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover table-checkable order-column" id="task">
                    <thead>
                    <tr>
                        <th> № </th>
                        <th> Документ </th>
                        <th> ID </th>
                        <th>  </th>
                    </tr>
                    </thead>
                    <tbody>
                    <? foreach ($model as $key => $val){ $docType = $doc->getDocType($val['docType']);
                        ?>
                        <tr class="odd gradeX">
                            <td><?=$key+1;?></td>
                            <td><?=$docType['name']?></td>
                            <td><?=$val["id"]?> </td>
                            <td>
                                <a href="javascript:;" data-id="<?=$val["id"]?>" data-view="<?=$docType["view"]?>" data-href="signed/?docId=<?=$val["docId"]?>&docType=<?=$val["docType"]?>&id=<?=$val["id"]?>" class="view" ><i class="fa fa-eye"></i></a> &nbsp; &nbsp;
                            </td>
                        </tr>
                    <?}?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
</div>
<script>
    $(document).on('click','.view', function () {
        var link = $(this).data('href');
        $.ajax({
            method: 'GET',
            url: $(this).data('view'),
            data: {id:$(this).data('id')},
            success: function (res) {
                $(".modal-body").html(res);
                $("#signed").attr('href',link);
                $("#docModal").modal('show');
            }
        })
    })
</script>

<div class="modal fade modals " id="docModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <a href="" id="signed" type="button" class="btn btn-default" >ok<i class="fa fa-check"></i></a>
                <button type="button" class="btn btn-success" data-dismiss="modal">отмена</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->