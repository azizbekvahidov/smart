<?php
require_once __DIR__ ."/../extensions/vendor/autoload.php";

class ApiController extends Controller{

    public function actionGetModel(){
        $model = Yii::app()->db->createCommand()
            ->select("model")
            ->from('phone')
            ->queryAll();

        echo json_encode($model);
    }

    public function actionGetSpare(){
        $model = Yii::app()->db->createCommand()
            ->select("name")
            ->from('spare')
            ->order("name")
            ->queryAll();

        echo json_encode($model);
    }
    public function actionGetError(){
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from('error')
            ->order("descUz")
            ->queryAll();

        echo json_encode($model);
    }

    public function actionScanRepair(){
        $model = Yii::app()->db->createCommand()
            ->select("r.registerId as registrId, p.model as model, e.descUz as errorOtk")
            ->from("register r")
            ->join("phone p","p.phoneId = r.phoneId")
            ->join("error e","e.errorId = r.errorIdOtk")
            ->where("r.registerCode = '".$_POST["code"]."'")
            ->queryRow();
        $response = array();
        if(!empty($model)){
            $response = $model;
            $response["success"] = true;
        }
        else{
            $response["success"] = false;
        }
        echo json_encode($response);
    }

    public function actionRegisterRepair(){

        $spare = $_POST["spare"];
        $userId = $_POST["userId"];
        $errorId = Yii::app()->db->createCommand()
            ->select("errorId")
            ->from("error")
            ->where("descUz = :desc",array(":desc"=>$_POST["errorId"]))
            ->queryRow();
        if($spare == ""){
            $spareId["spareId"] = 0;
        }
        else {
            $spareId=Yii::app()->db->createCommand()
                ->select()
                ->from("spare")
                ->where("name = :name", array(":name"=>$spare))
                ->queryRow();
        }
        $res = Yii::app()->db->createCommand()->update("register", array(
            "solve" => $_POST["repairText"],
            "spareId"=>$spareId["spareId"],
            "userIdRepair"=>$userId,
            "status"=>1,
            'cause'=>$_POST["cause"],
            //'errorIdRepair' =>$errorId["errorId"] ,
            "errorRepairDate" => date("Y-m-d H:i:s")
        ),"registerId = :id",array(":id"=>$_POST["registerId"]));
        $response = array();
        if($res == 1){
            $response["success"] = true;
        }


        else{
            $response["success"] = false;
        }
        echo json_encode($response);
    }

    public function actionRegisterRepairSave(){


        $spare = $_POST["spare"];
        $userId = $_POST["userId"];
        $position = $_POST["position"];
        if($_POST["errorId"] != "") {
            $errorId = Yii::app()->db->createCommand()
                ->select("errorId")
                ->from("error")
                ->where("descUz = :desc", array(":desc" => $_POST["errorId"]))
                ->queryRow();
        }
        else{
            $errorId = Yii::app()->db->createCommand()
                ->select("errorId")
                ->from("error")
                ->where("descUz = :desc", array(":desc" => $_POST["errorText"]))
                ->queryRow();
        }
        
        $register = Yii::app()->db->CreateCommand()
            ->select()
            ->from("register")
            ->where("registerId = :id",array(":id"=>$_POST["registerId"]))
            ->queryRow();

        $linepos = Yii::app()->db->CreateCommand()
            ->select()
            ->from("lineposition lp")
            ->join("lineposconn lpc","lpc.positionId = lp.positionId")
            ->where("lp.phoneId = :pId and lpc.errorId = :id and lp.name like '%".$_POST["position"]."%'",array(":pId"=>$register["phoneId"],":id"=>$errorId["errorId"]))
            ->queryRow();

        $lineoperate = Yii::app()->db->CreateCommand()
            ->select()
            ->from("action")
            ->where("action = 'line' and actionType = 'line' and reason like '%".$_POST["position"]."%'")
            ->order("actionId desc")
            ->limit(1)
            ->queryRow();
        if($spare == ""){
            $spareId["spareId"] = 0;
        }
        else {
            $spareId=Yii::app()->db->createCommand()
                ->select()
                ->from("spare")
                ->where("name = :name", array(":name"=>$spare))
                ->queryRow();
        }
        $res = Yii::app()->db->createCommand()->update("register", array(
            "solve" => $_POST["repairText"],
            "spareId"=>$spareId["spareId"],
            "userIdRepair"=>$userId,
            "status"=>1,
            'cause'=>$_POST["cause"],
            'errorIdRepair' =>$errorId["errorId"] ,
            "errorRepairDate" => date("Y-m-d H:i:s")
        ),"registerId = :id",array(":id"=>$_POST["registerId"]));
        if($_POST["position"] != "" && !empty($linepos) && !empty($lineoperate))
            $this->actionAddBall($lineoperate["employeeId"], $linepos["departmentId"], "error", $errorId["errorId"]);

        $response = array();
        if($res == 1){
            $response["success"] = true;
        }


        else{
            $response["success"] = false;
        }
        echo json_encode($response);
    }

