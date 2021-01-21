<?php


require_once __DIR__ ."/../extensions/vendor/autoload.php";
class ProtokolController extends Controller {

    public $layout = '/layouts/cabinet';

    public function actionProtokol(){

        $protokol = Yii::app()->db->CreateCommand()
            ->select()
            ->from('protokol')
            ->order('protokolId desc')
            ->limit(1)
            ->queryRow();

        $model = Yii::app()->db->CreateCommand()
            ->select()
            ->from("employee")
            ->where("positionId > 1 and status != 1")
            ->order("positionId desc")
            ->queryAll();

        $this->render("protokol",array(
            'protokolNum'=>$protokol["protokolNumber"]+1,
            'model'=>$model
        ));
    }

    public function actionGetResponse(){
        $model = Yii::app()->db->CreateCommand()
            ->select()
            ->from("employee")
            ->where("positionId > 1 and status != 1")
            ->order("positionId desc")
            ->queryAll();
        echo json_encode($model);
    }

    public function actionSaveProtokol(){

        $upload = new UploadFile();
        $format = ['jpg','jpeg','gif','png','xls','xlsx','doc','docx','rar','zip','txt'];
        $participants = "";
        if(isset($_POST["participants"])) {
            foreach ($_POST["participants"] as $val) {
                $participants .= $val . ",";
            }
        }
        $lastId = 0;
        if(isset($_POST["question"])){
            Yii::app()->db->createCommand()->insert('protokol',array(
                'protokolDate' => date("Y-m-d"),
                'protokolType' => $_POST["meetingType"],
                'protokolWriter' => $_POST["stens"],
                'protokolStart' => $_POST["start"],
                'protokolEnd' => date("H:s:i"),
                'protokolTheme' => $_POST["theme"],
                'protokolPlace' => $_POST["place"],
                'protokolStatus' => 0,
                'protokolNumber' => $_POST["num"],
                'participants' => $participants
            ));
            $lastId = Yii::app()->db->getLastInsertID();

            foreach ($_POST["question"] as $key => $val) {
                $response = "";
                foreach ($_POST["response"][$key] as $value) {
                    $response .= $value . ",";
                }
                if(isset($_FILES)) {
                    $func = new Functions();
                    $filename = $func->translitRU($_FILES["file"]["name"][$key]);
                    $uploaded = $upload->upload( $filename, $_FILES["file"]["tmp_name"][$key], $_FILES["file"]["size"][$key],'/upload/files/protokol/'.$_POST["num"].'/',$format);
                    Yii::app()->db->createCommand()->insert('protList', array(
                        'meetQuestion' => $val,
                        'employeeId' => $response,
                        'solve' => "",
                        'deadline' => $_POST["deadline"][$key],
                        'protokolId' => $lastId,
                        'file' => $filename,
                    ));

                    $message = "Вам выставлена задача '". $val ."' по протоколу №".$_POST["num"];
                    $telgram = new telegramBot();
                    $telgram->sendMessageToEmp($response,$message);
                }
                else{
                    Yii::app()->db->createCommand()->insert('protList', array(
                        'meetQuestion' => $val,
                        'employeeId' => $response,
                        'solve' => "",
                        'deadline' => $_POST["deadline"][$key],
                        'protokolId' => $lastId,
                    ));
                    $message = "Вам выставлена задача '". $val ."' по протоколу №".$_POST["num"];
                    $telgram = new telegramBot();
                    $telgram->sendMessageToEmp($response,$message);
                }
            }
            $this->redirect('protokolView?id='.$lastId);
        }
        else{
            $this->redirect('protokol');
        }
    }

    public function actionProtokolView($id){
        $model = Yii::app()->db->CreateCommand()
            ->select()
            ->from("protokol")
            ->where('protokolId = :id',array(':id'=>$id))
            ->queryRow();
        $modelList = Yii::app()->db->CreateCommand()
            ->select()
            ->from('protList')
            ->where('protokolId = :id',array(":id"=>$id))
            ->queryAll();

        $this->render("protokolView",array(
            'model'=>$model,
            'modelList'=>$modelList,
            'id'=>$id
        ));
    }

    public function actionProtokolPrint($id){
        $model = Yii::app()->db->CreateCommand()
            ->select()
            ->from("protokol")
            ->where('protokolId = :id',array(':id'=>$id))
            ->queryRow();
        $modelList = Yii::app()->db->CreateCommand()
            ->select()
            ->from('protList')
            ->where('protokolId = :id',array(":id"=>$id))
            ->queryAll();
        $this->renderPartial("protokolPrint",array(
            'model'=>$model,
            'modelList'=>$modelList,
        ));
    }

    public function actionGetProtokol(){

    }
}