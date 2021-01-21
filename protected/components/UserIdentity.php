<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    protected $_id;
    protected $_name;
    protected $_role;
    public $_authType;
    protected $_username;
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
        $user = Users::model()->find('LOWER(login)=:username', array(':username'=>strtolower($this->username)));
        if(($user===null)||(md5(md5($this->password))!==$user->password))
        {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        }
        else
        {
            $this->_id = $user->userId;
            $this->_username = $user->login;
            $this->_role = $user->role;
            $this->_authType = 'auth';
            $this->_name = $user->surname." ".$user->name." ".$user->lastname;

            $this->errorCode = self::ERROR_NONE;
        }
        return !$this->errorCode;
        /*
		if($this->username===$model->username && $this->password===$model->password)
			$this->errorCode=self::ERROR_NONE;
		else
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		return !$this->errorCode;
        */

    }

    public function getId()
    {
        return $this->_id;
    }

    public function getRole()
    {
        return $this->_role;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function getLogin()
    {
        return $this->_username;
    }

    public function getAuthType(){
        return $this->_authType;
    }
}