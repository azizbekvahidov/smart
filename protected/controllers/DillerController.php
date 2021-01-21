<?php
/**
 * Created by PhpStorm.
 * User: Aziz
 * Date: 22.12.2017
 * Time: 16:23
 */

class DillerController extends Controller
{

    public function actionIndex(){
        $this->render("");
    }

    public function actionComing(){
        $this->render("coming");
    }

    public function actionAjaxComing(){
        $print["hasPhone"] = false;
        $print = Yii::app()->db->createCommand()
            ->select()
            ->from("print p")
            ->join("phone ph","ph.phoneId = p.phoneId")
            ->where("p.`SN` = :sn OR p.`IMEI1` = :imei1 OR p.`IMEI2` = :imei2",array(":sn"=>$_POST["val"],":imei1"=>$_POST["val"],":imei2"=>$_POST["val"]))
            ->queryRow();
        $check = Yii::app()->db->createCommand()
            ->select()
            ->from("dillercom dc")
            ->where("dc.printId = :id",array(":id"=>$print["printId"]))
            ->queryRow();
        if(!empty($check)) $print["hasPhone"] = true;
        $color = substr($print["SN"],6,2);
        $result = Yii::app()->db->createCommand()
            ->select()
            ->from("color c")
            ->where("c.code = :name",array(":name"=>$color))
            ->queryRow();
        $print["color"] = $result["name"];
        echo json_encode($print);
    }


    public function actionComings(){
        $func = new Functions();
        $phones = $_POST["printId"];
        if(!empty($phones)) {
            foreach ($phones as $phone) {
                $model=Yii::app()->db->createCommand()
                    ->select()
                    ->from("dillercom")
                    ->where("printId = :id", array(":id"=>$phone))
                    ->queryAll();
                if (empty($model)) {
                    Yii::app()->db->createCommand()->insert("dillercom", array(
                        'createAt'=>time(),
                        'printId'=>$phone,
                        'userId'=>Yii::app()->user->getId(),
                    ));
                    $func->setLog($phone, "prixod", "", Yii::app()->user->getId());
                }
            }
            $this->redirect("/");
        }
        else{
            echo "<pre>";
            echo "Пустой список телефонов ";
            echo "</pre>";
        }

    }
    public function actionOut(){
        $list = Yii::app()->db->createCommand()
            ->select()
            ->from("users u")
            ->where("role = 1 AND parent = :id",array(":id"=>Yii::app()->user->getId()))
            ->queryAll();
        $this->render("out",array(
            'list'=>$list
        ));
    }

    public function actionOuts(){
        $func = new Functions();
        $phones = $_POST["printId"];
        foreach ($phones as $phone) {
            $model = Yii::app()->db->createCommand()
                ->select()
                ->from("dillerOut")
                ->where("printId = :id",array(":id"=>$phone))
                ->queryAll();
            if(empty($model)){
                Yii::app()->db->createCommand()->insert("dillerout", array(
                    'createAt' => time(),
                    'printId' =>$phone,
                    'userId'=>Yii::app()->user->getId(),
                    'touserId' => $_POST["seller"],
                ));
                Yii::app()->db->createCommand()->update("dillercom", array(
                    'status' => 1,
                ),"printId = :id",array(":id"=>$phone));
                $func->setLog($phone, "rasxod", "", Yii::app()->user->getId());
                //$func->setLog($phone, "prixod", "", $userId);
            }
        }
        $this->redirect("/");
    }

    public function actionAjaxOut(){
        $print["hasPhone"] = false;
        $print = Yii::app()->db->createCommand()
            ->select()
            ->from("print p")
            ->join("phone ph","ph.phoneId = p.phoneId")
            ->where("p.`SN` = :sn OR p.`IMEI1` = :imei1 OR p.`IMEI2` = :imei2",array(":sn"=>$_POST["val"],":imei1"=>$_POST["val"],":imei2"=>$_POST["val"]))
            ->queryRow();
        $isset = Yii::app()->db->createCommand()
            ->select()
            ->from("dillerout")
            ->where("printId = :id",array(":id"=>$print["printId"]))
            ->queryRow();
            
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("dillercom")
            ->where("printId = :id",array(":id"=>$print["printId"]))
            ->queryRow();
        if(!empty($model)) $print["hasPhone"] = true;
        $color = substr($print["SN"],6,2);
        $result = Yii::app()->db->createCommand()
            ->select()
            ->from("color c")
            ->where("c.code = :name",array(":name"=>$color))
            ->queryRow();
        $print["color"] = $result["name"];
        $print["isset"] = !empty($isset) ? 1 : 0;
        echo json_encode($print);
    }

    public function actionPoint(){
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from('point')
            ->where('userId = :id',array(':id'=>Yii::app()->user->getId()))
            ->queryAll();
        $this->render("point");
    }

}