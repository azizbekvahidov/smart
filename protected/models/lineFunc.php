<?
    class lineFunc {

        public function getPositionName($id,$modelId){
            $model = Yii::app()->db->CreateCommand()
                ->select()
                ->from("lineposition")
                ->where("linePosition like '%".$id."%' and phoneId = :model ",array(":model"=>$modelId))
                ->queryRow();
            return $model["name"];
        }


    }
?>