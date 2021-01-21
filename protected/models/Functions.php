<?
use GuzzleHttp\Client;
class Functions {

    public function getPhoneColor($id,$sn){

        $query = Yii::app()->db->createCommand()
            ->select()
            ->from("sn s")
            ->join("color c", "s.colorId = c.colorId")
            ->where("s.code LIKE :code",array(":code"=>"%".substr($sn,0,8)."%"))
            ->queryRow();
        return $query["name"];
    }

    public function getfacError($regId){
        $model = Yii::app()->db->createCommand()
            ->select("count(r.registerid) as cnt")
            ->from("register r")
            ->where("r.registerId = :id and r.cause like '%Ishlab chiqarish brak%'",array(":id"=>$regId))
            ->group("r.registerId")
            ->queryRow();
        return ($model["cnt"] > 0) ? $model["cnt"] : 0;
    }
    public function getSkdError($regId){
        $model = Yii::app()->db->createCommand()
            ->select("count(r.registerid) as cnt")
            ->from("register r")
            ->where("r.registerId = :id and r.cause like '%SKD brak%'",array(":id"=>$regId))
            ->group("r.registerId")
            ->queryRow();
        return ($model["cnt"] > 0) ? $model["cnt"] : 0;
    }
    public function getErrors($dates,$status,$phoneId,$codeId,$spareId){
        $list = "";
        $model = Yii::app()->db->createCommand()
            ->select("count(r.solve) as cnt, r.solve")
            ->from("register r")
            ->join("error e","e.errorId = r.errorIdOtk")
            ->join("phone p","p.phoneId = r.phoneId")
            ->join("codeerror ce","ce.codeErrorId = e.codeId")
            ->where("date(r.errorOtkDate) = :regDate and r.status = :status and p.phoneId = :phoneId and e.codeId = :codeId and r.spareId = :spareId ",array(":regDate"=>$dates,":status"=>$status,":phoneId"=>$phoneId,"codeId"=>$codeId,":spareId"=>$spareId))
            ->group("r.solve")
            ->queryAll();
        foreach ($model as $item) {
            $list .= $item["solve"]."(".$item["cnt"].")";
        }
        return $list;
    }

    public function getSpare($id){
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("spare")
            ->where("spareId = :id",array(":id"=>$id))
            ->queryRow();


        return $model["name"];
    }

    public function setLogs($id,$action,$desc,$table){
        Yii::app()->db->createCommand()->insert("logs",array(
            "logDate" => date("Y-m-d H:i:s"),
            "tableId" => $id,
            "actions" => $action,
            "desc" => $desc,
            "table" => $table,
            "userId" => Yii::app()->user->getId()
        ));
    }

    public function ArrayToString($model){
        $result = "";
        $cnt = 1;
        foreach ($model as $key => $val){
            if($cnt != count($model)){
                $result .= $key . "=>" .$val . ",";
            }
            else{
                $result .= $key . "=>" .$val;
            }
            $cnt++;
        }
        return $result;
    }


    public function getPlace($id){
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("point")
            ->where("parent = :id",array(":id"=>$id))
            ->queryAll();
        return $model;
    }

