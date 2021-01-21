<?php

require_once __DIR__ ."/../extensions/vendor/autoload.php";
class ParseController extends Controller{


    public function actionGetDbId(){
        $response = array();
        $response["meta"]["status"] = "ok";
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("tempbasedb")
            ->queryAll();
        $response["response"] = $model;

        echo json_encode($response);
    }

    public function actionParseData(){
        $data = $_POST["data"];
     $jsonParsed = json_decode($data, true);
       echo "<pre>";
       print_r($jsonParsed);
       echo "</pre>";
        foreach ($jsonParsed["data"] as $val) {
            $color = Yii::app()->db->createCommand()
                ->select("colorId")
                ->from("color")
                ->where("name like '%" . $val[4] . "%'")
                ->queryRow();

            $print = Yii::app()->db->createCommand()
                ->select("printId")
                ->from("print")
                ->where("SN like '%" . $val[3] . "%'")
                ->queryRow();

            if(!empty($color)) {
                $res=Yii::app()->db->createCommand()->insert("produce", array(
                    "phoneId" => $jsonParsed["phoneId"],
                    "printId" => $print["printId"],
                    "sn" => $val[3],
                    "box" => $val[2],
                    "colorId" => $color["colorId"],
                    "NW" => floatval($val[5]),
                    "GW" => floatval($val[6]),
                    "produceDate" => date("Y-m-d H:i:s", strtotime($val[7])),
                    "whom" => $val[9],
                ));
                if($res == 1)
                    Yii::app()->db->createCommand()->update("tempbasedb",array(
                        "dbId"=> $val[0]
                    ), "name = :name",array(":name"=>$jsonParsed["table"]));
            }
        }
    }

}