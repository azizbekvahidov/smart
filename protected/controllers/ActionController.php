<?php
error_reporting(0);
require_once __DIR__ ."/../extensions/vendor/autoload.php";
class ActionController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout='//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */



    /**
     * Lists all models.
     */
    public function actionIndex()
    {

    }

    /*все методы для приложения продажи*/

    public function actionScan(){
//        $_POST["sn"] = '191IB9ABHA00512';
//        $_POST["userId"] = 1;
        //echo $cCode;
        $response = array();
        $query = Yii::app()->db->createCommand()
            ->select()
            ->from("print")
            ->where("`SN` = :sn OR `IMEI1` = :sn OR `IMEI2` = :sn",array(":sn"=>$_POST["sn"]))
            ->order("printId desc")
            ->queryRow();
        $myProd = Yii::app()->db->createCommand()
            ->select()
            ->from("dillerout")
            ->where("printId = :id AND touserId = :userId",array(":id"=>$query["printId"],":userId"=>$_POST["userId"]))
            ->queryRow();
        $cCode = substr($query["SN"],6,2);
        //if(!empty($myProd)) {
            $phones = Yii::app()->db->createCommand()
                ->select("p.model as phone,c.name as color")
                ->from("phone p")
                ->join("sn s", "s.phoneId = p.phoneId")
                ->join("color c", "c.colorId = s.colorId")
                ->where("p.phoneId = :id AND s.code LIKE '%".$cCode."%'", array(":id" => $query["phoneId"]))
                ->queryRow();
            $response = array();
            if (!empty($query)) {
                $response["sn"] = $query["SN"];
                $response["cCode"] = $cCode;
                $response["imei1"] = $query["IMEI1"];
                $response["imei2"] = $query["IMEI2"];
                $response["phoneId"] = $query["phoneId"];
                $response["color"] = $phones["color"];
                $response["phone"] = $phones["phone"];
                $response["date"] = date("d-m-Y", strtotime($query["printDate"]));
                $response["printId"] = $query["printId"];
                $response["status"] = $query["sell"];
                $response["success"] = true;
            } else {
                $response["success"] = false;
            }
        //}
        //else{
            //$response["success"] = "not yours";
        //}
        echo json_encode($response);

    }

    public function actionAddSell(){
        $func = new Functions();
//        $_POST["snNum"] = '191IB2AFG700001';
//        $_POST["printId"] = '285491';
//        $_POST["userId"] = 10;
//        $_POST["name"] = "";
//        $_POST["address"] = "";
//        $_POST["phone"] = "";
        $upPrint = 0;
        $crSell = 0;
        $client = 0;
        $query = Yii::app()->db->createCommand()
            ->select()
            ->from("print")
            ->where("SN = :sn",array(":sn"=>$_POST["snNum"]))
            ->queryRow();
        if(!empty($_POST["snNum"])) {
            if($query["sell"] != 1) {
                $crSell = 0;
                $upPrint = Yii::app()->db->createCommand()->update("print", array(
                    "sell" => 1,
                ), "SN = :snn", array(":snn" => $_POST["snNum"]));
                if($_POST["name"] != ""){
                $clients = Yii::app()->db->createCommand()->insert("client", array(
                        'name' => $_POST["name"],
                        'point' => $_POST["address"],
                        'phone' => $_POST["phone"]
                    ));
                    $client = Yii::app()->db->lastInsertID;
                }
                if ($upPrint == 1) {
                    Yii::app()->db->createCommand()->update("dillerout",array("status"=>1),"printId = :id",array(":id"=>(int)$_POST["printId"]));
                    $crSell = Yii::app()->db->createCommand()->insert("sold", array(
                        'printId' => (int)$_POST["printId"],
                        'userId' => (int)$_POST["userId"],
                        'sellDate' => time(),
                        'clientId' => $client
                    ));
                    $func->setLog($_POST["printId"], "sold", "", $_POST["userId"]);
                }
            }
        }
        if($query["sell"] != 1){
            if($upPrint == 1 && $crSell == 1){
                $response["success"] = true;
                $func->setBall((int)$_POST["userId"],$query["phoneId"]);
            }
            else{
                $response["success"] = false;
            }
        }
        else{
            $response["success"] = "sell";
        }
        echo json_encode($response);
    }

    public function actionBackSell(){

        $upPrint = 0;
        $crSell = 0;
        $query = Yii::app()->db->createCommand()
            ->select("sell")
            ->from("print")
            ->where("SN = :sn",array(":sn"=>$_POST["snNum"]))
            ->queryRow();
        if(!empty($_POST["snNum"])) {
            if($query["sell"] != 0) {
                $crSell = 0;
                $upPrint = Yii::app()->db->createCommand()->update("print", array(
                    "sell" => 0,
                    "backDay"=>date("Y-m-d")
                ), "SN = :snn", array(":snn" => $_POST["snNum"]));
                if ($upPrint == 1) {
                    $crSell = Yii::app()->db->createCommand()->insert("back", array(
                        'printId' => (int)$_POST["printId"],
                        'userId' => (int)$_POST["userId"],
                        'backDate' => date("Y-m-d H:i:s")
                    ));
                }
            }
        }
        if($query["sell"] != 0){
            if($upPrint == 1 && $crSell == 1){
                $response["success"] = true;
            }
            else{
                $response["success"] = false;
            }
        }
        else{
            $response["success"] = "sell";
        }
        echo json_encode($response);
    }
    
    public function actionGetUserReport(){
        $model = Yii::app()->db->createCommand()
            ->select("")
            ->from("phone")
            ->queryAll();

        $this->renderPartial("/admin/ajaxBalanceUser",array(
            "model"=>$model,
            "userId"=>$_GET["userId"],
            "month"=>$_GET["month"]
        ));
    }

    public function actionNews(){
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("news")
            ->where('status = 0 and newsType = :type',array(":type"=>"sold"))
            ->queryAll();
        $this->renderPartial("/news/sellerNews",array(
            'model' => $model
        ));
    }

    public function actionShop(){
        //$_GET["id"] = 10;
        $userId = $_GET["id"];

        $user = Yii::app()->db->createCommand()
            ->select()
            ->from("users u")
            ->join("ball b","u.userId = b.userId")
            ->where("b.userId = :id",array(":id"=>$userId))
            ->queryRow();
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("shopproduct")
            ->where("status = 0")
            ->queryAll();
        $this->renderPartial("/admin/shop/shop",array(
            'model' => $model,
            "user" => $user
        ));
    }
    
    public function actionGetCart(){
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("shopdetail shd")
            ->join("shopproduct sh","sh.shopproductId = shd.shopproductId")
            ->where("shd.userId = :id and shd.status = 0",array(":id"=>$_POST["id"]))
            ->queryAll();
        echo json_encode($model);
    }

    public function actionAddCart(){
        Yii::app()->db->createCommand()->insert("shopdetail",array(
            "userId" => $_POST["id"],
            "shopproductId" => $_POST["prodId"]
        ));
    }

    public function actionDeleteCart(){
        Yii::app()->db->createCommand()->delete("shopdetail","shopdetailId = :id",array(
            ":id" => $_POST["id"]
        ));
    }

    public function actionCartClose(){
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("shopdetail shd")
            ->join("shopproduct sh","sh.shopproductId = shd.shopproductId")
            ->where("shd.userId = :id and shd.status = 0",array(":id"=>$_POST["id"]))
            ->queryAll();
        $ball = Yii::app()->db->createCommand()
            ->select()
            ->from("ball")
            ->where("userId = :id",array(":id"=>$_POST["id"]))
            ->queryRow();
        $sum = 0;
        foreach ($model as $item) {
            $sum = $sum + $item["ball"];
        }

        Yii::app()->db->createCommand()->update("shopdetail",array(
            "status" => 1
        ),"userId = :id and status = 0",array(":id"=>$_POST["id"]));
        Yii::app()->db->createCommand()->update("ball",array(
            "ball" => $ball["ball"]-$sum
        ),"userId = :id",array(":id"=>$_POST["id"]));
    }



    /*конец методы для приложения продажи*/


    public function actionEmployeeOutreason(){
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("outReason")
            ->queryAll();
        $this->renderPartial("../admin/employee/outReason",array(
            'model' => $model
        ));
    }

    public function actionLineInterface(){
        $spare = Yii::app()->db->createCommand()
            ->select()
            ->from("spare")
            ->order("name")
            ->queryAll();
        $list = Yii::app()->db->createCommand()
            ->select()
            ->from("phone")
            ->queryAll();
        $dep = Yii::app()->db->createCommand()
            ->select()
            ->from("department")
            ->queryAll();
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("outReason")
            ->queryAll();
        $phone = Yii::app()->db->createCommand()
            ->select()
            ->from("phone")
            ->where("status = 0")
            ->queryAll();

        $callEmp = Yii::app()->db->createCommand()
            ->select()
            ->from("employee")
            ->where("positionId > 4")
            ->queryAll();
        
        $operation = Yii::app()->db->createCommand()
            ->select()
            ->from("operation")
            ->queryAll();
        $this->renderPartial("../admin/employee/lineInterface",array(
            'model' => $model,
            'operation' => $operation,
            'list' => $list,
            'spare' => $spare,
            'phone' => $phone,
            'callEmp' => $callEmp,
            'dep' => $dep
        ));
    }


    public function actionGetPosition(){
        $data = Yii::app()->db->CreateCommand()
            ->select()
            ->from("employee")
            ->where("code = :id",array(":id"=>$_POST["id"]))
            ->queryRow();

        $model = Yii::app()->db->CreateCommand()
            ->select()
            ->from("lineposition")
            ->where("phoneId = :id and departmentId = :depId",array(":id"=>$_POST["phoneId"],":depId"=>$data["departmentId"]))
            ->queryAll();

        echo json_encode($model);
    }

    public  function actionLineOperation(){
        $data = Yii::app()->db->CreateCommand()
            ->select()
            ->from("employee")
            ->where("code = :id",array(":id"=>$_POST["id"]))
            ->queryRow();
        Yii::app()->db->createCommand()->insert("action",array(
            "actionType" => "line",
            "employeeId" => $data["employeeId"],
            "action" => "line",
            "reason" => $_POST["reason"],
            "actionTime" => date("Y-m-d H:i:s"),
            "phone"=>$_POST["phoneId"]
        ));
    }

    public function actionAddActDetail(){
        $res = array();
        if($_POST["cnt"] == "" or $_POST["desc"] == ""){
            $res["alert"] = "Не заполнены некоторые поля";
            $res["alertType"] = "danger";
        }
        else{
            $func = new Functions();
            $res = Yii::app()->db->createCommand()->insert("actdetail",array(
                'actregisterId' => $_POST["id"],
                'phoneId' => $_POST["phone"],
                'spareId' => $_POST["spare"],
                'cnt' => $_POST["cnt"],
                'desc' => $_POST["desc"],
                'cause' => $_POST["cause"],
            ));
            $model = Yii::app()->db->createCommand()
                ->select()
                ->from("actdetail")
                ->where("actdetailId = :id",array(":id"=>$_POST["id"]))
                ->queryRow();

            if($res){
                $res["alert"] = "Данные успешно сохранены";
                $res["alertType"] = "success";
                $func->setLogs(Yii::app()->db->getLastInsertID(),"insert","actregisterId:".$_POST["regId"].",phoneId:".$_POST["phone"].", spareId:".$_POST["spare"].",cnt:".$_POST["cnt"].",cause:".$_POST["cause"].",desc:".$_POST["desc"],"actdetail");
            }
        }

        echo json_encode($res);

        /**/
    }

    public function actionCreateAct(){

        $dep = Yii::app()->db->createCommand()
            ->select()
            ->from("department")
            ->where("departmentId = :id",array(":id"=>$_POST["id"]))
            ->queryRow();
        $lastId = Yii::app()->db->createCommand()
            ->select()
            ->from("actregister a")
            ->join("department d", "d.departmentId = a.departmentId")
            ->where("d.code = :code", array(":code"=>$dep["code"]))
            ->order("actNum DESC")
            ->queryRow();
        Yii::app()->db->createCommand()->insert("actregister",array(
            'status'=>0,
            'brigadir'=>$dep["brigadir"],
            'departmentId'=>$_POST["id"],
            'actDate'=>date("Y-m-d H:i:s"),
            'actNum'=>$lastId["actNum"]+1
        ));

        echo Yii::app()->db->getLastInsertID();
    }

    public function actionCheckAct(){
        $model = array();
        $isEmpty = Yii::app()->db->createCommand()
            ->select()
            ->from("actregister")
            ->where("status = 0 and departmentId = :id",array(":id"=>$_POST["id"]))
            ->queryRow();
        if(!empty($isEmpty)){
            $model["id"]=$isEmpty["actregisterId"];
            $actCheck = Yii::app()->db->createCommand()
                ->select()
                ->from("actDetail ad")
                ->join("phone p","p.phoneId = ad.phoneId")
                ->join("spare s","s.spareId = ad.spareId")
                ->join("actregister ar","ar.actregisterId = ad.actregisterId")
                ->where("ar.actregisterId = :id",array(":id"=>$isEmpty["actregisterId"]))
                ->queryAll();
            $model["info"] = $actCheck;
        }else{
            $model["id"]=0;
        }
        echo json_encode($model);
    }

    public function actionProduce(){
        $res = array();
        if($_POST["produce"] == "true") {
            if ($_POST["modelCnt"] == "") {
                $res["alert"]="Не заполнены некоторые поля";
                $res["alertType"]="danger";
            } else {
                Yii::app()->db->createCommand()->insert("producedep",array(
                    "produceType" => 'produced',
                    "departmentId" => $_POST["depId"],
                    "cnt" => $_POST["modelCnt"],
                    "produceDate" => date("Y-m-d H:i:s"),
                    "phoneId" => $_POST["modelId"],
                ));
                $res["alert"]="Данные успешно сохранены";
                $res["alertType"]="success";
            }
        }
        else{
            Yii::app()->db->createCommand()->insert("producedep",array(
                "produceType" => 'produce',
                "departmentId" => $_POST["depId"],
                "cnt" => 0,
                "produceDate" => date("Y-m-d H:i:s"),
                "phoneId" => $_POST["modelId"],
            ));
            $res["alert"]="Данные успешно сохранены";
            $res["alertType"]="success";
        }
        echo json_encode($res);
    }

    public function actionCallEmp(){
        $func = new telegramBot();
        $emp = Yii::app()->db->createCommand()
            ->select("e.name,e.surname,e.lastname,d.name as dName")
            ->from("employee e")
            ->join("department d","e.departmentId = d.departmentId")
            ->where("e.employeeId = :id",array(":id"=>$_POST["userId"]))
            ->queryRow();
        $message = $emp["surname"]." ".$emp["name"]." ".$emp["lastname"]." просит вас подойти к отделу ".$emp["dName"];
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("telegrambot")
            ->where("employeeId = :id",array(":id"=>$_POST["id"]))
            ->queryRow();
        $func->sendMessage($model["chatId"],$message);
        $res["alert"]="Запрос выполнен";
        $res["alertType"]="success";
        echo json_encode($res);
    }

    public function actionCalltoFtq(){
        if(date("w") != 0) {
            $model = Yii::app()->db->CreateCommand()
                ->select()
                ->from("telegrambot")
                ->where("employeeId is not null")
                ->queryAll();
            foreach ($model as $item) {
                $func = new telegramBot();
                $func->sendMessage($item["chatId"], "В 10 часов собрание FTQ");
            }
        }
    }

    public function actionDayResultTelegram(){

        $day = "";
        if(isset($_GET["day"])){
            $day = $_GET["day"];
        }
        else
            $day = date('Y-m-d');//'2019-04-22';
        $plan = Yii::app()->db->CreateCommand()
            ->select()
            ->from("planning p")
            ->join("phone ph", "ph.phoneId = p.phoneId")
            ->where("p.planningDate = :from and planType = 'current' ",array(":from"=>$day))
            ->queryAll();
        $models = Yii::app()->db->CreateCommand()
            ->select("ph.model,p.phoneId,p.colorId,c.name,count(p.produceId) as cnt")
            ->from("produce p")
            ->join("phone ph", "ph.phoneId = p.phoneId")
            ->join("color c", "c.colorId = p.colorId")
            ->where("date(p.produceDate) = :from",array(":from"=>$day))
            ->group("p.phoneId,p.colorId")
            ->queryAll();
        $brak = Yii::app()->db->CreateCommand()
            ->select("r.cause,ph.model,r.phoneId,count(r.registerId) as cnt")
            ->from("register r")
            ->join("phone ph", "ph.phoneId = r.phoneId")
            ->where("date(r.errorOtkDate) = :from and solve != 'Vxodnoy'",array(":from"=>$day))
            ->group("r.phoneId,r.cause")
            ->queryAll();
//            $func = new telegramBot();
//        $model = Yii::app()->db->CreateCommand()
//            ->select()
//            ->from("telegrambot")
//            ->where("userId != 0")
//            ->queryAll();
//        foreach ($model as $item) {
//            $func->sendMessage($item["chatId"], $text);
//        }
        $this->renderPartial('dailyReport',array(
            'plan' => $plan,
            'models' => $models,
            'brak' => $brak,
            'day' => $day
        ));
        //$func->sendMessage(147781051, $text);
    }

    public function actionDailyReport(){


        $url = 'http://10.94.20.16/action/DayResultTelegram';

        $html = file_get_contents($url);
        $css = "body{width: 1024px;} .text-center {text-align: center;} .table {
    width: 100%;
    max-width: 100%;
    margin-bottom: 20px;
        border-spacing: 0;
    border-collapse: collapse;
} .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
    border: 1px solid #ddd;} .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
    padding: 8px;
    line-height: 1.42857143;
    vertical-align: top;
    border-top: 1px solid #ddd;}";

        $client = new GuzzleHttp\Client();
