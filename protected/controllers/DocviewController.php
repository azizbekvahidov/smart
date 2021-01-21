<?php

class DocviewController extends Controller{

    public function actionActView($id){


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

        $this->renderPartial('actView',array(
            'model' => $model,
            "act" => $act,
            'list' => $list,
            'spare' => $spare
        ));
    }

}