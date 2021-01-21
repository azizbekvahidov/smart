<?php
require_once __DIR__ ."/../extensions/vendor/autoload.php";

class CabinetController extends Controller{

    public $layout = '/layouts/cabinet';
    public function actionIndex(){
        if (Yii::app()->user->isGuest)
            $this->redirect('/site/index');
        else {
            $this->render("index");
        }
    }

    public function actionLogin()
    {
        $model=new LogForm;
        $reg = Yii::app()->user->getFlash('registration');
        $serviceName = Yii::app()->request->getQuery('service');
        if (isset($serviceName)) {
            /** @var $eauth EAuthServiceBase */
            $eauth = Yii::app()->eauth->getIdentity($serviceName);
            $eauth->redirectUrl = 'index';
            $eauth->cancelUrl = $this->createAbsoluteUrl('cabinet/login');

            try {
                if ($eauth->authenticate()) {
                    //var_dump($eauth->getIsAuthenticated(), $eauth->getAttributes());
                    $identity = new EAuthUserIdentity($eauth);

                    // successful authentication
                    if ($identity->authenticate()) {
                        Yii::app()->user->login($identity);
                        //var_dump($identity->id, $identity->name, Yii::app()->user->id);exit;

                        // special redirect with closing popup window
                        $eauth->redirect();
                    } else {
                        // close popup window and redirect to cancelUrl
                        $eauth->cancel();
                    }
                }
                // Something went wrong, redirect to login page
                $this->redirect(array('cabinet/login'));
            } catch (EAuthException $e) {
                // save authentication error to session
                Yii::app()->user->setFlash('error', 'EAuthException: ' . $e->getMessage());

                // close popup window and redirect to cancelUrl
                $eauth->redirect($eauth->getCancelUrl());
            }
        }

        // if it is ajax validation request
        if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        // collect user input data
        if(isset($_POST['LogForm']))
        {
            $model->attributes=$_POST['LogForm'];
            // validate user input and redirect to the previous page if valid
            if($model->validate() && $model->login())
                $this->redirect('index');
            $model->attributes=$_POST['LogForm'];
            /*
            // validate user input and redirect to the previous page if valid
            if($model->validate() && $model->login())
            {
                if(Yii::app()->user->getOrganization()!=null)
                    $this->redirect('/reestr/addresses/create');//Yii::app()->user->returnUrl);
                else
                    $this->redirect('/site/regorg');
            }*/

        }
        // display the login form
        $this->renderPartial(   'login',array('model'=>$model, 'message'=>$reg));
    }

    public function actionCalendar(){
        if (Yii::app()->user->isGuest)
            $this->redirect('/site/index');
        else {
            $this->render('calendar');
        }
    }

    public function actionProfile(){
        if (Yii::app()->user->isGuest)
            $this->redirect('/site/index');
        else {
            $this->render('profile');
        }
    }

    public function actionTask(){

        if (Yii::app()->user->isGuest)
            $this->redirect('/site/index');
        else {
            $model = Yii::app()->db->CreateCommand()
                ->select()
                ->from('protList pl')
                ->join('protokol p','pl.protokolId = p.protokolId')
                ->where('pl.employeeId LIKE "%'.Yii::app()->user->getId().'%" AND pl.status = 0')
                ->queryAll();
            $task['protokol'] = $model;
            $model2 = Yii::app()->db->CreateCommand()
                ->select()
                ->from('task')
                ->where('employeeId = :id and status != 1',array(':id'=>Yii::app()->user->getId()))
                ->queryAll();

            $task['task'] = $model2;
            $message = "";
            if (isset($_GET["message"])){
                $message =unserialize($_GET["message"]);
            }
            $this->render('task',array('model'=>$task,'message'=>$message));
        }
    }
    
    public function actionGetTask(){
        $model = Yii::app()->db->CreateCommand()
            ->select()
            ->from('protList pl')
            ->where('pl.employeeId like "%'.Yii::app()->user->getId().'%" AND pl.status = 0')
            ->queryAll();
        $model2 = Yii::app()->db->CreateCommand()
            ->select()
            ->from('task')
            ->where('employeeId = :id and status != 1',array(':id'=>Yii::app()->user->getId()))
            ->queryAll();

        $checkDock = Yii::app()->db->CreateCommand()
            ->select()
            ->from('doc')
            ->where('sign = "" and (employeeId = :id or (redirectEmployee = :emp and redirectEmployee is not null))',array(':id'=>Yii::app()->user->getId(),':emp'=>Yii::app()->user->getId()))
            ->group('docType')
            ->queryAll();
        $task['task'] = $model2;
        $task['protokol'] = $model;
        $task['sign'] = $checkDock;
        echo json_encode($task);
    }

