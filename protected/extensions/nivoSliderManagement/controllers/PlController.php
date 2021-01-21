<?php
/**
* Nivo Slider Management Controller file
*
* @author Kabasakalis Spiros <kabasakalis@gmail.com>,myspace.com/spiroskabasakalis
* @copyright Copyright &copy; 2011 Spiros Kabasakalis
* @since 1.0
* @license The MIT License
*/
class PlController extends Controller
{

 
public function init(){
   //sets the layout.
    $this->layout=$this->module->columnLayout;
}

  //Renders the Nivo Slider Management Page
  public function actionIndex() {
      $this->render('index');
  }


  //this action is  ajax-called when _imgupdateform view is rendered.
  //Fetches image info for the particular image that is being updated
 public function actionFetch_image_info() {

$rawimg=new Img();
$img=array();
$rawimg=Img::model()->find('t.file_id=:fileid', array(':fileid'=>$_POST['file_id']));

$img['id']=$rawimg->id;
$img['file_id']=$rawimg->file_id;
$img['basename']=$rawimg->basename;
$img['extension']=$rawimg->extension;
$img['title']=html_entity_decode($rawimg->title);
$img['size']=$rawimg->size;
$img['type']=$rawimg->type;
$img['path']=$rawimg->path;
$img['url']=$rawimg->url;

echo  (json_encode($img));

       }


//This action fetches all links stored in sitestructure table (installed as part of
//cmssitestructuremodule (http://www.yiiframework.com/extension/cmssitestructuremodule) ,
//to populate the options for the image link select control.
    public function actionGetlinks() {

        $ops = "<option value='nolink' selected=\"selected\">No Link</option>";
        $links['nolink'] = array('title' => 'No link', 'url' => 'nolink', 'name' => 'nolink');

// We check to see if cmssitestructuremodule
// is installed,if yes it will fetch the urls of the pages so that the user can choose a link for the image.
// If it's not installed,only the default "No Link" will be available.Of course you can modify this action according
// to your own CMS.

        if (is_file(Yii::getPathOfAlias('application.modules.cms.models') . DIRECTORY_SEPARATOR . 'Cms.php')) {
            Yii::import('application.modules.cms.models.Cms');
            $linkProvider = new CActiveDataProvider('Cms', array(
                        'criteria' => array(
                        'select' => array('url', 'name', 'title')
                        ),
                        'pagination' => array(
                        'pageSize' => 100),
                    ));

            $linksraw = $linkProvider->getData();
            $links = array();

            foreach ($linksraw as $l) {
                $ops = $ops . "<option value='" . $l['url'] . "'>" . $l["title"] . "</option>";
                $links[$l['url']] = array('title' => $l['title'], 'url' => $l['url'], 'name' => $l['name']);
            }
        } //end if
        //we call this action either via ajax or not,handle both cases.
        if (Yii::app()->getRequest()->getPost('echojson') == 'true') {
            echo json_encode($links);
        } else {
            return $ops;
        }
    }

//deletes an image,(file,thumb and database entry).
       public function  actionDelete()
      {

    $app_root=substr( Yii::app()->baseUrl, 1, strlen(Yii::app()->baseUrl)-1);
    $filetodelete= $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR .
                                                              $app_root.DIRECTORY_SEPARATOR .
                                                                   'assets'.DIRECTORY_SEPARATOR .
                            $this->module->upload_directory. DIRECTORY_SEPARATOR .
                                                                 $_POST['file_id'].   $_POST['extension'];

             $thumbtodelete= $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR .
                                                                               $app_root.DIRECTORY_SEPARATOR .
                                                                                'assets'.   DIRECTORY_SEPARATOR .
                                         $this->module->upload_directory. DIRECTORY_SEPARATOR .
                                                                                          'tmb'. DIRECTORY_SEPARATOR .
                                                                 $_POST['file_id'].'_100_100'. '_thumb'.$_POST['extension'];

               //delete the image
                if  ( is_file($filetodelete))  unlink( $filetodelete);
              //delete thumb
              if  ( is_file($thumbtodelete))   unlink( $thumbtodelete);
              //remove from database,file_id is unique for each image.
              Img::model()->deleteAllByAttributes(array('file_id'=>$_POST['file_id']));


             echo json_encode($_POST);

       }


