<?php


class StockController extends Controller{

    public function actionInProduce(){
        $produce = Yii::app()->db->CreateCommand()
            ->select("count(*) as cnt, ph.model, c.`name`,p.phoneId, p.colorId")
            ->from("stockin s")
            ->join("produce p","p.produceId = s.produceId")
            ->join("phone ph","ph.phoneId = p.phoneId")
            ->join("color c", "c.colorId = p.colorId")
            ->where("date(s.stockinDate) = :date",array(":date"=>date("Y-m-d")))
            ->group("p.phoneId,p.colorId")
            ->queryAll();

        $out = Yii::app()->db->CreateCommand()
            ->select()
            ->from("stockin s")
            ->join("produce p","p.produceId = s.produceId")
            ->join("phone ph","ph.phoneId = p.phoneId")
            ->join("color c", "c.colorId = p.colorId")
            ->where("date(s.stockinDate) = :date",array(":date"=>date("Y-m-d")))
            ->order("p.produceId desc")
            ->queryAll();
        $this->render("inProduce",array(
            "produce" => $produce,
            'out' => $out
        ));
    }

    public function actionIdentyPhone(){
        $produce = Yii::app()->db->CreateCommand()
            ->select()
            ->from("produce p")
            ->join("color c","c.colorId = p.colorId")
            ->join("phone ph","ph.phoneId = p.phoneId")
            ->where("p.sn = :sn",array(":sn"=>$_POST["val"]))
            ->queryRow();
        $in = Yii::app()->db->CreateCommand()
            ->select()
            ->from("stockin")
            ->where("produceId = :id",array(":id"=>$produce["produceId"]))
            ->queryRow();

//        $photo = Yii::app()->db->CreateCommand()
//            ->select()
//            ->from("sn")
//            ->where("colorId = :color and phoneId = :id",array(":color"=>$produce["colorId"],":id"=>$produce["phoneId"]))
//            ->queryRow();
        if(!empty($produce)){
            if(empty($in)) {
            Yii::app()->db->createCommand()->insert("stockin",array(
                "stockinDate"=>date("Y-m-d H:i:s"),
                "produceId"=>$produce["produceId"]
            ));
            Yii::app()->db->createCommand()->update("produce", array(
                    "produce" => 0,
                    "outDate" => date("Y-m-d H:i:s")
                ),"produceId = :id",array(":id"=>$produce["produceId"]));
            }
            else{
                $res["message"] = "Этот телефон уже приходован!!!";
            }
        }
        else{
            $res["message"] = "Этот телефон не существует в базе";
        }
        $box = Yii::app()->db->CreateCommand()
            ->select("p.sn,p.produce")
            ->from("produce p")
            ->where("p.box = :box",array(":box"=>$produce["box"]))
            ->queryAll();
        $res["produce"] = $produce;
        $res["box"] = $box;
//        $res["photo"] = $photo;
        echo json_encode($res);
    }

    public function actionOther(){
        $this->render("other");
    }

    public function actionOutPhones(){

        $produce = Yii::app()->db->CreateCommand()
            ->select()
            ->from("produce")
            ->where("sn = :sn",array(":sn"=>$_POST["val"]))
            ->queryRow();
        $stock = Yii::app()->db->CreateCommand()
            ->select()
            ->from("stockin p")
            ->where("p.produceId = :pr",array(":pr"=>$produce["produceId"]))
            ->queryRow();
        $res["res"] = "notOk";
        if(empty($stock)){
            Yii::app()->db->createCommand()->update("produce",array(
                "produce"=>0,
            ),"produceId = :id",array(":id"=>$produce["produceId"]));
            Yii::app()->db->createCommand()->insert("stockin",array(
                "stockinDate"=>date("Y-m-d H:i:s"),
                "produceId"=>$produce["produceId"]
            ));

            $res["res"] = "Ok";
        }
        else{
            $res["message"] = "Этот телефон уже приходован";
        }
//        $res["photo"] = $photo;
        echo json_encode($res);
    }

