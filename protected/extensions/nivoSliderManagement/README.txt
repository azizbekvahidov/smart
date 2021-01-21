/**
* Nivo Slider Management
*
* @author Spiros Kabasakalis <kabasakalis@gmail.com>,myspace.com/spiroskabasakalis
* @copyright Copyright &copy; 2011 Spiros Kabasakalis
* @since 1.0
* @license The MIT License
*/

OVERVIEW
This is  an extension module  for  a typical jQuery slider,Nivo Slider.
It makes possible for an end user to upload images,provide a caption
 and optionally a link to a page for each image of the slider.
The module can easily be modified to manage any widget expecting an array of images
 as part of it's configuration.Plus,the management page could work very well as an image gallery.

TESTING
Demo link for testing is provided below.
I realize that opening this demo for testing makes it vulnerable to abuse,
so please be kind!You can upload/delete your own images,but please don't delete the ones I have
already uploaded,so that the demo retains a decent appearance.
The CSS for Nivo Slider,nivo-slider.css, was modified for 500x375 sized images,
so if you upload images with different dimensions,the layout will be broken,although the slider will still work.
The slider will render up to 50 images in this demo,this can be configured of course by specifying
 the number of items that the database returns,(see action that renders the view file containing Nivo Slider).
You can upload up to 10 images at once with PlUpload widget,this can also be configured.
Disclaimer:I have only uploaded the Nationial Geographic images,the rest is all from your testing!

DEMO
http://libkal.gr/yii_test/                                         Home Page with Nivo Slider
http://libkal.gr/yii_test/nivoSliderManagement   NivoSlider Management page.

This module makes heavy use of ajax calls.It is based on:

NivoSlider for Yii
http://www.yiiframework.com/extension/nivoslider
Nivo Slider Project Page
http://nivo.dev7studios.com/

PlUpload Widget for Yii  (image uploader)
http://www.yiiframework.com/extension/pupload

prettyPhoto ,(lightbox clone)
http://www.no-margin-for-errors.com/projects/prettyphoto-jquery-lightbox-clone/

jQuery UI,for JUI dialog (the core widget is not used for reasons that have to do with ajax page calls-CListView paging)
http://jqueryui.com/download

Fancybox ,(lightbox clone)
 http://fancybox.net/

image extension (Kohana image library for Yii)
http://www.yiiframework.com/extension/image

NOTE:You don't have to download these extensions,they are already included
 in module's extension folder.

optionally for linking the images to content,a CMS Yii extension:
cmssitestructuremodule.
http://www.yiiframework.com/extension/cmssitestructuremodule.
This extension is not included in the module,you have to download it and 
 install it.


INSTALLATION INSTRUCTIONS

Exctract  the module download zip and copy nivoSliderManagement folder to modules folder
of your Yii application.
Create a table in your database using the images_table.sql file found in data module folder.
Create the folder that images will be uploaded to, with a name of your choice,
inside assets folder of your Yii application.In module configuration (see below)  you will specify
the name of the folder you just created as 'upload_directory'.


In config/main.php of your Yii application  include nivoSliderManagement in modules array,like so:
( The module is configured with default values.)

'modules'=>array(

    //...other modules...

'nivoSliderManagement'=>array(
               'columnLayout'=> '/layouts/column2',
               'upload_directory'=> 'up',
               'max_file_number'=> '10',
               'max_file_size'=> '1mb',
              ),

    //...other modules...
     );

Place the following code inside the view that you want Nivo Slider in,
check Nivo Slider for Yii extension page for details on options.

$this->widget('application.modules.nivoSliderManagement.extensions.nivoslider.CNivoSlider', array(
                    //nsprovider is  a variable sent  from the corresponding action,see below.
                       'images'=>$nsprovider,
                       'effect'=>'sliceUpDown',
                       'config'=>array(
		       'effect'=>'sliceUpDown',
		       'slices'=>25,
		       'animSpee'=>500,
		       'pauseTime'=>6000,
		       'startSlide'=>0,
		       'directionNav'=>true,
		       'directionNavHide'=>true,
		       'controlNav'=>true,
		       'keyboardNav'=>true,
		       'pauseOnHover'=>true,
		       'manualAdvance'=>false,
		       'captionOpacity'=>0.5,
                               // 'controlNavThumbs'=>true,
                              // 'controlNavThumbsSearch'=>'.jpg', //Replace this with...
                              // 'controlNavThumbsReplace'=>'_100_100_thumb.jpg', //...this in thumb Image src
                              // 'controlNavThumbsFromRel'=>true
			)
    )
);


Place the following code  inside the action that renders the view file containing Nivo Slider.


      Yii::import('application.modules.nivoSliderManagement.models.Img');


    //get images from database
    $ns_dataProvider = new CActiveDataProvider('Img', array(
                    'criteria' => array(
                                                'select' => array('t.path', 't.title', 't.basename', 't.extension', 't.url', 't.file_id'),
                                                'order' => 'title'
                                              ),
                    'pagination'=>array(
                                                   'pageSize'=>50,
                                                  ),
                                                  ));

  $ns_images = $ns_dataProvider->getData();

        $nsprovider = array();
        $baseurl = Yii::app()->baseUrl;
        foreach ($ns_images as $image) {
                 if ($image['url'] != "nolink") {
                $nsprovider[] = array('src' => $image['path'] . '/' . $image['file_id'] . $image['extension'],
                                                 'caption' => $image['title'],
                                                 'url' => $baseurl . '/' . $image['url'],
                                                 'imageOptions' => array('rel' => $image['path'] . '/tmb/' . $image['file_id'] . '_100_100_thumb' . $image['extension'])
                                                 );
            } else {
                $nsprovider[] = array('src' => $image['path'] . '/' . $image['file_id'] . $image['extension'],
                                                 'caption' => $image['title'],
                                                 'imageOptions' => array('rel' => $image['path'] . '/tmb/' . $image['file_id'] . '_100_100_thumb' . $image['extension'])
                                                 );
                     }
                    };

	    $this->render('[VIEW CONTAINING NIVO SLIDER]',array('nsprovider'=>$nsprovider));


Last,optionally, for linking the images to content,install cmssitestructuremodule Yii extension:
http://www.yiiframework.com/extension/cmssitestructuremodule
As an alternate option,you can easily adapt your module to your own CMS simply by modifying  actionGetlinks action
 in PlController.

The URL for the management page will be  [root folder of your Yii application]/nivoSliderManagement.

Cheers.
