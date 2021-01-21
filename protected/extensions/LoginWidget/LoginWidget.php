<?php 
class LoginWidget extends CWidget
{
    /**
     * Is called when $this->beginWidget() is called
     */
    public function init()
    {

    }
	
	public $type = 'login';
    /**
     * Is called when $this->endWidget() is called
     */
    public function run()
    {
		if($this->type=='login'){
			$model = new LogForm;
        	$this->render('login', array('model'=>$model));	
		}
        
    }
}
?>