    public function getPoint($point){
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

    public function getDillerOutPhones($id, $from, $till){
        $model = Yii::app()->db->createCommand()
            ->select("ph.model, count(p.phoneId) as cnt")
            ->from("dillerout do")
            ->join("print p", "p.printId = do.printId")
            ->join("phone ph", "p.phoneId = ph.phoneId")
            ->where("do.createAt BETWEEN :start AND :end and do.userId = :id ",array(":id"=>$id,":start"=>strtotime($from),":end"=>strtotime($till)))
            ->group("p.phoneId")
            ->queryAll();
        $res["data"] = $model;
        $sum = 0;
        foreach ($model as $item) {
            $sum = $sum + $item["cnt"];
        }
        $res["sum"] = $sum;
        return $res;
    }


    public function getDillerSellPhones($id, $from, $till){
        $model = Yii::app()->db->createCommand()
            ->select("ph.model, count(p.phoneId) as cnt")
            ->from("sold do")
            ->join("print p", "p.printId = do.printId")
            ->join("phone ph", "p.phoneId = ph.phoneId")
            ->join("users u","u.userId = do.userId")
            ->where("do.sellDate BETWEEN :start AND :end and u.parent = :parentId",array(":parentId"=>$id,":start"=>strtotime($from),":end"=>strtotime($till)))
            ->group("p.phoneId")
            ->queryAll();
        $res["data"] = $model;
        $sum = 0;
        foreach ($model as $item) {
            $sum = $sum + $item["cnt"];
        }
        $res["sum"] = $sum;
        return $res;
    }

    public function setBall($userId,$phoneId){
        try {
            $model=Yii::app()->db->createCommand()
                ->select()
                ->from("ball")
                ->where("userId = :id", array(":id"=>$userId))
                ->queryRow();
            $phone=Yii::app()->db->createCommand()
                ->select()
                ->from("phone")
                ->where("phoneId = :id", array(":id"=>$phoneId))
                ->queryRow();
            $ball=0;
//        foreach ($phone as $item) {
//            $cnt = Yii::app()->db->createCommand()
//                ->select("count(*) as cnt")
//                ->from("sold s")
//                ->join("print p","p.printId = s.printId")
//                ->where("s.sellDate BETWEEN :start AND :end AND p.phoneId = :phoneId and s.userId = :id",array(":id"=>$userId,":phoneId"=>$item["phoneId"],":start"=>strtotime("30-06-2018 23:59:59"),":end"=>strtotime("15-07-2018 23:59:59")))
//                ->queryRow();
//            $ball = $ball + $cnt["cnt"]*$item["ball"];
//        }

            $ball=$phone["ball"];
            if ($ball != 0) {
                if (!empty($model)) {
                    Yii::app()->db->createCommand()->update("ball", array(
                        "ball"=>$model["ball"] + $ball
                    ), "userId = :id", array(":id"=>$userId));
                } else {
                    Yii::app()->db->createCommand()->insert("ball", array(
                        "ball"=>$ball,
                        "userId"=>$userId
                    ));
                }
            }
        }
        catch (Exception $e){
            $this->setLog($phoneId, "ball", "", $userId);
        }
    }
    public function delBall($printId){
        $print = Yii::app()->db->createCommand()
            ->select()
            ->from("print p")
            ->join("sold s","s.printId = p.printId")
            ->where("p.printId = :id",array(":id"=>$printId))
            ->queryRow();
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("ball")
            ->where("userId = :id",array(":id"=>$print["userId"]))
            ->queryRow();
        $phone = Yii::app()->db->createCommand()
            ->select()
            ->from("phone")
            ->where("phoneId = :id",array(":id"=>$print["phoneId"]))
            ->queryRow();
        $ball = 0;
//        foreach ($phone as $item) {
//            $cnt = Yii::app()->db->createCommand()
//                ->select("count(*) as cnt")
//                ->from("sold s")
//                ->join("print p","p.printId = s.printId")
//                ->where("s.sellDate BETWEEN :start AND :end AND p.phoneId = :phoneId and s.userId = :id",array(":id"=>$userId,":phoneId"=>$item["phoneId"],":start"=>strtotime("30-06-2018 23:59:59"),":end"=>strtotime("15-07-2018 23:59:59")))
//                ->queryRow();
//            $ball = $ball + $cnt["cnt"]*$item["ball"];
//        }
        $ball = $phone["ball"];
        if($ball != 0) {
            Yii::app()->db->createCommand()->update("ball",array(
                "ball" => $model["ball"] - $ball
            ),"userId = :id",array(":id"=>$print["userId"]));
        }
    }

    public function getBall($id, $sCont, $plan,$userId){
        $user = Yii::app()->db->createCommand()
            ->select()
            ->from("users")
            ->where("userId = :id",array(":id"=>$userId))
            ->queryRow();
        $ball = 0;
        //if($plan != "нет") {
            if($user["uType"] == 1){
                $model=Yii::app()->db->createCommand()
                    ->select()
                    ->from("phone")
                    ->where("phoneId = :id", array(":id"=>$id))
                    ->queryRow();
                /*$percent=($sCont * 100) / $plan;
                if ($percent < 50) {
                    $ball=0;
                }
                if ($percent >= 50&&$percent < 100) {
                    $ball=($model["ball"] * $sCont) / 2;
                }
                if ($percent == 100) {
                    $ball=$model["ball"] * $sCont;
                }*/
                $ball=$model["ball"] * $sCont;
            }
            if($user["uType"] == 0){
                $model=Yii::app()->db->createCommand()
                    ->select()
                    ->from("phone")
                    ->where("phoneId = :id", array(":id"=>$id))
                    ->queryRow();

                $ball=$model["ball"] * $sCont;
            }
        /*}
        else{
            $ball = "нет";
        }*/

        return $ball;
    }

    public function getPlan($userId,$id,$month){
        $res = 0;
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("plan")
            ->where("phoneId = :id AND month LIKE '%".$month."%' AND userId = :userId",array(":id"=>$id,":userId"=>$userId))
            ->queryRow();
        (!empty($model)) ? $res = $model["plan"] : $res = "нет";
        return $res;
    }


    public function getSold($userId,$phoneId,$month){
        $model = Yii::app()->db->createCommand()
            ->select("count(p.phoneId) as cnt")
            ->from("sold s")
            ->join("print p","p.printId = s.printId")
            ->where("p.phoneId = :id AND s.userId = :userId AND FROM_UNIXTIME(s.sellDate,'%m.%Y') LIKE '%".$month."%'",array(":id"=>$phoneId,":userId"=>$userId))
            ->queryRow();
        return $model["cnt"];
    }

    public function getBalanceUser($userId,$id,$month){
        $res = 0;
        $temp = explode('.',$month);
        $tempDate = strtotime(date('Y-m-t',strtotime($temp[1]."-".$temp[0]."-01 ")));
        $model = Yii::app()->db->createCommand()
            ->select("count(p.phoneId) as cnt")
            ->from("dillerout do")
            ->join("print p","p.printId = do.printId")
            ->where("do.touserId = :id And do.status = 0 AND p.phoneId = :phoneId AND do.createAt < :month",array(":id"=>$userId,":phoneId"=>$id,":month"=>$tempDate))
            ->queryRow();
        (!empty($model)) ? $res = $model["cnt"] : $res = 0;
        return $res;
    }


    public function setLog($printId, $actions, $desc, $userId){
//        Yii::app()->db->createCommand()->insert("logs",array(
//            "logDate" => date("Y-m-d H:i:s"),
//            "tableId" => $printId,
//            "actions" => $actions,
//            "desc" => "",
//            "table" => "",
//            "userId" => $userId
//        ));
        Yii::app()->db->createCommand()->insert("soldlogs", array(
            'logDate' => date("Y-m-d H:i:s"),
            'printId' =>$printId,
            'actions' => $actions,
            'desc' => $desc,
            'userId' => $userId,
        ));
    }



    public function getRepairError($id){
        $model = Yii::app()->db->createCommand()
            ->select("e.descUz, ce.name")
            ->from("error e")
            ->join("codeerror ce","ce.codeErrorId = e.codeId")
            ->where("e.errorId = :id",array(":id"=>$id))
            ->queryRow();
        return $model;
    }

    public function getRepairPhoto($id){
        $model = Yii::app()->db->createCommand()
            ->select("")
            ->from("registerPhoto rp")
            ->join("photo p","p.photoId = rp.photoId")
            ->where("rp.registerId = :id",array(":id"=>$id))
            ->queryAll();
        return $model;
    }

    public function getSKDCnt($spareId, $phoneId, $regId){
        if(is_null($phoneId) || $phoneId == 0 || $phoneId == ""){
            $model = Yii::app()->db->createCommand()
                ->select("sum(cnt) as cnt")
                ->from("actDetail")
                ->where("(actregisterId = :id and cause = 'SKD brak' and spareId = :spareId)",array(":id"=>$regId,":spareId"=>$spareId))
                ->queryRow();
        }
        else{
            $model = Yii::app()->db->createCommand()
                ->select("sum(cnt) as cnt")
                ->from("actDetail")
                ->where("(actregisterId = :id and phoneId = :phoneId and cause = 'SKD brak' and spareId = :spareId)",array(":id"=>$regId,":phoneId"=>$phoneId,":spareId"=>$spareId))
                ->queryRow();

        }

        return $model["cnt"];
    }

    public function getProduceCnt($spareId, $phoneId, $regId){
        $model = array();
        if(is_null($phoneId) || $phoneId == 0 || $phoneId == "" || $phoneId == NULL){
            $model = Yii::app()->db->createCommand()
                ->select("sum(cnt) as cnt")
                ->from("actDetail")
                ->where("actregisterId = :id and cause = 'Ishlab chiqarish brak' and spareId = :spareId",array(":id"=>$regId,":spareId"=>$spareId))
                ->queryRow();
        }
        else{
            $model = Yii::app()->db->createCommand()
                ->select("sum(cnt) as cnt")
                ->from("actDetail")
                ->where("(actregisterId = :id and phoneId = :phoneId and cause = 'Ishlab chiqarish brak' and spareId = :spareId)",array(":id"=>$regId,":phoneId"=>$phoneId,":spareId"=>$spareId))
                ->queryRow();
        }

        return $model["cnt"];
        
    }
    
    public function getActDesc($spareId, $phoneId, $regId){
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("actDetail")
            ->where("actregisterId = :id and phoneId = :phoneId and spareId = :spareId",array(":id"=>$regId,":phoneId"=>$phoneId,":spareId"=>$spareId))
            ->queryAll();
        $res = "";
        foreach ($model as $key => $val) {
            if(count($model) == $key+1){
                $res .= "<span class='desc' onclick='change(".$val["actdetailId"].",".$val["cnt"].")'>".$val["desc"]."(".$val["cnt"].")</span>";
            }
            else{
                $res .= "<span class='desc' onclick='change(".$val["actdetailId"].",".$val["cnt"].")'>".$val["desc"]."(".$val["cnt"].")</span>, ";
            }
        }
        return $res;
    }

    public function getActDescToExcel($spareId, $phoneId, $regId){
        if(is_null($phoneId) || $phoneId == 0){
            $model = Yii::app()->db->createCommand()
                ->select()
                ->from("actDetail")
                ->where("(actregisterId = :id and spareId = :spareId)",array(":id"=>$regId,":spareId"=>$spareId))
                ->queryAll();
        }
        else{
            $model = Yii::app()->db->createCommand()
                ->select()
                ->from("actDetail")
                ->where("(actregisterId = :id and phoneId = :phoneId and spareId = :spareId)",array(":id"=>$regId,":phoneId"=>$phoneId,":spareId"=>$spareId))
                ->queryAll();
        }
        $res = "";
        foreach ($model as $key => $val) {
            if(count($model) == $key+1){
                $res .= $val["desc"]."(".$val["cnt"].")";
            }
            else{
                $res .= $val["desc"]."(".$val["cnt"]."), ";
            }
        }
        return $res;
    }
    
    public function getReportTemplate($depId,$type){
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("reporttemplate")
            ->where("departmentId = :id AND name = :name",array(":id"=>$depId,":name"=>$type))
            ->queryRow();
        return $model["template"];
    }

    public function insertRegisterPhoto($id,$regId){

        Yii::app()->db->createCommand()->insert("registerphoto",array(
            'registerId' => $regId,
            'photoId' => $id,
        ));

    }

    public function getCity($text){
        $text = explode(',',$text);
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("point")
            ->where("pointId = :id AND  place = 'city'",array(":id" => $text[0]))
            ->queryRow();
        return $model["name"];
    }

    public function getRegion($text){
        $text = explode(',',$text);
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("point")
            ->where("pointId = :id AND  place = 'district'",array(":id" => $text[1]))
            ->queryRow();
        return $model["name"];
    }

    // functions for Umutbek
    //diller reportFunction start
    public function getDealerBalance($to, $phoneId, $id = 0){
        $model = array();
        if($id == 0){
            $model = Yii::app()->db->createCommand()
                ->select("count(*) as cnt")
                ->from("dillercom dc")
                ->join("print p","p.printId = dc.printId")
                ->where("dc.createAt <= :date AND dc.status  = 0 AND p.phoneId = :phoneId",array(":date"=> strtotime($to),":phoneId"=>$phoneId))
                ->queryRow();
        }
        else{
            $model = Yii::app()->db->createCommand()
                ->select("count(*) as cnt")
                ->from("dillercom dc")
                ->join("print p","p.printId = dc.printId")
                ->where("dc.createAt <= :date AND dc.status  = 0 AND dc.userId  = :id AND p.phoneId = :phoneId",array(":date"=> strtotime($to),":id"=>$id,":phoneId"=>$phoneId))
                ->queryRow();
        }
        return $model["cnt"];
    }

    public function getDealerIn($from, $to, $phoneId, $id){
        $model = Yii::app()->db->createCommand()
            ->select("count(*) as cnt")
            ->from("dillercom dc")
            ->join("print p","p.printId = dc.printId")
            ->where("dc.createAt <= :to AND dc.createAt >= :from AND dc.userId  = :id AND p.phoneId = :phoneId",array(":from"=>strtotime($from),":to"=> strtotime($to),":id"=>$id,":phoneId"=>$phoneId))
            ->queryRow();
        return $model["cnt"];
    }

    public function getDealerOut($from, $to, $phoneId, $id){
        $model = Yii::app()->db->createCommand()
            ->select("count(*) as cnt")
            ->from("dillerout do")
            ->join("print p","p.printId = do.printId")
            ->where("do.createAt <= :to AND do.createAt >= :from AND do.userId  = :id AND p.phoneId = :phoneId",array(":from"=>strtotime($from),":to"=> strtotime($to),":id"=>$id,":phoneId"=>$phoneId))
            ->queryRow();

        return $model["cnt"];
    }
    //diller reportFunction end

    //retailer reportFunction start




    public function getRetailerIn($from, $to, $phoneId, $id){

        $model = Yii::app()->db->createCommand()
            ->select("count(*) as cnt")
            ->from("dillerout do")
            ->join("print p","p.printId = do.printId")
            ->where("do.createAt <= :to AND do.createAt >= :from AND do.touserId  = :id AND p.phoneId = :phoneId",array(":from"=>strtotime($from),":to"=> strtotime($to),":id"=>$id,":phoneId"=>$phoneId))
            ->queryRow();
        return $model["cnt"];
    }

    public function getRetailerBalance($to, $phoneId, $id = 0){
        $model = array();
        if($id == 0){
            $model = Yii::app()->db->createCommand()
                ->select("count(*) as cnt")
                ->from("dillerout do")
                ->join("print p","p.printId = do.printId")
                ->where("do.createAt <= :to AND do.status = 0 AND p.phoneId = :phoneId",array(":to"=> strtotime($to),":phoneId"=>$phoneId))
                ->queryRow();
        }
        else{
            $model = Yii::app()->db->createCommand()
                ->select("count(*) as cnt")
                ->from("dillerout do")
                ->join("print p","p.printId = do.printId")
                ->where("do.createAt <= :to AND do.touserId  = :id AND do.status = 0 AND p.phoneId = :phoneId",array(":to"=> strtotime($to),":id"=>$id,":phoneId"=>$phoneId))
                ->queryRow();
        }
        return $model["cnt"];
    }

    public function getRetailerSold($from, $to, $phoneId, $id = 0){
        $model = array();
        if($id == 0){
            $model=Yii::app()->db->createCommand()
                ->select("count(*) as cnt")
                ->from("sold s")
                ->join("print p", "p.printId = s.printId")
                ->where("s.sellDate <= :to AND s.sellDate >= :from AND p.phoneId = :phoneId", array(":from"=>strtotime($from), ":to"=>strtotime($to), ":phoneId"=>$phoneId))
                ->queryRow();
        }
        else {
            $model=Yii::app()->db->createCommand()
                ->select("count(*) as cnt")
                ->from("sold s")
                ->join("print p", "p.printId = s.printId")
                ->where("s.sellDate <= :to AND s.sellDate >= :from AND s.userId  = :id AND p.phoneId = :phoneId", array(":from"=>strtotime($from), ":to"=>strtotime($to), ":id"=>$id, ":phoneId"=>$phoneId))
                ->queryRow();
        }
        return $model["cnt"];
    }

    //retailer reportFunction end

    public function GetContentType(){
        $role = Yii::app()->user->getRole();
        $newsType = "produce";
        if($role == "selAdmin"){
            $newsType = "sold";
        }

        return $newsType;
    }

    public function getUserProd($userId){
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("shopdetail shd")
            ->join("shopproduct sh","sh.shopproductId = shd.shopproductId")
            ->where("shd.userId = :id and shd.status = 1",array(":id"=>$userId))
            ->queryAll();
        return $model;
    }

    public function addBalance($phoneId,$depId,$colorId,$cnt){

//        Yii::app()->db->createCommand()->
    }

    public function getBalance($phone,$date){
        $model = Yii::app()->db->CreateCommand()
            ->select("d.name as dName, c.name as cName, b.cnt")
            ->from("manufacBalance b")
            ->join("color c","c.colorId = b.colorId")
            ->join("department d","d.departmentId = b.departmentId")
            ->where("b.balanceDate = '".$date."' and b.phoneId = ".$phone." and cnt != 0")
            ->queryAll();
        return $model;
    }
    
    public function getEmpName($id){
        $model = Yii::app()->db->CreateCommand()
            ->select()
            ->from("employee")
            ->where("employeeId = :id",array(":id"=>$id))
            ->queryRow();
        return $model["surname"]." ".$model["name"];
    }
    
    public function getPhone($t){
        $model = array();
        if($t != "") {
            $model = Yii::app()->db->CreateCommand()
                ->select("p.SN,p.IMEI1,p.IMEI2,ph.model")
                ->from("print p")
                ->join("phone ph","ph.phoneId = p.phoneId")
                ->where("SN like '%" . $t . "%' or IMEI1 like '%" . $t . "%' or IMEI2 like '%" . $t . "%'")
                ->queryRow();
        }
        return $model;
    }

    function translitRU($value)
    {
        $converter = array(
            'а' => 'a',    'б' => 'b',    'в' => 'v',    'г' => 'g',    'д' => 'd',
            'е' => 'e',    'ё' => 'e',    'ж' => 'zh',   'з' => 'z',    'и' => 'i',
            'й' => 'y',    'к' => 'k',    'л' => 'l',    'м' => 'm',    'н' => 'n',
            'о' => 'o',    'п' => 'p',    'р' => 'r',    'с' => 's',    'т' => 't',
            'у' => 'u',    'ф' => 'f',    'х' => 'h',    'ц' => 'c',    'ч' => 'ch',
            'ш' => 'sh',   'щ' => 'sch',  'ь' => '',     'ы' => 'y',    'ъ' => '',
            'э' => 'e',    'ю' => 'yu',   'я' => 'ya',

            'А' => 'A',    'Б' => 'B',    'В' => 'V',    'Г' => 'G',    'Д' => 'D',
            'Е' => 'E',    'Ё' => 'E',    'Ж' => 'Zh',   'З' => 'Z',    'И' => 'I',
            'Й' => 'Y',    'К' => 'K',    'Л' => 'L',    'М' => 'M',    'Н' => 'N',
            'О' => 'O',    'П' => 'P',    'Р' => 'R',    'С' => 'S',    'Т' => 'T',
            'У' => 'U',    'Ф' => 'F',    'Х' => 'H',    'Ц' => 'C',    'Ч' => 'Ch',
            'Ш' => 'Sh',   'Щ' => 'Sch',  'Ь' => '',     'Ы' => 'Y',    'Ъ' => '',
            'Э' => 'E',    'Ю' => 'Yu',   'Я' => 'Ya',
        );

        $value = strtr($value, $converter);
        return $value;
    }

    function translitEN($value)
    {
        $converter = array(
            'a' => 'а',    'b' => 'б',    'v' => 'в',    'g' => 'г',    'd' => 'д',
            'e' => 'е',    'e' => 'ё',    'zh' => 'ж',   'z' => 'з',    'i' => 'и',
            'y' => 'й',    'k' => 'к',    'l' => 'л',    'm' => 'м',    'n' => 'н',
            'o' => 'о',    'p' => 'п',    'r' => 'р',    's' => 'с',    't' => 'т',
            'u' => 'u',    'f' => 'ф',    'h' => 'х',    'c' => 'ц',    'ch' => 'ч',
            'sh' => 'ш',   'sch' => 'щ',    '' => 'ь',     'y' => 'ы',    '' => 'ъ',
            'e' => 'э',    'yu' => 'ю',    'ya' => 'я',

            'A' => 'А',    'B' => 'Б',    'V' => 'В',    'G' => 'Г',    'D' => 'Д',
            'E' => 'Е',    'E' => 'Ё',    'Zh' => 'Ж',   'Z' => 'З',    'I' => 'И',
            'Y' => 'Й',    'K' => 'К',    'L' => 'Л',    'M' => 'М',    'N' => 'Н',
            'O' => 'О',    'P' => 'П',    'R' => 'Р',    'S' => 'С',    'T' => 'Т',
            'U' => 'У',    'F' => 'Ф',    'H' => 'Х',    'C' => 'Ц',    'Ch' => 'Ч',
            'Sh' => 'Ш',   'Sch' => 'Щ',  '' => 'Ь',     'Y' => 'Ы',    '' => 'Ъ',
            'E' => 'Э',    'Yu' => 'Ю',   'Ya' => 'Я',
        );

        $value = strtr($value, $converter);
        return $value;
    }

    public function getPositionError($id){
        $res = "";
        $model = Yii::app()->db->CreateCommand()
            ->select()
            ->from("lineposconn li")
            ->join("error e","e.errorId = li.errorId")
            ->where("li.positionId = :id",array(":id"=>$id))
            ->queryAll();
        foreach ($model as $item) {
            $res .= $item["description"].",\n";
        }

        return $res;
    }
}
