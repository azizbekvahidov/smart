<?php

/*     in config/main.php include nivoSliderManagement in modules array,like so:
       The module is configured with default values.


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

*/


 //-----------------------------------------------------------------------------------------------
//The following code goes inside the view that you want Nivo Slider in.
//check Nivo Slider site for details on options.

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

//----------------------------------------------------------------------------------------------------------
//the following code goes inside the action that renders the view file containing Nivo Slider.

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


?>
