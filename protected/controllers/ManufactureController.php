<?php
error_reporting(0);
require_once __DIR__ ."/../extensions/vendor/autoload.php";
class ManufactureController extends Controller{

    public function actionInvoice(){
        $model = Yii::app()->db->CreateCommand()
            ->select()
            ->from("phone")
            ->where("status = 0")
            ->queryAll();
        
        $this->render("invoice",array(
            'model'=>$model
        ));

    }

    public function BalanceCheck($curDate, $data){

        $checkBalance = Yii::app()->db->CreateCommand()
            ->select()
            ->from("manufacBalance")
            ->where("balanceDate = :date", array(":date" => $curDate))
            ->queryRow();
        if(!empty($checkBalance)){
            $temp = Yii::app()->db->CreateCommand()
                ->select()
                ->from("manufacBalance")
                ->where("phoneId = :pId and colorId = :cId and departmentId = :depId and balanceDate = :date", array(":pId" => $data["model"], ":cId" => $data["color"], ":depId" => 4,":date" => $curDate))
                ->queryRow();
            if (empty($temp)) {
                Yii::app()->db->createCommand()->insert("manufacBalance", array(
                    'departmentId' => 4,
                    'cnt' => $data["cnt"],
                    'balanceDate' => $curDate,
                    'phoneId' => $data["model"],
                    'colorId' => $data["color"]
                ));
            }
            else{
                Yii::app()->db->createCommand("update manufacBalance set cnt = cnt + ".$data["cnt"]." where departmentId = 4 and colorId = ".$data["color"]." and phoneId = ".$data["model"]." and balanceDate = '".$curDate."'")->execute();
            }
        }
        else {
            $balance = Yii::app()->db->CreateCommand()
                ->select()
                ->from("manufacBalance")
                ->having("balanceDate = (select max(balanceDate) from manufacBalance )")
                ->queryAll();
            foreach ($balance as $val) {
                if($data["model"] == $val["phoneId"] && $data["color"] == $val["colorId"] && $val["departmentId"] == 4){
                    $trumb = true;
                }
                Yii::app()->db->createCommand()->insert("manufacBalance", array(
                    'departmentId' => $val["departmentId"],
                    'cnt' => $val["cnt"],
                    'balanceDate' => $curDate,
                    'phoneId' => $val["phoneId"],
                    'colorId' => $val["colorId"]
                ));
            }
            if($trumb) {
                Yii::app()->db->createCommand("update manufacBalance set cnt = cnt + ".$data["cnt"]." where departmentId = 4 and colorId = ".$data["color"]." and phoneId = ".$data["model"]." and balanceDate = '".$curDate."'")->execute();
            }
            else{
                Yii::app()->db->createCommand()->insert("manufacBalance", array(
                    'departmentId' => 4,
                    'cnt' => $data["cnt"],
                    'balanceDate' => $curDate,
                    'phoneId' => $data["model"],
                    'colorId' => $data["color"]
                ));
            }
        }
    }

    public function actionInvoiceAdd(){
        $message = array();
        $curDate = date("Y-m-d");
        if($_POST["cnt"] != '' && $_POST["model"] != '') {
            $trumb = false;
            Yii::app()->db->createCommand()->insert("manufacInvoice", array(
                'invoiceDate' => date("Y-m-d H:i:s"),
                'userId' => $userId,
                'cnt' => $_POST["cnt"],
                'phoneId' => $_POST["model"],
                'colorId' => $_POST["color"]
            ));
            $this->BalanceCheck($curDate,$_POST);
            $message = array("messageText"=>'Запись добавлена',"messageType"=>"success");
        }
        else{
            $message = array("messageText"=>'Не заполнены поля',"messageType"=>"danger");
        }
        echo  json_encode($message);
    }

    public function actionBalance(){
        $curDate = $_POST["date"];

        $dep = Yii::app()->db->CreateCommand()
            ->select()
            ->from("manufacBalance b")
            ->join("phone p","p.phoneId = b.phoneId")
            ->where("b.balanceDate = '".$curDate."' and cnt != 0")
            ->group("b.phoneId")
            ->queryAll();
        //json_encode($model);
        $this->renderPartial("balance",array(
            'dep' => $dep
        ));
    }

    public function actionSetProduce(){
        $model = Yii::app()->db->CreateCommand()
            ->select()
            ->from("phone")
            ->where("status = 0")
            ->queryAll();
        $dep = Yii::app()->db->CreateCommand()
            ->select()
            ->from("department")
            ->queryAll();

        $this->render("setProduce",array(
            'model' => $model,
            'dep' => $dep
        ));
    }

    public function actionProduceAdd(){
        $message = array();
        if($_POST["cnt"] != '' && $_POST["model"] != '' && $_POST["department"] != '') {
            Yii::app()->db->createCommand()->insert("manufacProduce", array(
                'produceDate' => date("Y-m-d H:i:s"),
                'cnt' => $_POST["cnt"],
                'departmentId' => $_POST["department"],
                'phoneId' => $_POST["model"],
                'colorId' => $_POST["color"]
            ));
            $message = array("messageText"=>'Запись добавлена',"messageType"=>"success");
        }
        else{
            $message = array("messageText"=>'Не заполнены поля',"messageType"=>"danger");
        }
        echo  json_encode($message);
    }

