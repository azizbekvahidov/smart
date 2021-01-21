  <!--
/**
* This is the partial view that is rendered inside fancybox when the edit icon is clicked.
*
* @author Spiros Kabasakalis <kabasakalis@gmail.com>,myspace.com/spiroskabasakalis
* @copyright Copyright &copy; 2011 Spiros Kabasakalis
* @since 1.0
* @license The MIT License
*/
    -->
    <div  class="image_update"  >
    <h3><?php echo 'Update Image Title And Link' ?></h3><br>
    <h1><?php echo ($_POST['basename'] . $_POST['extension'] ); ?></h1>

    <form  id="updateimageform_<?php echo $_POST['file_id'] ?>" style="display: block;">
        <div class="input_div">
            Title: <input type="text"  size="60"
                          value=<?php echo!empty($_POST['title']) ? '"' . $_POST['title'] . '"' : '&nbsp'; ?>
                          name=<?php echo '"' . $_POST['file_id'] . '_title_input' . '"'; ?>  >


            Link:<select name="<?php echo $_POST['file_id'] ?>_url_select"
                         id="<?php echo $_POST['file_id'] ?>_url_select"
                         ><?php echo $ops ?> </select>


            <input type="hidden" name="file_id"  value=<?php echo '"' . $_POST['file_id'] . '"' ?>>
        </div>

        <div class="thumb_container">
            <?php echo CHtml::image($_POST['path'] . '/tmb/' . $_POST['file_id'] . '_100_100' . '_thumb' . $_POST['extension'], $_POST['title'], array('class' => 'tmb')) ?>
        </div>
        <div class="savebutton_div">
            <?php
            $this->widget('zii.widgets.jui.CJuiButton',
                    array(
                        'name' => 'button',
                        'buttonType' => 'button',
                        'caption' => 'Save',
                        'id' => 'jui_update_btn',
                        'value' => 'asd',
                    )
            );
            ?>
        </div>
    </form>
</div>


<script type="text/javascript">
    $(function() {

        //Fetch Image Info to set  the values of title input and selected option.
        $.ajax({
            type:'POST',
            url: '<?php echo Yii::app()->baseUrl; ?>/nivoSliderManagement/pl/fetch_image_info',
            data:$('#updateimageform_<?php echo $_POST['file_id']; ?>').serialize(),
            success: function(data){

                img=jQuery.parseJSON(data);

                $("input[name='"+img.file_id+"_title_input']").val(img.title);

                if (img.url !='nolink')
                    $("#"+img.file_id+"_url_select option[value='"+img.url+"']").attr('selected', 'selected');
                else
                    $("#"+img.file_id+"_url_select option[value='nolink']").attr('selected', 'selected');
            }
        });//ajax



      
        //Update Image Info In Database
        $('#jui_update_btn').click(function(){

            $.ajax({
                type:'POST',
                url: '<?php echo Yii::app()->baseUrl; ?>/nivoSliderManagement/pl/updateinfo',
                data:$('#updateimageform_<?php echo $_POST['file_id']; ?>').serialize(),
                success: function(res){
                    var imginfo=jQuery.parseJSON(res);
                    //console.log(res);
                   // console.log("NEW TITLE "+imginfo.title);
                    $('#'+imginfo.file_id+'_thumb').attr('alt', imginfo.title);
                    $ ('#<?php echo $_POST['file_id'] . '_title'; ?>').text( $('input[name="<?php echo $_POST['file_id']; ?>_title_input"]').val() ).effect("pulsate", { times:3 }, 1000);
               
                    $.fancybox.close();
                 
                }
            });

        });
    });
</script>