// Retrieve your user_id and api_key from https://htmlcsstoimage.com/dashboard
        $res = $client->request('POST', 'https://hcti.io/v1/image', [
            'auth' => ['a5781665-e953-4e0e-ad42-a3bdc39105c7', '0d9d2c77-d999-4dd1-b03d-96af2ec5e466'],
            'form_params' => ['html' => $html, 'css' => $css]
        ]);
        $temp = json_decode($res->getBody(),true);
        $temp = $temp["url"];

        $image = file_get_contents($temp);
        /*
        $data = json_decode(file_get_contents('http://api.rest7.com/v1/html_to_image.php?url=' . $url . '&format=png'));

        if (@$data->success !== 1)
        {
            echo "<pre>";
            print_r($data);
            echo "</pre>";
        }
        $image = file_get_contents($data);*/
        $path = 'images/dayResult/'.date('Ymd').'.png';
        $temp = file_put_contents($path, $image);
            $func = new telegramBot();
            $model = Yii::app()->db->CreateCommand()
                ->select()
                ->from("telegrambot")
                ->where("userId != 0")
                ->queryAll();
            foreach ($model as $item) {
                $func->sendMessage($item['chatId'], "http://smartmobile.uz/".$path);
            }
    }

    public function actionTpaInterface(){
        $this->renderPartial("../admin/employee/tpaInterface");
    }

    public function actionPillSet($id){

        $model = Yii::app()->db->CreateCommand()
            ->select()
            ->from("ill")
            ->where('status = 1')
            ->queryAll();
        $this->renderPartial("pillSet",array(
            'model' => $model,
            'id'=>$id
        ));
    }

    public function actionGetPill(){
        $model = Yii::app()->db->CreateCommand()
            ->select()
            ->from("illpill ip")
            ->join("pill p","p.pillId = ip.pillId")
            ->where("ip.illId = ".$_POST["ill"])
            ->queryAll();

        echo json_encode($model);
    }


    public function actionSetPill(){
        if(isset($_POST)) {
            Yii::app()->db->createCommand()->insert(
                'usepill',
                array(
                    'pillId' => $_POST["pillId"],
                    'illId' => $_POST["illId"],
                    'cnt' => $_POST["cnt"],
                    'useDate' => date("Y-m-d H:i:s"),
                    'employeeId' => $_POST["id"]
                )
            );
        }
        $model = Yii::app()->db->CreateCommand()
            ->select("i.name as Illname,p.name as Pillname, e.name, e.surname,up.cnt, time(up.useDate) as useTime")
            ->from("usepill up")
            ->join("pill p","p.pillId = up.pillId")
            ->join("ill i","i.illId = up.illId")
            ->join("employee e","e.employeeId = up.employeeId")
            ->where("date(up.useDate) = '".date("Y-m-d")."'")
            ->order("up.useDate desc")
            ->queryAll();
        echo json_encode($model);

    }
}
