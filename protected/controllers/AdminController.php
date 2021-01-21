<?php


class AdminController extends Controller{

    public function actionPlace(){

        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("point")
            ->where("parent is null")
            ->queryAll();
        $this->render("place",array(
            "model"=>$model
        ));
    }

    public function actionAddSoldPhone($res = true){
        if(isset($_POST["phone"])) {
            $model = Yii::app()->db->createCommand()
                ->select()
                ->from("print p")
                ->where("(SN like '%" . $_POST["phone"]["sn"] . "%' OR IMEI1 like '%" . $_POST["phone"]["sn"] . "%' OR IMEI2 like '%" . $_POST["phone"]["sn"] . "%') AND sell != 1")
                ->queryRow();
            $user = Yii::app()->db->createCommand()
                ->select()
                ->from("users u")
                ->where("login = :login and password = :pass", array(":login"=>$_POST["phone"]["login"], ":pass"=>md5(md5($_POST["phone"]["pass"]))))
                ->queryRow();
            if(!empty($model) && !empty($user)){
                Yii::app()->db->createCommand()->insert("sold",array(
                    "userId" => $user["userId"],
                    "printId" => $model["printId"],
                    "sellDate" => time(),
                    "clientId" => 0
                ));
                $lastId=Yii::app()->db->getLastInsertId();
                Yii::app()->db->createCommand()->update("print", array(
                    'sell' => 1
                ), "printId = :id", array(":id" => $model["printId"]));

                $func=new Functions();
                $func->setLogs($lastId, "insert", "add to sold", "sold");
                $this->redirect("/");
            }
            else{
                $this->redirect("addSoldPhone?res=false");
            }

        }
        $this->render("addSoldPhone",array(
            "res" => $res
        ));

    }

    public function actionAjaxCkeckPhone(){
        $result["bool"] = false;
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("print p")
            ->where("SN like '%" . $_POST["data"] . "%' OR IMEI1 like '%" . $_POST["data"] . "%' OR IMEI2 like '%" . $_POST["data"] . "%'")
            ->queryRow();
        if(!empty($model)) {
            $result["bool"]=true;
            $result["model"]=$model;
            if($model["sell"] == 1) {
                $user = Yii::app()->db->createCommand()
                    ->select()
                    ->from("sold s")
                    ->join("users u", "u.userId = s.userId")
                    ->where("s.printId = :id", array(":id"=>$model["printId"]))
                    ->queryRow();
                $result["user"] = $user;
            }
        }
        echo json_encode($result);
    }

    public function actionAddPhone(){
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("phone")
            ->queryAll();
        $list = array();
        foreach ($model as $val){
            $list[$val["phoneId"]] = $val["model"];
        }
        if(isset($_POST["phone"])){
            $printDate = date("Y-m-d",strtotime($_POST["phone"]["date"]));
            $check = Yii::app()->db->createCommand()
                ->select()
                ->from("print")
                ->where("SN like '%" . $_POST["phone"]["sn"] . "%'")
                ->queryRow();
            if(empty($check)) {
                $model=Yii::app()->db->createCommand()
                    ->select("ph.model,ph.phoneId")
                    ->from("sn s")
                    ->join("phone ph", "ph.phoneId = s.phoneId")
                    ->where("SUBSTRING(s.code,1,8) = SUBSTRING(:sn,1,8)", array(":sn"=>$_POST["phone"]["sn"]))
                    ->queryRow();
                Yii::app()->db->createCommand()->insert("print",array(
                    'SN' => $_POST["phone"]["sn"],
                    'IMEI1' => $_POST["phone"]["imei1"],
                    'IMEI2' => $_POST["phone"]["imei2"],
                    'printDate' =>$printDate,
                    'phoneId' => $model["phoneId"],
                    'userId' => Yii::app()->user->getId(),
                    'sell' => 0
                ));
                $lastId = Yii::app()->db->getLastInsertId();

                    Yii::app()->db->createCommand()->update("produce",array(
                        'printId' => $lastId
                    ),"sn like '%" . $_POST["phone"]["sn"] . "%'");
                $func = new Functions();
                $func->setLog($lastId,"add","add to print",Yii::app()->user->getId());
                $this->redirect("/");
            }
        }
        $this->render("addPhone",array(
            "list" => $list
        ));
    }


    public function actionAjaxGetPlace(){

        $place = "";

        switch ($_POST["place"]){
            case "city":
                $place = "";
                break;
            case "district":
                $place = "city";
                break;
            case "market":
                $place = "district";
                break;
            case "shop":
                $place = "market";
                break;
        }

        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("point")
            ->where("place = :place",array(":place"=>$place))
            ->queryAll();
        $this->renderPartial("ajax/ajaxGetPlace",array(
            'model'=>$model
        ));
    }

    public function actionCreatePlace(){

        if(isset($_POST["place"])){
            Yii::app()->db->createCommand()->insert("point",array(
                "name"=>$_POST["place"]["name"],
                "place"=>$_POST["place"]["pType"],
                "parent"=>(isset($_POST["place"]["parent"])) ? $_POST["place"]["parent"] : null
            ));
        }
        $this->render("createPlace",array(

        ));
    }

    public function actionUpPlace($id){
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("point")
            ->where("pointId = :id",array(":id"=>$id))
            ->queryRow();
        $list = array(
            'city'=>"Город",
            'district'=>"Район",
            'market'=>"Рынок",
            'shop'=>"Магазин",
        );
        if(isset($_POST["place"])){
        //echo "<pre>";
        //print_r($_POST);
        //echo "</pre>";
            Yii::app()->db->createCommand()->update("point",array(
                "name"=>$_POST["place"]["name"],
                //"place"=>$_POST["place"]["pType"],
                //"parent"=>(isset($_POST["place"]["parent"])) ? $_POST["place"]["parent"] : null
            ),"pointId = :id",array(":id"=>$id));
            $this->redirect("/");
        }
        $this->render("upPlace",array(
            'list'=>$list,
            "model"=>$model
        ));
    }

    public function actionStatus($id){

    }

    public function actionPlan(){

        $list = Yii::app()->db->createCommand()
            ->select()
            ->from("users u")
            ->where("role = 1",array())
            ->queryAll();
        $this->render("plan",array(
            'list'=>$list
        ));
    }

    public function actionAjaxPlan(){
        $model["hasPlan"] = false;
        $model["data"] = Yii::app()->db->createCommand()
            ->select()
            ->from("plan p")
            ->join("phone ph","ph.phoneId = p.phoneId")
            ->where("p.month = :m AND p.userId = :id",array(":m"=>$_POST["month"],":id"=>$_POST["seller"]))
            ->queryAll();
        if(!empty($model["data"])){
            $model["hasPlan"] = true;
        }
        echo json_encode($model);
    }

    public function actionCreatePlan($seller,$month){

        $model = Yii::app()->db->createCommand()
            ->select("")
            ->from("phone")
            ->queryAll();
        if(isset($_POST["model"])){
            foreach ($_POST["model"] as $key => $item) {
                 if($item == null){
                     $item = 0;
                 }
                Yii::app()->db->createCommand()->insert("plan",array(
                    "phoneId"=>$key,
                    "userId"=>$_POST["userId"],
                    "month"=>$_POST["month"],
                    "plan"=>$item
                ));
            }
            $this->redirect("plan");
        }
        $this->render("createPlan",array(
            "model"=>$model,
            "month"=>$month,
            "seller"=>$seller
        ));
    }

    public function actionSetball(){
        $model = Yii::app()->db->createCommand()
            ->select("")
            ->from("phone")
//            ->where("kind = 2")
            ->queryAll();
        if(isset($_POST["model"])){
            foreach ($_POST["model"] as$key => $item) {
                Yii::app()->db->createCommand()->update("phone",array(
                    "ball"=>$item
                ),"phoneId = :id",array(":id"=>$key));
            }
            $this->redirect("/");
        }
        $this->render("setBall",array(
            "model"=>$model
        ));
    }

    public function actionBalanceDiller(){
    $list = Yii::app()->db->createCommand()
        ->select()
        ->from("users u")
        ->where("role = 2",array())
        ->queryAll();
    $this->render("balanceDiller",array(
        "list"=>$list
    ));
}

    public function actionAjaxBalanceDiller(){
        $model = Yii::app()->db->createCommand()
            ->select("ph.model as model,count(ph.phoneId) as cnt")
            ->from("dillercom dc")
            ->join("print p","p.printId = dc.printId")
            ->join("phone ph","ph.phoneId = p.phoneId")
            ->where("dc.userId = :id AND dc.status  = 0",array(":id"=>$_POST["userId"]))
            ->group("ph.phoneId")
            ->queryAll();
        $this->renderPartial("ajax/ajaxBalanceDiller",array(
            "model"=>$model
        ));
    }

    public function actionBalanceUser(){
        $list = Yii::app()->db->createCommand()
            ->select()
            ->from("users u")
            ->where("role = 1",array())
            ->queryAll();
        $this->render("balanceUser",array(
            "list"=>$list

        ));
    }

    public function actionAjaxBalanceUser(){
        if($_POST["userId"] == ""){
            $model = Yii::app()->db->createCommand()
                ->select("")
                ->from("phone")
                ->where("kind = 2")
                ->queryAll();

            $point = "";
            if(!empty($_POST["city"])){
                $point = $point.$_POST["city"];
                if(!empty($_POST["district"])){
                    $point = $point.",".$_POST["district"];
                    if(!empty($_POST["market"])){
                        $point = $point.",".$_POST["market"];
                        if(!empty($_POST["shop"])){
                            $point = $point.",".$_POST["shop"];
                        }
                    }
                }
            }
            $users = Yii::app()->db->createCommand()
                ->select()
                ->from("users")
                ->where("point like '%" . $point . "%' AND role = 1")
                ->queryAll();
            $this->renderPartial("ajax/ajaxBalanceAllUser",array(
                "model"=>$model,
                "users"=>$users,
                "month"=>$_POST["month"]
            ));
        }
        else{
            $model = Yii::app()->db->createCommand()
                ->select("")
                ->from("phone")
                ->where("kind = 2")
                ->queryAll();
            $this->renderPartial("ajax/ajaxBalanceUser",array(
                "model"=>$model,
                "userId"=>$_POST["userId"],
                "month"=>$_POST["month"]
            ));
        }
    }