   //this action is called via ajax and renders the update form inside fancy box
    public function actionReturnform() {

        //we don't want to reload the js files
        Yii::app()->clientScript->scriptMap['*.js'] = false;

//get the link options for the select control
        $ops = $this->actionGetlinks();

        $this->renderPartial('_imgupdateform', array('ops' => $ops), false, true);
    }


    //this action updates the title and link of an image,called via ajax
    public function  actionUpdateinfo()
      {
 
  Img::model()->updateAll(array(
                                            
                                                'title'=>filter_input(INPUT_POST, $_POST['file_id'].  '_title_input',FILTER_SANITIZE_STRING),
                                                'url'=>$_POST[$_POST['file_id'].'_url_select'],),
                                                            'file_id=:unique_id',
                                                          array(
                                                               ':unique_id'=>$_POST['file_id'],
                                                                                                    ) ) ;

$response= array('file_id'=>$_POST['file_id'],
                            'title'=>filter_input(INPUT_POST, $_POST['file_id'].  '_title_input',FILTER_SANITIZE_STRING),
                            'url'=>$_POST[$_POST['file_id'].'_url_select']);

   echo json_encode($response);

    }


 //this action submits the title and link-if any- for newly uploaded images
    public function  actionHandle()
      {
    
foreach ($_POST as $unique_id=> $fileinfo) {

     Img::model()->updateAll(array(
                                                     'title'=> filter_var( $fileinfo['title'],FILTER_SANITIZE_STRING),
                                                     'url'=>$fileinfo['url']),
                                                     'file_id=:unique_id',
                                                          array(
                                                               ':unique_id'=>$unique_id,
                                                                                                    ) ) ;
                                                                                              }
}


//This action is called from PluploadWidget,uploads images and creates thumbnails.
 //It is a modified version of the upload.php file that comes with Plupload
         public function  actionUpload()
      {

 Yii::import('nivoSliderManagement.components.image.ImageHelper');

         $filenames=array();

          // HTTP headers for no cache etc
          header('Content-type: text/plain; charset=UTF-8');
          header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
          header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
          header("Cache-Control: no-store, no-cache, must-revalidate");
          header("Cache-Control: post-check=0, pre-check=0", false);
          header("Pragma: no-cache");

          // Settings

          $targetDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . "plupload";
          $cleanupTargetDir = false; // Remove old files
          $maxFileAge = 60 * 60; // Temp file age in seconds

          // 5 minutes execution time
          @set_time_limit(5 * 60);
          // usleep(5000);

          // Get parameters
          $chunk = isset($_REQUEST["chunk"]) ? $_REQUEST["chunk"] : 0;
          $chunks = isset($_REQUEST["chunks"]) ? $_REQUEST["chunks"] : 0;
          $fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';

          // Clean the fileName for security reasons
          $fileName = preg_replace('/[^\w\._\s]+/', '', $fileName);

          // Create target dir
          if (!file_exists($targetDir))
                  @mkdir($targetDir);

          // Remove old temp files
          if (is_dir($targetDir) && ($dir = opendir($targetDir))) {
                  while (($file = readdir($dir)) !== false) {
                          $filePath = $targetDir . DIRECTORY_SEPARATOR . $file;

                          // Remove temp files if they are older than the max age
                          if (preg_match('/\\.tmp$/', $file) && (filemtime($filePath) < time() - $maxFileAge))
                                  @unlink($filePath);
                  }

                  closedir($dir);
          } else
                  throw new CHttpException (500, Yii::t('app', "Can't open temporary directory."));

       

          // Look for the content type header
          if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
                  $contentType = $_SERVER["HTTP_CONTENT_TYPE"];

          if (isset($_SERVER["CONTENT_TYPE"]))
                  $contentType = $_SERVER["CONTENT_TYPE"];



      if (strpos($contentType, "multipart") !== false) {

           
                  if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
                          // Open temp file
                           
                          $out = fopen($targetDir . DIRECTORY_SEPARATOR . $fileName, $chunk == 0 ? "wb" : "ab");
                          if ($out) {
                                  // Read binary input stream and append it to temp file
                                  $in = fopen($_FILES['file']['tmp_name'], "rb");

                              

                                  if ($in) {
                                          while ($buff = fread($in, 4096))
                                                  fwrite($out, $buff);
            
  
                                  } else
                                          throw new CHttpException (500, Yii::t('app', "Can't open input stream."));

                                  fclose($out);

 
                       
                                  
                                 @unlink($_FILES['file']['tmp_name']);

                     

                          } else
                                  throw new CHttpException (500, Yii::t('app', "Can't open output stream."));
                  } else
                          throw new CHttpException (500, Yii::t('app', "Can't move uploaded file."));
          } else {

                    
                  // Open temp file
                  $out = fopen($targetDir . DIRECTORY_SEPARATOR . $fileName, $chunk == 0 ? "wb" : "ab");
                  if ($out) {
                          // Read binary input stream and append it to temp file
                         $in = fopen("php://input", "rb");

                          if ($in) {
                                  while ($buff = fread($in, 4096))
                                          fwrite($out, $buff);
                          } else
                                  throw new CHttpException (500, Yii::t('app', "Can't open input stream."));

                          fclose($out);
                  } else
                          throw new CHttpException (500, Yii::t('app', "Can't open output stream."));
          }




    
          //process the file
         $file_id= substr($fileName, 0, strripos($fileName, '.'));
         $filename=  $_FILES['file']['name'];
         $file_basename=substr($filename, 0, strripos($filename, '.'));
         $file_ext      = substr($filename, strripos($filename, '.'));
         $file_size=$this->display_filesize( $_FILES['file']['size']);
         $file_type=  $_FILES['file']['type'];
         $file_error=  $_FILES['file']['error'];
        $app_root=substr( Yii::app()->baseUrl, 1, strlen(Yii::app()->baseUrl)-1);

