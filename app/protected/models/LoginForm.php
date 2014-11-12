<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'AccountController'.
 */
class LoginForm extends CFormModel {

    public $email;
    public $password;
    public $rememberMe;
    private $_identity;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            // email is required
            array('email','required'),
            // email is required
            array('password', 'required', 'on' => 'signin'),
            // email should be email
            array('email','match', 'pattern' => '/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/', 'message' => 'Email address is not valid.'),
            // email should exists
            array('email', 'emailExist', 'on' => 'forgot'),
            // email should have status 1
            array('email', 'checkStatus', 'on' =>'forgot'),
            // rememberMe needs to be a boolean
            array('rememberMe', 'boolean', 'on' => 'signin'),
            // password needs to be authenticated
            array('password', 'authenticate', 'on' => 'signin'),
        );
    }

    
    
    
    /*
     * Check email exists
     * Checks in the database
     * @return boolean when 
     */
     public function emailExist() {
        if (!$this->hasErrors()) {
            $user = User::model()->findByAttributes(array('email' => $this->email));
            if ($user === null) {
                // no user found
                // $this->addError('email', 'Email address does not exist.');
                $this->addError('email', 'There is no user account registered with that email address. Please re-enter or email support@eatads.com if you seek further help.');
                //return false;
            } 
            /* else {
              return true;
              } */
        }
    }

    /*
     * Check status
     * Checks in the database
     * @return boolean when 
     */

    public function checkStatus() {
        if (!$this->hasErrors()) {
            $user = User::model()->findByAttributes(array('email' => $this->email));
            if($user->status == 0) {
                $this->addError('email', 'Your account is currently inactive. If this is a mistake email us at support@eatads.com');
            }
            /* else {
              return true;
              } */
        }
    }
    
    /*public function emailValid() {
        if (!$this->hasErrors()) {
            if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                $this->addError('email', 'Email address is not valid.');
            } else {
                $regMatch = preg_match($pattern, substr($subject,3), $matches, PREG_OFFSET_CAPTURE);
                if($regMatch) {
                    $this->addError('email', 'Email address is not valid.');
                }
            }
        }
    }*/
    
    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'email' => 'Email',
            'rememberMe' => 'Remember me next time',
        );
    }

    /**
     * Authenticates the password.
     * This is the 'authenticate' validator as declared in rules().
     */
    public function authenticate($attribute, $params) {
        if (!$this->hasErrors()) {
            $this->_identity = new UserIdentity($this->email, $this->password);
            if (!$this->_identity->authenticate())
                $this->addError('password', 'Incorrect email or password.');
        }
    }

    /**
     * Logs in the user using the given email and password in the model.
     * @return boolean whether login is successful
     */
    public function login() {
        if ($this->_identity === null) {
            $this->_identity = new UserIdentity($this->email, $this->password);
            $this->_identity->authenticate();
        }
        if ($this->_identity->errorCode === UserIdentity::ERROR_NONE) {
            //echo '<pre>'; var_dump($this->_identity); echo '</pre>'; die();
            $duration = $this->rememberMe ? 3600 * 24 * 30 : 0; // 30 days
            Yii::app()->user->login($this->_identity, $duration);
            return true;
        }
        else
            return false;
    }

    /**
     * Logs in the user using User Email.
     * @return boolean whether login is successful
     */
    public function loginWithoutPassword($userEmail) {
        if ($this->_identity === null) {
            $this->_identity = new UserIdentity($userEmail, false);
            $this->_identity->authenticate($setPassword = false);
        }
        if ($this->_identity->errorCode === UserIdentity::ERROR_NONE) {
            $duration = $this->rememberMe ? 3600 * 24 * 30 : 0; // 30 days
            Yii::app()->user->login($this->_identity, $duration);
            return true;
        }
        else
            return false;
    }

}