    public function actionTaskRefresh(){
        $model = Yii::app()->db->CreateCommand()
            ->select()
            ->from('protList pl')
            ->join('protokol p','pl.protokolId = p.protokolId')
            ->where('pl.employeeId = :id AND pl.status = 0',array(':id'=>Yii::app()->user->getId()))
            ->queryAll();
        $task['protokol'] = $model;
        $model2 = Yii::app()->db->CreateCommand()
            ->select()
            ->from('task')
            ->where('employeeId = :id and status != 1',array(':id'=>Yii::app()->user->getId()))
            ->queryAll();
        $task['task'] = $model2;
        echo json_encode($task);
    }

    public function actionProtokolTaskSolve(){
        $model = false;
        $format = ['jpg','jpeg','gif','png','xls','xlsx','d oc','docx','rar','zip','txt'];

        $upload = new UploadFile();
        if(isset($_FILES) && $_FILES["file"]["name"] != '') {
            $func = new Functions();
            $filename = $func->translitRU($_FILES["file"]["name"]);
            $uploaded = $upload->upload($filename, $_FILES["file"]["tmp_name"], $_FILES["file"]["size"], '/upload/files/protokol/'.$_POST["id"].'/solve/', $format);

            if ($uploaded['result'] == 1) {
                try {
                    $model = Yii::app()->db->createCommand()->update('protList',
                        array(
                            'solve' => $_POST["solve"],
                            'status' => $_POST["status"] == 'on' ? 0 : 1,
                            'solveDate' => $_POST["status"] == 'on' ? null : date("y-m-d"),
                            'solveFile'=>$filename,
                        ),
                        'protListId = :id',array(':id'=>$_POST["id"])
                    );
                }
                catch(Exception $ex){
                    echo "<pre>";
                    print_r($ex->getMessage());
                    echo "</pre>";
                }
            }
        }
        else{
            $model = Yii::app()->db->createCommand()->update('protList',
                array(
                    'solve' => $_POST["solve"],
                    'status' => $_POST["status"] == 'on' ? 0 : 1,
                    'solveDate' => $_POST["status"] == 'on' ? null : date("y-m-d"),
                ),
                'protListId = :id',array(':id'=>$_POST["id"])
            );
        }
        $message = "";
        if($model){
            $message["type"] = "success";
            $message["text"] = "Задача успешно сохранена";
        }
        else{
            $message["type"] = "warning";
            $message["text"] = "что то пошло не так";
        }
        $this->redirect("/cabinet/task?message=".serialize($message));
    }

    public function actionTaskSave(){
        $model = false;
        $format = ['jpg','jpeg','gif','png','xls','xlsx','doc','docx','rar','zip','txt'];

        $upload = new UploadFile();
        if($_POST["task"] != "") {
            if(isset($_FILES) && $_FILES["file"]["name"] != ''){
                $func = new Functions();
                $filename = $func->translitRU($_FILES["file"]["name"]);
                $uploaded = $upload->upload( $filename, $_FILES["file"]["tmp_name"], $_FILES["file"]["size"],'/upload/files/task/',$format);
                if($uploaded['result']) {
                $model = Yii::app()->db->createCommand()->insert(
                        'task',
                        array(
                            'task' => $_POST["task"],
                            'taskDate' => $_POST["taskDate"],
                            'taskDeadline' => $_POST["deadline"],
                            'employeeId' => $_POST["response"],
                            'taskManager' => Yii::app()->user->getId(),
                            'file' => $filename,
                        )
                    );
                $message = "Вам выставлена задача '". $_POST["task"] ."' от ".Yii::app()->user->getName().".";
                $telgram = new telegramBot();
                $telgram->sendMessageToEmp($_POST["response"],$message);
                }
            }
            else{
                $model = Yii::app()->db->createCommand()->insert(
                    'task',
                    array(
                        'task' => $_POST["task"],
                        'taskDate' => $_POST["taskDate"],
                        'taskDeadline' => $_POST["deadline"],
                        'employeeId' => $_POST["response"],
                        'taskManager' => Yii::app()->user->getId(),
                    )
                );
                $message = "Вам выставлена задача '". $_POST["task"] ."' от ".Yii::app()->user->getName().".";
                $telgram = new telegramBot();
                $telgram->sendMessageToEmp($_POST["response"],$message);
            }
        }
        echo $model;
    }