    public function actionLoosePhones(){

        $produce = Yii::app()->db->CreateCommand()
            ->select()
            ->from("produce p")
            ->join("color c","c.colorId = p.colorId")
            ->join("phone ph","ph.phoneId = p.phoneId")
            ->where("p.sn like '%" . $_POST["val"] . "%' and p.placer != 1")
            ->queryRow();
        $res["res"] = "notOk";
        if(!empty($produce)){
            Yii::app()->db->createCommand()->insert("stockin",array(
                "stockinDate"=>date("Y-m-d H:i:s"),
                "produceId"=>$produce["produceId"]
            ));
            Yii::app()->db->createCommand()->update("produce",array(
                "produce"=>1,
                "placer"=>1
            ),"produceId = :id",array(":id"=>$produce["produceId"]));

            $res["res"] = "Ok";
        }
        else{
            $res["message"] = "Этот телефон уже приходован как рассыпной";
        }
//        $res["photo"] = $photo;
        echo json_encode($res);
    }

    public function actionStockPhones(){
        $produceCompress = Yii::app()->db->CreateCommand()
            ->select("count(*) as cnt, ph.model, c.`name`,p.phoneId")
            ->from("produce p")
            ->join("phone ph","ph.phoneId = p.phoneId")
            ->join("color c", "c.colorId = p.colorId")
            ->where("p.placer = 1")
            ->group("p.phoneId,p.colorId")
            ->queryAll();

        $outCompress = Yii::app()->db->CreateCommand()
            ->select("count(*) as cnt, ph.model, c.`name`,p.phoneId")
            ->from("produce p")
            ->join("phone ph","ph.phoneId = p.phoneId")
            ->join("color c", "c.colorId = p.colorId")
            ->where("p.produce = 1")
            ->group("p.phoneId,p.colorId")
            ->queryAll();

        $produce = Yii::app()->db->CreateCommand()
            ->select("")
            ->from("produce p")
            ->join("phone ph","ph.phoneId = p.phoneId")
            ->join("color c", "c.colorId = p.colorId")
            ->where("p.placer = 1")
            ->queryAll();

        $out = Yii::app()->db->CreateCommand()
            ->select("")
            ->from("produce p")
            ->join("phone ph","ph.phoneId = p.phoneId")
            ->join("color c", "c.colorId = p.colorId")
            ->where("p.produce = 1")
            ->queryAll();
        $this->render("stockPhones",array(
            "produce" => $produce,
            'out' => $out,
            "produceCompress" => $produceCompress,
            'outCompress' => $outCompress
        ));
    }

    public function actionOutPhone(){
        $produce = Yii::app()->db->CreateCommand()
            ->select("count(*) as cnt, ph.model, c.`name`,p.phoneId, p.colorId")
            ->from("stockin s")
            ->join("produce p","p.produceId = s.produceId")
            ->join("phone ph","ph.phoneId = p.phoneId")
            ->join("color c", "c.colorId = p.colorId")
            ->where("p.outDate = :date",array(":date"=>date("Y-m-d")))
            ->group("p.phoneId,p.colorId")
            ->queryAll();

        $out = Yii::app()->db->CreateCommand()
            ->select("")
            ->from("stockin s")
            ->join("produce p","p.produceId = s.produceId")
            ->join("phone ph","ph.phoneId = p.phoneId")
            ->join("color c", "c.colorId = p.colorId")
            ->where("date(p.outDate) = :date and s.status = 0",array(":date"=>date("Y-m-d")))
            ->queryAll();
        $this->render("outPhone",array(
            "produce" => $produce,
            'out' => $out
        ));
    }

    public function actionOutBox(){
        $produce = Yii::app()->db->CreateCommand()
            ->select()
            ->from("stockin s")
            ->join("produce p","p.produceId = s.produceId")
            ->join("color c","c.colorId = p.colorId")
            ->join("phone ph","ph.phoneId = p.phoneId")
            ->where("p.box = :box AND s.status = 1",array(":box"=>$_POST["val"]))
            ->queryAll();
//        $photo = Yii::app()->db->CreateCommand()
//            ->select()
//            ->from("sn")
//            ->where("colorId = :color and phoneId = :id",array(":color"=>$produce["colorId"],":id"=>$produce["phoneId"]))
//            ->queryRow();
        if(!empty($produce)){
                foreach($produce as $val) {
                    Yii::app()->db->createCommand()->update("stockin", array(
                        "status" => 0,
                    ),"produceId = :id",array(":id"=>$val["produceId"]));

                }
        }
        else{
            $res["message"] = "Эта коробка не существует в базе или отгружана";
        }
        $res["produce"] = $produce;
//        $res["photo"] = $photo;
        echo json_encode($res);

    }

}