<?php

require_once __DIR__ ."/../extensions/vendor/autoload.php";
class UsersController extends Controller
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
            //'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id,$pass)
    {
        $model = $this->loadModel($id);
        $this->render('view',array(
            'model'=>$this->loadModel($id),
            'pass'=>$pass
        ));
        if(!empty($model->phone))
            $this->telegramBot($id,$model->phone,$model->login,$pass);
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new Users();
        $title = "Пользователь";
        $list = CHtml::listData(Users::model()->findAll("role = 2"),'userId', 'login');
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if(isset($_POST['Users'])){
            $_POST["Users"]["point"] = "";
            if(!empty($_POST["Users"]["city"])){
                $_POST["Users"]["point"] = $_POST["Users"]["point"].$_POST["Users"]["city"];
                if(!empty($_POST["Users"]["district"])){
                    $_POST["Users"]["point"] = $_POST["Users"]["point"].",".$_POST["Users"]["district"];
                    if(!empty($_POST["Users"]["market"])){
                        $_POST["Users"]["point"] = $_POST["Users"]["point"].",".$_POST["Users"]["market"];
                        if(!empty($_POST["Users"]["shop"])){
                            $_POST["Users"]["point"] = $_POST["Users"]["point"].",".$_POST["Users"]["shop"];
                        }
                    }
                }
            }
            $model = new Users();
            $pass = $this->randomPassword();
            $model->attributes=$_POST['Users'];
            $model->password = $this->generatePass($pass);
            $model->parent = $_POST["Users"]["parent"];
            $model->role = 1;
            $model->uType = $_POST['Users']['uType'];
            $model->name = $_POST['Users']["name"];
            $model->phone = $_POST['Users']["phone"];
            if($model->save()) {
                $this->redirect(array('view', 'id'=>$model->userId, 'pass'=>$pass));
            }
        }

        $this->render('create',array(
            'model'=>$model,
            'list'=>$list,
            'title'=>$title
        ));
    }

    public function actionCreateDil()
    {
        $title = "Диллер";
        $model = new Users();
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if(isset($_POST['Users']))
        {
            $_POST["Users"]["point"] = "";
            if(!empty($_POST["Users"]["city"])){
                $_POST["Users"]["point"] = $_POST["Users"]["point"].$_POST["Users"]["city"];
                if(!empty($_POST["Users"]["district"])){
                    $_POST["Users"]["point"] = $_POST["Users"]["point"].",".$_POST["Users"]["district"];
                    if(!empty($_POST["Users"]["market"])){
                        $_POST["Users"]["point"] = $_POST["Users"]["point"].",".$_POST["Users"]["market"];
                        if(!empty($_POST["Users"]["shop"])){
                            $_POST["Users"]["point"] = $_POST["Users"]["point"].",".$_POST["Users"]["shop"];
                        }
                    }
                }
            }
            $model = new Users();
            $pass = $this->randomPassword();
            $model->attributes=$_POST['Users'];
            $model->password = $this->generatePass($pass);
            $model->parent = 0;
            $model->role = 2;
            $model->phone = $_POST['Users']["phone"];
            $model->name = $_POST['Users']["name"];
            if($model->save()) {
                $this->redirect(array('view', 'id'=>$model->userId, 'pass'=>$pass,));
            }
        }

        $this->render('createDil',array(
            'model'=>$model,
            'title'=>$title
        ));
    }

    public function actionGetPlace(){
        $ctype = "";
        $name = "";
        $id = "";
        $ctypeNum = 0;
        $next = "";
        switch ($_POST["ctype"]){
            case 1:
                $id = "district";
                $ctypeNum = 2;
                $ctype = "Users[district]";
                $name = "Район";
                $next = "market";
                break;
            case 2:
                $id = "market";
                $ctypeNum = 3;
                $ctype = "Users[market]";
                $name = "Рынок";
                $next = "shop";
                break;
            case 3:
                $id = "shop";
                $ctypeNum = 4;
                $ctype = "Users[shop]";
                $name = "Магазин";
                $next = "";
                break;
        }
        $model = CHtml::listData(Point::model()->findAll("parent = :id",array(":id"=>$_POST["id"])),"pointId","name");

        $this->renderPartial("getPlace",array(
            'model'=>$model,
            'ctype'=>$ctype,
            'name'=>$name,
            'id'=>$id,
            'next'=>$next,
            'ctypeNum'=>$ctypeNum
        ));
    }

    public function actionGetUsers(){
        $point = "";
        if(!empty($_POST["city"])){
            $point = $point.$_POST["city"];
            if(!empty($_POST["district"])){
                $point = $point.",".$_POST["district"];
                if(!empty($_POST["market"])){
                    $point = $point.",".$_POST["market"];
                    if(!empty($_POST["shop"])){
                        $point = $point.",".$_POST["shop"];
                    }
                }
            }
        }
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("users")
            ->where("point like '%" . $point . "%' AND role = 1")
            ->queryAll();
        echo json_encode($model);
    }

    function randomPassword() {
        $alphabet = '1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 4; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from('users')
            ->where("userId = :id",array(":id"=>$id))
            ->queryRow();
        $list = CHtml::listData(Users::model()->findAll("role = 2"),'userId', 'login');
        $title = "Пользователь";
        $point = explode(',',$model["point"]);
        $model["city"] = (!empty($point[0]) ? $point[0] : 0);
        $model["district"] = (!empty($point[1]) ? $point[1] : 0);
        $model["market"] = (!empty($point[2]) ? $point[2] : 0);
        $model["shop"] = (!empty($point[3]) ? $point[3] : 0);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Users']))
        {
            $model = $this->loadModel($id);
            $_POST["Users"]["point"] = "";
            if(!empty($_POST["Users"]["city"])){
                $_POST["Users"]["point"] = $_POST["Users"]["point"].$_POST["Users"]["city"];
                if(!empty($_POST["Users"]["district"])){
                    $_POST["Users"]["point"] = $_POST["Users"]["point"].",".$_POST["Users"]["district"];
                    if(!empty($_POST["Users"]["market"])){
                        $_POST["Users"]["point"] = $_POST["Users"]["point"].",".$_POST["Users"]["market"];
                        if(!empty($_POST["Users"]["shop"])){
                            $_POST["Users"]["point"] = $_POST["Users"]["point"].",".$_POST["Users"]["shop"];
                        }
                    }
                }
            }
            $pass = "";
            if(isset($_POST["Users"]["pass"])){
                $pass = $this->randomPassword();
                $_POST["Users"]["password"] = $this->generatePass($pass);
            }
            $model->attributes=$_POST['Users'];
            $model->phone = $_POST['Users']['phone'];
            $model->uType = $_POST['Users']['uType'];
            $model->parent = $_POST['Users']['parent'];
            if($model->save()) {
                $this->redirect(array('view', 'id'=>$model->userId, "pass"=>$pass));
            }
        }

        $this->render('update',array(
            'model'=>$model,
            'list'=>$list,
            'title'=>$title
        ));
    }

    public function telegramBot($userId,$phone,$login,$pass){
        if($pass != "") {
            $func=new Functions();
            $model= Yii::app()->db->createCommand()
                ->select()
                ->from("telegramBot")
                ->where("userId = :id", array("id"=>$userId))
                ->queryRow();

            if (empty($model)) {
                Yii::app()->db->createCommand()->update("telegramBot", array(
                    "userId"=>$userId
                ), "phone = '" . $phone . "'");
                $model=Yii::app()->db->createCommand()
                    ->select()
                    ->from("telegramBot")
                    ->where("userId = :id", array("id"=>$userId))
                    ->queryRow();
                $res = $func->sendMessage($model["chatId"],"Ваш логин: ".$login.", пароль: ".$pass);;
            } else {
                $res = $func->sendMessage($model["chatId"],"Ваш логин: ".$login.", пароль: ".$pass);
            }
        }
    }


    public function actionUpdateDil($id)
    {

        $model = Yii::app()->db->createCommand()
            ->select()
            ->from('users')
            ->where("userId = :id",array(":id"=>$id))
            ->queryRow();
        $title = "Диллер";
        $point = explode(',',$model["point"]);
        $model["city"] = (!empty($point[0]) ? $point[0] : 0);
        $model["district"] = (!empty($point[1]) ? $point[1] : 0);
        $model["market"] = (!empty($point[2]) ? $point[2] : 0);
        $model["shop"] = (!empty($point[3]) ? $point[3] : 0);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Users']))
        {
            $model = $this->loadModel($id);
            $_POST["Users"]["point"] = "";
            if(!empty($_POST["Users"]["city"])){
                $_POST["Users"]["point"] = $_POST["Users"]["point"].$_POST["Users"]["city"];
                if(!empty($_POST["Users"]["district"])){
                    $_POST["Users"]["point"] = $_POST["Users"]["point"].",".$_POST["Users"]["district"];
                    if(!empty($_POST["Users"]["market"])){
                        $_POST["Users"]["point"] = $_POST["Users"]["point"].",".$_POST["Users"]["market"];
                        if(!empty($_POST["Users"]["shop"])){
                            $_POST["Users"]["point"] = $_POST["Users"]["point"].",".$_POST["Users"]["shop"];
                        }
                    }
                }
            }
            $pass = "";
            if(isset($_POST["Users"]["pass"])){
                $pass = $this->randomPassword();
                $_POST["Users"]["password"] = $this->generatePass($pass);
            }
            $model->phone = $_POST['Users']["phone"];
            $model->attributes=$_POST['Users'];
            if($model->save())
                $this->redirect(array('view', 'id'=>$model->userId, "pass"=>$pass));

        }

        $this->render('updateDil',array(
            'model'=>$model,
            'title'=>$title
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {

        //$this->loadModel($id)->delete();
        Yii::app()->db->createCommand()->update("users",array("status" => 1),"userId = :id",array(":id"=>$id));

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        //if(!isset($_GET['ajax']))
        $this->redirect(Yii::app()->request->urlReferrer);
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $dataProvider=new CActiveDataProvider('Users');
        $this->render('index',array(
            'dataProvider'=>$dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $modelNew = Yii::app()->db->createCommand()
            ->select()
            ->from("users")
            ->where("role = 1 and status = 0")
            ->queryAll();
        $title = "Пользователь";
        $model=new Users('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Users']))
            $model->attributes=$_GET['Users'];

        $this->render('admin',array(
            'model'=>$model,
            'modelNew'=>$modelNew,
            'title'=>$title
        ));
    }

    public function actionAdminDil()
    {

        $modelNew = Yii::app()->db->createCommand()
            ->select()
            ->from("users")
            ->where("role = 2 and status = 0")
            ->queryAll();
        $title = "Диллер";
        $model=new Users('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Users']))
            $model->attributes=$_GET['Users'];

        $this->render('adminDil',array(
            'model'=>$model,
            'modelNew'=>$modelNew,
            'title'=>$title
        ));
    }

    public function actionLogins(){
//        $_POST["password"] = "852";
//        $_POST["username"] = "user";
        $query = Yii::app()->db->createCommand()
            ->select()
            ->from("users")
            ->where("password = :pass",array(":pass"=>$this->generatePass($_POST["password"])))
            ->queryRow();

        $response = array();

        if($query["userId"] != ""){
            $response = $query;
            $response["success"] = true;
        }
        else{
            $response["success"] = false;
        }
        echo json_encode($response);

    }

    public function actionLogin(){
//        $_POST["password"] = "123456";
//        $_POST["username"] = "user";
        $func = new Functions();
        $query = Yii::app()->db->createCommand()
            ->select()
            ->from("users")
            ->where("password = :pass AND login = :log AND status = 0 AND role = 1",array(":pass"=>$this->generatePass($_POST["password"]),":log"=>$_POST["username"]))
            ->queryRow();
        //print_r($query);
        $response = array();

        if($query["userId"] != ""){
            $query["point"] = $func->getPoint($query["point"]);
            $response = $query;
            $response["success"] = true;
        }
        else{
            $response["success"] = false;
        }
        echo json_encode($response);

    }

    public function actionRegistrations(){
        $temp = 0;
//        $_POST["region"] = "sadadadqew";
//        $_POST["district"] = "sadadaqwed";
//        $_POST["desc"] = "sadasdadad";
//        $_POST["username"] = "asd";
//        $_POST["password"] = "1234";
//        $_POST["name"] = "sadafsdfdad";
//        $_POST["lastname"] = "sawerdadad";
//        $_POST["surname"] = "sasafdadad";
//        $_POST["phone"] = "+998973203171";
        if(!empty($_POST["username"]) || !empty($_POST["password"]) || !empty($_POST["name"]) || !empty($_POST["lastname"]) || !empty($_POST["surname"]) || !empty($_POST["region"]) || !empty($_POST["district"]) || !empty($_POST["desc"]) || !empty($_POST["phone"])){
            $point = Yii::app()->db->createCommand()->insert("point", array(
                'region' => $_POST["region"],
                'district' => $_POST["district"],
                'desc' => $_POST["desc"]
            ));
            $point = Yii::app()->db->getLastInsertId();
            if(!empty($point)) {
                $temp = Yii::app()->db->createCommand()->insert("users", array(
                    'login' => $_POST["username"],
                    'password' => $this->generatePass($_POST["password"]),
                    'name'=>$_POST["name"],
                    'surname'=>$_POST["surname"],
                    'lastname'=>$_POST["lastname"],
                    'phone' => $_POST["phone"],
                    'point' => $point,
                    'role' => 0
                ));
            }
            $response = array();
            if($temp == 1)
                $response["success"] = true;
            else
                $response["success"] = false;
        }
        else{
            $response["success"] = false;
        }
        echo json_encode($response);
    }

    public function generatePass($pass){
        return md5(md5($pass));
    }


    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Users the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model=Users::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Users $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='users-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