    public function actionHistory(){
        $this->render("history");
    }

    public function actionAjaxHistory(){

        $print = Yii::app()->db->createCommand()
            ->select()
            ->from("print p")
            ->join("phone ph","ph.phoneId = p.phoneId")
            ->where("p.`SN` = :sn OR p.`IMEI1` = :imei1 OR p.`IMEI2` = :imei2",array(":sn"=>$_POST["phone"],":imei1"=>$_POST["phone"],":imei2"=>$_POST["phone"]))
            ->queryRow();
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("soldLogs l")
            ->join("users u","u.userId = l.userId")
            ->where("l.printId = :id",array(":id"=>$print["printId"]))
            ->queryAll();
        $colorSym = substr($print["SN"],6,2);
        $color = Yii::app()->db->createCommand()
            ->select()
            ->from("color c")
            ->where("c.code = :name",array(":name"=>$colorSym))
            ->queryRow();
        $result['phone'] = $print;
        $result['phone']["color"] = $color["name"];
        $result["logs"] = $model;

        echo json_encode($result);
    }

    public function actionDeleteAllActions(){
        if(isset($_POST["printId"])){
            $func = new Functions();
            foreach ($_POST["printId"] as $item) {
                $func->delBall($_POST["printId"]);
                Yii::app()->db->createCommand()->delete("dillercom", "printId = :id", array(':id'=>$item));
                Yii::app()->db->createCommand()->delete("dillerout", "printId = :id", array(':id'=>$item));
                Yii::app()->db->createCommand()->delete("sold", "printId = :id", array(':id'=>$item));
                Yii::app()->db->createCommand()->update("print", array(
                    'sell'=>0
                ), "printId = :id",array(":id"=>$item));
                $func->setLog($item, "delete", "", Yii::app()->user->getId());
            }

            $this->redirect("/");
        }
        $this->render("deleteAllActions");
    }

    public function actionAjaxGetDeletePhone(){
        $print = Yii::app()->db->createCommand()
            ->select()
            ->from("print p")
            ->join("phone ph","ph.phoneId = p.phoneId")
            ->where("p.`SN` = :sn OR p.`IMEI1` = :imei1 OR p.`IMEI2` = :imei2",array(":sn"=>$_POST["val"],":imei1"=>$_POST["val"],":imei2"=>$_POST["val"]))
            ->queryRow();
        $color = substr($print["SN"],6,2);
        $result = Yii::app()->db->createCommand()
            ->select()
            ->from("color c")
            ->where("c.code = :name",array(":name"=>$color))
            ->queryRow();
        $print["color"] = $result["name"];
        echo json_encode($print);
    }

    public function actionPhone(){
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("phone")
            ->queryAll();
        $this->render("phone",array(
            "model" => $model
        ));
    }

    public function actionPhoneStruct(){
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("struct s")
            ->join("spare sp","sp.spareId = s.spareId")
            ->where("s.phoneId = :id",array(":id"=>$_POST["id"]))
            ->queryAll();
        $this->renderPartial("ajax/phoneStruct",array(
            "model" => $model
        ));
    }

    public function actionDepartmentStruct(){
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("departmentSpare ds")
            ->join("spare sp","sp.spareId = ds.spareId")
            ->where("ds.departmentId = :id AND semiSpareId = 0",array(":id"=>$_POST["id"]))
            ->queryAll();
        $model2 = Yii::app()->db->createCommand()
            ->select()
            ->from("departmentSpare ds")
            ->join("semiSpare sp","sp.semiSpareId = ds.semiSpareId")
            ->where("ds.departmentId = :id AND spareId = 0",array(":id"=>$_POST["id"]))
            ->queryAll();
        $this->renderPartial("ajax/departmentStruct",array(
            "model" => $model,
            "model2" => $model2
        ));
    }

    public function actionCheck(){
        $model = Yii::app()->db->CreateCommand()
            ->select()
            ->from("produce")
            ->where("date(produceDate) = '2018-10-11' and (box like '%111018LA007%' or box like '%111018LA009%')")
            ->queryAll();
        $res = array();
        foreach ($model as $key => $item) {
            $temp = Yii::app()->db->CreateCommand()
                ->select()
                ->from("produce")
                ->where("sn = :sn and box not like '%111018LA007%' and  box not like '%111018LA009%' != '2018-10-11'",array(":sn"=>$item["sn"]))
                ->queryRow();
            if(!empty($temp)){
                $res[$item["produceId"]] = $item["sn"];
            }
        }
        echo "<pre>";
        print_r($res);
        echo "</pre>";
    }

    public function actionDepartmentSpare(){
        if(isset($_POST["struct"])){
            $func = new Functions();
            foreach ($_POST["struct"]["spare"] as $key => $item) {
                Yii::app()->db->createCommand()->insert("departmentSpare",array(
                    "departmentId" => $_POST["struct"]["departmentId"],
                    'spareId' => $item,
                    'cnt' => $_POST["struct"]["cnt"][$key],
                ));
                $func->setLogs(Yii::app()->db->getLastInsertID(),"insert","semispareId:".$item." added to departmentId:".$_POST["struct"]["departmentId"],"departmentSpare");
            }

            foreach ($_POST["struct"]["semiSpare"] as $key => $item) {
                Yii::app()->db->createCommand()->insert("departmentSpare",array(
                    "departmentId" => $_POST["struct"]["departmentId"],
                    'semiSpareId' => $item,
                    'cnt' => $_POST["struct"]["semiCnt"][$key],
                ));

                $func->setLogs(Yii::app()->db->getLastInsertID(),"insert","spareId:".$item." added to departmentId:".$_POST["struct"]["departmentId"],"departmentSpare");
            }
        }
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("department d")
            ->queryAll();
        $list = array();
        $this->render("departmentSpare",array(
            'model' => $model

        ));
    }
    public function actionSpareList(){
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("spare")
            ->order("name")
            ->queryAll();
        echo json_encode($model,true);
    }

    public function actionSpareListTest(){
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("spare")
            ->where("spareType = :val",array(":val"=>$_POST["actType"]))
            ->order("name")
            ->queryAll();
        echo json_encode($model,true);
    }

    public function actionPhoneList(){
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("phone")
            ->queryAll();
        echo json_encode($model,true);
    }

    public function actionActRegistered(){
        $regId = Yii::app()->db->createCommand()
            ->select()
            ->from("actregister")
            ->order("actregisterId DESC")
            ->queryRow();
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("phone")
            ->queryAll();
        $this->render("actRegistered",array(
            "model" => $model,
            "regId" => (!empty($regId)) ? $regId["actregisterId"] : 0
        ));
    }

    public function actionSaveAct(){
        $func = new Functions();
        if(isset($_POST["act"])){
            $regId = 0;
            $model = Yii::app()->db->createCommand()
                ->select()
                ->from("actregister")
                ->where("departmentId = :depId AND actNum = :actNum",array(":depId"=>$_POST["departmentId"],":actNum"=>$_POST["regId"]))
                ->queryRow();
            if(empty($model)){
                $department = Yii::app()->db->createCommand()
                    ->select()
                    ->from("department d")
                    ->where("departmentId = :id",array(":id"=>$_POST["departmentId"]))
                    ->queryRow();
                Yii::app()->db->createCommand()->insert("actregister",array(
                    'departmentId' => $_POST["departmentId"],
                    'actNum' => $_POST["regId"],
                    'actDate' => date("Y-m-d H:i:s"),
                    'brigadir' => $department["brigadir"]
                ));
                $text = "departmentId:".$_POST["departmentId"];
                $regId = Yii::app()->db->getLastInsertID();
                $func->setLogs($regId,"insert",$text,"actregister");
            }
            else{
                $regId = $model["actregisterId"];
            }
            if($_POST["phoneId"] == null)
                $_POST["phoneId"] = 0;
            foreach ($_POST["act"]["spare"] as $key => $val) {
                Yii::app()->db->createCommand()->insert("actdetail",array(
                    "spareId" => $val,
                    "cnt" => $_POST["act"]["cnt"][$key],
                    "cause" => $_POST["act"]["cause"][$key],
                    "desc" => $_POST["act"]["desc"][$key],
                    "actregisterId" => $regId,
                    "phoneId" => $_POST["phoneId"]
                ));

                $text = "actregisterId:".$_POST["regId"]."departmentId:".$_POST["departmentId"].",spareId:".$val.",cnt:".$_POST["act"]["cnt"][$key].",cause:".$_POST["act"]["cause"][$key].",desc:".$_POST["act"]["desc"][$key];
                $func->setLogs(Yii::app()->db->getLastInsertID(),"insert",$text,"actdetail");
            }

            $model = Yii::app()->db->createCommand()
                ->select()
                ->from("phone")
                ->queryAll();
            echo json_encode($model,true);
        }
    }

