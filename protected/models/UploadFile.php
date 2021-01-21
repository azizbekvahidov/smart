<?php

class UploadFile extends CFormModel{
    public $upload_file;

    public function rules()
    {
        return array(
            array(
                'upload_file',
                'file',
                'allowEmpty' => true,
                'types'=>'jpg,jpeg,gif,png,xls,xlsx,doc,docx,rar,zip,txt',
                'maxSize'=>10*1024*1024,
                'tooLarge'=>'The file was larger than 50MB. Please upload a smaller file.',),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'upload_file'=>'Upload File',
        );
    }

    public function upload($fileName,$tempName,$size,$path,$format){

        $uploaded = false;
        $message = "";

        $target_dir = $_SERVER["DOCUMENT_ROOT"].$path;
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        else{
            $message = "Не получилось создать папку";
        }
        $target_file = $target_dir . $fileName;
        $uploadOk = 1;
        $fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if file already exists
        if (file_exists($target_file)) {
            $message = "Извените, файл с таким названием уже существует.";
            $uploadOk = 0;
        }
// Check file size
        if ($size > 100*1024*1024) {
            $message = "Извените, ваш файл слишком большой.";
            $uploadOk = 0;
        }

// Allow certain file formats
        if(!in_array($fileType,$format))  {
            $message = "Формат файла не подходит";
            $uploadOk = 0;
        }
// Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $message .=  "Извените, ваш файл не загрузился.";
// if everything is ok, try to upload file
        }
        else {
            if (move_uploaded_file($tempName, $target_file)) {

                $message = "Файл с названием ". basename( $fileName). " был загружен.";
                $uploaded = true;

            } else {
                $message = "Извените, произошла ошибка при загрузке файла.";
                $uploaded = false;
            }
        }
        $result['message'] = $message;
        $result['result'] = $uploaded;
        return $result;
    }

}