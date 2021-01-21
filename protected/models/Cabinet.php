<?

class Cabinet{

    public function getUserImage($id){
        $model = Yii::app()->db->CreateCommand()
            ->select()
            ->from("employee")
            ->where("employeeId = :id",array(':id'=>$id))
            ->queryRow();
        return $model["photo"];
    }

    public function getUserPosition($id){
        $model = Yii::app()->db->CreateCommand()
            ->select()
            ->from("position")
            ->where("positionId = :id",array(':id'=>$id))
            ->queryRow();
        return $model["name"];
    }
}