    public function actionSaveOldAct(){
        $func = new Functions();
        if(isset($_POST["act"])){
            $regId = 0;
            $model = Yii::app()->db->createCommand()
                ->select()
                ->from("actregister")
                ->where("departmentId = :depId AND actNum = :actNum",array(":depId"=>$_POST["departmentId"],":actNum"=>$_POST["regId"]))
                ->queryRow();
            if(empty($model)){
                Yii::app()->db->createCommand()->insert("actregister",array(
                    'departmentId' => $_POST["departmentId"],
                    'actNum' => $_POST["regId"],
                    'actDate' => date("Y-m-d", strtotime($_POST["regDate"])),
                    'brigadir' => $_POST["brigadir"],
                    'status' => 1
                ));
                $text = "departmentId:".$_POST["departmentId"];
                $regId = Yii::app()->db->getLastInsertID();
                $func->setLogs($regId,"insert",$text,"actregister");
            }
            else{
                $regId = $model["actregisterId"];
            }
            foreach ($_POST["act"]["spare"] as $key => $val) {
                Yii::app()->db->createCommand()->insert("actdetail",array(
                    "spareId" => $val,
                    "cnt" => $_POST["act"]["cnt"][$key],
                    "cause" => $_POST["act"]["cause"][$key],
                    "desc" => $_POST["act"]["desc"][$key],
                    "actregisterId" => $regId,
                    "phoneId" => $_POST["phoneId"]
                ));

                $text = "actregisterId:".$_POST["regId"]."departmentId:".$_POST["departmentId"].",spareId:".$val.",cnt:".$_POST["act"]["cnt"][$key].",cause:".$_POST["act"]["cause"][$key].",desc:".$_POST["act"]["desc"][$key];
                $func->setLogs(Yii::app()->db->getLastInsertID(),"insert",$text,"actdetail");
            }

            $model = Yii::app()->db->createCommand()
                ->select()
                ->from("phone")
                ->queryAll();
            echo json_encode($model,true);
        }
    }
    public function actionOldActRegistered(){

        $regId = Yii::app()->db->createCommand()
            ->select()
            ->from("actregister")
            ->order("actregisterId DESC")
            ->queryRow();
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("phone")
            ->queryAll();
        $department = Yii::app()->db->createCommand()
            ->select()
            ->from("department")
            ->queryAll();
        $this->render("oldActRegistered",array(
            "model" => $model,
            "regId" => (!empty($regId)) ? $regId["actregisterId"] : 0,
            "dep" => $department
        ));
    }

    public function actionLoginDepartment(){
            $department=array();
            $model=Yii::app()->db->createCommand()
                ->select()
                ->from("department")
                ->where("pass = :pass", array(":pass"=>$_POST["pass"]))
                ->queryRow();
            if(!empty($model)) {
                $act=Yii::app()->db->createCommand()
                    ->select()
                    ->from("actregister a")
                    ->join("department d", "d.departmentId = a.departmentId")
                    ->where("d.code = :code", array(":code"=>$model["code"]))
                    ->order("actNum DESC")
                    ->queryRow();
                $model["actNum"]=(!empty($act)) ? $act["actNum"] : 0;
                if (!empty($model))
                    $department=$model;
                echo json_encode($department);
            }
    }
    public function actionAct(){
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("actregister a")
            ->join("department d","d.departmentId = a.departmentId")
            ->queryAll();
        $this->render("actChecking",array(
            "model" => $model
        ));

    }

    public function actionActChecking(){
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("actregister a")
            ->join("department d","d.departmentId = a.departmentId")
            ->where("a.status != 1")
            ->queryAll();
        $this->render("actChecking",array(
            "model" => $model
        ));

    }

    public function actionActDetail($id){
        $this->render("ajax/actDetail",array(
            "regId" => $id
        ));
    }

    public function actionChange(){
        $func = new Functions();
        Yii::app()->db->createCommand()->update("actdetail",array(
            'cnt' => $_POST["cnt"]
        ),"actdetailId = :id",array(":id"=>$_POST["id"]));
        $func->setLogs($_POST["id"],"update","cnt:".$_POST["cnt"],"actdetail");
    }

    public function actionactDetailContent($id){

        $spare = Yii::app()->db->createCommand()
            ->select()
            ->from("spare")
            ->order("name")
            ->queryAll();
        $list = Yii::app()->db->createCommand()
            ->select()
            ->from("phone")
            ->queryAll();
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("actDetail a")
            ->join("spare s","s.spareId = a.spareId")
            ->leftjoin("phone p","p.phoneId = a.phoneId")
            ->where("a.actregisterId = :id OR (a.actregisterId = :id and s.spareType = 'detail')",array(":id"=>$id))
            ->order("p.model")
            ->queryAll();
//        $model2 = Yii::app()->db->createCommand()
//            ->select()
//            ->from("actDetail a")
//            ->join("spare s","s.spareId = a.spareId")
//            ->where("a.actregisterId = :id and s.spareType = 'detail'",array(":id"=>$id))
//            ->queryAll();
//        $model = array_merge($model, $model2);

        $act = Yii::app()->db->createCommand()
            ->select()
            ->from("actregister a")
            ->join("department d","d.departmentId = a.departmentId")
            ->where("a.actregisterId = :id",array(":id"=>$id))
            ->queryRow();
        $this->renderPartial("ajax/actDetailContent",array(
            'model' => $model,
            "act" => $act,
            'list' => $list,
            'spare' => $spare
        ));
    }

    public function actionUpdateServiceUtil(){
        $func = new Functions();
        $res = Yii::app()->db->createCommand()->update("actdetail",array(
            "service" => $_POST["service"],
            "util" => $_POST["util"]
        ),"actdetailId = :id",array(":id"=>$_POST["id"]));
        if($res){
            $func->setLogs($_POST["id"],"update","service:".$_POST["service"].", util:".$_POST["util"],"actdetail");
        }

    }

    public function actionDeleteActDetail(){
        $func = new Functions();
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("actdetail")
            ->where("actdetailId = :id",array(":id"=>$_POST["id"]))
            ->queryRow();
        $res = Yii::app()->db->createCommand()->delete("actdetail","actdetailId = :id",array(":id"=>$_POST["id"]));
        if($res){
            $func->setLogs($_POST["id"],"delete","actregisterId:".$model["actregisterId"].",phoneId:".$model["phoneId"].", spareId:".$model["spareId"].",cnt:".$model["cnt"].",cause:".$model["cause"].",desc:".$model["desc"],"actdetail");
        }
    }