    public function actionRegister(){
        $func = new telegramBot();
        $model = $_POST["model"];
        $error = $_POST["error"];
        $code = $_POST["code"];
        $userId = $_POST["userId"];
        $checkCode = Yii::app()->db->createCommand()
            ->select()
            ->from("register")
            ->where("registerCode = :code",array(":code"=>$code))
            ->queryRow();
        Yii::app()->db->createCommand()->insert("some",array(
            'temp' => $checkCode["registerCode"]
        ));
        $phoneId = Yii::app()->db->createCommand()
            ->select("")
            ->from("phone")
            ->where("model = :model",array(":model"=>$model))
            ->queryRow();
        $errorId = Yii::app()->db->createCommand()
            ->select("errorId")
            ->from("error")
            ->where("descUz = :desc",array(":desc"=>$error))
            ->queryRow();
        $res = 0;
        $response = array();
        $response["success"] = false;
        if(empty($checkCode) && isset($_POST["code"])) {
            $res = Yii::app()->db->createCommand()->insert("register", array(
                "errorIdOtk"=>$errorId["errorId"],
                "phoneId"=>$phoneId["phoneId"],
                "registerCode"=>$code,
                "userIdOtk"=>$userId,
                "status" => 0,
                "errorOtkDate" => date("Y-m-d H:i:s")
            ));
        }
        if($res == 1){
            $func->sendErrorMessage($errorId["errorId"],$phoneId["phoneId"]);
            $response["success"] = true;
        }
        else{
            $response["success"] = false;
        }
        //$this->actionAddBall($lineoperate["employeeId"], $linepos["departmentId"], "error", $errorId);

        echo json_encode($response);
    }

    public function actionTestSend(){
        $func = new telegramBot();
//
        $func->sendErrorMessage(47,1);
    }

    public function actionTelegramSellerApi(){
        $func = new telegramBot();

        $updates = $func->getUpdatesSeller();
        foreach($updates as $update){
            if(preg_match('/^[0-9]{2}-[0-9]{3}-[0-9]{2}-[0-9]{2}$/', $update->message->text)) {
                $check = Yii::app()->db->createCommand()
                    ->select()
                    ->from("telegramBot")
                    ->where('chatId = :id',array(':id'=>$update->message->chat->id))
                    ->queryRow();
                if(!empty($check)){
                    $func->sendMessageSeller($update->message->chat->id, "Вы уже зарегистрированы");
                }
                else{
                    $insert = Yii::app()->db->createCommand()->insert("telegramBot",array(
                        'phone' => $update->message->text,
                        'chatId' => $update->message->chat->id
                    ));
                    if($insert)
                        $func->sendMessageSeller($update->message->chat->id, "Вы зарегистрировались");
                    else
                        $func->sendMessageSeller($update->message->chat->id, "Не удалось пройти регистрацию попробуйте позже");

                }
            }
            else if($update->message->text == '/start'){
                $func->sendMessageSeller($update->message->chat->id, "Введите телефонный номер для регистрации в формате 97-123-45-67");
            }
            else{
                $check = Yii::app()->db->createCommand()
                    ->select()
                    ->from("telegramBot")
                    ->where('chatId = :id',array(':id'=>$update->message->chat->id))
                    ->queryRow();
                if(!empty($check)){
                    $func->sendMessageSeller($update->message->chat->id, "Вы уже зарегистрированы не пишите всякую чужь");
                }
                else{
                    $func->sendMessageSeller($update->message->chat->id, "Введите телефонный номер для регистрации");
                }
            }
        }
    }


