<?php

class ImageController extends CController {

    public function actionUploadImage($type){
//        require $phpPath.'/autoload.php';

        $uploaded = false;
        $message = "";
        $target_dir = $_SERVER["DOCUMENT_ROOT"]."/upload/ftqImages/";
        $target_file = $target_dir . basename($_FILES["file"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
        if(isset($_FILES["file"])) {
            $check = getimagesize($_FILES["file"]["tmp_name"]);
            if($check !== false) {
                $message = "Файл изображение - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                $message = "Этот файл не изображение";
                $uploadOk = 0;
            }
        }
// Check if file already exists
        if (file_exists($target_file)) {
            $message = "Извените, файл с таким названием уже существует.";
            $uploadOk = 0;
        }
// Check file size
//        if ($_FILES["file"]["size"] > 500000) {
//            $message = "Извените, ваш файл слишком большой.";
//            $uploadOk = 0;
//        }
// Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
            $message = "Извените, но принимаются только JPG, JPEG, PNG & GIF форматы.";
            $uploadOk = 0;
        }
// Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Извените, ваш файл не загрузился.";
// if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {

//                $setting = array(
//                    'directory' => 'compressed', // directory file compressed output
//                    'file_type' => array( // file format allowed
//                        'image/jpeg',
//                        'image/png',
//                        'image/gif'
//                    )
//                );
//
//// create object
//                $ImgCompressor = new ImgCompressor($setting);
//
//// run('STRING original file path', 'output file type', INTEGER Compression level: from 0 (no compression) to 9);
//                $result = $ImgCompressor->run($target_file, 'jpg', 5); // example level = 2 same quality 80%, level = 7 same quality 30% etc

                $phpPath = Yii::getPathOfAlias('ext.compress-image');
                spl_autoload_unregister(array('YiiBase','autoload'));
                Yii::import('ext.compress-image/autoload', true);
                $file = $target_file; //file that you wanna compress
                $new_name_image = "compress_".$_FILES["file"]["name"]; //name of new file compressed
                $quality = 60; // Value that I chose

                $image_compress = new Compress($file, $new_name_image, $quality, $target_dir."compress/");
                $image_compress->compress_image();

                spl_autoload_register(array('YiiBase','autoload'));
                $message = "Файл с названием ". basename( $_FILES["file"]["name"]). " был загружен.";
                $uploaded = true;

            } else {
                $message = "Извените, произошла ошибка при загрузке файла.";
                $uploaded = false;
            }
        }

        if($uploaded == true){
            Yii::app()->db->createCommand()->insert("photo",array(
                'name' => $_FILES["file"]["name"],
                'path' => $target_dir,
            ));
            $id = Yii::app()->db->getLastInsertId();
            if($type == "register"){
                $func = new Functions();
                $func->insertRegisterPhoto($id,$_POST["id"]);
            }
        }
        echo $message;
    }
}