            $upload_path=                                          '/'.
                                                                              $app_root.'/' .
                                                                                'assets'.  '/'.
                                                                               $this->module->upload_directory;


          //prepare Img instance  to save in database
                $newimage=new Img();
                             $newimage->basename=str_replace(' ','',$file_basename);
                             $newimage->extension=$file_ext;
                             $newimage->size=$file_size;
                             $newimage->type=$file_type;
                             $newimage->path=$upload_path;
                             $newimage->file_id=$file_id;



         //Return Array
          $ret = array('result' => '1',
                               'file_error'=> $file_error,
                               'filename'=> str_replace(' ','',$filename),
                               'file_id'=> $file_id,
                               'file_basename'=> str_replace(' ','',$file_basename),
                               'file_ext'=> $file_ext,
                               'file_size'=> $file_size,
                               'file_type'=> $file_type,                  
                              'upload_path'=> $upload_path,
                            

                                                                              );


          if (intval($chunk) + 1 >= intval($chunks)) {

              
              $originalname = $fileName;

            
              if (isset($_SERVER['HTTP_CONTENT_DISPOSITION'])) {
                  $arr = array();
                  preg_match('@^attachment; filename="([^"]+)"@',$_SERVER['HTTP_CONTENT_DISPOSITION'],$arr);
                  if (isset($arr[1]))
                      $originalname = $arr[1];
         
              }

              // **********************************************************************************************
              // Do whatever you need with the uploaded file, which has $originalname as the original file name
              // and is located at $targetDir . DIRECTORY_SEPARATOR . $fileName
              // **********************************************************************************************

              $temppath=$targetDir . DIRECTORY_SEPARATOR . $fileName;
              
              $app_root=substr( Yii::app()->baseUrl, 1, strlen(Yii::app()->baseUrl)-1);


               $dest= $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR .
                                                                               $app_root.DIRECTORY_SEPARATOR .
                                                                                'assets'.     DIRECTORY_SEPARATOR .
                                    $this->module->upload_directory. DIRECTORY_SEPARATOR .
                                                                                      $file_id.$file_ext;


       
                //copy from temporary to final directory
                @copy($temppath, $dest);
                //create thumb,(destination is tmb directory inside upload_directory directory,see ImageHelper.php of
                //image component
               ImageHelper::thumb(100, 100, $dest);
                //save in database
                $newimage->save();
          }
  

          // Return response
          die(json_encode($ret));

      

      }


     //helper function to format file size
 public    function display_filesize($filesize){

    if(is_numeric($filesize)){
    $decr = 1024; $step = 0;
    $prefix = array('Byte','KB','MB','GB','TB','PB');

    while(($filesize / $decr) > 0.9){
        $filesize = $filesize / $decr;
        $step++;
    }
    return round($filesize,0).' '.$prefix[$step];
    } else {

    return 'NaN';
    }

}
      

}