    public function actionSetBalance(){
        $message = array();
        if($_POST["cnt"] != '' && $_POST["model"] != '' && $_POST["department"] != '') {
            Yii::app()->db->createCommand()->insert("manufacBalance", array(
                'balanceDate' => date("Y-m-d H:i:s"),
                'cnt' => $_POST["cnt"],
                'departmentId' => $_POST["department"],
                'phoneId' => $_POST["model"],
                'colorId' => $_POST["color"]
            ));
            $message = array("messageText"=>'Запись добавлена',"messageType"=>"success");
        }
        else{
            $message = array("messageText"=>'Не заполнены поля',"messageType"=>"danger");
        }
        echo  json_encode($message);
    }

    public function actionCalculateProduce(){
        $curDate = date("Y-m-d");
        $result = array();
//        $registerProduce = Yii::app()->db->CreateCommand()
//            ->select("phoneId,count(*) as cnt")
//            ->from("register")
//            ->where('date(errorRepairDate) = :date',array(':date'=>$curDate))
//            ->group('phoneId')
//            ->queryAll();
//        foreach ($registerProduce as $val) {
//            Yii::app()->db->createCommand()->insert("manufacProduce",array(
//                'departmentId' => 6,
//                'cnt' => $val["cnt"],
//                'produceDate' => $curDate,
//                'phoneId' => $val["phoneId"],
//                'colorId' => 1
//            ));
////            $result["produce"][6][$val["phoneId"]][1] = [$val["cnt"]];
//        }
        $print = Yii::app()->db->CreateCommand()
            ->select("p.phoneId,s.colorId,count(*) as cnt")
            ->from("print p")
            ->join("sn s","SUBSTRING(s.code,1,8) = SUBSTRING(p.SN,1,8)")
            ->where("date(p.printDate) = :date",array(':date'=>$curDate))
            ->group('p.phoneId,s.colorId')
            ->queryAll();

        foreach ($print as $val) {
            Yii::app()->db->createCommand()->insert("manufacProduce",array(
                'departmentId' => 10,
                'cnt' => $val["cnt"],
                'produceDate' => $curDate,
                'phoneId' => $val["phoneId"],
                'colorId' => $val["colorId"]
            ));
            $result["produce"][10][$val["phoneId"]][$val["colorId"]] = [$val["cnt"]];
        }
        $produce = Yii::app()->db->CreateCommand()
            ->select('p.phoneId,p.colorId,count(*) as cnt')
            ->from('stockin s')
            ->join("produce p","p.produceId = s.produceId")
            ->where('date(s.stockinDate) = :date',array(':date'=>$curDate))
            ->group('p.phoneId,p.colorId')
            ->queryAll();
        foreach ($produce as $val) {
            Yii::app()->db->createCommand()->insert("manufacProduce",array(
                'departmentId' => 5,
                'cnt' => $val["cnt"],
                'produceDate' => $curDate,
                'phoneId' => $val["phoneId"],
                'colorId' => $val["colorId"]
            ));
            $result["produce"][5][$val["phoneId"]][$val["colorId"]] = [$val["cnt"]];
        }
        $this->redirect("setProduce");

    }

