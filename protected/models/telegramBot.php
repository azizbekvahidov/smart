<?
use GuzzleHttp\Client;
class telegramBot{


    // Seller telegram bot methods

    protected $sellertoken = "567946008:AAEnPSVafQGkBqyKFuTkpXuL1BTBWTclTDk";
    protected $updateId = 673397987;

    protected function queryBotSeller($method, $params = []){
        $url = "https://api.telegram.org/bot";
        $url .= $this->sellertoken;
        $url .= "/" . $method;

        if(!empty($params)){
            $url .= "?" . http_build_query($params);
        }
        $client = new Client([
            'base_uri' => $url
        ]);
        $result = $client->request("GET");
        return json_decode($result->getBody());
    }

    public function getUpdatesSeller(){
        $model = Yii::app()->db->createCommand()
            ->select("value")
            ->from("settings")
            ->where("name = 'sellerBotUpdateId'")
            ->queryRow();
        $response = $this->queryBotSeller("getUpdates",[
            'offset' => $model["value"] + 1
        ]);

        if(!empty($response->result)){
            foreach ($response->result as $item) {
                Yii::app()->db->createCommand()->insert("telegramMessage",array(
                    "message" => $item->message->text,
                    "chatId" => $item->message->chat->id,
                    "bot" => "seller"
                ));
            }
            Yii::app()->db->createCommand()->update("settings",array(
                'value'=>$response->result[count($response) - 1]->update_id
            ),"name = 'sellerBotUpdateId'");
        }
        return $response->result;
    }

    public function sendMessageSeller($chatId, $text){
        $response = $this->queryBotSeller("sendMessage", [
            'text' => $text,
            'chat_id' => $chatId
        ]);
        return  $response->result;
    }


    // Info telegram bot methods

    protected $infotoken = "591236082:AAFhFnxmODXrvACAkWnmYDmoIwZ8wMeu6FM";
    protected $infoupdateId = 673397987;

    protected function queryBotInfo($method, $params = []){
        $url = "https://api.telegram.org/bot";
        $url .= $this->infotoken;
        $url .= "/" . $method;

        if(!empty($params)){
            $url .= "?" . http_build_query($params);
        }
        $client = new Client([
            'base_uri' => $url
        ]);
        $result = $client->request("GET");
        return json_decode($result->getBody());
    }

    public function getUpdatesInfo(){
        $model = Yii::app()->db->createCommand()
            ->select("value")
            ->from("settings")
            ->where("name = 'infoBotUpdateId'")
            ->queryRow();
        $response = $this->queryBotInfo("getUpdates",[
            'offset' => $model["value"] + 1
        ]);

        if(!empty($response->result)){
            foreach ($response->result as $item) {
                Yii::app()->db->createCommand()->insert("telegramMessage",array(
                    "message" => $item->message->text,
                    "chatId" => $item->message->chat->id,
                    "bot" => "info"
                ));
            }
            Yii::app()->db->createCommand()->update("settings",array(
                'value'=>$response->result[count($response) - 1]->update_id
            ),"name = 'infoBotUpdateId'");
        }
        return $response->result;
    }

    public function sendMessageInfo($chatId, $text){
        $response = $this->queryBotInfo("sendMessage", [
            'text' => $text,
            'chat_id' => $chatId
        ]);
        return  $response->result;
    }

    public function sendMessage($chatId,$text){
        try {
            $params=array(
                "messages"=>array(
                    "message"=>$text,
                    "chatId"=>$chatId
                )
            );
            $this->sendCustomMessage($params);
        }
        catch (Exception $ex){
            echo "<pre>";
            print_r($ex);
            echo "</pre>";
        }
    }

    public function sendMessageToEmp($empId,$text){
        try{
            $model = Yii::app()->db->CreateCommand()
                ->select()
                ->from("telegramBot")
                ->where('employeeId = :id',array(':id'=>$empId))
                ->queryRow();
            $this->sendMessage($model["chatId"],$text);
        }
        catch (Exception $ex){
            echo "<pre>";
            print_r($ex);
            echo "</pre>";
        }
    }

    public function sendPhoto($chatId,$filePath){

        try {
            $img = new CURLFile($filePath);
            $params=array(
                "photo"=>array(
                    "photo"=>$img,
                    "chatId"=>$chatId
                )
            );
            echo file_get_contents($this->sendCustomMessage($params));
        }
        catch (Exception $ex){
            echo "<pre>";
            print_r($ex);
            echo "</pre>";
        }
    }

    public function sendErrorMessage($error,$phoneId){
        $model = Yii::app()->db->createCommand()
            ->select("e.description as description, count(r.registerId) as cnt, p.model as model")
            ->from("register r")
            ->join("error e","e.errorId = r.errorIdOtk")
            ->join("phone p","p.phoneId = r.phoneId")
            ->where("date(r.errorOtkDate) = :curDate AND r.errorIdOtk = :error and r.phoneId = :id",array(":curDate" => date("Y-m-d"), ":error"=>$error,':id'=>$phoneId))
            ->group("r.phoneId")
            ->queryAll();
        $users = Yii::app()->db->createCommand()
            ->select("chatId")
            ->from("telegrambot t")
            ->join("users u","u.userId = t.userId")
            ->where("(u.role = 3 or u.role = 8) and t.userId != 0")
            ->queryAll();
        foreach ($model as $item) {
            if(($item["cnt"] % 3) == 0) {
                $params=array(
                    "info" => array(
                        "message"=> 'Модель: '.$item["model"].",\nОшибка: ".$item["description"].",\nКол-во: ".$item["cnt"],
                        "name"=>$item["description"],
                        "cnt"=>$item["cnt"],
                        "model"=> $item["model"]
                    ),
                    "users"=>$users
                );
                $this->sendCustomMessage($params);
            }
        }
    }

    public function sendCustomMessage($params){
        $url="http://telegramBot";
        $url.="?" . http_build_query($params);
        $client = new Client([
            'base_uri'=>$url
        ]);
        $result = $client->request("GET");
    }
}