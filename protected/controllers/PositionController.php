<?php

class PositionController extends Controller
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
    public function actionPositionAdmin()
    {
        $model = Yii::app()->db->CreateCommand()
            ->select()
            ->from("lineposition l")
            ->join("phone p","p.phoneId = l.phoneId")
            ->queryAll();
        $this->render("admin",array(
            "model"=>$model
        ));
    }

    public function actionPositionCreate(){
        $model = Yii::app()->db->CreateCommand()
            ->select()
            ->from("phone")
            ->where("status = 0")
            ->queryAll();
        if(isset($_POST["pos"])){
            $post = $_POST["pos"];
//            $res = true;
            $res = Yii::app()->db->createCommand()->insert("linePosition",array(
                "name"=>$post["name"],
                "phoneId"=>$post["phoneId"],
                "people" => $post["people"]
            ));
            $id = Yii::app()->db->getLastInsertID();
            if($res)
                $this->redirect("positionUpdate?id=".$id);
        }$department = Yii::app()->db->CreateCommand()
            ->select()
            ->from("department")
            ->where("brigadir is not null")
            ->queryAll();
        $this->render("create",array(
            "model" => $model,
            'department' => $department
        ));
    }

    public function actionGetPosition(){
        $model = Yii::app()->db->CreateCommand()
            ->select("li.positionId,li.name as lName,li.people, li.positionId, d.name as dName, li.linePosition")
            ->from("lineposition li")
            ->join('department d','d.departmentId = li.departmentId')
            ->where("li.phoneId = :id",array(":id"=>$_POST["id"]))
            ->queryAll();
        $this->renderPartial('getPosition',array(
            'model'=>$model
        ));
    }


    public function actionDelPosition(){
        $model = Yii::app()->db->CreateCommand()
            ->delete('lineposition',"positionId = :id",array(":id"=>$_POST["id"]));
    }
    
    public function actionEditPosition(){
        $model = Yii::app()->db->CreateCommand()
            ->select()
            ->from("lineposition")
            ->where("positionId = :id",array(":id"=>$_POST["id"]))
            ->queryRow();
        $error = Yii::app()->db->CreateCommand()
            ->select()
            ->from("error")
            ->queryAll();
        $struct = Yii::app()->db->CreateCommand()
            ->select()
            ->from("lineposconn l")
            ->join("error e","e.errorId = l.errorId")
            ->where("l.positionId = :id",array(":id"=>$_POST["id"]))
            ->queryAll();
        $department = Yii::app()->db->CreateCommand()
            ->select()
            ->from("department")
            ->where("brigadir is not null")
            ->queryAll();
        $this->renderPartial('editPos',array(
            'model'=>$model,
            "error" => $error,
            "struct" => $struct,
            "department" => $department
        ));
    }

    public function actionPositionUpdate(){
        $res = Yii::app()->db->createCommand()->update('lineposition',
            array(
                'name' => $_POST["name"],
                'people' => $_POST["people"],
                'departmentId' => $_POST["departmentId"]
            ),
            'positionId = :id',
            array(
                ":id"=>$_POST["id"]
            )
        );
    }

    public function actionPositionStore(){
        $res = Yii::app()->db->createCommand()->insert('lineposition',
            array(
                'phoneId'=>$_POST["id"],
                'name' => $_POST["name"],
                'people' => $_POST["people"],
                'departmentId' => $_POST["departmentId"]
            )
        );
        echo Yii::app()->db->getLastInsertID();
    }

    public function actionAddStruct(){
        $model = array();
        $res = Yii::app()->db->createCommand()->insert("lineposconn",array(
            "errorId" => $_POST["errorId"],
            "positionId" => $_POST["id"],
        ));
        $id = Yii::app()->db->getLastInsertID();
        if($res){
            $model = Yii::app()->db->CreateCommand()
                ->select()
                ->from("lineposconn l")
                ->join("error e","e.errorId = l.errorId")
                ->where("l.posConnId = :id",array(":id"=>$id))
                ->queryRow();
        }
        echo json_encode($model);
    }

    public function actionDeleteStruct(){
        Yii::app()->db->createCommand()->delete("lineposconn","posConnId = :id",array(":id"=>$_POST["id"]));
    }

    public function actionPositionDelete(){
        Yii::app()->db->createCommand()->delete("lineposition","positionId = :id",array(":id"=>$_POST["id"]));
    }

    public function actionPositionUpdateAjax(){
        Yii::app()->db->createCommand()->update("lineposition",array(
            "name" => $_POST["pos"]["name"],
            "phoneId" => $_POST["pos"]["phoneId"],
            "people" => $_POST["pos"]["people"]
        ),"positionId = :id",array(":id"=>$_POST["id"]));
    }

    public function actionLineRate(){

        $department = Yii::app()->db->CreateCommand()
            ->select()
            ->from("department")
            ->queryAll();
        return $this->render("lineRate",array(
            "dep" => $department
        ));

    }

    public function actionGetRateList(){
        $model = array();
        $res = array();
        switch ($_POST["rateType"]){
            case "error":
                $model = Yii::app()->db->CreateCommand()
                    ->select()
                    ->from("error")
                    ->queryAll();
                foreach ($model as $item) {
                    $res[$item['errorId']] = $item["description"];
                }
                break;
            case "inout":
                $res[1] = 'Посящение';
                break;
            case "discipline":
                $model = Yii::app()->db->CreateCommand()
                    ->select()
                    ->from("discipline")
                    ->queryAll();
                foreach ($model as $item) {
                    $res[$item['disciplineId']] = $item["name"];
                }
                break;
        }

        echo json_encode($res);
    }

    public function actionRateSave(){
        Yii::app()->db->createCommand()->insert("lineRate",array(
            "departmentId" => $_POST["department"],
            "ratetype" => $_POST["rateType"],
            "id" => $_POST["rateId"],
            "rate" => $_POST["rate"],
        ));
    }

    public function actionGetList(){
        $model = Yii::app()->db->CreateCommand()
            ->select()
            ->from("linerate")
            ->where("departmentId = :dep",array(":dep"=>$_POST["department"]))
            ->order("ratetype")
            ->queryAll();
        $res = array();
        foreach ($model as $key => $item) {
            switch ($item["ratetype"]){
                case "error":
                    $model = Yii::app()->db->CreateCommand()
                        ->select()
                        ->from("error")
                        ->where("errorId = :id",array(":id"=>$item["id"]))
                        ->queryRow();
                    $res[$key]["name"] = $model["description"];
                    break;
                case "inout":
                    $res[$key]["name"] = 'Посящение';
                    break;
                case "discipline":
                    $model = Yii::app()->db->CreateCommand()
                        ->select()
                        ->from("discipline")
                        ->where("disciplineId = :id",array(":id"=>$item["id"]))
                        ->queryRow();
                    $res[$key]["name"] = $model["name"];
                    break;
            }
            $res[$key]["ratetype"] = $item["ratetype"];
            $res[$key]["rate"] = $item["rate"];
            $res[$key]["rateId"] = $item["lineRateId"];

        }
        echo json_encode($res);
    }

    public function actionDeleteRate(){
        Yii::app()->db->createCommand()->delete("lineRate","lineRateId = :id",array(":id"=>$_POST["id"]));
    }
}