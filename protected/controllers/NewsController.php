<?php

class NewsController extends Controller{

    public function actionAdmin(){
        $func = new Functions();
        $newsType = $func->GetContentType();
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("news")
            ->where('status = 0 and newsType = :type',array(":type"=>$newsType))
            ->queryAll();
        $this->render("admin",array(
            'model' => $model
        ));
    }

    public function actionCreate(){
        $func = new Functions();
        $newsType = $func->GetContentType();
        $model = array();
        if(isset($_POST["news"])){

            if($_FILES["file"]["name"] != "") {
                $target_dir=$_SERVER["DOCUMENT_ROOT"] . "/upload/news/";
                $target_file=$target_dir . basename($_FILES["file"]["name"]);
                $uploadOk=1;
                $imageFileType=strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
                if (isset($_FILES["file"])) {
                    $check=getimagesize($_FILES["file"]["tmp_name"]);
                    if ($check !== false) {
                        $message="Файл изображение - " . $check["mime"] . ".";
                        $uploadOk=1;
                    } else {
                        $message="Этот файл не изображение";
                        $uploadOk=0;
                    }
                }
// Check if file already exists
                if (file_exists($target_file)) {
                    $message="Извените, файл с таким названием уже существует.";
                    $uploadOk=0;
                }
// Check file size
//        if ($_FILES["file"]["size"] > 500000) {
//            $message = "Извените, ваш файл слишком большой.";
//            $uploadOk = 0;
//        }
// Allow certain file formats
                if ($imageFileType != "jpg"&&$imageFileType != "png"&&$imageFileType != "jpeg"
                    &&$imageFileType != "gif"
                ) {
                    $message="Извените, но принимаются только JPG, JPEG, PNG & GIF форматы.";
                    $uploadOk=0;
                }
// Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 0) {
                    echo "Извените, ваш файл не загрузился.";
// if everything is ok, try to upload file
                } else {
                    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {


                        $message="Файл с названием " . basename($_FILES["file"]["name"]) . " был загружен.";
                        $uploaded=true;

                    } else {
                        $message="Извените, произошла ошибка при загрузке файла.";
                        $uploaded=false;
                    }
                }
            }
            Yii::app()->db->createCommand()->insert("news",array(
                "header" => $_POST["news"]["header"],
                "content" => $_POST["news"]["content"],
                "newsDate" => date("Y-m-d H:i:s"),
                "newsType" => $newsType,
                "foto" => $_FILES["file"]["name"]
            ));
            $this->redirect("/news/admin");
        }
        $this->render("create",array(
            "model" => $model
        ));
    }

    public function actionUpdate($id){
        $func = new Functions();
        $newsType = $func->GetContentType();
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("news")
            ->where("newsId = :id",array(":id"=>$id))
            ->queryRow();;
        if(isset($_POST["news"])){

            if($_FILES["file"]["name"] != "") {
                $target_dir=$_SERVER["DOCUMENT_ROOT"] . "/upload/news/";
                $target_file=$target_dir . basename($_FILES["file"]["name"]);
                $uploadOk=1;
                $imageFileType=strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
                if (isset($_FILES["file"])) {
                    $check=getimagesize($_FILES["file"]["tmp_name"]);
                    if ($check !== false) {
                        $message="Файл изображение - " . $check["mime"] . ".";
                        $uploadOk=1;
                    } else {
                        $message="Этот файл не изображение";
                        $uploadOk=0;
                    }
                }
// Check if file already exists
                if (file_exists($target_file)) {
                    $message="Извените, файл с таким названием уже существует.";
                    $uploadOk=0;
                }
// Check file size
//        if ($_FILES["file"]["size"] > 500000) {
//            $message = "Извените, ваш файл слишком большой.";
//            $uploadOk = 0;
//        }
// Allow certain file formats
                if ($imageFileType != "jpg"&&$imageFileType != "png"&&$imageFileType != "jpeg"
                    &&$imageFileType != "gif"
                ) {
                    $message="Извените, но принимаются только JPG, JPEG, PNG & GIF форматы.";
                    $uploadOk=0;
                }
// Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 0) {
                    echo "Извените, ваш файл не загрузился.";
// if everything is ok, try to upload file
                } else {
                    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {


                        $message="Файл с названием " . basename($_FILES["file"]["name"]) . " был загружен.";
                        $uploaded=true;

                    } else {
                        $message="Извените, произошла ошибка при загрузке файла.";
                        $uploaded=false;
                    }
                }
            }

            Yii::app()->db->createCommand()->update("news",array(
                "header" => $_POST["news"]["header"],
                "content" => $_POST["news"]["content"],
                "newsDate" => date("Y-m-d H:i:s"),
                "newsType" => $newsType,
                "foto" => $_FILES["file"]["name"]
            ),"newsId = :id",array(":id"=>$id));
            $this->redirect("/news/admin");
        }
        $this->render("create",array(
            "model" => $model
        ));
    }

    public function actionDelete($id){
        Yii::app()->db->createCommand()->update("news",array(
            'status' => 1
        ),"newsId = :id",array(":id"=>$id));
        $this->redirect("/news/admin");
    }

}