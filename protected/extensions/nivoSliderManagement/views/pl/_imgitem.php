<?php
/**
* Nivo Slider Management  CListView item view file.
*
* @author Kabasakalis Spiros <kabasakalis@gmail.com>,myspace.com/spiroskabasakalis
* @copyright Copyright &copy; 2011 Spiros Kabasakalis
* @since 1.0
* @license The MIT License
*/

//this is the view for items in the ListView (see  itemView property of CListView)
$iconfolder = YiiBase::getPathOfAlias('nivoSliderManagement.images.icons');
$iconbaseUrl = Yii::app()->assetManager->publish($iconfolder);
?>

<div class=" span-3 griditem"  id="<?php echo $data->file_id ?>" >

    <div id="<?php echo $data->file_id ?>_title"  class="img_title">
        <?php echo html_entity_decode($data->title); ?>
    </div>
    <?php
        echo CHtml::link(CHtml::image($iconbaseUrl . '/edit-icon24.png',
                                                                            'Edit info',
                                                                          array('id' => $data->file_id . '_editimg')),
                                                                           '#',
                                                                        array('class' => 'fan', 'id' => $data->file_id . '_link')
        );
    ?>
    <?php
        echo CHtml::link(CHtml::image($iconbaseUrl . '/delete-icon24.png', 'delete image'),
                                                                             '#', array(
                                                                                          'id' => 'delete_' . $data->file_id,
                                                                                           'class' => 'del_link'
                                                                                           ));
    ?>
        <div >
        <?php echo CHtml::link(CHtml::image($data->path . '/tmb/' . $data->file_id . '_100_100' . '_thumb' . $data->extension, $data->title
                                            , array('class' => 'tmb', 'id' => $data->file_id . '_thumb')),
                                             $data->path . '/' . $data->file_id . $data->extension,
                                              array('rel' => 'prettyPhoto[pp_gal]')); ?>
    </div>

</div>



        <?php echo CHtml::hiddenField('path', $data->path,array('class'=>'fileinfo'.$data->file_id)); ?>
         <?php echo CHtml::hiddenField('basename', $data->basename,array('class'=>'fileinfo'.$data->file_id)); ?>
         <?php echo CHtml::hiddenField('extension', $data->extension,array('class'=>'fileinfo'.$data->file_id)); ?>
         <?php echo CHtml::hiddenField('file_id', $data->file_id,array('class'=>'fileinfo'.$data->file_id)); ?>
        <?php echo CHtml::hiddenField('title', $data->title,array('class'=>'fileinfo'.$data->file_id)); ?>
        <?php echo CHtml::hiddenField('url', $data->url,array('class'=>'fileinfo'.$data->file_id)); ?>
         <?php echo CHtml::hiddenField('echojson','false',array('class'=>'fileinfo'.$data->file_id)); ?>




 




