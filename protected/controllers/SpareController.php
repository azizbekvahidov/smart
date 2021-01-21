<?php

class SpareController extends Controller
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
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','struct','spareList','semiSpareList','semiSpare','semiSpareView','semiSpareCreate','semiSpareUpdate','semiSpareDelete'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Spare;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Spare']))
		{
			$model->attributes=$_POST['Spare'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->spareId));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Spare']))
		{
			$model->attributes=$_POST['Spare'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->spareId));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Spare');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Spare('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Spare']))
			$model->attributes=$_GET['Spare'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Spare the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Spare::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Spare $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='spare-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionStruct(){

	    if(isset($_POST["struct"])){
            foreach ($_POST["struct"]["spare"] as $key => $item) {
                Yii::app()->db->createCommand()->insert("struct",array(
                    "phoneId" => $_POST["struct"]["phoneId"],
                    'spareId' => $item,
                    'cnt' => $_POST["struct"]["cnt"][$key],
                    'SAPCode' => $_POST["struct"]["code"][$key],
                    'buxName' => $_POST["struct"]["buxName"][$key]
                ));
	        }
        }
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("phone p")
            ->queryAll();
        $list = array();
	    $this->render("struct",array(
            'model' => $model
        ));
    }

    public function actionSpareList(){
	    $model = Yii::app()->db->createCommand()
	        ->select()
	        ->from("spare")
	        ->queryAll();
	    echo json_encode($model,true);
    }

    public function actionSemiSpareList(){
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("semiSpare")
            ->queryAll();
        echo json_encode($model,true);
    }

    public function actionSemiSpare(){
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("semispare")
            ->queryAll();
        $this->render("semiSpare/admin",array(
            'model' => $model
        ));
    }

    public function actionSemiSpareCreate(){
        if(isset($_POST["semiSpare"])){
            $func = new Functions();
            Yii::app()->db->createCommand()->insert("semispare",array(
                "name" => $_POST["semiSpare"]["name"],
            ));
            $lastId = Yii::app()->db->getLastInsertID();
            $func->setLogs($lastId,"insert",$_POST["semiSpare"]["name"],"semiSpare");
            foreach ($_POST["semiSpare"]["spare"] as $key => $item) {
                Yii::app()->db->createCommand()->insert("semisparestruct",array(
                    "spareId" => $item,
                    "semispareId" => $lastId,
                    "cnt" => $_POST["semiSpare"]["cnt"][$key],
                ));
                $func->setLogs(Yii::app()->db->getLastInsertID(),"insert","semispareId:".$id.",spareId:".$item,"semiSpareStruct");
            }
            $this->redirect("semiSpare");
        }
        $this->render("semiSpare/create");
    }

    public function actionSemiSpareUpdate($id){
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("semiSpare")
            ->where("status != 1 and semispareId = :id",array(":id"=>$id))
            ->queryRow();

        $Struct = Yii::app()->db->createCommand()
            ->select()
            ->from("semisparestruct")
            ->where("semispareId = :id",array(":id"=>$model["semispareId"]))
            ->queryAll();
        $spareList = Yii::app()->db->createCommand()
            ->select()
            ->from("spare")
            ->queryAll();
        if(isset($_POST["semiSpare"])){
            $func = new Functions();
            Yii::app()->db->createCommand()->update("semispare",array(
                "name" => $_POST["semiSpare"]["name"],
            ),"semiSpareId = :id",array(":id"=>$id));
            $lastId = Yii::app()->db->getLastInsertID();
            $func->setLogs($id,"update",$_POST["semiSpare"]["name"],"semiSpare");
            Yii::app()->db->createCommand()->delete("semisparestruct","semispareId = :id",array(":id"=>$id));
            $func->setLogs(0,"delete","delete for update","semiSpareStruct");
            foreach ($_POST["semiSpare"]["spare"] as $key => $item) {
                Yii::app()->db->createCommand()->insert("semisparestruct",array(
                    "spareId" => $item,
                    "semispareId" => $id,
                    "cnt" => $_POST["semiSpare"]["cnt"][$key],
                ));
                $func->setLogs(Yii::app()->db->getLastInsertID(),"insert","semispareId:".$id.",spareId:".$item,"semiSpareStruct");
            }
            $this->redirect("semiSpare");
        }
        $this->render("semiSpare/update",array(
            'model' => $model,
            'struct' => $Struct,
            'spareList' => $spareList
        ));
    }

    public function actionSemiSpareDelete($id){
        $func = new Functions();
        Yii::app()->db->createCommand()->update("semiSpare",array(
            'status' => 1
        ),"semispareId = :id",array(":id"=>$id));
        $func->setLogs($id,"delete","deleted semiSpare","semiSpare");

    }

    public function actionSemiSpareView($id){

        $Struct = Yii::app()->db->createCommand()
            ->select()
            ->from("semisparestruct ss")
            ->join("spare s","ss.spareId = s.spareId")
            ->where("ss.semispareId = :id",array(":id"=>$id))
            ->queryAll();
        $this->renderPartial("semiSpare/view",array(
            'struct' => $Struct,
        ));
    }


}
