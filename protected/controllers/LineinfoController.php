<?

class LineinfoController extends Controller
{
    public function actionLinePos(){
//        $model = Yii::app()->db->CreateCommand()
//            ->select()
//            ->from("lineposition")
//            ->where("phoneId = :id",array(":id"=>$_POST["model"]))
//            ->queryAll();
        $this->renderPartial("lineBegin",array(
//            'model'=>$model,
            'id'=>$_POST["id"],
            'modelId'=>$_POST["model"]
        ));
    }

    public function actionSetPosition(){
        $model = Yii::app()->db->CreateCommand()
            ->select()
            ->from("lineposition")
            ->where("positionId = :id",array(":id"=>$_POST["posId"]))
            ->queryRow();
        $res = "";
        if($model["linePosition"] == ""){
            $res = $_POST["id"];
        }
        else{
            $res = $model["linePosition"].",".$_POST["id"];
        }
        Yii::app()->db->createCommand()->update("lineposition",array(
            'linePosition'=>$res
        ),"positionId = :id",array(":id"=>$_POST["posId"]));
    }

    public function actionAssemblyLineInfo(){

        $this->renderPartial("assemblyLineInfo",array(

        ));
    }

    public function actionGetEmpPos(){
        $produceDep = Yii::app()->db->CreateCommand()
            ->select()
            ->from("producedep")
            ->where("produceType = 'produce' AND departmentId = 4 AND date(produceDate) = '".date("Y-m-d")."'")
            ->order("producedepId desc")
            ->queryRow();
        $phone = Yii::app()->db->createCommand()
            ->select()
            ->from("phone")
            ->where("phoneId = ".$produceDep["phoneId"])
            ->queryRow();
        $linePos = Yii::app()->db->CreateCommand()
            ->select()
            ->from("lineposition")
            ->where("phoneId = :model ",array(":model"=>$produceDep["phoneId"]))
            ->queryAll();

        $model = Yii::app()->db->CreateCommand()
            ->select("e.photo, e.name as eName, e.surname, a.reason as linePosition")
            ->from("action a")
            ->join("employee e","e.employeeId = a.employeeId")
            ->join("linePosition l","l.linePosition like CONCAT('%', a.reason, '%') ")
            ->where("a.actionType='line' AND date(a.actionTime)= :d AND l.phoneId = :pId  AND a.phone = :pId",
                array(
                    ":pId"=>$produceDep["phoneId"],
                    ":d"=>date("Y-m-d"),
                )
            )
            ->order("actionId desc")
            ->queryAll();

        echo json_encode($res = array("model"=>$model,"linePos"=>$linePos,"phone"=>$phone));
    }
}