    public function actionCalculateBalance(){

        $curDate = date("Y-m-d");
        $result = array();


        $register = Yii::app()->db->CreateCommand()
            ->select("phoneId,count(*) as cnt")
            ->from("register")
            ->where("date(errorOtkDate) = :otk and (date(errorRepairDate) < :repair OR status = 0) ",array(":otk"=>$curDate,":repair" => $curDate))
            ->group('phoneId')
            ->queryAll();
        Yii::app()->db->createCommand("update manufacBalance set cnt = 0 where departmentId = 6 and balanceDate = '".$curDate."'")->execute();

        foreach ($register as $val) {
            $balance = Yii::app()->db->CreateCommand()
                ->select()
                ->from("manufacBalance")
                ->where("departmentId = :depId AND phoneId = :pId and colorId = :cId and balanceDate = :date", array(":depId" => 6, ":pId" => $val["phoneId"], ":cId" => 1,":date"=>$curDate))
                ->order("balanceDate desc")
                ->queryRow();
            if(!empty($balance)){
                Yii::app()->db->createCommand("update manufacBalance set cnt = ".($val["cnt"])." where departmentId = 6 and colorId = 1 and phoneId = ".$val["phoneId"]." and balanceDate = '".$curDate."'")->execute();
                Yii::app()->db->createCommand("update manufacBalance set cnt = cnt - ".($val["cnt"])." where departmentId = 10 and colorId = 1 and phoneId = ".$val["phoneId"]." and balanceDate = '".$curDate."'")->execute();
            }
            else{
                Yii::app()->db->createCommand()->insert("manufacBalance",array(
                    'departmentId' => 6,
                    'cnt' => $val["cnt"],
                    'balanceDate' => $curDate,
                    'phoneId' => $val["phoneId"],
                    'colorId' => $val["colorId"]
                ));
            }
        }
        $repair = Yii::app()->db->CreateCommand()
            ->select("phoneId,count(*) as cnt")
            ->from("register")
            ->where("date(errorOtkDate) < :otk and date(errorRepairDate) = :repair ",array(":otk"=>$curDate,":repair" => $curDate))
            ->group('phoneId')
            ->queryAll();
        foreach ($repair as $val) {
                Yii::app()->db->createCommand("update manufacBalance set cnt = cnt + ".($val["cnt"])." where departmentId = 10 and colorId = 1 and phoneId = ".$val["phoneId"]." and balanceDate = '".$curDate."'")->execute();

        }

        $model = Yii::app()->db->CreateCommand()
            ->select()
            ->from("manufacProduce")
            ->where("produceDate = :date",array(":date"=>$curDate))
            ->queryAll();

        foreach ($model as $val) {
            if($val["departmentId"] == 5) {
                $balance = Yii::app()->db->CreateCommand()
                    ->select()
                    ->from("manufacBalance")
                    ->where("departmentId = :depId AND phoneId = :pId and colorId = :cId and balanceDate = :date", array(":depId" => $val["departmentId"], ":pId" => $val["phoneId"], ":cId" => $val["colorId"],":date"=>$curDate))
                    ->queryRow();
                $produced = Yii::app()->db->CreateCommand()
                    ->select()
                    ->from("manufacProduce")
                    ->where("produceDate = :date and departmentId = :depId AND phoneId = :pId and colorId = :cId", array(":date" => $curDate, ":depId" => 10, ":pId" => $val["phoneId"], ":cId" => $val["colorId"]))
                    ->queryRow();
                if(!empty($balance)){
                    Yii::app()->db->createCommand("update manufacBalance set cnt = cnt + ".($produced["cnt"] - $val["cnt"])." where departmentId = ".$val["departmentId"]." and colorId = ".$val["colorId"]." and phoneId = ".$val["phoneId"]." and balanceDate = '".$curDate."'")->execute();
                }
                else{
                    Yii::app()->db->createCommand()->insert("manufacBalance", array(
                        'departmentId' => $val["departmentId"],
                        'cnt' => $balance["cnt"] + $produced["cnt"] - $val["cnt"],
                        'balanceDate' => $curDate,
                        'phoneId' => $val["phoneId"],
                        'colorId' => $val["colorId"]
                    ));
                }

            }
            else if($val["departmentId"] == 10) {
                $balance = Yii::app()->db->CreateCommand()
                    ->select()
                    ->from("manufacBalance")
                    ->where("departmentId = :depId AND phoneId = :pId and colorId = :cId and balanceDate = :date", array(":depId" => $val["departmentId"], ":pId" => $val["phoneId"], ":cId" => $val["colorId"],":date"=>$curDate))
                    ->order("balanceDate desc")
                    ->queryRow();
                $produced = Yii::app()->db->CreateCommand()
                    ->select()
                    ->from("manufacProduce")
                    ->where("produceDate = :date and departmentId = :depId AND phoneId = :pId and colorId = :cId", array(":date" => $curDate, ":depId" => 4, ":pId" => $val["phoneId"], ":cId" => $val["colorId"]))
                    ->queryRow();
                if(!empty($balance)){
                    Yii::app()->db->createCommand("update manufacBalance set cnt = cnt + ".($produced["cnt"] - $val["cnt"])." where departmentId = ".$val["departmentId"]." and colorId = ".$val["colorId"]." and phoneId = ".$val["phoneId"]." and balanceDate = '".$curDate."'")->execute();
                }
                else {
                    Yii::app()->db->createCommand()->insert("manufacBalance", array(
                        'departmentId' => $val["departmentId"],
                        'cnt' => $balance["cnt"] + $produced["cnt"] - $val["cnt"],
                        'balanceDate' => $curDate,
                        'phoneId' => $val["phoneId"],
                        'colorId' => $val["colorId"]
                    ));
                }
            }
            else if($val["departmentId"] == 4) {
                $balance = Yii::app()->db->CreateCommand()
                    ->select()
                    ->from("manufacBalance")
                    ->where("departmentId = :depId AND phoneId = :pId and colorId = :cId and balanceDate = :date", array(":depId" => $val["departmentId"], ":pId" => $val["phoneId"], ":cId" => $val["colorId"],":date"=>$curDate))
                    ->order("balanceDate desc")
                    ->queryRow();
                if(!empty($balance)){
                    Yii::app()->db->createCommand("update manufacBalance set cnt = cnt - ".($val["cnt"])." where departmentId = ".$val["departmentId"]." and colorId = ".$val["colorId"]." and phoneId = ".$val["phoneId"]." and balanceDate = '".$curDate."'")->execute();
                }
                else {
                    Yii::app()->db->createCommand()->insert("manufacBalance", array(
                        'departmentId' => $val["departmentId"],
                        'cnt' => $balance["cnt"] - $val["cnt"],
                        'balanceDate' => $curDate,
                        'phoneId' => $val["phoneId"],
                        'colorId' => $val["colorId"]
                    ));
                }
            }
        }
        $this->redirect("setProduce");

    }

}