    public function actionTaskSolve(){
        $model = false;
        $format = ['jpg','jpeg','gif','png','xls','xlsx','doc','docx','rar','zip','txt'];

        $emp = Yii::app()->db->CreateCommand()
            ->select()
            ->from('task')
            ->where('taskId = :id',array(':id'=>$_POST["id"]))
            ->queryRow();
        $upload = new UploadFile();
        if(isset($_FILES) && $_FILES["file"]["name"] != '') {
            $func = new Functions();
            $filename = $func->translitRU($_FILES["file"]["name"]);
            $uploaded = $upload->upload($filename, $_FILES["file"]["tmp_name"], $_FILES["file"]["size"], '/upload/files/task/solve/', $format);

            if ($uploaded['result']) {
                $model = Yii::app()->db->createCommand()->update('task',
                    array(
                        'solve' => $_POST["solve"],
                        'status' => $_POST["status"] == 'on' ? 0 : 1,
                        'solveDate' => $_POST["status"] == 'on' ? null : date("y-m-d"),
                        'solveFile' => $filename,
                    ),
                    'taskId = :id',array(':id'=>$_POST["id"])
                );
                $message = "Задача выставленная вами '". $emp["task"] ."' для ".Yii::app()->user->getName()." выполнена.";
                $telgram = new telegramBot();
                $telgram->sendMessageToEmp($emp["taskManager"],$message);
            }
        }
        else{
            $model = Yii::app()->db->createCommand()->update('task',
                array(
                    'solve' => $_POST["solve"],
                    'status' => $_POST["status"] == 'on' ? 0 : 1,
                    'solveDate' => $_POST["status"] == 'on' ? null : date("y-m-d")
                ),
                'taskId = :id',array(':id'=>$_POST["id"])
            );
            $message = "Задача выставленная вами '". $emp["task"] ."' для ".Yii::app()->user->getName()." выполнена.";
            $telgram = new telegramBot();
            $telgram->sendMessageToEmp($emp["taskManager"],$message);
        }

        $message = "";
        if($model){
            $message["type"] = "success";
            $message["text"] = "Задача успешно сохранена";
        }
        else{
            $message["type"] = "warning";
            $message["text"] = "что то пошло не так";
        }
        $this->redirect("/cabinet/task?message=".serialize($message));
    }

    public function actionSolvedTask(){
        $date = new DateTime('now');
        $date->modify('first day of this month');
        $start = $date->format('Y-m-d');
        $date->modify('last day of this month');
        $date->format('Y-m-d');
        $end = $date->format('Y-m-d');
        $model = Yii::app()->db->CreateCommand()
            ->select("pl.meetQuestion as task,pl.solve,p.protokolDate as taskDate, pl.solveDate,CONCAT('Протокол') as taskManager")
            ->from('protList pl')
            ->join('protokol p','pl.protokolId = p.protokolId')
            ->where('p.protokolDate BETWEEN :start AND :end AND pl.employeeId = :id AND pl.status = 1',array(':id'=>Yii::app()->user->getId(),':start'=>$start,':end'=>$end))
            ->getText();
        $model2 = Yii::app()->db->CreateCommand()
            ->select("t.task,t.solve,t.taskDate,t.solveDate, CONCAT(e.surname,' ',e.name) as taskManager")
            ->from('task t')
            ->join('employee e','e.employeeId = t.taskManager')
            ->where('t.taskDate BETWEEN :start AND :end AND t.employeeId = :id and t.status = 1',array(':id'=>Yii::app()->user->getId(),':start'=>$start,':end'=>$end))
            ->union($model)
            ->order('taskDate')
            ->queryAll();
        $task = $model2;
        $this->render('solvedTask', array(
            'model' => $task,
            'start' => $start,
            'end' => $end
        ));
    }

