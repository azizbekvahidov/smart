<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: sell.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
        if(Yii::app()->user->getAuthType() == 'cabinet')
            $this->redirect('/cabinet/index');
		// renders the view file 'protected/views/site/sell.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index',array(
        ));
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    $this->layout = '/layouts/cabinet';
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->renderPartial('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-Type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
        $model=new LoginForm;
        $reg = Yii::app()->user->getFlash('registration');

        $serviceName = Yii::app()->request->getQuery('service');
        if (isset($serviceName)) {
            /** @var $eauth EAuthServiceBase */
            $eauth = Yii::app()->eauth->getIdentity($serviceName);
            $eauth->redirectUrl = Yii::app()->user->returnUrl;
            $eauth->cancelUrl = $this->createAbsoluteUrl('site/login');

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
                $this->redirect(array('site/login'));
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
        if(isset($_POST['LoginForm']))
        {
            $model->attributes=$_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->returnUrl);
            $model->attributes=$_POST['LoginForm'];
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
        $this->render('login',array('model'=>$model, 'message'=>$reg));
	}

	public function actionFtq(){
	    $model = Yii::app()->db->createCommand()
	        ->select()
	        ->from("employee")
	        ->where("month(birthday) = :month and day(birthday) = :day",array(":month"=>date("m"),":day"=>date("d")))
	        ->queryAll();
	    $this->renderPartial("ftq",array(
            "model"=>$model
        ));
    }

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

	public function actionFtqDisplay(){
        $model = Yii::app()->db->createCommand()
            ->select()
            ->from("employee")
            ->where("month(birthday) = :month and day(birthday) = :day and status != 1",array(":month"=>date("m"),":day"=>date("d")))
            ->queryAll();
        $this->renderPartial("ftq",array(
            "model"=>$model
        ));
    }



    public function actionTemp(){
	    $this->render("temp");
    }

    public function actionStatistic(){
        $this->renderPartial("statistic");
    }

    public function actionGetData(){
        $date = date("Y-m-d");
        $data = array();
        $data["all"]["produce"] = 0;
        $data["all"]["plan"] = 0;
        $produce = Yii::app()->db->CreateCommand()
            ->select("count(p.phoneId) as cnt, ph.model, p.phoneId")
            ->from("produce p")
            ->join("phone ph","ph.phoneId = p.phoneId")
            ->where("date(p.produceDate) = :date",array(":date"=>$date))
            ->group("p.phoneId")
            ->queryAll();
        $plan = Yii::app()->db->CreateCommand()
            ->select("p.cnt, ph.model")
            ->from("planning p")
            ->join("phone ph","ph.phoneId = p.phoneId")
            ->where("date(p.planningDate) = :date and planType = 'current'",array(":date"=>$date))
            ->queryAll();
        foreach ($produce as $item) {
            $data["model"][$item["model"]] = 0;
            $data["produce"][$item["model"]] = $item["cnt"];
            $data["all"]["produce"] = $data["all"]["produce"] + $item["cnt"];
            $tempplan = Yii::app()->db->CreateCommand()
                ->select("p.cnt, ph.model")
                ->from("planning p")
                ->join("phone ph","ph.phoneId = p.phoneId")
                ->where("date(p.planningDate) = :date AND p.phoneId = :id",array(":date"=>'Y-m-d',":id"=>$item["phoneId"]))
                ->queryRow();
            if(empty($tempplan)){
                $data["plan"][$item["model"]] = 0;
                $data["all"]["plan"] = $data["all"]["plan"] + 0;
            }
        }
        foreach ($plan as $item) {
            if(!isset($data["produce"][$item["model"]]))
                $data["produce"][$item["model"]] = 0;
            $data["model"][$item["model"]] = 0;
            $data["plan"][$item["model"]] = $item["cnt"];
            $data["all"]["plan"] = $data["all"]["plan"] + $item["cnt"];
        }
        $curPhone = Yii::app()->db->CreateCommand()
            ->select("value")
            ->from("settings")
            ->where("name = 'curPhone'")
            ->queryRow();
        $data["scroll"] = $curPhone["value"];
        echo json_encode($data);
    }

    public function actionFtqData(){
        $time = date("Y-m-d");//strtotime('today');
        if(isset($_POST["month"])){
            $time = $_POST["month"];
        }
        $dateArray = array();
        $model = array();
        $skdDetail = array();
        $result = array();
        $skdError = array();
        $producePlan = array();
        $repairPlan = array();
        $model[0]["name"] = "SKD";
        $model[1]["name"] = "Производстенные";
        $model[2]["name"] = "Все";
        //формирование выполненного плана

        // формирование данных для ftq таблицы
        $result["ftq"]["producePercentDiag"][0]["name"] = " ";
        $result["ftq"]["repairPercentDiag"][0]["name"] = " ";
        $lastday = date('t',$time);
        for ($i = 1; $i <= $lastday; $i++){
            $curDay = (date("Y",strtotime($time)))."-".date("m",strtotime($time))."-".$i;
            $officialplan = Yii::app()->db->createCommand()
                ->select("sum(cnt) as cnt")
                ->from("planning")
                ->where("planningDate = :thisDate and planType = 'official'",array(":thisDate" => $curDay))
                ->queryRow();
            $currentplan = Yii::app()->db->createCommand()
                ->select("sum(cnt) as cnt")
                ->from("planning")
                ->where("planningDate = :thisDate and planType = 'current'",array(":thisDate" => $curDay))
                ->queryRow();
            $produce = Yii::app()->db->CreateCommand()
                ->select("count(*) as cnt")
                ->from("produce")
                ->where("date(produceDate) = :date",array(":date"=>$curDay))
                ->queryRow();
            $phones = "";
            $end = Yii::app()->db->createCommand()
                ->select("count(p.phoneId) as cnt")
                ->from("produce p")
                ->join("phone ph","ph.phoneId = p.phoneId")
                ->where("date(p.produceDate) = :curDate",array(":curDate"=>$curDay))
                ->queryRow();
            $phoneModel = Yii::app()->db->createCommand()
                ->select("p.model")
                ->from("register r")
                ->join("phone p","p.phoneId = r.phoneId")
                ->where("date(errorOtkDate) = :curDate AND cause is not null  AND r.solve != 'Vxodnoy'",array(":curDate" => $curDay))
                ->group("p.phoneId")
                ->queryAll();
            foreach ($phoneModel as $key => $item) {
                if($key > 0){
                    $phones .= "/ ".$item["model"];
                } else{
                    $phones = $item["model"];
                }
            }
            $cnt = Yii::app()->db->createCommand()
                ->select("count(*) as cnt")
                ->from("register")
                ->where("date(errorOtkDate) = :curDate AND cause is not null  AND solve != 'Vxodnoy'",array(":curDate" => $curDay))
                ->queryRow();
            $oficcialproducePercent = number_format((!empty($officialplan["cnt"]) ? 100*$produce["cnt"]/(($officialplan["cnt"] == 0) ? 1 : $officialplan["cnt"]) : 0),0, ",", " ");
            $currentproducePercent = number_format((!empty($currentplan["cnt"]) ? 100*$produce["cnt"]/(($currentplan["cnt"] == 0) ? 1 : $currentplan["cnt"]) : 0),0, ",", " ");
            $repairPercent = number_format((!empty($produce["cnt"]) ? (100-($cnt["cnt"]/$produce["cnt"])*100): 0),0, ",", " ");

            $result["days"][$i-1] = $i;
            $result["ftq"]["phoneModel"][$i] = $phones;
            $result["ftq"]["officialplan"][$i] = (!empty($officialplan["cnt"]) ? $officialplan["cnt"] : 0);
            $result["ftq"]["currentplan"][$i] = (!empty($currentplan["cnt"]) ? $currentplan["cnt"] : 0);
            $result["ftq"]["produce"][$i] = (!empty($produce["cnt"]) ? $produce["cnt"] : 0);
            $result["ftq"]["oficcialproducePercent"][$i] = $oficcialproducePercent;
            $result["ftq"]["currentproducePercent"][$i] = $currentproducePercent;
            $result["ftq"]["repairPercent"][$i] = $repairPercent;
            $result["ftq"]["repairCnt"][$i] = $cnt["cnt"];
            $result["ftq"]["producePercentDiag"][0]["data"][$i-1] = floatval($oficcialproducePercent);
            $result["ftq"]["producePercentDiag"][1]["data"][$i-1] = floatval($currentproducePercent);
            $result["ftq"]["repairPercentDiag"][0]["data"][$i-1] = floatval($repairPercent);
            $result["ftq"]["producePercentDiag"][0]["name"] = "Официальный";
            $result["ftq"]["producePercentDiag"][1]["name"] = "Текущий";
            $result["ftq"]["producePercentDiag"][0]["color"] = "#7CB5EC";
            $result["ftq"]["producePercentDiag"][1]["color"] = "#62FF5E";

        }
        $this->renderPartial("slide/ftqData",array(
            'repairStat'=>$result,
            'lastday' => $lastday

        ));
    }

    public function actionProduceError(){

        $this->renderPartial("slide/produceError");
    }

    public function actionGetProduceError(){
        $month = intval(date("m",strtotime("today")-86400));
        $year = intval(date("Y",strtotime("today")-86400));
        $lastday = date('t',strtotime('today')-86400);
        $produceError = array();
        $produceDetail = array();
        //Формирование производственных браков
        $produceModel = Yii::app()->db->createCommand()
            ->select("count(*) as cnt,e.descUz,date(r.errorOtkDate) as days")
            ->from("register r")
            ->join("error e","e.errorId = r.errorIdOtk")
            ->where("r.cause = 'Ishlab chiqarish brak' AND month(r.errorOtkDate) = :curMonth AND year(r.errorOtkDate) = :curYear AND r.solve != 'Vxodnoy' AND r.solve != 'Vxodnoy'",array(":curMonth"=>$month,":curYear"=>$year))
            ->group("e.errorId, date(r.errorOtkDate)")
            ->order("count(*) desc")
            ->queryAll();
        foreach ($produceModel as $val) {
            $produceError[$val["descUz"]] = true;
            $produceDetail[intval(date("d",strtotime($val["days"])))][$val["descUz"]] = $val["cnt"];
        }
        $result["produceDetail"] = $produceDetail;
        $result["produceError"] = $produceError;
        $result["lastDay"] = $lastday;
        echo json_encode($result);
    }

    public function actionSkdError(){
        $this->renderPartial("slide/skdError");
    }
    public function actionGetSkdError(){

        $lastday = date('t',strtotime('today')-86400);

        $skdDetail = array();
        $skdError = array();
        $month = intval(date("m",strtotime("today")-86400));
        $year = intval(date("Y",strtotime("today")-86400));
        //Формирование заводских браков
        $skdModel = Yii::app()->db->createCommand()
            ->select("count(*) as cnt,e.descUz,date(r.errorOtkDate) as days")
            ->from("register r")
            ->join("error e","e.errorId = r.errorIdOtk")
            ->where("r.cause = 'SKD brak' AND month(r.errorOtkDate) = :curMonth AND year(r.errorOtkDate) = :curYear AND r.solve != 'Vxodnoy'",array(":curMonth"=>$month,":curYear"=>$year))
            ->group("e.errorId, date(r.errorOtkDate)")
            ->order("count(*) desc")
            ->queryAll();
        foreach ($skdModel as $val) {
            $skdError[$val["descUz"]] = true;
            $skdDetail[intval(date("d",strtotime($val["days"])))][$val["descUz"]] = $val["cnt"];
        }

        $result["skdDetail"] = $skdDetail;
        $result["skdError"] = $skdError;
        $result["lastDay"] = $lastday;
        echo json_encode($result);
    }
    public function actionRegister(){
        $dateArray = array();
        $model = array();
        $result = array();
        $model[0]["name"] = "SKD";
        $model[1]["name"] = "Производстенные";
        $model[2]["name"] = "Все";
        //формирование выполненного плана


        // Запрос на выборку зарегистрированных ошибок
        $registerDate = Yii::app()->db->createCommand()
            ->select("date(r.errorOtkDate) as curDate")
            ->from("register r")
            ->group("date(r.errorOtkDate)")
            ->order("date(r.errorOtkDate) DESC")
            ->limit(7)
            ->queryAll();
        foreach ($registerDate as $key => $item) {
            $skd = Yii::app()->db->createCommand()
                ->select("count(*) as cnt")
                ->from("register r")
                ->where("r.cause = 'SKD brak' AND date(r.errorOtkDate) = :curDate AND r.solve != 'Vxodnoy'",array(':curDate'=>$item["curDate"]))
                ->queryRow();
            $dateArray[$key] = $item["curDate"];
            $model[0]["data"][$key] = intval($skd["cnt"]);
            $model[2]["data"][$key] = $model[2]["data"][$key] + $skd["cnt"];

            $produce = Yii::app()->db->createCommand()
                ->select("count(*) as cnt")
                ->from("register r")
                ->where("r.cause = 'Ishlab chiqarish brak' AND date(r.errorOtkDate) = :curDate AND r.solve != 'Vxodnoy'",array(':curDate'=>$item["curDate"]))
                ->queryRow();
            $dateArray[$key] = $item["curDate"];
            $model[1]["data"][$key] = intval($produce["cnt"]);
            $model[2]["data"][$key] = $model[2]["data"][$key] + $produce["cnt"];
        }

        $result["dateArray"] = $dateArray;
        $result["model"] = $model;
        $this->renderPartial("slide/register",array(
            "repairStat" => $result
        ));
    }

    public function actionNewyear(){
        $model = Yii::app()->db->CreateCommand()
            ->select()
            ->from("employee")
            ->where("status != 1 and positionId < 5")
            ->queryAll();
        $first = 0;
        $last = 0;
        $emp = array();
        foreach ($model as $key => $val) {
            if($key == 0)$first = $val["employeeId"];
            $last = $val["employeeId"];
        }
        $this->renderPartial("year",array(
            'first' => $first,
            'last' => $last,
            'cnt' => count($model)
        ));
    }

    public function actionReceptionReport(){

        $temp = date('Y-m-d',strtotime($_POST["from"]));
        $res = array();
        $emp = Yii::app()->db->CreateCommand()
            ->select()
            ->from("action a")
            ->join("employee e","e.employeeId = a.employeeId")
            ->where("DATE(a.actionTime) = :date and a.actionType = 'reception'",array(":date"=>$temp))
            ->group("a.employeeId")
            ->queryAll();
        foreach ($emp as $item) {

            $model = Yii::app()->db->CreateCommand()
                ->select()
                ->from("action a")
                ->where("DATE(a.actionTime) = :date and a.actionType = 'reception' and a.employeeId = :id",array(":date"=>$temp,":id"=>$item["employeeId"]))
                ->queryAll();

            $res[$item["surname"]." ".$item["name"]] = $model;

        }
        $this->renderPartial("/site/slide/receptionReport",array(
            'res'=>$res
        ));
    }
    public function actionGetWinner(){
        $model = Yii::app()->db->CreateCommand()
            ->select()
            ->from("employee")
            ->where("employeeId = :id and status != 1 and positionId < 5",array(":id"=>$_POST["id"]))
            ->queryRow();

        echo json_encode($model);
    }

    public function actionGetreceptionReport(){
        $this->renderPartial('/site/slide/getReceptionReport');
    }

}