<?php

  /**
* Nivo Slider Management- Module's main view file .
*
* @author Spiros Kabasakalis <kabasakalis@gmail.com>,myspace.com/spiroskabasakalis
* @copyright Copyright &copy; 2011 Spiros Kabasakalis
* @since 1.0
* @license The MIT License
*/

  ?>

<h1>Nivo slider Management Page</h1><br>
The CSS for Nivo Slider,nivo-slider.css, was modified  for 500x375 sized images,
so if you upload images with different dimensions,the layout will be broken,although the slider will still work.
 The slider will render up to 50 images in this demo,this
 can be configured of course by specifying the number of items that the database returns.
 (see action that renders the view file containing Nivo Slider.)
You can upload up to 10 images at once with PlUpload widget,this can also be configured.
<br>
<br>
<div id="pl" >
<?php


$this->widget('nivoSliderManagement.extensions.plupload.PluploadWidget', array(
             'config' => array(
            'runtimes' => 'gears,flash,silverlight,browserplus,html5',
             'url' => $this->createUrl('/nivoSliderManagement/pl/upload/'),
             'max_file_size' => $this->module->max_file_size,
             'chunk_size' => '1mb',
             'unique_names' => true,
             'filters' => array(
                  array('title' => Yii::t('app', 'Images files'), 'extensions' => 'jpg,jpeg,gif,png'),
              ),
             'language' => Yii::app()->language,
             'max_file_number' => $this->module->max_file_number,
             'autostart' => false,
             'jquery_ui' => false,
             'reset_after_upload' => true,
         ),
    //the following callback function will generate  for every image uploaded :a title input ,a link select control,
    //and an thumbnail image ,then it will append them to the images_form form.
    //It will also populate the javascript array unique_ids with the unique file ids of the newly uploaded images.

         'callbacks' => array(
             'FileUploaded' => 'function(up,file,response){
                    var res=response.response;
                    var resobj=jQuery.parseJSON(res);
                    var links=new Object();

                 //   console.log("RESPONSE RESPONSE: "+res);
                  //  console.log("ORNAME: "+resobj.filename);

                  $(\'#status\').empty();
                 $(\'#uploaded_images\').show();
               $("#image_inputs").prepend("<div id=\'"+ resobj.file_id +
               "\' class=\'span-6 griditem imgupload\'><a  rel=\'just_uploaded\'   href=" + resobj.upload_path +"/"+resobj.file_id + resobj.file_ext +
             "  target=\'_blank\'> <img   src=\'"+resobj.upload_path +"/tmb/"   +resobj.file_id  +"_100_100"+"_thumb" + resobj.file_ext +"\' border=\'0\'><br>" + resobj.filename + "</a><br>(" + resobj.file_size + ") <span></span><br>"
              +"Title: <input  size=\'30\'  type=\'text\' name=\'title_"+resobj.file_id+"\'value=\'\'  /><br />"+
             "   <label for=\'link_image\'>Link</label>  "+
            " <input type=\'checkbox\' value=\'1\' id=\'link_image\' name=\'link_image\' style=\'display: inline;\' onclick=$(\'#select_div_"+resobj.file_id+"\').toggle();> "+
            "<div id=\'select_div_"+resobj.file_id+"\'  style=\'display: none;\'><select id=\'url_select_"+resobj.file_id+"\' name=\'url_select_"+resobj.file_id+"\'>"+op+"</select></div>"+
            "</div>"
      
               );

 $(\"a[rel^=\'just_uploaded\']\").prettyPhoto();
unique_ids.push(resobj.file_id);
//console.log("FILES_UNIQUE IDS: "+ unique_ids);
                        }',

        ),
         'id' => 'uploader'
      ));
?>
</div>
<br>
<br>
<div id="uploaded_images">

    <form  id="images_form" action="" >
        <div id="image_inputs" class="container">

        </div>
        <div id="sub_btn_div" >
        <?php
        $this->widget('zii.widgets.jui.CJuiButton', array(
            'name' => 'button',
            'buttonType' => 'button',
            'id' => 'sub_btn',
            'caption' => 'Save',
            'options' => array(
            ),
        ));
        ?>
    </div>
    </form>
    <div id="status" > </div>
</div>
<h1>Manage Uploaded Images</h1>
<?php
//List view with uploaded images
$dataProvider=new CActiveDataProvider('Img');
$this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$dataProvider,
    'id'=>'lv',
    'itemsCssClass'=>'clear imageitem',
    'pagerCssClass' =>'clear',
    'itemView'=>'_imgitem',   
    'sortableAttributes'=>array(
      'title'=>'Title',
     'basename'=>'Name',
    
    ),
//this callback is used to rebind jQuery behaviors for elements,after a page of CListView is
// requested with ajax.It's necessary for  the elements to work with paging.
     'afterAjaxUpdate' => 'function(id, data) {

//TAKE CARE OF FANCY BOX
$(".fan").each(function(index) {
            var $id=$(this).attr("id");
            var parentdiv=$(this).parent("div");
            var fileid=parentdiv.attr("id");

            $(this).bind("click", function() {
                $.ajax({
                    type: "POST",
                    url: "'.Yii::app()->baseUrl.'/nivoSliderManagement/pl/returnform",
                    data:$(".fileinfo"+fileid).serialize(),
                    success: function(data){
                        
                        $.fancybox(data,
                        {"transitionIn"	:	"elastic",
                            "transitionOut"	:	"elastic",
                            "speedIn"		:	600,
                            "speedOut"		:	200,
                            "overlayShow"	:	false,
                            "hideOnContentClick": false
                        }
                    );
                        //  console.log(data);
                    }
                });//ajax
                return false;
            });//bind

        }); //each


         //TAKE CARE OF JQPRETTYPHOTO
                                    $(".ppt").remove();
                                    $(".pp_overlay").remove();
                                    $(".pp_pic_holder").remove();
      $("a[rel^=\'prettyPhoto\']").prettyPhoto({theme: "facebook",slideshow:5000, autoplay_slideshow:true});



//TAKE CARE OF DELETE DIALOG

        $(".del_link").each(function(index) {
            var $id=$(this).attr("id");
            var parentdiv=$(this).parent("div");
            var fileid=parentdiv.attr("id");

        var filename=$(".fileinfo"+fileid).filter("#basename").attr(\'value\')
                +$(".fileinfo"+fileid).filter("#extension").attr(\'value\');
       var title=$(".fileinfo"+fileid).filter("#title").attr(\'value\');
            var  dialog=$("<div></div>")
            .html("Are you sure you want to delete this file?<br> "+
                      "Title : "+ title+"<br>"+
                       "Original Filename :"+filename)
            .dialog(
            {
                autoOpen: false,
                title: "File Delete Confirmation",
                modal:true,
                buttons: [
                    {
                        text: "Delete",
                        click: function() {
                            $.ajax({
                                type:"POST",
                                 url: "'.Yii::app()->baseUrl.'/nivoSliderManagement/pl/delete",
                                data:$(".fileinfo"+fileid).serialize(),
                                success: function(res){
                                  var imginfo= jQuery.parseJSON (res);
                                    $("#"+fileid).fadeOut(1500,function(){
                                        $(this).remove();
                                       var message=$("<div class=\'clear\'>"+ imginfo.basename +imginfo.extension+ " has been deleted.</div>").hide();
                                         $("#msg").append(message).find(":last-child").fadeIn(1000);

                                        // alert(" ");
                                    });

                                }
                            });

                            $(this).dialog("close"); }
                    },

                    {
                        text: "Cancel",
                        click: function() { $(this).dialog("close"); }
                    }


                ]
            }
        );


            $("#"+$id).bind("click", function() {
                dialog.dialog("open");
		// prevent the default action, e.g., following a link
		return false;
            });

  });  //each

}',

));
?>
<div id="msg" class="clear"></div>

<script  type="text/javascript">

          var imginfo=new Object();
          var unique_ids=new Array();
          var op="";

       $(document).ready(function() {

     
//get links for the select options.
  $.ajax({
                    type: "POST",
                    url: "<?php echo Yii::app()->baseUrl; ?>/nivoSliderManagement/pl/getlinks/",
                    data:{'echojson':true},

                    success: function(data) {

                      links=jQuery.parseJSON(data);
                      for (var l in links)
                             {                      
                        op=op+"<option value='"+links[l]["url"]+"'>"+links[l]["title"]+"</option>";
                                 };
                    } //success
                }); //ajax


//show the form only when user has uploaded images
          $("#uploaded_images").hide();


//Submit title and link for newly uploaded images,will call actionHandle of Pl controller
            $("#sub_btn").click(function() {

                for (var i=0;i<unique_ids.length;i++)
                {
                    var infoobj=new Object;
                    var titlekey="title_"+unique_ids[i];
                    var urlkey="url_select_"+unique_ids[i];

                   infoobj["title"]= $('input[name =' + titlekey+']').val();
                   infoobj["url"]=$('select:[name='+ urlkey +'] option:selected').val();

                    imginfo[unique_ids[i]]= infoobj;
                   // console.log("INFOOBJ: "+ JSON.stringify(infoobj));
                   // console.log("TITLEKEY: "+ titlekey);
                   // console.log("URLKEY: "+ urlkey);
                };

                var imginfo_parsed= jQuery.parseJSON (JSON.stringify( imginfo));

                $.ajax({
                    type: "POST",
                    url: "<?php echo Yii::app()->baseUrl; ?>/nivoSliderManagement/pl/handle/",
                    data:  imginfo_parsed,
                    success: function() {
                      window.location.reload();
                        $('#image_inputs').empty();
                        $('#status').html("<div id='message'></div>");
                        $('#message').html("<h2>Images Submitted!</h2>")
                        .append("<p>Nivo Slider Updated.</p>")
                        .hide()
                        .fadeIn(1500, function() {
                           // $('#message').append("<img id='checkmark' src='images/check.png' />");
                        });
                    } //success
                }); //ajax

              }); //subbtn



      //BIND FANCYBOX to to edit icon,so that when clicked,  _imgupdateform
      //partial view is rendered inside fancybox.
        $('.fan').each(function(index) {
        
            var $id=$(this).attr('id');
            var parentdiv=$(this).parent('div');
            var fileid=parentdiv.attr('id');


            $(this).bind('click', function() {
                $.ajax({
                    type: "POST",
                    url: "<?php echo Yii::app()->baseUrl; ?>/nivoSliderManagement/pl/returnform",
                    data:$(".fileinfo"+fileid).serialize(),
                    success: function(data){

                        $.fancybox(data,
                        {    "transitionIn"	:	"elastic",
                            "transitionOut"    :      "elastic",
                             "speedIn"		:	600,
                            "speedOut"		:	200,
                            "overlayShow"	:	false,
                            "hideOnContentClick": false
                        }
                    );
                        //  console.log(data);
                    } //success
                });//ajax
                return false;
            });//bind

        }); //each


//BIND PRETTY PHOTO to show a slideshow of the images
$("a[rel^='prettyPhoto']").prettyPhoto({theme: "facebook",slideshow:5000, autoplay_slideshow:true});



  //BIND JUIDIALOG ,for delete confirmation.
  $('.del_link').each(function(index) {
  
            var $id=$(this).attr('id');
            var parentdiv=$(this).parent('div');
            var fileid=parentdiv.attr('id');

              var title=$(".fileinfo"+fileid).filter("#title").attr('value');
              var filename=$(".fileinfo"+fileid).filter("#basename").attr('value')
                +$(".fileinfo"+fileid).filter("#extension").attr('value');

         
            var  dialog=$('<div></div>')
            .html('Are you sure you want to delete this file?<br> '+
                      'Title : '+ title+'<br>'+
                      'Original Filename :'+filename)
            .dialog(
            {
                autoOpen: false,
                title: 'File Delete Confirmation',
                modal:true,
                buttons: [
                    {
                        text: "Delete",
                        click: function() {
                            $.ajax({
                                type:"POST",
                                url: "<?php echo Yii::app()->baseUrl; ?>/nivoSliderManagement/pl/delete",
                                data:$(".fileinfo"+fileid).serialize(),
                                success: function(res){

                                   var imginfo= jQuery.parseJSON (res);
                                  // console.log(imginfo);
                                 //  console.log("test basename "+imginfo.basename);

                                    $("#"+fileid).fadeOut(1500,function(){
                                        $(this).remove();
                                        var message=$("<div class='clear' >"+ imginfo.basename +imginfo.extension+ " has been deleted.</div>").hide();
                                        $("#msg").append(message).find(":last-child").fadeIn(1000);


                                    });

                                }
                            });

                            $(this).dialog("close"); }
                    },

                    {
                        text: "Cancel",
                        click: function() { $(this).dialog("close"); }
                    }


                ]
            }
        );


            $('#'+$id).bind('click', function() {
                dialog.dialog('open');
		// prevent the default action, e.g., following a link
		return false;
            });


            //console.log('fileid ' + ': ' + $id);
        });  //each




    });//document ready


      </script>