    public function actionAjaxSolvedTask(){
        $start = $_POST["start"];
        $end = $_POST["end"];
        try {
            $model=Yii::app()->db->CreateCommand()
                ->select("t.task,t.solve,t.taskDate,t.solveDate, CONCAT(e.surname,' ',e.name) as taskManager")
                ->from('task t')
                ->join('employee e', 'e.employeeId = t.taskManager')
                ->where('t.taskDate BETWEEN :start AND :end AND t.employeeId = :id and t.status = 1', array(':id'=>Yii::app()->user->getId(), ':start'=>$start, ':end'=>$end))
                ->getText();
            $model2=Yii::app()->db->CreateCommand()
                ->select("pl.meetQuestion as task,pl.solve,p.protokolDate as taskDate, pl.solveDate,CONCAT('Протокол') as taskManager")
                ->from('protList pl')
                ->join('protokol p', 'pl.protokolId = p.protokolId')
                ->where('p.protokolDate BETWEEN :start AND :end AND pl.employeeId = :id AND pl.status = 1', array(':id'=>Yii::app()->user->getId(), ':start'=>$start, ':end'=>$end))
                ->union($model)
                ->order('t.taskDate')
                ->queryAll();
        }
        catch (Exception $ex){
            echo "<pre>";
            print_r($ex->getMessage());
            echo "</pre>";
        }

        echo json_encode($model2);
    }

    public function actionSettings(){
        $this->renderPartial('ajax/'.$_POST['type'],array());
    }

    public function actionChangePass(){
        $message['type'] = "";
        $message['text'] = "";
        $message['icon'] = "";
        $model = Yii::app()->db->CreateCommand()
            ->select()
            ->from('employee')
            ->where('employeeId = :id',array(':id'=>Yii::app()->user->getId()))
            ->queryRow();
        $oldPass = md5(md5($_POST["oldPass"]));
        if($oldPass == $model["password"]){
            Yii::app()->db->CreateCommand()
                ->update('employee',array('password'=>md5(md5($_POST["newPass"]))),'employeeId = :id',array(':id'=>Yii::app()->user->getId()));
            $message['type'] = "success";
            $message['text'] = "Пароль успешно изменен";
            $message['icon'] = "check";
        }
        else{
            $message['type'] = "danger";
            $message['text'] = "Текущий пароль неправильно введен!";
            $message['icon'] = "warning";
        }
        echo json_encode($message);
    }

    public function actionProtokolTaskCencel(){
        $model = false;

        $format = ['jpg','jpeg','gif','png','xls','xlsx','doc','docx','rar','zip','txt'];
        $path = '/upload/files/protokol/'.$_POST['id'].'/cencel/';
        $upload = new UploadFile();
        if(isset($_FILES)) {
            $func = new Functions();
            $filename = $func->translitRU($_FILES["file"]["name"]);
            $uploaded = $upload->upload($filename, $_FILES["file"]["tmp_name"], $_FILES["file"]["size"], $path, $format);
            if ($uploaded['result']) {
                $model = Yii::app()->db->createCommand()->update('protList',
                    array(
                        'solve' => $_POST["solve"],
                        'status' => 1,
                        'solveDate' => date("y-m-d"),
                        'refuse' => 1,
                        'solveFile' => $filename,
                    ),
                    'protListId = :id',array(':id'=>$_POST["id"])
                );
            }
        }
        else{
            $model = Yii::app()->db->createCommand()->update('protList',
                array(
                    'solve' => $_POST["solve"],
                    'status' => 1,
                    'solveDate' => date("y-m-d"),
                    'refuse' => 1,
                ),
                'protListId = :id',array(':id'=>$_POST["id"])
            );
        }

        $message = "";
        if($model){
            $message["type"] = "success";
            $message["text"] = "Задача успешно сохранена";
        }
        else{
            $message["type"] = "warning";
            $message["text"] = "что то пошло не так";
        }
        $this->redirect("/cabinet/task?message=".serialize($message));
    }