    public function actionAddActDetail(){
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
            $func->setLogs(Yii::app()->db->getLastInsertID(),"insert","actregisterId:".$_POST["regId"].",phoneId:".$_POST["phone"].", spareId:".$_POST["spare"].",cnt:".$_POST["cnt"].",cause:".$_POST["cause"].",desc:".$_POST["desc"],"actdetail");
        }
    }

    public function actionCloseAct(){
        $func = new Functions();
        $Doc = new Doc();
        Yii::app()->db->createCommand()->update("actRegister",array(
//            "signed" => 1
            "status" => 1
        ),"actregisterId = :id",array(":id"=>$_GET["id"]));
        //$Doc->setDocSign('act',$_GET["id"]);
        $func->setLogs($_GET["id"],"update","Передача акта на подпись","actregister");
        $this->redirect("actChecking");
    }

    public function actionTest($id){
        $_monthsList = array(".01." => "января", ".02." => "февраля",
            ".03." => "марта", ".04." => "апреля", ".05." => "мая", ".06." => "июня",
            ".07." => "июля", ".08." => "августа", ".09." => "сентября",
            ".10." => "октября", ".11." => "ноября", ".12." => "декабря");
        $func = new Functions();
        $model = Yii::app()->db->createCommand()
            ->select("p.model, s.name,a.cnt,a.desc,a.cause,sum(a.service) as service,sum(a.util) as util, s.spareId,a.actregisterId,p.phoneId")
            ->from("actDetail a")
            ->join("spare s","s.spareId = a.spareId")
            ->leftjoin("phone p","p.phoneId = a.phoneId")
            ->where("a.actregisterId = :id OR (a.actregisterId = :id and s.spareType = 'detail')",array(":id"=>$id))
            ->group("a.phoneId, a.spareId")
            ->order("p.model, a.actdetailId")
            ->queryAll();

        /*$model2 = Yii::app()->db->createCommand()
            ->select("s.name,a.cnt,a.desc,a.cause,sum(a.service) as service,sum(a.util) as util, s.spareId,a.actregisterId,a.phoneId")
            ->from("actDetail a")
            ->join("spare s","s.spareId = a.spareId")
            ->where("a.actregisterId = :id and s.spareType = 'detail'",array(":id"=>$id))
            ->group("a.spareId")
            ->queryAll();*/
        //$model = array_merge($model, $model2);
        $act = Yii::app()->db->createCommand()
            ->select("a.actregisterId, a.brigadir, a.departmentId,a.actNum, d.code, a.actDate, d.name")
            ->from("actregister a")
            ->join("department d","d.departmentId = a.departmentId")
            ->where("a.actregisterId = :id",array(":id"=>$id))
            ->queryRow();
        $template = $func->getReportTemplate($act["departmentId"],"akt");
        $phones = Yii::app()->db->createCommand()
            ->select("ph.model, count(*) as cnt")
            ->from("produce p")
            ->join("phone ph","p.phoneId = ph.phoneId")
            ->where("date(p.produceDate) = :date",array(":date"=>date("Y-m-d", strtotime($act["actDate"]))))
            ->group("p.phoneId")
            ->queryAll();
        $department = Yii::app()->db->createCommand()
            ->select()
            ->from("department")
            ->where("departmentId = :id",array(":id"=>$act["departmentId"]))
            ->queryRow();
        $phpExcelPath = Yii::getPathOfAlias('ext.phpexcel');
        spl_autoload_unregister(array('YiiBase','autoload'));
        Yii::import('ext.PHPExcel.PHPExcel', true);
        //echo $phpExcelPath;
        //die;
        //$path = Yii::getPathOfAlias('webroot').'/protected/extensions/phpexcel/PHPExcel.php';//$phpExcelPath . DIRECTORY_SEPARATOR . 'PHPExcel.php';
        //          require_once $path;
        //          require_once 'PHPExcel/IOFactory.php';


        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        date_default_timezone_set('Europe/London');

        if (PHP_SAPI == 'cli')
            die('Запуск поддерживаеться через браузер');

        /** Include PHPExcel */
        $file = "/upload/template/".$template.".xls";
        //$file2 = "/upload/template/".$template2.".xls";
        //require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';
        $path = Yii::getPathOfAlias('webroot').$file;
        $inputFileType = 'Excel5';
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($path);



        $styleArray = array(
            'borders' => array(
                'outline' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => '000000'),
                ),
            ),
        );

        if($act["departmentId"] == 7 || $act["departmentId"] == 8 || $act["departmentId"] == 9 ){
            // Add some data
            $objPHPExcel->setActiveSheetIndex(1)
                ->setCellValue('D2', "" . date("d", strtotime($act["actDate"])) . " " . date(".m.", strtotime($act["actDate"])) . "" . date("Y", strtotime($act["actDate"])));
            $objPHPExcel->setActiveSheetIndex(1)->setCellValue('D1', "S - ".$department["name"] . $act["actNum"]);

            $objPHPExcel->setActiveSheetIndex(1)->setCellValue('C5', "Kimdan/От кого:  " . $act["name"]);
            if($act["departmentId"] != 7){
                $objPHPExcel->setActiveSheetIndex(1)->setCellValue('C14', "Berdim/Отпустил:  ".$act["brigadir"]);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A10', "".$department["desc"].":")
                                                    ->setCellValue('J10', "".$department["brigadir"]."");
            }
            //$objPHPExcel->setActiveSheetIndex(1)->setCellValue('C14', "Berdim/Отпустил:  Азимжонова Ж");
            $serCnt=0;
            foreach ($model as $item) {
                if ($item["service"] != 0)
                    $serCnt++;
            }
            if ($serCnt > 0)
                $objPHPExcel->getActiveSheet()->insertNewRowBefore(10, $serCnt);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('M5', "" . date("d", strtotime($act["actDate"])) . date(".m.", strtotime($act["actDate"])) . date("Y", strtotime($act["actDate"])));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M4', "АКТ  ".$act["code"] . " - " . $act["actNum"]);

            $i=0;
            //if($i!=0)
            $skd=0;
            $serCnt=0;
            $produce=0;
            $service=0;
            $util=0;
            $rowCnt = 0;
            $modelName = "";
            foreach ($model as $key=>$val) {
                $desc = Yii::app()->db->createCommand()
                    ->select()
                    ->from("actdetail")
                    ->where("spareId = :id and phoneId = :pId and actregisterId = :regId",array(":id"=>$val["spareId"], ":pId"=>$val["phoneId"], ":regId"=>$val["actregisterId"]))
                    ->queryAll();
                if (count($desc) > 0)
                    $objPHPExcel->setActiveSheetIndex(0)->insertNewRowBefore(8+$i, count($desc)+1);

                $skdCnt=$func->getSKDCnt($val["spareId"], $val["phoneId"], $val["actregisterId"]);
                $skd=$skd + $skdCnt;
                $produceCnt=$func->getProduceCnt($val["spareId"], $val["phoneId"], $val["actregisterId"]);
                $produce=$produce + $produceCnt;
                $service=$service + $val["service"];
                $util=$util + $val["util"];
                $i++;
                $rowCnt = count($desc);

                $objPHPExcel->getActiveSheet()
                    ->setCellValue('A' . ($i + 7), $key + 1)
                    ->setCellValue('B' . ($i + 7), $val["model"])
                    ->setCellValue('C' . ($i + 7), "")
                    ->setCellValue('D' . ($i + 7), $val["name"])
                    ->setCellValue('E' . ($i + 7), "")
                    ->setCellValue('F' . ($i + 7), "")
                    ->setCellValue('G' . ($i + 7), "")
                    ->setCellValue('H' . ($i + 7), "100%")
                    ->setCellValue('I' . ($i + 7), $val["service"]+$val["util"])
                    ->setCellValue('K' . ($i + 7), "");
                $objPHPExcel->getActiveSheet()
                    ->mergeCells("A".($i+7).":A".($i+7 +count($desc)-1));
                $objPHPExcel->getActiveSheet()
                    ->mergeCells("B".($i+7).":B".($i+7+count($desc)-1));
                $objPHPExcel->getActiveSheet()
                    ->mergeCells("C".($i+7).":C".($i+7+count($desc)-1));
                $objPHPExcel->getActiveSheet()
                    ->mergeCells("D".($i+7).":D".($i+7+count($desc)-1));
                $objPHPExcel->getActiveSheet()
                    ->mergeCells("E".($i+7).":E".($i+7+count($desc)-1));
                $objPHPExcel->getActiveSheet()
                    ->mergeCells("F".($i+7).":F".($i+7+count($desc)-1));
                $objPHPExcel->getActiveSheet()
                    ->mergeCells("G".($i+7).":G".($i+7+count($desc)-1));
                $objPHPExcel->getActiveSheet()
                    ->mergeCells("H".($i+7).":H".($i+7+count($desc)-1));
                $objPHPExcel->getActiveSheet()
                    ->mergeCells("I".($i+7).":J".($i+7+count($desc)-1));
                $objPHPExcel->getActiveSheet()
                    ->mergeCells("K".($i+7).":K".($i+7+count($desc)-1));
                $rCnt = 0;
                foreach ($desc as $item) {
                    $objPHPExcel->getActiveSheet()
                        ->setCellValue('L' . ($i + 7 + $rCnt), $item["desc"])
                        ->setCellValue('M' . ($i + 7 + $rCnt), $item["cnt"]);
                    $rCnt++;
                }


                $i = $i+$rowCnt;

                $objPHPExcel->setActiveSheetIndex(0)
                    ->mergeCells("A".($i+7).":E".($i+7));
                $objPHPExcel->getActiveSheet()
                    ->mergeCells("I".($i+7).":J".($i+7));
                $objPHPExcel->getActiveSheet()
                    ->setCellValue('A' . ($i + 7), "Итого:")
                    ->setCellValue('F' . ($i + 7), "")
                    ->setCellValue('G' . ($i + 7), "")
                    ->setCellValue('H' . ($i + 7), "100%")
                    ->setCellValue('I' . ($i + 7), $val["service"]+$val["util"])
                    ->setCellValue('K' . ($i + 7), "")
                    ->setCellValue('L' . ($i + 7), "")
                    ->setCellValue('M' . ($i + 7), $val["service"]+$val["util"]);
                //$i = $i+5;

                if ($val["service"] != 0) {
                    $serCnt++;
                    $objPHPExcel->setActiveSheetIndex(1)
                        ->setCellValue('B' . ($serCnt + 9), $serCnt)
                        ->setCellValue('C' . ($serCnt + 9), $val["model"] . " " . $val["name"])
                        ->setCellValue('D' . ($serCnt + 9), "шт")
                        ->setCellValue('E' . ($serCnt + 9), ($val["service"] == 0) ? "-" : $val["service"])
                        ->setCellValue('H' . ($serCnt + 9), $serCnt)
                        ->setCellValue('I' . ($serCnt + 9), $val["model"] . " " . $val["name"])
                        ->setCellValue('J' . ($serCnt + 9), "шт")
                        ->setCellValue('K' . ($serCnt + 9), ($val["service"] == 0) ? "-" : $val["service"]);
                }
                $modelName = $val["model"];
            }
            $text="АКТ по итогам входного контроля материалов \"SKD\"  на модели Artel " . $modelName;
            if($act["departmentId"] == 7)
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3', $text);

            $objPHPExcel->setActiveSheetIndex(1)
                 ->setCellValue('B' . ($serCnt + 10), "Итого")
                ->setCellValue('E' . ($serCnt + 10), $service)
                ->setCellValue('H' . ($serCnt + 10), "Итого")
                ->setCellValue('K' . ($serCnt + 10), $service);
            // Miscellaneous glyphs, UTF-8

            // Rename worksheet

            $objPHPExcel->getActiveSheet()->setTitle('nakladnoy');

            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);

            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->setTitle('act');
        }
        else{
            try {
                // Add some data
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('G5', "\"" . date("d", strtotime($act["actDate"])) . "\"   " . $_monthsList[date(".m.", strtotime($act["actDate"]))] . "   " . date("Y", strtotime($act["actDate"])));
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E3', $act["code"] . " - " . $act["actNum"]);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B22', $department["desc"] . "________________");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D22', $act["brigadir"]);
                if (count($model) > 1)
                    $objPHPExcel->getActiveSheet()->insertNewRowBefore(14, count($model));


                $objPHPExcel->setActiveSheetIndex(1)
                    ->setCellValue('D2', "" . date("d", strtotime($act["actDate"])) . " " . date(".m.", strtotime($act["actDate"])) . "" . date("Y", strtotime($act["actDate"])));
                $objPHPExcel->setActiveSheetIndex(1)->setCellValue('D1', "S - " . $department["name"] . $act["actNum"]);

                $objPHPExcel->setActiveSheetIndex(1)->setCellValue('C5', "Kimdan/От кого:  " . $act["name"]);

                $objPHPExcel->setActiveSheetIndex(1)->setCellValue('C14', "Berdim/Отпустил:  " . $act["brigadir"]);
                $serCnt=0;
                foreach ($model as $item) {
                    if ($item["service"] != 0)
                        $serCnt++;
                }
                if ($serCnt > 0)
                    $objPHPExcel->getActiveSheet()->insertNewRowBefore(10, $serCnt);
                $i=0;

                //if($i!=0)
                $skd=0;
                $serCnt=0;
                $produce=0;
                $service=0;
                $util=0;

                foreach ($model as $key=>$val) {
                    $skdCnt=$func->getSKDCnt($val["spareId"], $val["phoneId"], $val["actregisterId"]);
                    $skd=$skd + $skdCnt;
                    $produceCnt=$func->getProduceCnt($val["spareId"], $val["phoneId"], $val["actregisterId"]);
//                    echo "<pre>";
//                    print_r($produceCnt);
//                    echo "</pre>";
                    $produce=$produce + $produceCnt;
                    $service=$service + $val["service"];
                    $util=$util + $val["util"];
                    $i++;
                    if ($val["service"] != 0) {
                        $serCnt++;
                        $objPHPExcel->setActiveSheetIndex(1)
                            ->setCellValue('B' . ($serCnt + 9), $serCnt)
                            ->setCellValue('C' . ($serCnt + 9), $val["model"] . " " . $val["name"])
                            ->setCellValue('D' . ($serCnt + 9), "шт")
                            ->setCellValue('E' . ($serCnt + 9), ($val["service"] == 0) ? "-" : $val["service"])
                            ->setCellValue('H' . ($serCnt + 9), $serCnt)
                            ->setCellValue('I' . ($serCnt + 9), $val["model"] . " " . $val["name"])
                            ->setCellValue('J' . ($serCnt + 9), "шт")
                            ->setCellValue('K' . ($serCnt + 9), ($val["service"] == 0) ? "-" : $val["service"]);
                    }
                    //->setCellValue('F'.($i+3), '')
                    $objPHPExcel->setActiveSheetIndex(0)->getStyle('B' . ($i + 13))->applyFromArray($styleArray);
                    $objPHPExcel->setActiveSheetIndex(0)->getStyle('C' . ($i + 13))->applyFromArray($styleArray);
                    $objPHPExcel->setActiveSheetIndex(0)->getStyle('D' . ($i + 13))->applyFromArray($styleArray);
                    $objPHPExcel->setActiveSheetIndex(0)->getStyle('E' . ($i + 13))->applyFromArray($styleArray);
                    $objPHPExcel->setActiveSheetIndex(0)->getStyle('H' . ($i + 13))->applyFromArray($styleArray);
                    $objPHPExcel->setActiveSheetIndex(0)->getStyle('I' . ($i + 13))->applyFromArray($styleArray);
                    $objPHPExcel->setActiveSheetIndex(0)->getStyle('J' . ($i + 13))->applyFromArray($styleArray);
                    $objPHPExcel->setActiveSheetIndex(0)->getStyle('K' . ($i + 13))->applyFromArray($styleArray);
                    //$objPHPExcel->getActiveSheet()->duplicateStyle( $objPHPExcel->getActiveSheet()->getStyle('I1'), 'A'.($i+9).':K'.($i+9) );

                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . ($i + 13), $key + 1)
                        ->setCellValue('B' . ($i + 13), $val["model"] . " " . $val["name"])
                        ->setCellValue('C' . ($i + 13), "шт")
                        ->setCellValue('D' . ($i + 13), ($skdCnt != 0) ? $skdCnt : '')
                        ->setCellValue('E' . ($i + 13), ($produceCnt != 0) ? $produceCnt : '')
                        ->setCellValue('F' . ($i + 13), $val["util"])
                        ->setCellValue('G' . ($i + 13), $func->getActDescToExcel($val["spareId"], $val["phoneId"], $val["actregisterId"]));
                    //->setCellValue('F'.($i+3), '')
                    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A' . ($i + 13))->applyFromArray($styleArray);
                    $objPHPExcel->setActiveSheetIndex(0)->getStyle('B' . ($i + 13))->applyFromArray($styleArray);
                    $objPHPExcel->setActiveSheetIndex(0)->getStyle('C' . ($i + 13))->applyFromArray($styleArray);
                    $objPHPExcel->setActiveSheetIndex(0)->getStyle('D' . ($i + 13))->applyFromArray($styleArray);
                    $objPHPExcel->setActiveSheetIndex(0)->getStyle('E' . ($i + 13))->applyFromArray($styleArray);
                    $objPHPExcel->setActiveSheetIndex(0)->getStyle('F' . ($i + 13))->applyFromArray($styleArray);
                    $objPHPExcel->setActiveSheetIndex(0)->getStyle('G' . ($i + 13))->applyFromArray($styleArray);
                    $objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('I1'), 'A' . ($i + 13) . ':G' . ($i + 13));

                }

                $objPHPExcel->setActiveSheetIndex(1)
                    ->setCellValue('B' . ($serCnt + 10), "Итого")
                    ->setCellValue('E' . ($serCnt + 10), $service)
                    ->setCellValue('H' . ($serCnt + 10), "Итого")
                    ->setCellValue('K' . ($serCnt + 10), $service);

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . ($i + 14), "")
                    ->setCellValue('B' . ($i + 14), "Итого")
                    ->setCellValue('C' . ($i + 14), "шт")
                    ->setCellValue('D' . ($i + 14), $skd)
                    ->setCellValue('E' . ($i + 14), $produce)
                    ->setCellValue('F' . ($i + 14), $util)
                    ->setCellValue('G' . ($i + 14), "");
                //->setCellValue('F'.($i+3), '')
                $objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('I1'), 'A' . ($i + 14) . ':G' . ($i + 14));

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . ($i + 15), "")
                    ->setCellValue('B' . ($i + 15), "Итог")
                    ->setCellValue('C' . ($i + 15), "шт")
                    ->setCellValue('D' . ($i + 15), $skd + $produce)
                    ->setCellValue('E' . ($i + 15), "")
                    ->setCellValue('F' . ($i + 15), $util)
                    ->setCellValue('G' . ($i + 15), "");

                $objPHPExcel->getActiveSheet()->mergeCells("D" . ($i + 15) . ":E" . ($i + 15));
                //->setCellValue('F'.($i+3), '')
                $objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('I1'), 'A' . ($i + 15) . ':G' . ($i + 15));

                if (count($phones) > 3)
                    $objPHPExcel->getActiveSheet()->insertNewRowBefore(5, count($phones));
                foreach ($phones as $i=>$val) {
                    $text="Кол-во произведенных телефонов модели " . $val["model"] . "-" . $val["cnt"];
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . ($i + 2), $text);
                    //->setCellValue('F'.($i+3), '')
                    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A' . ($i + 13))->applyFromArray($styleArray);
                    $objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('B2'), 'B' . ($i + 2));
                }
                // Miscellaneous glyphs, UTF-8

                // Rename worksheet


                // Set active sheet index to the first sheet, so Excel opens this as the first sheet
                $objPHPExcel->setActiveSheetIndex(0);
                $objPHPExcel->getActiveSheet()->setTitle('act');

                $objPHPExcel->setActiveSheetIndex(1);
                $objPHPExcel->getActiveSheet()->setTitle('nakladnoy');
            }
            catch (Exception $ex){
                echo "<pre>";
                print_r($ex);
                echo "</pre>";
            }
        }
        // Redirect output to a client’s web browser (Excel2007)
        //header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-type:application/vnd.ms-excel");
        header('Content-Disposition: attachment;filename="Акт № '. $act["actNum"] .' от '.date('d.m.Y',strtotime($act['actDate'])).'.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0


        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        if (ob_get_contents()) ob_end_clean();
        $objWriter->save('php://output');
        spl_autoload_register(array('YiiBase','autoload'));
        exit;
    }

    // Empolyee section

    public function actionEmpAdmin(){
        $model = Yii::app()->db->createCommand()
            ->select("e.employeeId, e.name, e.surname, e.lastname, e.birthday, e.photo, e.sex, d.name as dName")
            ->from("employee e")
            ->join("department d","d.departmentId = e.departmentId")
            ->where("e.status = 0")
            ->queryAll();
        $this->render("employee/admin",array(
            "model" => $model
        ));
    }

    public function actionEmployeeList(){
        $model = Yii::app()->db->createCommand()
            ->select("e.employeeId, e.name, e.surname, e.lastname, e.birthday, e.photo, e.sex, d.name as dName,e.departmentId")
            ->from("employee e")
            ->join("department d","d.departmentId = e.departmentId")
            ->where("e.status = 0")
            ->queryAll();
        $this->render("employee/empList",array(
            "model" => $model
        ));
    }


    public function actionEmpListPrint(){
        if(isset($_GET["id"])){
            $data = explode(",",$_GET["id"]);
            foreach ($data as $key => $val) {
                $temp = Yii::app()->db->createCommand()
                    ->select("e.employeeId, e.name, e.surname, e.lastname, e.birthday, e.photo, e.sex, d.name as dName,e.departmentId")
                    ->from("employee e")
                    ->join("department d", "d.departmentId = e.departmentId")
                    ->where("e.employeeId = :id", array(":id" => $val))
                    ->queryRow();
                $model[$key] = $temp;
            }
        }
        else {
            $model = Yii::app()->db->createCommand()
                ->select("e.employeeId, e.name, e.surname, e.lastname, e.birthday, e.photo, e.sex, d.name as dName,e.departmentId")
                ->from("employee e")
                ->join("department d", "d.departmentId = e.departmentId")
                ->where("e.status = 0")
                ->queryAll();
        }
        $this->renderPartial("employee/empListPrint",array(
            "model" => $model
        ));
    }

    public function actionEmpCreate(){
        $dep = Yii::app()->db->createCommand()
            ->select()
            ->from("department")
            ->where("status != 1")
            ->queryAll();
        $pos = Yii::app()->db->createCommand()
            ->select()
            ->from("position")
            ->queryAll();
        if(!empty($_POST)){

            $uploaded = false;
            $message = "";
            if($_FILES["file"]["name"] != "") {
                $target_dir=$_SERVER["DOCUMENT_ROOT"] . "/upload/employee/";
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
            if($_FILES["file2"]["name"] != "") {
                $target_dir=$_SERVER["DOCUMENT_ROOT"] . "/upload/employee/";
                $target_file=$target_dir . basename($_FILES["file2"]["name"]);
                $uploadOk=1;
                $imageFileType=strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
                if (isset($_FILES["file2"])) {
                    $check=getimagesize($_FILES["file2"]["tmp_name"]);
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
                    if (move_uploaded_file($_FILES["file2"]["tmp_name"], $target_file)) {


                        $message="Файл с названием " . basename($_FILES["file2"]["name"]) . " был загружен.";
                        $uploaded=true;

                    } else {
                        $message="Извените, произошла ошибка при загрузке файла.";
                        $uploaded=false;
                    }
                }
            }
            Yii::app()->db->createCommand()->insert("employee",array(
                "name" => $_POST["name"],
                "surname" => $_POST["surname"],
                "lastname" => $_POST["lastname"],
                "birthday" => date("Y-m-d",strtotime($_POST["start"])),
                "sex" => $_POST["sex"],
                "departmentId" => $_POST["depId"],
                "positionId" => $_POST["posId"],
                "photo" => $_FILES["file"]["name"],
                "photo2" => $_FILES["file2"]["name"]
            ));
        }
        $this->render("employee/create",array(
            'dep' => $dep,
            'pos' => $pos
        ));
    }

    public function actionEmpUpdate($id){

        $dep = Yii::app()->db->createCommand()
                ->select()
                ->from("department")
                ->where("status != 1")
                ->queryAll();
        $pos = Yii::app()->db->createCommand()
            ->select()
            ->from("position")
            ->queryAll();
        $model = Yii::app()->db->createCommand()
            ->select("e.employeeId, e.name, e.surname, e.lastname, e.birthday, e.photo, e.sex, e.departmentId, e.positionId,e.code")
            ->from("employee e")
            ->where("e.status = 0 and e.employeeId = :id",array(":id"=>$id))
            ->queryRow();
            if(!empty($_POST)){
                if($_FILES["file"]["name"] != "") {
                    $uploaded=false;
                    $message="";
                    $target_dir=$_SERVER["DOCUMENT_ROOT"] . "/upload/employee/";
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
                    if ($uploaded) {
                        Yii::app()->db->createCommand()->update("employee", array(
                            "photo"=>$_FILES["file"]["name"]
                        ), "employeeId = :id", array(":id"=>$id));

                    }
                }
                if($_FILES["file2"]["name"] != "") {
                    $uploaded=false;
                    $message="";
                    $target_dir=$_SERVER["DOCUMENT_ROOT"] . "/upload/employee/";
                    $target_file=$target_dir . basename($_FILES["file2"]["name"]);
                    $uploadOk=1;
                    $imageFileType=strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
                    if (isset($_FILES["file"])) {
                        $check=getimagesize($_FILES["file2"]["tmp_name"]);
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
                        if (move_uploaded_file($_FILES["file2"]["tmp_name"], $target_file)) {


                            $message="Файл с названием " . basename($_FILES["file2"]["name"]) . " был загружен.";
                            $uploaded=true;

                        } else {
                            $message="Извените, произошла ошибка при загрузке файла.";
                            $uploaded=false;
                        }
                    }
                    if ($uploaded) {
                        Yii::app()->db->createCommand()->update("employee", array(
                            "photo2"=>$_FILES["file2"]["name"]
                        ), "employeeId = :id", array(":id"=>$id));

                    }
                }
                Yii::app()->db->createCommand()->update("employee",array(
                    "name" => $_POST["name"],
                    "surname" => $_POST["surname"],
                    "lastname" => $_POST["lastname"],
                    "birthday" => date("Y-m-d",strtotime($_POST["start"])),
                    "sex" => $_POST["sex"],
                    "positionId" => $_POST["posId"],
                    "departmentId" => $_POST["depId"],
                    "code" => $_POST["code"]
                ),"employeeId = :id",array(":id" => $id));
                $this->redirect("/admin/empAdmin");
            }
            $this->render("employee/update",array(
                'dep' => $dep,
                'model' => $model,
                'pos' => $pos
            ));
    }

    public function actionEmpDelete($id){
        Yii::app()->db->createCommand()->update("employee",array(
            "status" => 1,
            "code" => null
        ),"employeeId = :id",array(":id" => $id));
        $this->redirect("/admin/empAdmin");
    }

    public function actionGetEmployee(){
        $data[0] = $_POST["id"];//explode("-",$_POST["id"]);
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("employee")
            ->where("code = :id",array(":id"=>$data[0]))
            ->queryRow();
        $model["stat"]="false";
        $cnt=Yii::app()->db->createCommand()
            ->select()
            ->from("action")
            ->where("employeeId = :id and actionType = 'reception' and date(actionTime) = :date", array(":id"=>$model["employeeId"],":date"=>date("Y-m-d")))
            ->order('actionId desc')
            ->queryRow();
        if ($cnt["action"] == 'out' || empty($cnt)) {
            $model["stat"]="true";
        }
        echo json_encode($model);
    }

    public function actionTestEmp(){
        $data[0] = $_POST["id"];//explode("-",$_POST["id"]);
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("employee")
            ->where("code = :id",array(":id"=>$data[0]))
            ->queryRow();
        $model["stat"]="false";
        $cnt=Yii::app()->db->createCommand()
            ->select()
            ->from("action")
            ->where("employeeId = :id and actionType = 'reception' and date(actionTime) = :date", array(":id"=>$model["employeeId"],":date"=>date("Y-m-d")))
            ->order('actionId desc')
            ->queryRow();
//            if($cnt["cnt"] == 0) $cnt["cnt"] = 1;
        if ($cnt["action"] == 'out' || empty($cnt)) {
            $model["stat"]="true";
        }
        echo json_encode($model);
    }

    public function actionGetEmployees(){
        $data = explode("-",$_POST["id"]);
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("employee")
            ->where("code = :id",array(":id"=>$_POST["id"]))
            ->queryRow();
        $model["stat"]="true";
        echo json_encode($model);
    }

    public function actionSetReason(){
        $data = Yii::app()->db->CreateCommand()
            ->select()
            ->from("employee")
            ->where("code = :id", array(":id" => $_POST["id"]))
            ->queryRow();
        $reason = Yii::app()->db->CreateCommand()
            ->select()
            ->from("action")
            ->where("employeeId = :id and date(actionTime) = :d",array(":id"=>$data["employeeId"],":d"=>date("Y-m-d")))
            ->order("actionTime desc")
            ->queryRow();
        if($_POST["type"] == "in") {
            Yii::app()->db->createCommand()->insert("action", array(
                "employeeId" => $data["employeeId"],
                "action" => $_POST["type"],
                "reason" => "",
                "actionType" => "reception",
                "actionTime" => date("Y-m-d H:i:s")
            ));
        }
        else if($_POST["type"] == "out") {
            Yii::app()->db->createCommand()->insert("action", array(
                "actionType" => "reception",
                "employeeId" => $data["employeeId"],
                "action" => $_POST["type"],
                "reason" => $_POST["reason"],
                "actionTime" => date("Y-m-d H:i:s")
            ));
        }
    }

    public function actionExportEmployee(){

        $func = new Functions();
        $model = Yii::app()->db->createCommand()
            ->select("e.employeeId, e.name, e.surname, e.lastname, e.birthday, e.photo, e.sex, d.name as dName")
            ->from("employee e")
            ->join("department d","d.departmentId = e.departmentId")
            ->where("e.status = 0")
            ->queryAll();
        $phpExcelPath = Yii::getPathOfAlias('ext.phpexcel');
        spl_autoload_unregister(array('YiiBase','autoload'));
        Yii::import('ext.PHPExcel.PHPExcel', true);

        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        date_default_timezone_set('Europe/London');

        if (PHP_SAPI == 'cli')
            die('Запуск поддерживаеться через браузер');

        /** Include PHPExcel */
        $file = "/upload/template/employee.xls";
        //require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';
        $path = Yii::getPathOfAlias('webroot').$file;
        $inputFileType = 'Excel5';
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($path);



        $styleArray = array(
            'borders' => array(
                'outline' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => '000000'),
                ),
            ),
        );

        // Add some data
        $i = 0;
        //if($i!=0)
        $skd = 0;
        $produce = 0;
        $service = 0;
        $util = 0;
        foreach($model as $key => $val){
            $i++;
            $objPHPExcel->getActiveSheet()->getRowDimension($i+2)->setRowHeight(40);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.($i+2), $key + 1)
                ->setCellValue('B'.($i+2), $val["surname"] . " " . $val["name"] . " " . $val["lastname"])
                ->setCellValue('C'.($i+2), $val["dName"])
                ->setCellValue('D'.($i+2), date("d.m.Y",strtotime($val["birthday"])))
                ->setCellValue('E'.($i+2), ($val["sex"] != 1) ? "Жен" : "Муж")
                ->setCellValue('G'.($i+2), Yii::app()->basePath . DIRECTORY_SEPARATOR . "../upload/employee/" . $val["photo"] );

            if($val["photo"] != "") {
                $objDrawingPType=new PHPExcel_Worksheet_Drawing();
                $objDrawingPType->setWorksheet($objPHPExcel->setActiveSheetIndex(0));
                $objDrawingPType->setName("Pareto By Type");
                $objDrawingPType->setPath(Yii::app()->basePath . DIRECTORY_SEPARATOR . "../upload/employee/" . $val["photo"]);
                $objDrawingPType->setCoordinates('F' . ($i + 2));
                $objDrawingPType->setWidth(100);
                $objDrawingPType->setHeight(40);
                $objDrawingPType->setOffsetX(1);
                $objDrawingPType->setOffsetY(5);
            }

            //->setCellValue('F'.($i+3), '')
            //$objPHPExcel->getActiveSheet()->duplicateStyle( $objPHPExcel->getActiveSheet()->getStyle('B8'), 'B'.($i+13).':F'.($i+13) );
            //$objPHPExcel->setActiveSheetIndex(0)->getStyle('F'.($i+3))->applyFromArray($styleArray);
        }
        // Miscellaneous glyphs, UTF-8

        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('act');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);


        // Redirect output to a client’s web browser (Excel2007)
        //header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-type:application/vnd.ms-excel");
        header('Content-Disposition: attachment;filename="Сотрудники.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        if (ob_get_contents()) ob_end_clean();
        $objWriter->save('php://output');
        spl_autoload_register(array('YiiBase','autoload'));
        exit;
    }

    //End of employee block


    public function actionActRegisteredTest(){
        $regId = Yii::app()->db->createCommand()
            ->select()
            ->from("actregister")
            ->order("actregisterId DESC")
            ->queryRow();
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("phone")
            ->queryAll();
        $this->render("actRegisteredTest",array(
            "model" => $model,
            "regId" => (!empty($regId)) ? $regId["actregisterId"] : 0
        ));
    }

    public function actionSaveActTest(){
        $func = new Functions();
        if(isset($_POST["act"])){
            $regId = 0;
            $model = Yii::app()->db->createCommand()
                ->select()
                ->from("actregister")
                ->where("departmentId = :depId AND actNum = :actNum",array(":depId"=>$_POST["departmentId"],":actNum"=>$_POST["regId"]))
                ->queryRow();
            if(empty($model)){
                $department = Yii::app()->db->createCommand()
                    ->select()
                    ->from("department d")
                    ->where("departmentId = :id",array(":id"=>$_POST["departmentId"]))
                    ->queryRow();
                Yii::app()->db->createCommand()->insert("actregister",array(
                    'departmentId' => $_POST["departmentId"],
                    'actNum' => $_POST["regId"],
                    'actDate' => date("Y-m-d H:i:s"),
                    'brigadir' => $department["brigadir"]
                ));
                $text = "departmentId:".$_POST["departmentId"];
                $regId = Yii::app()->db->getLastInsertID();
                $func->setLogs($regId,"insert",$text,"actregister");
            }
            else{
                $regId = $model["actregisterId"];
            }
            if($_POST["phoneId"] == null)
                $_POST["phoneId"] = 0;
            foreach ($_POST["act"]["spare"] as $key => $val) {
                Yii::app()->db->createCommand()->insert("actdetail",array(
                    "spareId" => $val,
                    "cnt" => $_POST["act"]["cnt"][$key],
                    "cause" => $_POST["act"]["cause"][$key],
                    "desc" => $_POST["act"]["desc"][$key],
                    "actregisterId" => $regId,
                    "phoneId" => $_POST["phoneId"]
                ));

                $text = "actregisterId:".$_POST["regId"]."departmentId:".$_POST["departmentId"].",spareId:".$val.",cnt:".$_POST["act"]["cnt"][$key].",cause:".$_POST["act"]["cause"][$key].",desc:".$_POST["act"]["desc"][$key];
                $func->setLogs(Yii::app()->db->getLastInsertID(),"insert",$text,"actdetail");
            }

            $model = Yii::app()->db->createCommand()
                ->select()
                ->from("phone")
                ->queryAll();
            echo json_encode($model,true);
        }
    }

    public function actionTests(){

    }
    
    public function actionShopAdmin(){
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("shopproduct")
            ->where("status = 0")
            ->queryAll();
        $this->render("shop/admin",array(
            "model" => $model
        ));
    }


    public function actionShopCreate(){
        if(!empty($_POST)){

            $uploaded = false;
            $message = "";
            if($_FILES["file"]["name"] != "") {
                $target_dir=$_SERVER["DOCUMENT_ROOT"] . "/upload/shop/";
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
            Yii::app()->db->createCommand()->insert("shopproduct",array(
                "name" => $_POST["name"],
                "ball" => $_POST["ball"],
                "content" => $_POST["content"],
                "photo" => $_FILES["file"]["name"]
            ));
            $this->redirect("/admin/shopAdmin");
        }
        $this->render("shop/create",array(
        ));
    }

    public function actionShopUpdate($id){

        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("shopproduct")
            ->where("status = 0 and shopproductId = :id",array(":id"=>$id))
            ->queryRow();
        if(!empty($_POST)){
            if($_FILES["file"]["name"] != "") {
                $uploaded=false;
                $message="";
                $target_dir=$_SERVER["DOCUMENT_ROOT"] . "/upload/shop/";
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
                if ($uploaded) {
                    Yii::app()->db->createCommand()->update("shopproduct", array(
                        "photo"=>$_FILES["file"]["name"]
                    ), "shopproductId = :id", array(":id"=>$id));

                }
            }
            Yii::app()->db->createCommand()->update("shopproduct",array(
                "name" => $_POST["name"],
                "ball" => $_POST["ball"],
                "content" => $_POST["content"],
            ),"shopproductId = :id",array(":id" => $id));
            $this->redirect("/admin/shopAdmin");
        }
        $this->render("shop/update",array(
            'model' => $model,
        ));
    }

    public function actionShopDetail(){
        $_POST["id"] = 10;
        $model = Yii::app()->db->createCommand()
            ->select("sum(ball) as ball,u.name, u.surname, u.lastname,u.login,u.userId")
            ->from("shopdetail shd")
            ->join("shopproduct sh","sh.shopproductId = shd.shopproductId")
            ->join("users u","u.userId = shd.userId")
            ->where("shd.userId = :id and shd.status = 1",array(":id"=>$_POST["id"]))
            ->group("shd.userId")
            ->queryAll();
        $this->render("shop/shopDetail",array(
            "model" => $model
        ));
    }

    public function actionShopDelete(){
        Yii::app()->db->createCommand()->update("shopproduct",array(
            'status'=> 1
        ),"shopproductId = :id",array(":id"=>$_GET["id"]));
        $this->redirect("/admin/shopAdmin");
    }

    public function actionOkOrder(){
        Yii::app()->db->createCommand()->delete("shopdetail","userId = :id",array(":id"=>$_GET["id"]));
        $this->redirect('/shopDetail');
    }

    public function actionCencelOrder(){
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("shopdetail shd")
            ->join("shopproduct sh","sh.shopproductId = shd.shopproductId")
            ->where("shd.userId = :id and shd.status = 1",array(":id"=>$_GET["id"]))
            ->queryAll();
        $ball = Yii::app()->db->createCommand()
            ->select()
            ->from("ball")
            ->where("userId = :id",array(":id"=>$_GET["id"]))
            ->queryRow();
        $sum = 0;
        foreach ($model as $item) {
            $sum = $sum + $item["ball"];
        }

        Yii::app()->db->createCommand()->update("shopdetail",array(
            "status" => 0
        ),"userId = :id and status = 1",array(":id"=>$_GET["id"]));
        Yii::app()->db->createCommand()->update("ball",array(
            "ball" => $ball["ball"]+$sum
        ),"userId = :id",array(":id"=>$_GET["id"]));
        $this->redirect('/shopDetail');
    }

    public function actionCheckBall(){
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("ball b")
            ->join("users u","u.userId = b.userId")
            ->queryAll();

        $this->render("checkBall",array(
            "model" => $model
        ));
    }

    public function actionChangeBall(){
        Yii::app()->db->createCommand()->update("ball",array(
            "ball" => $_POST["val"]
        ),"ballId = :id",array(":id"=>$_POST["id"]));
    }

    public function actionActOtk(){
        $dep = Yii::app()->db->createCommand()
            ->select()
            ->from("department")
            ->where("departmentId = 7")
            ->queryRow();
        $regId = Yii::app()->db->createCommand()
            ->select()
            ->from("actregister")
            ->order("actregisterId DESC")
            ->where("departmentId = 7 and status != 1")
            ->queryRow();
        $id = $regId["actregisterId"];
        if(empty($regId)){
            $regId = Yii::app()->db->createCommand()
                ->select()
                ->from("actregister")
                ->order("actregisterId DESC")
                ->where("departmentId = 7")
                ->order("actregisterId desc")
                ->queryRow();
            Yii::app()->db->createCommand()->insert("actregister",array(
                "status" => 0,
                "brigadir" => $dep["brigadir"],
                "departmentId" => 7,
                "actDate" => date("Y-m-d H:i:s"),
                "actNum" => $regId["actNum"]+1
            ));
            $id = Yii::app()->db->getLastInsertId();
        }

        $this->render("ajax/actOtk",array(
            "regId"=>$id
        ));
    }


    public function actionactLabDetail($id){

        $spare = Yii::app()->db->createCommand()
            ->select()
            ->from("spare")
            ->order("name")
            ->queryAll();
        $error = Yii::app()->db->createCommand()
            ->select()
            ->from("error")
            ->order("description")
            ->queryAll();
        $list = Yii::app()->db->createCommand()
            ->select()
            ->from("phone")
            ->queryAll();
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("actDetail a")
            ->join("spare s","s.spareId = a.spareId")
            ->join("phone p","p.phoneId = a.phoneId")
            ->where("a.actregisterId = :id",array(":id"=>$id))
            ->order("p.model")
            ->queryAll();
        $model2 = Yii::app()->db->createCommand()
            ->select()
            ->from("actDetail a")
            ->join("spare s","s.spareId = a.spareId")
            ->where("a.actregisterId = :id and s.spareType = 'detail'",array(":id"=>$id))
            ->queryAll();
        $model = array_merge($model, $model2);

        $act = Yii::app()->db->createCommand()
            ->select()
            ->from("actregister a")
            ->join("department d","d.departmentId = a.departmentId")
            ->where("a.actregisterId = :id",array(":id"=>$id))
            ->queryRow();
        $this->renderPartial("ajax/actLabDetail",array(
            'model' => $model,
            "act" => $act,
            'list' => $list,
            'spare' => $spare,
            'error' => $error
        ));
    }

    public function actionAddActLabDetail(){
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

        for ($i = 1; $i <= $_POST["cnt"];$i++){
            Yii::app()->db->createCommand()->insert("register",array(
                "errorIdOtk" => $_POST["error"],
                "registerCode" => $_POST["code"],
                "phoneId" => $_POST["phone"],
                "status" => 1,
                "solve" => "Vxodnoy",
                "spareId" => 0,
                "userIdOtk" => Yii::app()->user->getId(),
                "errorOtkDate" => date("Y-m-d H:i:s"),
                "cause" => "SKD brak",
            ));
        }

        if($res){
            $func->setLogs(Yii::app()->db->getLastInsertID(),"insert","actregisterId:".$_POST["regId"].",phoneId:".$_POST["phone"].", spareId:".$_POST["spare"].",cnt:".$_POST["cnt"].",cause:".$_POST["cause"].",desc:".$_POST["desc"],"actdetail");
        }
    }

    public function actionProducePhones(){
        $callStartTime = microtime(true);
        $val=array();
        $phpExcelPath = Yii::getPathOfAlias('ext.phpexcel');
        spl_autoload_unregister(array('YiiBase','autoload'));
        //Yii::import('ext.PHPExcel.PHPExcel', true);
        //echo $phpExcelPath;
        //die;
        //$path = Yii::getPathOfAlias('webroot').'/protected/extensions/phpexcel/PHPExcel.php';//$phpExcelPath . DIRECTORY_SEPARATOR . 'PHPExcel.php';
        //          require_once $path;
        //          require_once 'PHPExcel/IOFactory.php';


        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        date_default_timezone_set('Europe/London');

        if (PHP_SAPI == 'cli')
            die('Запуск поддерживаеться через браузер');

        /** Include PHPExcel */
        $file = "/upload/produce.sql";
        $path = Yii::getPathOfAlias('webroot').$file;
        require_once('PHPExcel/IOFactory.php');
        $objPHPExcel = PHPExcel_IOFactory::load("upload/U3.xlsx");

        $objPHPExcel->setActiveSheetIndex(0);

        $highestRow = $objPHPExcel->getActiveSheet()->getHighestRow(); // e.g. 10

            $highestColumn      = $objPHPExcel->getActiveSheet()->getHighestColumn(); // e.g 'F'
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
            $nrColumns = ord($highestColumn) - 64;
            for ($row = 2; $row <= $highestRow; ++ $row) {

                for ($col = 0; $col < $highestColumnIndex; ++ $col) {
                    $cell = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($col, $row);
                    $val[$row][] = $cell->getValue();
                }
                //$sql="insert into produce (`phoneId`, `sn`, `printId`, `box`, `colorId`, `NW`, `GW`, `produceDate`, `whom`, `placer`) values(`".$val[0]."`, `".$val[1]."`, `".$val[2].")";
            }
        spl_autoload_register(array('YiiBase','autoload'));
        $callEndTime = microtime(true);
        $callTime = $callEndTime - $callStartTime;
        echo 'Формирование массива заняло ' . sprintf('%.4f',$callTime) . " seconds <br>";
        //$str = "INSERT INTO produce (`phoneId`, `sn`, `printId`, `box`, `colorId`, `NW`, `GW`, `produceDate`, `whom`, `placer`) VALUES \n";
//echo "<pre>";
//print_r($val);
//echo "</pre>";
        $cnt = 1;
        foreach ($val as $item) {
            /*if($cnt%1200 == 0){
                $str .= "(" . $this->getPhone($item[3]) . ", '" . $item[3] . "', " . $this->getPrintId($item[3]) . ", '" . $item[2] . "', " . $this->getColor($item[4]) . ", '" . floatval(preg_replace("/[^-0-9\.]/", ".", $item[6])) . "', '" . floatval(preg_replace("/[^-0-9\.]/", ".", $item[6])) . "', '" . date("Y-m-d H:i:s", strtotime($item[7])) . "', '" . $item[9] . "', 0);\n";
                $str .= "INSERT INTO produce (`phoneId`, `sn`, `printId`, `box`, `colorId`, `NW`, `GW`, `produceDate`, `whom`, `placer`) VALUES \n";

                $current = file_get_contents($path);
                $current .= $str;
                file_put_contents($path, $current);
                $str = "";
            }
            else {
                $str .= "(" . $this->getPhone($item[3]) . ", '" . $item[3] . "', " . $this->getPrintId($item[3]) . ", '" . $item[2] . "', " . $this->getColor($item[4]) . ", '" . floatval(preg_replace("/[^-0-9\.]/", ".", $item[6])) . "', '" . floatval(preg_replace("/[^-0-9\.]/", ".", $item[6])) . "', '" . date("Y-m-d H:i:s", strtotime($item[7])) . "', '" . $item[9] . "', 0),\n";
            }
            $cnt++;
            //echo $str;*/
            Yii::app()->db->createCommand()->insert("produce",array(
                    'phoneId' => $this->getPhone($item[3]),
                    'sn' => $item[3],
                    'printId' => $this->getPrintId($item[3]),
                    'box' => $item[2],
                    'colorId' => $this->getColor($item[4]),
                    'NW' => floatval(preg_replace("/[^-0-9\.]/",".",$item[5])),
                    'GW' => floatval(preg_replace("/[^-0-9\.]/",".",$item[6])),
                    'produceDate' => date("Y-m-d H:i:s",strtotime($item[7])),
                    'whom' => $item[9],
                    'placer' => 0,
                    'produce' => 0
                ));
        }
//        $current = file_get_contents($path);
//        $current .= $str;
//        file_put_contents($path, $current);
        $callEndTime = microtime(true);
        $callTime = $callEndTime - $callStartTime;
        echo 'С записью в базу  ' . sprintf('%.4f',$callTime) . " seconds";
    }
    
    public function getColor($name){
        $model = Yii::app()->db->CreateCommand()
            ->select()
            ->from("color")
            ->where("name like '".$name."'")
            ->queryRow();

        return $model["colorId"];
    }

    public function getPhone($sn){
        $temp = substr($sn,4,2);
        $model = Yii::app()->db->CreateCommand()
            ->select()
            ->from("phone")
            ->where("code like '".$temp."'")
            ->queryRow();

        return $model["phoneId"];
    }

    public function getPrintId($sn){
        $model = Yii::app()->db->CreateCommand()
            ->select()
            ->from("print")
            ->where("`SN` like '".$sn."'")
            ->queryRow();
        return (!empty($model)) ? $model["printId"] : 0;
    }


    public function actionSetPlan(){
        $model = Yii::app()->db->CreateCommand()
            ->select()
            ->from("phone")
            ->where("status != 1")
            ->queryAll();
        if(isset($_POST["plan"])){
            $temp = $_POST["plan"];;
            Yii::app()->db->createCommand()->insert("planning",array(
                "cnt" => $temp["cnt"],
                "phoneId" => $temp["model"],
                "planningDate" => date("Y-m-d",strtotime($temp["planDate"])),
                "planType" => "official"
            ));
            $this->redirect("setplan");
        }
        $this->render("setplan",array(
            'model' => $model
        ));
    }
    
    public function actiongetPlan(){
        $model = Yii::app()->db->CreateCommand()
            ->select()
            ->from("planning")
            ->where("phoneId = :id and planningDate = :pdate and planType = 'official'",array(":id"=>$_POST["model"],":pdate"=>$_POST["planDate"]))
            ->queryRow();
        echo json_encode($model);
    }

    public function actionReception(){
        $temp = date("Y-m-d", strtotime($_POST["from"]));
        $res = array();
        $emp = Yii::app()->db->CreateCommand()
            ->select()
            ->from("action a")
            ->join("employee e","e.employeeId = a.employeeId")
            ->where("DATE(a.actionTime) = :date and a.actionType = 'reception'",array(":date"=>$temp))
            ->group("a.employeeId")
            ->queryAll();
        foreach ($emp as $item) {

            $model = Yii::app()->db->CreateCommand()
                ->select()
                ->from("action a")
                ->where("DATE(a.actionTime) = :date and a.actionType = 'reception' and a.employeeId = :id",array(":date"=>$temp,":id"=>$item["employeeId"]))
                ->queryAll();

                $res[$item["surname"]." ".$item["name"]] = $model;

        }
        $this->renderPartial("/admin/ajax/reception",array(
            'res'=>$res
        ));
    }

    public function actionGetReception(){
        $this->render("/admin/getReception");
    }


    public function actionGetPage(){


        $this->render('getPage');
    }

    public function actionAjaxGetPage(){
        $lastPage = $_POST["lastPage"];
        $pageStr = $_POST["pages"];

        $temp = explode(',',$pageStr);
        $result = '';
        foreach ($temp as $key => $item) {
            $result .= $item+$lastPage;
            $result .= (count($temp) == $key+1) ? '' : ',';
        }

        echo $result;
    }
}