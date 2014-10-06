<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{

    // Need to store the user's ID:
    private $_id;
    
	/**
	 * Authenticates a user.
	 * Makes sure if the email and password are both matched
	 * Authentication against database
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate($setPassword=true)
	{
        // if normal login check for status as well and
        // in case of signup->ver. w/o pass no need to check status
        $user = ($setPassword==true) 
                ? User::model()->findByAttributes(array('email'=>$this->username, 'status'=>1, 'active' => 1), 'companyid IS NOT NULL')
                : User::model()->findByAttributes(array('email'=>$this->username));
        // echo '<pre>';var_dump($user); echo '</pre>'; die();
        $ph = new PasswordHash(Yii::app()->params['phpass']['iteration_count_log2'], Yii::app()->params['phpass']['portable_hashes']);
        if($user === null) {
            // no user found
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } else if ($setPassword==true && !$ph->CheckPassword($this->password, $user->password)) {
            // invalid password
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } else {
            // okay
            $this->_id = $user->id;
            // set any user information that'll be available during the login session, used in layout/main.php
            $this->setState('name', ucfirst(strtolower($user->fname)));
            $this->setState('lname', ucfirst(strtolower($user->lname)));
            $this->setState('cid', $user->companyid);
            $this->setState('active', $user->active);
            $this->setState('status', $user->status);
            $this->setState('email', $user->email);
            // set user role id
            $roleId = JoyUtilities::getUserRoleId($user->id);
            $this->setState('roleId', $roleId); 
            
            $user->lastlogin = date('Y-m-d H:i:s');
            $user->save();
            //$this->setState('roles', JoyUtilities::getUserRoleId($user->id));
            $this->errorCode = self::ERROR_NONE;
        }
        return !$this->errorCode;
        /*$users=array(
			// username => password
			'demo'=>'demo',
			'admin'=>'admin',
		);
		if(!isset($users[$this->username]))
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		elseif($users[$this->username]!==$this->password)
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else
			$this->errorCode=self::ERROR_NONE;
		return !$this->errorCode;*/
	}
    public function getId()
    {
        return $this->_id;
    }    
}