    public function actionTaskCencel(){

        $model = false;
        $format = ['jpg','jpeg','gif','png','xls','xlsx','doc','docx','rar','zip','txt'];

        $upload = new UploadFile();
        if(isset($_FILES)) {
            $func = new Functions();
            $filename = $func->translitRU($_FILES["file"]["name"]);
            $uploaded = $upload->upload($filename, $_FILES["file"]["tmp_name"], $_FILES["file"]["size"], '/upload/files/task/cencel/', $format);
            if ($uploaded['result']) {
                $model = Yii::app()->db->createCommand()->update('task',
                    array(
                        'solve' => $_POST["solve"],
                        'status' => 1,
                        'solveDate' => date("y-m-d"),
                        'refuse' => 1,
                        'solveFile' => $filename,
                    ),
                    'taskId = :id',array(':id'=>$_POST["id"])
                );
            }
        }
        else{
            $model = Yii::app()->db->createCommand()->update('task',
                array(
                    'solve' => $_POST["solve"],
                    'status' => 1,
                    'solveDate' => date("y-m-d"),
                    'refuse' => 1,
                ),
                'taskId = :id',array(':id'=>$_POST["id"])
            );
        }

        $message = "";
        if($model){
            $message["type"] = "success";
            $message["text"] = "Задача успешно сохранена";
        }
        else{
            $message["type"] = "warning";
            $message["text"] = "что то пошло не так";
        }
        $this->redirect("/cabinet/task?message=".serialize($message));
    }

    public function actionSendMsgTelegram(){
        $emp = Yii::app()->db->CreateCommand()
            ->select()
            ->from("employee e")
            ->join('telegramBot t','t.employeeId = e.employeeId')
            ->queryAll();

        $this->render('sendMsgTelegram',array(
            'emp' => $emp
        ));
    }

    public function actionSendTelegram(){
        $telgram = new telegramBot();
        foreach ($_POST['emp'] as $key => $val) {
            $telgram->sendMessage($key,$_POST['message']);
        }
    }

    public function actionMySetTask(){
        $model = Yii::app()->db->CreateCommand()
            ->select('*,t.status as sts')
            ->from('task t')
            ->join('employee e','e.employeeId = t.employeeId')
            ->where('t.taskManager = :id',array(':id'=>Yii::app()->user->getId()))
            ->queryAll();
        $this->render('mySetTask',array(
            'model' => $model
        ));
    }

    public function actionDeleteTask($id){
        $model = Yii::app()->db->createCommand()->delete('task','taskId = :id',array(':id'=>$id));
        echo $model;
    }

    public function actionEditTask($id){
        $model = Yii::app()->db->CreateCommand()
            ->select()
            ->from('task')
            ->where('taskId = :id',array(':id'=>$id))
            ->queryRow();
        if(isset($_GET['edit']['task'])){
            Yii::app()->db->createCommand()->update('task',
                array(
                    'task' => $_GET['edit']["task"],
                    'taskDeadline' => $_GET['edit']["deadline"]
                ),'taskId  = :id',array(':id'=>$id));
            $message = "Ваша задача '". $model["task"] ."' от ".Yii::app()->user->getName()." изменена на '" . $_GET['edit']['task'] . "'";
            $telgram = new telegramBot();
            $telgram->sendMessageToEmp($model["employeeId"],$message);
            $this->redirect('/cabinet/mySetTask');
        }
        else{
            $this->render('editTask',array(
                'model'=>$model
            ));
        }
    }

    public function actionSignDoc(){

        $checkDock = Yii::app()->db->CreateCommand()
            ->select()
            ->from('doc')
            ->where('sign = "" and (employeeId = :id or (redirectEmployee = :emp and redirectEmployee is not null))',array(':id'=>Yii::app()->user->getId(),':emp'=>Yii::app()->user->getId()))
            ->group('docType')
            ->queryAll();
        $this->render('signDoc',array(
            'model' => $checkDock
        ));
    }

    public function actionSigned(){
        $doc = new Doc();
        $model = Yii::app()->db->createCommand()->update('doc',array(
            'sign' => 1
        ),'docId = :id',array(':id'=>$_GET['docId']));
        if($model){
            $doc->checkDoc($_GET['docType'],$_GET['id']);
        }

        $this->redirect('/');
            
    }


    public function actionSendSomeMessage(){
        $telegram = new telegramBot();
        $emp = Yii::app()->db->CreateCommand()
            ->select()
            ->from("employee e")
            ->join('telegramBot t','t.employeeId = e.employeeId')
            ->queryAll();
        foreach ($emp as $item) {
            $telegram->sendMessage($item['chatId'],'Через 5 минут время установливать видеоконференцию');
        }
    }

}