    public function actionTelegramInfoApi(){
        $func = new telegramBot();

        $updates = $func->getUpdatesInfo();
        foreach($updates as $update){
            if(preg_match('/^[0-9]{2}-[0-9]{3}-[0-9]{2}-[0-9]{2}$/', $update->message->text)) {
                $check = Yii::app()->db->createCommand()
                    ->select()
                    ->from("telegramBot")
                    ->where('chatId = :id',array(':id'=>$update->message->chat->id))
                    ->queryRow();
                if(!empty($check)){
                    $func->sendMessageInfo($update->message->chat->id, "Вы уже зарегистрированы");
                }
                else{
                    $insert = Yii::app()->db->createCommand()->insert("telegramBot",array(
                        'phone' => $update->message->text,
                        'chatId' => $update->message->chat->id
                    ));
                    if($insert)
                        $func->sendMessageInfo($update->message->chat->id, "Вы зарегистрировались");
                    else
                        $func->sendMessageInfo($update->message->chat->id, "Не удалось пройти регистрацию попробуйте позже");

                }
            }
            else if($update->message->text == '/start'){
                $func->sendMessageInfo($update->message->chat->id, "Введите телефонный номер для регистрации в формате 97-123-45-67");
            }
            else{
                $check = Yii::app()->db->createCommand()
                    ->select()
                    ->from("telegramBot")
                    ->where('chatId = :id',array(':id'=>$update->message->chat->id))
                    ->queryRow();
                if(!empty($check)){
                    $func->sendMessageInfo($update->message->chat->id, "Вы уже зарегистрированы не пишите всякую чужь");
                }
                else{
                    $func->sendMessageInfo($update->message->chat->id, "Введите телефонный номер для регистрации");
                }
            }
        }
    }

    public function actionUserRegTelegram($chatId,$text){
        $insert = Yii::app()->db->createCommand()->insert("telegramBot",array(
            'phone' => $text,
            'chatId' => $chatId
        ));
        echo $insert;
    }

    public function actionCheckTelegramUser($chatId){
        $res = false;
        $check = Yii::app()->db->createCommand()
            ->select()
            ->from("telegramBot")
            ->where('chatId = :id',array(':id'=>$chatId))
            ->queryRow();
        (!empty($check)) ? $res = true: $res = false;
        echo $res;
    }

    public function actionAddBall($empId, $depId, $type, $id){
        try{
            $lineRate = Yii::app()->db->CreateCommand()
                ->select()
                ->from("linerate")
                ->where("departmentId = ".$depId." AND ratetype like '%".$type."%' AND id = ".$id)
                ->queryRow();
            $ball = Yii::app()->db->CreateCommand()
                ->select()
                ->from("operatorBall")
                ->where("rateDate = :date and employeeId = :empId",array(":date"=>date("Y-m-d"),":empId"=>$empId))
                ->queryRow();
            if(!empty($ball)){
                Yii::app()->db->createCommand()->update("operatorBall",array(
                    "ball" => $ball["ball"] + $lineRate["rate"],
                ));
            }
            else{
                Yii::app()->db->createCommand()->insert("operatorBall",array(
                    "rateDate" =>date("Y-m-d"),
                    "ball" => $lineRate["rate"],
                    "employeeId" => $empId,
                ));
            }
            Yii::app()->db->createCommand()->insert("archiveoperationrate",array(
                "rateDate" =>date("Y-m-d H:i:s"),
                "rateType" => $lineRate["ratetype"],
                "employeeId" => $empId,
                "lineRateId" => $lineRate["lineRateId"],
            ));
            $file = fopen('upload/log.txt',a);
            $text = "empId:".$empId.";depId:".$depId.";type:".$type.";id:".$id."\r\n";
            fwrite($file,$text);
            fclose($file);
        }
        catch(Exception $e){
            
            $file = fopen('upload/log.txt',a);
            $text = $e->getMessage()."\r\n";
            fwrite($file,$text);
            fclose($file);
        }
    }

    public function actionRemoveBall(){

    }


    public function actionGetPosition($id,$model){
        $phone = Yii::app()->db->CreateCommand()
            ->select()
            ->from("phone")
            ->where("model like '%".$model."%'")
            ->queryRow();
        $linepos = Yii::app()->db->CreateCommand()
            ->select()
            ->from("lineposition lp")
            //->join("lineposconn lpc","lpc.positionId = lp.positionId")
            //->join("error e","e.errorId = lpc.errorId")
            //->where("lp.phoneId = :pId and e.descUz like '%".$id."%'",array(":pId"=>$phone["phoneId"]))
            ->where("lp.phoneId = :pId",array(":pId"=>$phone["phoneId"]))
            ->queryAll();
        echo json_encode($linepos);
    }
}