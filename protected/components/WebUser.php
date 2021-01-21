<?php

class WebUser extends CWebUser {
    function getRole() {
        $role = Yii::app()->db->createCommand()
            ->select()
            ->from("users")
            ->where("userId = :id",array(":id"=>$this->id))
            ->queryRow();
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("role")
            ->where("roleId = :id",array(":id"=>$role['role']))
            ->queryRow();
        if($user = $model){
            return $user["name"];
        }
    }

    function getPos() {
        $role = Yii::app()->db->createCommand()
            ->select()
            ->from("employee")
            ->where("employeeId = :id",array(":id"=>$this->id))
            ->queryRow();
        if($user = $role){
            return $user["positionId"];
        }
    }

}