<?php
require_once __DIR__ ."/../extensions/vendor/autoload.php";

class Doc{

    public function setDocSign($docType,$id){
        $model = Yii::app()->db->CreateCommand()
            ->select()
            ->from("docSet")
            ->where('docType = :doc',array(':doc'=>$docType))
            ->queryAll();

        foreach ($model as  $item) {
            Yii::app()->db->createCommand()->insert('doc',array(
                'docType' => $docType,
                'id' => $id,
                'employeeId' => $item["employeeId"],
                'queue' => $item["queue"]
            ));
        }
        $this->checkDoc($docType,$id);
    }

    public function checkDoc($docType,$id){
        $model = Yii::app()->db->CreateCommand()
            ->select()
            ->from('doc')
            ->where('docType = :doc and id = :id and sign != 1',array(
                ':id' => $id,
                ':doc'=>$docType,
            ))
            ->order('queue')
            ->limit(1)
            ->queryRow();
        $empId = $model["employeeId"];

        if($model['redirectEmployee'] != null){
            $empId = $model['redirectEmployee'];
        }
        $docType = $this->getDocType($docType);
        $message = 'У вас есть документ '. $docType['name'] .'на подпись с ID '.$id;
        $telgram = new telegramBot();
        $telgram->sendMessageToEmp($empId,$message);
    }


    public function getDocType($docType){
        $model = Yii::app()->db->CreateCommand()
            ->select()
            ->from('docType')
            ->where('docType like "%'.$docType.'%"')
            ->queryRow();
        return $model;
    }

    public function getSignedList($docType,$id){
        $status = 0;
        $model = Yii::app()->db->CreateCommand()
            ->select('d.sign, e.name, e.surname')
            ->from('doc d')
            ->join('employee e','e.employeeId = d.employeeId')
            ->where('d.docType like "%'.$docType.'%" and d.id = :id',array(':id'=>$id))
            ->queryAll();
        $returnList['list'] = '<ol>';
        foreach ($model as $item) {
            if($item['sign'] == 1) {
                $status = 1;
                $returnList['list'] .= '<li>' . $item['surname'] . ' ' . $item['name'] . ' <i class="glyphicon glyphicon-ok alert-success"></i>';
            }

            else if($item['sign'] == 0) {
                $status = 0;
                $returnList['list'] .= '<li>' . $item['surname'] . ' ' . $item['name'] . ' <i class="glyphicon glyphicon-remove alert-danger"></i>';
            }
        }
        $returnList['list'] .= '</ol>';
        $returnList['status'] = $status;

        return $returnList;

    }

}