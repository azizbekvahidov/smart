<? 

class ReportController extends Controller
{
    public function actions()
    {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha'=>array(
                'class'=>'CCaptchaAction',
                'backColor'=>0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: sell.php?r=site/page&view=FileName
            'page'=>array(
                'class'=>'CViewAction',
            ),
        );
    }

    public function actionRepair(){
        $this->render("repair",array(
        ));

    }

    public function actionAjaxRepair(){
        $start = date("Y-m-d",strtotime($_POST["from"]));
        $plan = Yii::app()->db->createCommand()
            ->select()
            ->from("planproduce")
            ->where("produceDate = :thisDate",array(":thisDate" => $start))
            ->queryRow();
        $model = array();
        $phone = Yii::app()->db->createCommand()
            ->select("p.model, r.phoneId")
            ->from("register r")
            ->join("phone p","p.phoneId = r.phoneId")
            ->where("date(r.errorOtkDate) = :regDate",array(":regDate"=>$start))
            ->group("r.phoneId")
            ->queryAll();
        foreach ($phone as $value) {
            $model[$value["model"]] = Yii::app()->db->createCommand()
                ->select("r.registerId,r.status,r.errorOtkDate,r.errorRepairDate,r.spareId, r.solve, e.codeId, ce.name as ceName, r.phoneId, e.descUz as name,r.todo, u.login, r.registerCode, r.errorIdRepair")
                ->from("register r")
                ->join("error e","e.errorId = r.errorIdOtk")
                ->join("codeerror ce","ce.codeErrorId = e.codeId")
                ->join("users u","u.userId = r.userIdOtk")
                ->where("date(r.errorOtkDate) = :regDate AND r.phoneId = :id",array(":regDate"=>$start,":id"=>$value["phoneId"]))
                ->queryAll();
        }

        $this->renderPartial("ajax/ajaxRepair",array(
            "model"=>$model,
            'plans' => $plan
        ));
    }


    public function actionLabaratory(){
        $this->render("labaratory",array(
        ));
    }

    public function actionAjaxLabaratory(){
        $start = date("Y-m-d",strtotime($_POST["from"]));
        $end = date("Y-m-d",strtotime($_POST["end"]));
        $model = array();
        $model1 = array();
        $phone = Yii::app()->db->createCommand()
            ->select("p.model, r.phoneId")
            ->from("register r")
            ->join("phone p","p.phoneId = r.phoneId")
            ->where("date(r.errorOtkDate) >= :regDate AND date(r.errorOtkDate) <= :regDates AND r.solve != 'Vxodnoy'",array(":regDate"=>$start,":regDates"=>$end))
            ->group("r.phoneId")
            ->queryAll();
        $model2 = Yii::app()->db->createCommand()
            ->select("count(r.registerId) as cnt, e.descUz as name, u.login")
            ->from("register r")
            ->join("error e","e.errorId = r.errorIdOtk")
            ->join("users u","u.userId = r.userIdOtk")
            ->where("date(r.errorOtkDate) >= :regDate AND date(r.errorOtkDate) <= :regDates AND r.solve != 'Vxodnoy'",array(":regDate"=>$start,":regDates"=>$end))
            ->group("r.errorIdOtk")
            ->queryAll();
        foreach ($phone as $value) {
            $model[$value["model"]] = Yii::app()->db->createCommand()
                ->select("r.registerId,r.status,r.errorOtkDate,r.errorRepairDate,r.spareId, r.solve, e.codeId, ce.name as ceName, r.phoneId, e.descUz as name,r.todo, u.login")
                ->from("register r")
                ->join("error e","e.errorId = r.errorIdOtk")
                ->join("codeerror ce","ce.codeErrorId = e.codeId")
                ->join("users u","u.userId = r.userIdOtk")
                ->where("date(r.errorOtkDate) >= :regDate AND date(r.errorOtkDate) <= :regDates AND r.phoneId = :id AND r.solve != 'Vxodnoy'",array(":regDate"=>$start,":regDates"=>$end,":id"=>$value["phoneId"]))
                ->queryAll();

            $model1[$value["model"]] = Yii::app()->db->createCommand()
                ->select("count(r.registerId) as cnt, e.descUz as name, u.login")
                ->from("register r")
                ->join("error e","e.errorId = r.errorIdOtk")
                ->join("users u","u.userId = r.userIdOtk")
                ->where("date(r.errorOtkDate) >= :regDate AND date(r.errorOtkDate) <= :regDates AND r.phoneId = :id AND r.solve != 'Vxodnoy'",array(":regDate"=>$start,":regDates"=>$end,":id"=>$value["phoneId"]))
                ->group("r.errorIdOtk")
                ->queryAll();
        }

        $this->renderPartial("ajax/ajaxlabaratory",array(
            "model"=>$model,
            "model1"=>$model1,
            "model2"=>$model2,
        ));
    }

    public function actionSaveToDo(){
        Yii::app()->db->createCommand()->update("register",array(
            "todo" => $_POST["val"]
        ),"registerId = :id",array(":id"=>$_POST["id"]));
    }

    public function actionSaveSolve(){
        Yii::app()->db->createCommand()->update("register",array(
            "solve" => $_POST["val"]
        ),"registerId = :id",array(":id"=>$_POST["id"]));
    }

    public function actionAddProduce(){
        $start = date("Y-m-d",strtotime($_POST["thisDate"]));
        Yii::app()->db->createCommand()->insert("planproduce",array(
            'plan' => $_POST["plan"],
            'produce' => $_POST["produce"],
            'checked' => $_POST["checked"],
            'produceDate' => $start
        ));
    }

    public function actionShowRegistered(){
        $model = Yii::app()->db->createCommand()
            ->select("r.registerCode,r.status,r.errorOtkDate,r.cause,r.solve,p.model,u.login,e.descUz")
            ->from("register r")
            ->join("phone p","p.phoneId = r.phoneId")
            ->join("error e", "e.errorId = r.errorIdOtk")
            ->join("users u", "u.userId = r.userIdOtk")
            ->where("r.cause is null OR date(r.errorOtkDate) = :curDate",array(":curDate" => date("Y-m-d")))
            ->order("r.errorOtkDate DESC")
            ->queryAll();
        $this->renderPartial("showRegistered",array(
            "model" => $model
        ));
    }

    public function actionDeleteRegister(){

        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("register")
            ->where("registerId = :id",array(":id"=>$_POST["id"]))
            ->queryRow();
        $func = new Functions();

        $func->setLogs($_POST["id"],"delete",$func->ArrayToString($model),"register");
        Yii::app()->db->createCommand()->delete("register","registerId = :id",array(":id"=>$_POST["id"]));

    }

    public function actionSell()
    {
        $this->render("sell",array(

        ));
    }
    public function actionBack()
    {
        $this->render("back",array(

        ));
    }


    public function actionSellreport(){
        $model = Yii::app()->db->createCommand()
            ->select("")
            ->from("print p")
            ->join("phone po","po.phoneId = p.phoneId")
            ->where("p.sellDay BETWEEN :start AND :end AND sell = 1",array(":start"=>date("Y-m-d",strtotime($_POST["from"])),":end"=>date("Y-m-d",strtotime($_POST["to"]))))
            ->queryAll();
        $this->renderPartial("sellReport",array(
            "model"=>$model
        ));
    }

    public function actionBackreport(){
        $model = Yii::app()->db->createCommand()
            ->select("")
            ->from("print p")
            ->join("phone po","po.phoneId = p.phoneId")
            ->where("p.backDay BETWEEN :start AND :end AND sell = 0 ",array(":start"=>date("Y-m-d",strtotime($_POST["from"])),":end"=>date("Y-m-d",strtotime($_POST["to"]))))
            ->queryAll();
        $this->renderPartial("sellReport",array(
            "model"=>$model
        ));
    }

    public function actionViews(){
        $print = Yii::app()->db->createCommand()
            ->select()
            ->from("print")
            ->where("printId = :id",array(":id"=>$_GET["id"]))
            ->queryRow();

        $phones = Yii::app()->db->createCommand()
            ->select("p.model as phone,c.name as color")
            ->from("phone p")
            ->join("sn s","s.phoneId = p.phoneId")
            ->join("color c","c.colorId = s.colorId")
            ->where("p.phoneId = :id",array(":id"=>$print["phoneId"]))
            ->queryRow();

        $back = Yii::app()->db->createCommand()
            ->select()
            ->from("back b")
            ->where("b.printId = :id",array(":id"=>$_GET["id"]))
            ->queryAll();

        $sell = Yii::app()->db->createCommand()
            ->select()
            ->from("sell s")
            ->where("s.printId = :id",array(":id"=>$_GET["id"]))
            ->queryAll();
        $this->render("views",array(
            "print"=>$print,
            "phones"=>$phones,
            "back"=>$back,
            "sell"=>$sell
        ));
    }

    public function actionDillerComing(){
        $this->render("dillerComing");
    }

    public function actionAjaxComing(){
        $from = date("Y-m-d", strtotime($_POST["from"]));
        $to = date("Y-m-d", strtotime($_POST["to"]));

        $model = Yii::app()->db->createCommand()
            ->select("p.SN,DATE_FORMAT(FROM_UNIXTIME(`createAt`), '%d-%m-%Y') as createAt, p.IMEI1,p.IMEI2,ph.model,p.phoneId,c.name as color")
            ->from("dillercom dc")
            ->join("print p","p.printId = dc.printId")
            ->join("phone ph","ph.phoneId = p.phoneId")
            ->join("sn s","SUBSTRING(s.code,1,8) = SUBSTRING(p.SN,1,8)")
            ->join("color c","c.colorId = s.colorId")
            ->where("dc.userId = :id AND dc.createAt BETWEEN :start AND :end",array(":id"=>Yii::app()->user->getId(),":start"=>strtotime($from),":end"=>strtotime($to)))
            ->queryAll();
        $result["model"] = $model;
        $cnt = Yii::app()->db->createCommand()
            ->select("ph.model,count(p.phoneId) as cnt")
            ->from("dillercom dc")
            ->join("print p","p.printId = dc.printId")
            ->join("phone ph","ph.phoneId = p.phoneId")
            ->join("sn s","SUBSTRING(s.code,1,8) = SUBSTRING(p.SN,1,8)")
            ->join("color c","c.colorId = s.colorId")
            ->where("dc.userId = :id AND dc.createAt BETWEEN :start AND :end",array(":id"=>Yii::app()->user->getId(),":start"=>strtotime($from),":end"=>strtotime($to)))
            ->group("p.phoneId")
            ->queryAll();
        $result["cnt"] = $cnt;
        echo json_encode($result);
    }

    public function actionDillerOuting(){
        $list = Yii::app()->db->createCommand()
            ->select()
            ->from("users u")
            ->where("role = 1 AND parent  = :parent",array(":parent"=>Yii::app()->user->getId()))
            ->queryAll();
        $this->render("dillerOuting",array(
            'list'=>$list
        ));
    }

    public function actionAjaxOuting(){
        $from = date("Y-m-d", strtotime($_POST["from"]));
        $to = date("Y-m-d", strtotime($_POST["to"]));
        $model = array();
        $seller = $_POST["seller"];
        $result = array();

        if($seller != 0) {
            $model = Yii::app()->db->createCommand()
                ->select("SN,DATE_FORMAT(FROM_UNIXTIME(`createAt`), '%d-%m-%Y') as createAt,u.name, u.surname, u.lastname, u.point, p.IMEI1,p.IMEI2,ph.model,p.phoneId,c.name as color")
                ->from("dillerout do")
                ->join("print p","p.printId = do.printId")
                ->join("phone ph","ph.phoneId = p.phoneId")
                ->join("sn s","SUBSTRING(s.code,1,8) = SUBSTRING(p.SN,1,8)")
                ->join("color c","c.colorId = s.colorId")
                ->join("users u", "u.userId = do.touserId")
                ->where("do.userId = :id AND do.createAt BETWEEN :start AND :end AND do.touserId = :userId", array(":id" => Yii::app()->user->getId(), ":start" => strtotime($from), ":end" => strtotime($to),":userId"=>$seller))
                ->queryAll();
        }
        else
        {
            $model = Yii::app()->db->createCommand()
                ->select("SN,DATE_FORMAT(FROM_UNIXTIME(`createAt`), '%d-%m-%Y') as createAt,u.name, u.surname, u.lastname, u.point ,p.IMEI1,p.IMEI2,ph.model,p.phoneId,c.name as color")
                ->from("dillerout do")
                ->join("print p","p.printId = do.printId")
                ->join("phone ph","ph.phoneId = p.phoneId")
                ->join("sn s","SUBSTRING(s.code,1,8) = SUBSTRING(p.SN,1,8)")
                ->join("color c","c.colorId = s.colorId")
                ->join("users u", "u.userId = do.touserId")
                ->where("do.userId = :id AND do.createAt BETWEEN :start AND :end", array(":id" => Yii::app()->user->getId(), ":start" => strtotime($from), ":end" => strtotime($to)))
                ->queryAll();
        }

        foreach ($model as $index=>$value ) {
            $result[$index]["point"] = $this->GetPoint($value["point"]);
            $result[$index]["SN"] = $value["SN"];
            $result[$index]["createAt"] = $value["createAt"];
            $result[$index]["name"] = $value["name"];
            $result[$index]["surname"] = $value["surname"];
            $result[$index]["lastname"] = $value["lastname"];
            $result[$index]["IMEI1"] = $value["IMEI1"];
            $result[$index]["IMEI2"] = $value["IMEI2"];
            $result[$index]["model"] = $value["model"];
            $result[$index]["phoneId"] = $value["phoneId"];
            $result[$index]["color"] = $value["color"];

        }

        echo json_encode($result);
    }

    public function GetPoint($point){
        $temp = explode(',',$point);
        $result = "";
        if(!empty($temp[0])) {
            $city = Yii::app()->db->createCommand()
                ->select("name")
                ->from("point")
                ->where("pointId = :id", array(":id" => $temp[0]))
                ->queryRow();
            $result .= "Город -".$city["name"];
        }
        if(!empty($temp[1])) {
            $district = Yii::app()->db->createCommand()
                ->select("name")
                ->from("point")
                ->where("pointId = :id", array(":id" => $temp[1]))
                ->queryRow();
            $result .= ", Район " . $district["name"];
        }
        if(!empty($temp[2])) {
            $market = Yii::app()->db->createCommand()
                ->select("name")
                ->from("point")
                ->where("pointId = :id", array(":id" => $temp[2]))
                ->queryRow();
            $result .= ", Рынок -" . $market["name"];
        }
        if(!empty($temp[3])) {
            $shop = Yii::app()->db->createCommand()
                ->select("name")
                ->from("point")
                ->where("pointId = :id", array(":id" => $temp[3]))
                ->queryRow();
            $result .= ", Магазин -" . $shop["name"];
        }

        return $result;
    }

    public function actionSold(){
        $list = Yii::app()->db->createCommand()
            ->select()
            ->from("users u")
            ->where("role = 1 AND parent  = :parent",array(":parent"=>Yii::app()->user->getId()))
            ->queryAll();
        $this->render("soldPhone",array(
            'list'=>$list
        ));
    }

    public function actionAjaxSold(){
        $from = date("Y-m-d", strtotime($_POST["from"]));
        $to = date("Y-m-d", strtotime($_POST["to"]));
        $model = array();
        $seller = $_POST["seller"];
        if($seller != 0) {
            $result = array();
            $model = Yii::app()->db->createCommand()
                ->select("SN,DATE_FORMAT(FROM_UNIXTIME(`do`.`sellDate`), '%d-%m-%Y') as createAt,u.name, u.surname, u.lastname, u.point, p.IMEI1,p.IMEI2,ph.model,p.phoneId,c.name as color")
                ->from("sold do")
                ->join("print p","p.printId = do.printId")
                ->join("phone ph","ph.phoneId = p.phoneId")
                ->join("sn s","SUBSTRING(s.code,1,8) = SUBSTRING(p.SN,1,8)")
                ->join("color c","c.colorId = s.colorId")
                ->join("users u", "u.userId = do.userId")
                ->where("do.userId = :id AND do.sellDate BETWEEN :start AND :end AND u.parent = :parent", array(":id" => $seller, ":start" => strtotime($from), ":end" => strtotime($to),":parent"=>Yii::app()->user->getId()))
                ->queryAll();
        }
        else
        {
            $model = Yii::app()->db->createCommand()
                ->select("SN,DATE_FORMAT(FROM_UNIXTIME(`do`.`sellDate`), '%d-%m-%Y') as createAt,u.name, u.surname, u.lastname, u.point ,p.IMEI1,p.IMEI2,ph.model,p.phoneId,c.name as color")
                ->from("sold do")
                ->join("print p","p.printId = do.printId")
                ->join("phone ph","ph.phoneId = p.phoneId")
                ->join("sn s","SUBSTRING(s.code,1,8) = SUBSTRING(p.SN,1,8)")
                ->join("color c","c.colorId = s.colorId")
                ->join("users u", "u.userId = do.userId")
                ->where("do.sellDate BETWEEN UNIX_TIMESTAMP(:start) AND UNIX_TIMESTAMP(:end) AND u.parent = :parent", array( ":start" => $from, ":end" => $to,":parent"=>Yii::app()->user->getId()))
                ->queryAll();

        }

        foreach ($model as $index=>$value ) {
            $result[$index]["point"] = $this->GetPoint($value["point"]);
            $result[$index]["SN"] = $value["SN"];
            $result[$index]["createAt"] = $value["createAt"];
            $result[$index]["name"] = $value["name"];
            $result[$index]["surname"] = $value["surname"];
            $result[$index]["lastname"] = $value["lastname"];
            $result[$index]["IMEI1"] = $value["IMEI1"];
            $result[$index]["IMEI2"] = $value["IMEI2"];
            $result[$index]["model"] = $value["model"];
            $result[$index]["phoneId"] = $value["phoneId"];
            $result[$index]["color"] = $value["color"];

        }

        echo json_encode($result);
    }



    public function actionReportSold(){
        $list = Yii::app()->db->createCommand()
            ->select()
            ->from("users u")
            ->where("role = 1 AND parent  = :parent",array(":parent"=>Yii::app()->user->getId()))
            ->queryAll();
        $this->render("soldReportPhone",array(
            'list'=>$list
        ));
    }

    public function actionAjaxReportSold(){
        $from = date("Y-m-d", strtotime($_POST["from"]));
        $to = date("Y-m-d", strtotime($_POST["to"]));
        $model = array();
        $result = array();

        $model = Yii::app()->db->createCommand()
            ->select("SN,DATE_FORMAT(FROM_UNIXTIME(`do`.`sellDate`), '%d-%m-%Y') as createAt,u.name, u.surname, u.lastname, u.point ,p.IMEI1,p.IMEI2,ph.model,p.phoneId,c.name as color")
            ->from("sold do")
            ->join("print p","p.printId = do.printId")
            ->join("phone ph","ph.phoneId = p.phoneId")
            ->join("sn s","SUBSTRING(s.code,1,8) = SUBSTRING(p.SN,1,8)")
            ->join("color c","c.colorId = s.colorId")
            ->join("users u", "u.userId = do.userId")
            ->where("do.sellDate BETWEEN UNIX_TIMESTAMP(:start) AND UNIX_TIMESTAMP(:end) ", array( ":start" => date("Y-m-d",strtotime($from)), ":end" => date("Y-m-d",strtotime($to))))
            ->queryAll();
        //        Запрос связанный с расходом диллера
//        $model = Yii::app()->db->createCommand()
//            ->select("SN,DATE_FORMAT(FROM_UNIXTIME(`do`.`sellDate`), '%d-%m-%Y') as createAt,u.name, u.surname, u.lastname, u.point ,p.IMEI1,p.IMEI2,ph.model,p.phoneId,c.name as color")
//            ->from("sold do")
//            ->join("dillerOut dout","dout.printId = do.printId")
//            ->join("print p","p.printId = do.printId")
//            ->join("phone ph","ph.phoneId = p.phoneId")
//            ->join("sn s","SUBSTRING(s.code,1,8) = SUBSTRING(p.SN,1,8)")
//            ->join("color c","c.colorId = s.colorId")
//            ->join("users u", "u.userId = do.userId")
//            ->where("do.sellDate BETWEEN UNIX_TIMESTAMP(:start) AND UNIX_TIMESTAMP(:end)", array( ":start" => date("Y-m-d",strtotime($from)), ":end" => date("Y-m-d",strtotime($to))))
//            ->queryAll();

        foreach ($model as $index=>$value ) {
            $result[$index]["point"] = $this->GetPoint($value["point"]);
            $result[$index]["SN"] = $value["SN"];
            $result[$index]["createAt"] = $value["createAt"];
            $result[$index]["name"] = $value["name"];
            $result[$index]["surname"] = $value["surname"];
            $result[$index]["lastname"] = $value["lastname"];
            $result[$index]["IMEI1"] = $value["IMEI1"];
            $result[$index]["IMEI2"] = $value["IMEI2"];
            $result[$index]["model"] = $value["model"];
            $result[$index]["phoneId"] = $value["phoneId"];
            $result[$index]["color"] = $value["color"];

        }

        echo json_encode($result);
    }

    public function actionDillerBalance(){

        $model = Yii::app()->db->createCommand()
            ->select("ph.model as model,count(ph.phoneId) as cnt")
            ->from("dillercom dc")
            ->join("print p","p.printId = dc.printId")
            ->join("phone ph","ph.phoneId = p.phoneId")
            ->where("dc.userId = :id AND dc.status  = 0",array(":id"=>Yii::app()->user->getId()))
            ->group("ph.phoneId")
            ->queryAll();
        $this->render("dillerBalance",array(
            "model"=>$model
        ));
    }

    public function actionBrak(){

        $this->render("brak");
    }

    public function actionAjaxBrak(){
        $from = date("Y-m-d",strtotime($_POST["from"]));
        $to = date("Y-m-d",strtotime($_POST["to"]));
        $model = Yii::app()->db->createCommand()
            ->select("p.phoneId, p.model, r.cause, count(*) as cnt")
            ->from("register r")
            ->join("phone p","p.phoneId = r.phoneId")
            ->where("date(r.errorOtkDate) BETWEEN :start AND :to and r.spareId != 0",array(":start"=>$from,":to"=>$to))
            ->group("r.phoneId, r.cause")
            ->queryAll();
        echo json_encode($model);
    }

    public function actionAjaxBrakDetail(){
        $from = date("Y-m-d",strtotime($_POST["from"]));
        $to = date("Y-m-d",strtotime($_POST["to"]));
        $model = Yii::app()->db->createCommand()
            ->select("ce.name, count(r.registerId) as cnt")
            ->from("register r")
            ->join("error e","e.errorId = r.errorIdOtk")
            ->join("codeerror ce","e.codeId = ce.codeErrorId")
            ->where("date(r.errorOtkDate) BETWEEN :start AND :to and r.spareId != 0 AND r.cause = :cause AND r.phoneId = :id",array(":start"=>$from,":to"=>$to,":id"=>$_POST["id"],":cause"=>$_POST["cause"]))
            ->group("ce.codeErrorId")
            ->queryAll();
        echo json_encode($model);

    }

    public function actionRepairDetail(){
        $this->render("repairDetail");
    }

    public function actionAjaxRepairDetail(){
        $from = date("Y-m-d",strtotime($_POST["from"]));
        $to = date("Y-m-d",strtotime($_POST["to"]));
        $repair = Yii::app()->db->createCommand()
            ->select("u.login,count(*) as cnt")
            ->from("register r")
            ->join("users u","u.userId = r.userIdRepair")
            ->where("date(r.errorOtkDate) BETWEEN :start AND :end AND u.role = 7 and r.status != 0",array(":start"=>$from,":end"=>$to))
            ->group("r.userIdRepair")
            ->queryAll();
        $detail = Yii::app()->db->createCommand()
            ->select("p.model,count(*) cnt, r.cause,r.spareId,e.description errorname,r.solve,r.spareId")
            ->from("register r")
            ->join("phone p","p.phoneId = r.phoneId")
            ->join('error e','e.errorId = r.errorIdOtk')
            ->where("date(r.errorOtkDate) BETWEEN :start AND :end and r.status != 0 and solve != 'Vxodnoy'",array(":start"=>$from,":end"=>$to))
            ->group("r.phoneId,r.spareId,r.cause,r.solve")
            ->queryAll();
        $this->renderPartial("ajax/ajaxRepairDetail",array(
            "repair" => $repair,
            "detail" => $detail

        ));
    }

    //Reports for Umutbek

    public function actionDealer(){
        $this->render("dealer",array(

        ));
    }

    public function actionAjaxDealer(){
        $from = date("Y-m-d",strtotime($_POST["from"]));
        $to = date("Y-m-d",strtotime($_POST["to"]));
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("users")
            ->where("role = 2")
            ->queryAll();
        $phone = Yii::app()->db->createCommand()
            ->select()
            ->from("phone")
            ->where("status != 1 and kind = 2")
            ->queryAll();
        $this->renderPartial("ajax/ajaxDealer",array(
            'model' => $model,
            'from' => $from,
            'to' => $to,
            'phone' => $phone
        ));
    }

    public function actionRetailer(){
        $this->render("retailer",array(

        ));
    }

    public function actionFTQ(){
        $this->render("ftq");
    }

    public function actionAjaxRetailer(){
        $from = date("Y-m-d",strtotime($_POST["from"]));
        $to = date("Y-m-d",strtotime($_POST["to"]));
        $model = Yii::app()->db->createCommand()
            ->select('u.name,u.surname,u.lastname, us.name as dname, us.surname as dsurname, us.lastname as dlastname, u.userId,u.point')
            ->from("users u")
            ->join("users us","us.userId = u.parent")
            ->where("u.role = 1")
            ->queryAll();
        $phone = Yii::app()->db->createCommand()
            ->select()
            ->from("phone")
            ->where("status != 1")
            ->queryAll();
        $this->renderPartial("ajax/ajaxRetailer",array(
            'model' => $model,
            'from' => $from,
            'to' => $to,
            'phone' => $phone
        ));
    }

    public function actionProduct(){
        $this->render("product",array(

        ));
    }

    public function actionAjaxProduct(){
        $from = date("Y-m-d",strtotime($_POST["from"]));
        $to = date("Y-m-d",strtotime($_POST["to"]));
        $model = Yii::app()->db->createCommand()
            ->select('u.name,u.surname,u.lastname, us.name as dname, us.surname as dsurname, us.lastname as dlastname, u.userId,u.point')
            ->from("users u")
            ->join("users us","us.userId = u.parent")
            ->where("u.role = 1")
            ->queryAll();
        $phone = Yii::app()->db->createCommand()
            ->select()
            ->from("phone")
            ->where("status != 1 ")
            ->queryAll();
        $this->renderPartial("ajax/ajaxProduct",array(
            'model' => $model,
            'from' => $from,
            'to' => $to,
            'phone' => $phone
        ));
    }

    public function actionSmartMobileReport(){

        $this->render("smartMobileReport");
    }

    public function actionAjaxSmartMobileReport(){
        $from = date("Y-m-d",strtotime($_POST["from"]));
        $to = date("Y-m-d",strtotime($_POST["to"]));
        $user = Yii::app()->db->createCommand()
            ->select()
            ->from("users")
            ->where("role = 2")
            ->queryAll();
        $this->renderPartial("ajax/ajaxSmartMobileReport",array(
            'model' => $user,
            'from' => $from,
            'to' => $to,
        ));
    }

    public function actionProduced(){
        $this->render("admin/produced");
    }
    public function actionGetProduced(){
        $from = date("Y-m-d",strtotime($_POST["from"]));
        $till = date("Y-m-d",strtotime($_POST["till"]));
        $plan = Yii::app()->db->CreateCommand()
            ->select()
            ->from("planning")
            ->where("planningDate BETWEEN :from and :till",array(":from"=>$from,":till"=>$till))
            ->queryAll();
        $model = Yii::app()->db->CreateCommand()
            ->select("ph.model,p.phoneId,p.colorId,c.name,count(p.produceId) as cnt")
            ->from("produce p")
            ->join("phone ph", "ph.phoneId = p.phoneId")
            ->join("color c", "c.colorId = p.colorId")
            ->where("date(p.produceDate) BETWEEN :from and :till ",array(":from"=>$from,":till"=>$till))
            ->group("p.phoneId,p.colorId")
            ->queryAll();

        $models = Yii::app()->db->CreateCommand()
            ->select("ph.model,p.phoneId,count(p.produceId) as cnt")
            ->from("produce p")
            ->join("phone ph", "ph.phoneId = p.phoneId")
            ->where("date(p.produceDate) BETWEEN :from and :till ",array(":from"=>$from,":till"=>$till))
            ->group("p.phoneId")
            ->queryAll();
        $chart = array();
        foreach ($models as $key => $item) {
            $chart[$key]["name"] = $item["model"];
            $chart[$key]["y"] = intval($item["cnt"]);
        }

        $res["plan"]=$plan;
        $res["model"]=$model;
        $res["chart"]=$chart;
        $this->renderPartial("admin/ajax/getProduced",array(
            "res"=>$res
        ));

    }

    public function actionActBrak(){
        $this->render("admin/actBrak");
    }

    public function actiongetActBrak(){

        $from = date("Y-m-d",strtotime($_POST["from"]));
        $till = date("Y-m-d",strtotime($_POST["till"]));
        $cause = $_POST["cause"];
        $place = $_POST["place"];
        if($cause == "0"){
            if($place == '0') {
                $model = Yii::app()->db->CreateCommand()
                    ->select("p.model, s.name, sum(ad.cnt) as cnt")
                    ->from("actregister ac")
                    ->join("actdetail ad", "ad.actregisterId = ac.actregisterId")
                    ->join("phone p", "p.phoneId = ad.phoneId")
                    ->join("spare s", "s.spareId = ad.spareId")
                    ->where("date(ac.actDate) BETWEEN :from and :till ", array(":from" => $from, ":till" => $till))
                    ->group("ad.phoneId, ad.spareId")
                    ->queryAll();
                $ph = Yii::app()->db->CreateCommand()
                    ->select("p.model,sum(ad.cnt) as cnt")
                    ->from("actregister ac")
                    ->join("actdetail ad", "ad.actregisterId = ac.actregisterId")
                    ->join("phone p", "p.phoneId = ad.phoneId")
                    ->join("spare s", "s.spareId = ad.spareId")
                    ->where("date(ac.actDate) BETWEEN :from and :till ", array(":from" => $from, ":till" => $till))
                    ->group("ad.phoneId")
                    ->queryAll();
                $sp = Yii::app()->db->CreateCommand()
                    ->select("s.name, sum(ad.cnt) as cnt")
                    ->from("actregister ac")
                    ->join("actdetail ad", "ad.actregisterId = ac.actregisterId")
                    ->join("phone p", "p.phoneId = ad.phoneId")
                    ->join("spare s", "s.spareId = ad.spareId")
                    ->where("date(ac.actDate) BETWEEN :from and :till ", array(":from" => $from, ":till" => $till))
                    ->group("ad.spareId")
                    ->queryAll();
            }
            else if($place == '1'){
                $model = Yii::app()->db->CreateCommand()
                    ->select("p.model, s.name, sum(ad.cnt) as cnt")
                    ->from("actregister ac")
                    ->join("actdetail ad", "ad.actregisterId = ac.actregisterId")
                    ->join("phone p", "p.phoneId = ad.phoneId")
                    ->join("spare s", "s.spareId = ad.spareId")
                    ->where("date(ac.actDate) BETWEEN :from and :till and ac.departmentId != 11 ", array(":from" => $from, ":till" => $till))
                    ->group("ad.phoneId, ad.spareId")
                    ->queryAll();
                $ph = Yii::app()->db->CreateCommand()
                    ->select("p.model,sum(ad.cnt) as cnt")
                    ->from("actregister ac")
                    ->join("actdetail ad", "ad.actregisterId = ac.actregisterId")
                    ->join("phone p", "p.phoneId = ad.phoneId")
                    ->join("spare s", "s.spareId = ad.spareId")
                    ->where("date(ac.actDate) BETWEEN :from and :till  and ac.departmentId != 11 ", array(":from" => $from, ":till" => $till))
                    ->group("ad.phoneId")
                    ->queryAll();
                $sp = Yii::app()->db->CreateCommand()
                    ->select("s.name, sum(ad.cnt) as cnt")
                    ->from("actregister ac")
                    ->join("actdetail ad", "ad.actregisterId = ac.actregisterId")
                    ->join("phone p", "p.phoneId = ad.phoneId")
                    ->join("spare s", "s.spareId = ad.spareId")
                    ->where("date(ac.actDate) BETWEEN :from and :till  and ac.departmentId != 11 ", array(":from" => $from, ":till" => $till))
                    ->group("ad.spareId")
                    ->queryAll();
            }
            else{

                $model = Yii::app()->db->CreateCommand()
                    ->select("p.model, s.name, sum(ad.cnt) as cnt")
                    ->from("actregister ac")
                    ->join("actdetail ad", "ad.actregisterId = ac.actregisterId")
                    ->join("phone p", "p.phoneId = ad.phoneId")
                    ->join("spare s", "s.spareId = ad.spareId")
                    ->where("date(ac.actDate) BETWEEN :from and :till and ac.departmentId = 11 ", array(":from" => $from, ":till" => $till))
                    ->group("ad.phoneId, ad.spareId")
                    ->queryAll();
                $ph = Yii::app()->db->CreateCommand()
                    ->select("p.model,sum(ad.cnt) as cnt")
                    ->from("actregister ac")
                    ->join("actdetail ad", "ad.actregisterId = ac.actregisterId")
                    ->join("phone p", "p.phoneId = ad.phoneId")
                    ->join("spare s", "s.spareId = ad.spareId")
                    ->where("date(ac.actDate) BETWEEN :from and :till  and ac.departmentId = 11 ", array(":from" => $from, ":till" => $till))
                    ->group("ad.phoneId")
                    ->queryAll();
                $sp = Yii::app()->db->CreateCommand()
                    ->select("s.name, sum(ad.cnt) as cnt")
                    ->from("actregister ac")
                    ->join("actdetail ad", "ad.actregisterId = ac.actregisterId")
                    ->join("phone p", "p.phoneId = ad.phoneId")
                    ->join("spare s", "s.spareId = ad.spareId")
                    ->where("date(ac.actDate) BETWEEN :from and :till  and ac.departmentId = 11 ", array(":from" => $from, ":till" => $till))
                    ->group("ad.spareId")
                    ->queryAll();
            }
        }
        else {
            if($place == '0') {
                $model = Yii::app()->db->CreateCommand()
                    ->select("p.model, s.name, sum(ad.cnt) as cnt")
                    ->from("actregister ac")
                    ->join("actdetail ad", "ad.actregisterId = ac.actregisterId")
                    ->join("phone p", "p.phoneId = ad.phoneId")
                    ->join("spare s", "s.spareId = ad.spareId")
                    ->where("date(ac.actDate) BETWEEN :from and :till and ad.cause = :cause", array(":from" => $from, ":till" => $till, ":cause" => $cause))
                    ->group("ad.phoneId, ad.spareId")
                    ->queryAll();
                $ph = Yii::app()->db->CreateCommand()
                    ->select("p.model,sum(ad.cnt) as cnt")
                    ->from("actregister ac")
                    ->join("actdetail ad", "ad.actregisterId = ac.actregisterId")
                    ->join("phone p", "p.phoneId = ad.phoneId")
                    ->join("spare s", "s.spareId = ad.spareId")
                    ->where("date(ac.actDate) BETWEEN :from and :till and ad.cause = :cause ", array(":from" => $from, ":till" => $till, ":cause" => $cause))
                    ->group("ad.phoneId")
                    ->queryAll();
                $sp = Yii::app()->db->CreateCommand()
                    ->select("s.name, sum(ad.cnt) as cnt")
                    ->from("actregister ac")
                    ->join("actdetail ad", "ad.actregisterId = ac.actregisterId")
                    ->join("phone p", "p.phoneId = ad.phoneId")
                    ->join("spare s", "s.spareId = ad.spareId")
                    ->where("date(ac.actDate) BETWEEN :from and :till and ad.cause = :cause", array(":from" => $from, ":till" => $till, ":cause" => $cause))
                    ->group("ad.spareId")
                    ->queryAll();
            }
            else if($place == '1'){
                $model = Yii::app()->db->CreateCommand()
                    ->select("p.model, s.name, sum(ad.cnt) as cnt")
                    ->from("actregister ac")
                    ->join("actdetail ad", "ad.actregisterId = ac.actregisterId")
                    ->join("phone p", "p.phoneId = ad.phoneId")
                    ->join("spare s", "s.spareId = ad.spareId")
                    ->where("date(ac.actDate) BETWEEN :from and :till and ad.cause = :cause and ac.departmentId != 11", array(":from" => $from, ":till" => $till, ":cause" => $cause))
                    ->group("ad.phoneId, ad.spareId")
                    ->queryAll();
                $ph = Yii::app()->db->CreateCommand()
                    ->select("p.model,sum(ad.cnt) as cnt")
                    ->from("actregister ac")
                    ->join("actdetail ad", "ad.actregisterId = ac.actregisterId")
                    ->join("phone p", "p.phoneId = ad.phoneId")
                    ->join("spare s", "s.spareId = ad.spareId")
                    ->where("date(ac.actDate) BETWEEN :from and :till and ad.cause = :cause  and ac.departmentId != 11", array(":from" => $from, ":till" => $till, ":cause" => $cause))
                    ->group("ad.phoneId")
                    ->queryAll();
                $sp = Yii::app()->db->CreateCommand()
                    ->select("s.name, sum(ad.cnt) as cnt")
                    ->from("actregister ac")
                    ->join("actdetail ad", "ad.actregisterId = ac.actregisterId")
                    ->join("phone p", "p.phoneId = ad.phoneId")
                    ->join("spare s", "s.spareId = ad.spareId")
                    ->where("date(ac.actDate) BETWEEN :from and :till and ad.cause = :cause and ac.departmentId != 11", array(":from" => $from, ":till" => $till, ":cause" => $cause))
                    ->group("ad.spareId")
                    ->queryAll();
            }
            else{
                $model = Yii::app()->db->CreateCommand()
                    ->select("p.model, s.name, sum(ad.cnt) as cnt")
                    ->from("actregister ac")
                    ->join("actdetail ad", "ad.actregisterId = ac.actregisterId")
                    ->join("phone p", "p.phoneId = ad.phoneId")
                    ->join("spare s", "s.spareId = ad.spareId")
                    ->where("date(ac.actDate) BETWEEN :from and :till and ad.cause = :cause and ac.departmentId = 11", array(":from" => $from, ":till" => $till, ":cause" => $cause))
                    ->group("ad.phoneId, ad.spareId")
                    ->queryAll();
                $ph = Yii::app()->db->CreateCommand()
                    ->select("p.model,sum(ad.cnt) as cnt")
                    ->from("actregister ac")
                    ->join("actdetail ad", "ad.actregisterId = ac.actregisterId")
                    ->join("phone p", "p.phoneId = ad.phoneId")
                    ->join("spare s", "s.spareId = ad.spareId")
                    ->where("date(ac.actDate) BETWEEN :from and :till and ad.cause = :cause  and ac.departmentId = 11", array(":from" => $from, ":till" => $till, ":cause" => $cause))
                    ->group("ad.phoneId")
                    ->queryAll();
                $sp = Yii::app()->db->CreateCommand()
                    ->select("s.name, sum(ad.cnt) as cnt")
                    ->from("actregister ac")
                    ->join("actdetail ad", "ad.actregisterId = ac.actregisterId")
                    ->join("phone p", "p.phoneId = ad.phoneId")
                    ->join("spare s", "s.spareId = ad.spareId")
                    ->where("date(ac.actDate) BETWEEN :from and :till and ad.cause = :cause and ac.departmentId = 11", array(":from" => $from, ":till" => $till, ":cause" => $cause))
                    ->group("ad.spareId")
                    ->queryAll();
            }
        }
        $phone = array();
        $spare = array();
        foreach ($ph as $key => $item) {
            $phone[$key]["name"] = $item["model"];
            $phone[$key]["y"] =  intval($item["cnt"]);
        }

        foreach ($sp as $key => $item) {
            $spare[$key]["name"] = $item["name"];
            $spare[$key]["y"] =  intval($item["cnt"]);
        }

        $models = Yii::app()->db->CreateCommand()
            ->select("ph.model,p.phoneId,count(p.produceId) as cnt")
            ->from("produce p")
            ->join("phone ph", "ph.phoneId = p.phoneId")
            ->where("date(p.produceDate) BETWEEN :from and :till ",array(":from"=>$from,":till"=>$till))
            ->group("p.phoneId")
            ->queryAll();
        $chart = array();
        $chart["series"][0]["name"] = "Выпущенные телефоны";
        foreach ($models as $key => $item) {
            $chart["series"][0]["data"][$key] = intval($item["cnt"]);
            $chart["categories"][$key] = $item["model"];
        }
        $res["spare"] = $spare;
        $res["phone"] = $phone;
        $res["chart"] = $chart;
        $res["model"] = $model;
        $this->renderPartial("admin/ajax/getActBrak",array(
            "res" => $res
        ));
    }

    public function actionPhoneInfo(){
        $this->render("admin/phoneInfo");
    }

    public function actionGetPhoneInfo(){

        $model = Yii::app()->db->CreateCommand()
            ->select("ph.model, p.box,date(p.produceDate) as produceDate,p.NW,c.name,pr.SN,pr.IMEI1,pr.IMEI2")
            ->from("produce p")
            ->join("phone ph", "ph.phoneId = p.phoneId")
            ->join("color c", "c.colorId = p.colorId")
            ->join("print pr","p.printId = pr.printId")
            ->where("pr.SN like '%" . $_POST["data"] . "%' OR pr.IMEI1 like '%" . $_POST["data"] . "%' OR pr.IMEI2 like '%" . $_POST["data"] . "%'")
            ->order("pr.printId desc")
            ->queryRow();
        echo json_encode($model);
    }

    public function actionProducedByModel(){
        $phones = Yii::app()->db->CreateCommand()
            ->select()
            ->from("phone")
            ->where("status != 1")
            ->queryAll();
        $this->render("admin/producedByModel",array(
            "phones" => $phones
        ));
    }

    public function actionGetModelColor(){
        $model = Yii::app()->db->CreateCommand()
            ->select("s.colorId,c.name")
            ->from("sn s")
            ->join("color c","c.colorId = s.colorId")
            ->where("s.phoneId = :id",array(":id"=>$_POST["model"]))
            ->queryAll();
        echo json_encode($model);
    }

    public function actionGetProducedByModel(){
        $model = array();
        $from = date("Y-m-d",strtotime($_POST["from"]));
        $till = date("Y-m-d",strtotime($_POST["till"]));
        if($_POST["color"] == 0){
            $model = Yii::app()->db->CreateCommand()
                ->select("pr.SN,pr.IMEI1,pr.IMEI2")
                ->from("produce p")
                ->join("print pr", "pr.printId = p.printId")
                ->where("date(p.produceDate) BETWEEN :from and :till and p.phoneId = :pId ", array(":from" => $from, ":till" => $till, ":pId" => $_POST["model"]))
                ->queryAll();
        }
        else {
            $model = Yii::app()->db->CreateCommand()
                ->select("pr.SN,pr.IMEI1,pr.IMEI2")
                ->from("produce p")
                ->join("print pr", "pr.printId = p.printId")
                ->where("date(p.produceDate) BETWEEN :from and :till and p.colorId = :cId and p.phoneId = :pId ", array(":from" => $from, ":till" => $till, ":pId" => $_POST["model"], ":cId" => $_POST["color"]))
                ->queryAll();
        }
        echo json_encode($model);
    }

    public function actionPillReport(){
        $this->render("pillReport");
    }
    
    public function actionAjaxPillReport(){
        $model = array();
        $from = date("Y-m-d",strtotime($_POST["from"]));
        $till = date("Y-m-d",strtotime($_POST["till"]));
        $model = Yii::app()->db->createCommand()
            ->select('u.useDate, e.name as eName,e.surname as surname, e.lastname as lastname, u.cnt, i.name as iName')
            ->from("usepill u")
            ->join("employee e","e.employeeId = u.employeeId")
            ->join("ill i","u.illId = i.illId")
            ->where("date(u.useDate) BETWEEN :from  and :till and u.employeeId is not null",array(":from"=>$from,":till"=>$till))
            ->queryAll();
        $this->renderPartial("ajax/ajaxPillReport",array(
            'model' => $model
        ));
    }

    public function actionCurrentSN(){
        $model = Yii::app()->db->createCommand()
            ->select("p.model,s.SAPCode,s.lastNum,s.code, c.name")
            ->from("sn s")
            ->join("phone p","p.phoneId = s.phoneId")
            ->join("color c","c.colorId = s.colorId")
            ->where("s.lastNum != 1")
            ->queryAll();
        $this->render("currentSN",array(
            "model"=>$model
        ));
    }

}