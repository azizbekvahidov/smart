<?php
/**
* Nivo Slider Management- NivoSliderManagementModule.php
*
* @author Spiros Kabasakalis <kabasakalis@gmail.com>,myspace.com/spiroskabasakalis
* @copyright Copyright &copy; 2011 Spiros Kabasakalis
* @since 1.0
* @license The MIT License
*/
class NivoSliderManagementModule extends CWebModule
{

     public $defaultController='pl';

     //The following default values can be changed in config/main.php,
     //in module initialization.
     
     //Default layout file is column2 in module's views/layout folder,
     //and it uses application's  //layouts/main as the main layout.
     public $columnLayout='/layouts/column2';

     //Default upload directory,subdirectory of application's assets.
     //You have to create this directory.
     public  $upload_directory="up";

     //Maximum number of images that can be uploaded at once.
     public $max_file_number=10;

     //Maximum file size allowed.
     public $max_file_size= '1mb';


       public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'nivoSliderManagement.models.*',
                        'nivoSliderManagement.components.image.CImageComponent'
		));

        //initialize the component that handles image thumbnail creation
        $this->setComponent('image', new CImageComponent);
        $this->image->params=array('directory'=>'/opt/local/bin');



//Yii::app()->clientScript->registerCoreScript('jquery');
//There are good reasons-related to ajax calls and paging-for
//not using the extensions or core Yii widgets for PrettyPhoto,FancyBox and JuiDialog.

   //Registration of js and css files for PrettyPhoto,FancyBox and JuiDialog.
  $this->registerCssAndJs('nivoSliderManagement.extensions.prettyphoto',
                               '/js/jquery.prettyPhoto.js',
                               '/css/prettyPhoto.css');
  $this->registerCssAndJs('nivoSliderManagement.extensions.jqui1810',
                               '/js/jquery-ui-1.8.10.custom.min.js',
                               '/css/ui-darkness/jquery-ui-1.8.10.custom.css');
  $this->registerCssAndJs('nivoSliderManagement.extensions.fancybox',
                              '/jquery.fancybox-1.3.4.js',
                              '/jquery.fancybox-1.3.4.css');

  //CSS for management page.
 $nscss = YiiBase::getPathOfAlias('nivoSliderManagement.css');
 $nscssbaseUrl = Yii::app()->assetManager->publish($nscss);
 Yii::app()->clientScript->registerCssFile( $nscssbaseUrl.'/ns.css');



	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
                 
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}


        
      private function registerCssAndJs($folder, $jsfile, $cssfile) {
        $sourceFolder = YiiBase::getPathOfAlias($folder);
        $publishedFolder = Yii::app()->assetManager->publish($sourceFolder);
        Yii::app()->clientScript->registerScriptFile($publishedFolder . $jsfile, CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerCssFile($publishedFolder . $cssfile);
    }

    



}
