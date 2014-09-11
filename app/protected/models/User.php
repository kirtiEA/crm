<?php

Yii::import('application.models.base.BaseUser');

class User extends BaseUser {

    public $confirmPassword;
    public $id;
    public $email;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            array('email,phonenumber,password', 'required', 'on' => 'create', 'message' => 'All fields are required'),
            array('email', 'required', 'on' => 'signupScenario'),
            array('email', 'email', 'on' => 'signupScenario', 'message' => 'Email address is not valid.'),
            array('email', 'unique', 'attributeName' => 'email', 'caseSensitive' => 'false', 'className' => 'User', 'on' => 'signupScenario', 'message' => 'Email address already exists.'),
            array('email', 'match', 'pattern' => '/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/', 'on' => 'signupScenario', 'message' => 'Email address is not valid.'),
            // username and password are required
            array('fname, lname, phonenumber', 'required', 'on' => 'editProfile'),
            array('phonenumber', 'numerical', 'integerOnly' => true, 'on' => 'editProfile'),
            array('fname', 'match', 'pattern' => '/^([a-zA-Z ])+$/', 'message' => Yii::t("signup", "First name must contains letters only"), 'on' => 'editProfile'),
            array('lname', 'match', 'pattern' => '/^([a-zA-Z ])+$/', 'message' => Yii::t("signup", "Last name must contains letters only"), 'on' => 'editProfile'),
            array('fname, lname', 'length', 'max' => 20, 'min' => 2, 'on' => 'editProfile'),
            // pattern match with allow empty is not working so add js validtion on min length
            array('password', 'length', 'min' => 6, 'allowEmpty' => true, 'on' => 'editProfile'),
            array('confirmPassword', 'length', 'min' => 6, 'allowEmpty' => true, 'on' => 'editProfile'),
            array('confirmPassword', 'compare', 'compareAttribute' => 'password', 'allowEmpty' => true, 'message' => Yii::t("signup", "Passwords do not match."), 'on' => 'editProfile'),
            array('password', 'validatePassword', 'on' => 'editProfile'),
            array('confirmPassword', 'validateConfirmPassword', 'on' => 'editProfile'),
            array('fname, lname, email, phonenumber, password, confirmPassword', 'required', 'on' => 'createProfile'),
            array('phonenumber', 'numerical', 'integerOnly' => true, 'on' => 'createProfile'),
            array('fname', 'match', 'pattern' => '/^([a-zA-Z ])+$/', 'message' => Yii::t("signup", "First name must contains letters only"), 'on' => 'createProfile'),
            array('lname', 'match', 'pattern' => '/^([a-zA-Z ])+$/', 'message' => Yii::t("signup", "Last name must contains letters only"), 'on' => 'createProfile'),
            //array('username', 'length', 'max' => 20, 'min' => 6, 'on' => 'createProfile'),
            //array('username', 'match', 'pattern'=>'/^([a-zA-Z0-9_.])+$/', 'message' => Yii::t("signup", "Please use only letters (a-z), numbers, periods and underscores"), 'on' => 'createProfile'),                
            //array('username', 'match', 'pattern'=>'/^[a-zA-Z]([a-zA-Z0-9_.])+$/', 'message' => Yii::t("signup", "Username must start with letters (a-z)"), 'on' => 'createProfile'),                
            //array('username', 'match', 'pattern'=>'/^[a-zA-Z](?!_*\_{2})(?!.*\.{2})([a-zA-Z0-9_.](?!_*\_{2})(?!.*\.{2}))+$/', 'message' => Yii::t("signup", "Username cannot have continuous dots and underscores"), 'on' => 'createProfile'),                                
            array('fname, lname', 'length', 'max' => 20, 'min' => 2, 'on' => 'createProfile'),
            array('email', 'email', 'on' => 'createProfile', 'message' => 'Email address is not valid.'),
            array('email', 'unique', 'attributeName' => 'email', 'caseSensitive' => 'false', 'className' => 'User', 'on' => 'createProfile', 'message' => 'Email address already exists.'),
            array('email', 'match', 'pattern' => '/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/', 'message' => 'Email address is not valid.'),
            //array('username', 'unique', 'attributeName'=>'username', 'caseSensitive' => 'false', 'className'=>'User', 'on' => 'createProfile'),
            array('password', 'length', 'min' => 6, 'on' => 'createProfile'),
            array('password', 'match', 'pattern' => '/^([A-Za-z])+([0-9])+|([0-9])+([A-Za-z])+$/', 'message' => Yii::t("signup", "Password must be alphanumeric"), 'on' => 'createProfile'),
            array('confirmPassword', 'length', 'min' => 6, 'on' => 'createProfile'),
            array('confirmPassword', 'compare', 'compareAttribute' => 'password', 'message' => Yii::t("signup", "Passwords do not match"), 'on' => 'createProfile'),
            // subscribe needs to be a boolean
            array('subscribe', 'boolean'),
            array('username,password,phonenumber', // allows to a create a new user
                'required', 'on' => 'createScenario', 'message' => 'All fileds are Required'),
        );
    }

    public function validatePassword($attribute, $params) {
        if ($_POST['User']['password'] != '' && strlen($_POST['User']['password']) < 6) {
            $this->addError($attribute, 'Password is too short (minimum is 6 characters).');
            return;
        }
        if ($_POST['User']['password'] != '' && !preg_match('/^([A-Za-z])+([0-9])+|([0-9])+([A-Za-z])+$/', $_POST['User']['password'])) {
            $this->addError($attribute, 'Password must be alphanumeric.');
        }
    }

    public function validateConfirmPassword($attribute, $params) {
        if ($_POST['User']['password'] != '' && $_POST['User']['confirmPassword'] == '' && strlen($_POST['User']['confirmPassword']) < 6) {
            $this->addError($attribute, 'Confirm Password is too short (minimum is 6 characters).');
            return;
        }
        if (($_POST['User']['password'] != '' && $_POST['User']['confirmPassword'] == '') || ($_POST['User']['password'] == '' && $_POST['User']['confirmPassword'] != '')) {
            $this->addError($attribute, 'Passwords do not match.');
        }
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'fname' => 'First name',
            'lname' => 'Last name',
            'email' => 'Email',
            'username' => 'Username',
            'password' => 'Password',
            'phonenumber' => 'Phone number',
            'active' => 'Active',
            'status' => 'Status',
            'subscribe' => 'Subscribe',
            'lastlogin' => 'Last login',
            'datecreated' => 'Date created',
            'datemodified' => 'Date modified',
        );
    }

    /**
     * This method will return the user data with specified columns
     * @param int $id     
     * @param string $columns (optional)
     * @return array
     */
    public static function getUserAttributeById($id, $columns = '') {
        $criteria = new CDbCriteria();
        if (strlen($columns)) {
            $criteria->select = $columns;
        } else {
            $criteria->select = '*';
        }

        $criteria->condition = 'id=:id';
        $criteria->params = array(':id' => $id);
        $data = self::model()->find($criteria);
        return $data;
    }

    public static function getUserProduct($userData) {
        $email = $userData['email'];
        $productId = $userData['productid'];

        $criteria = new CDbCriteria();
        $criteria->condition = 'email=:email';
        $criteria->params = array(':email' => $email);
        $data = self::model()->find($criteria);
        return $data;
    }

    // Admin Dashboard Function
    public static function getRoleIdBasedCountByDateRange($startDate, $endDate, $roleId) {
        $criteria = new CDbCriteria();
        $criteria->select = 'id';
        $criteria->with = 'userRoles';
        $criteria->condition = "datecreated BETWEEN :startDate AND :endDate AND roleid = :roleId";
        $criteria->params = array(':startDate' => $startDate, ':endDate' => $endDate, ':roleId' => $roleId);
        return $result = self::model()->count($criteria);
    }

    public static function getRolebasedChartData($startDate, $endDate, $duration, $roleId) {
        if ($duration == "1") { // Weekly
            return User::getRoleBasedCountWeeklyByDateRange($startDate, $endDate, $roleId);
        } elseif ($duration == "2") { // Monthly
            return User::getRoleBasedCountMonthlyByDateRange($startDate, $endDate, $roleId);
        } elseif ($duration == "3") { // Yearly
            return User::getRoleBasedCountYearlyByDateRange($startDate, $endDate, $roleId);
        }
    }

    // Admin Dashboard function Chart
    public static function getRoleBasedCountWeeklyByDateRange($startDate, $endDate, $roleId) {
        $weeklyData = Yii::app()->db->createCommand("CALL userWeeklySignup ($roleId, '$startDate', '$endDate')")->queryAll();

        $weekArr = JoyUtilities::getNoOfWeek($startDate, $endDate);
        $weekListingData = JoyUtilities::formatAdminGraphData($weeklyData, $weekArr);

        return $weekListingData;
    }

    public static function getRoleBasedCountMonthlyByDateRange($startDate, $endDate, $roleId) {
        $monthlyData = Yii::app()->db->createCommand("CALL userMonthlySingup ($roleId, '$startDate', '$endDate')")->queryAll();

        $monthArr = JoyUtilities::getNoOfMonth($startDate, $endDate);
        $monthlyListingData = JoyUtilities::formatAdminGraphData($monthlyData, $monthArr);

        return $monthlyListingData;
    }

    public static function getRoleBasedCountYearlyByDateRange($startDate, $endDate, $roleId) {
        $yearlyData = Yii::app()->db->createCommand("CALL userYearlySignup ($roleId, '$startDate', '$endDate')")->queryAll();

        $yearArr = JoyUtilities::getNoOfYear($startDate, $endDate);
        $yearlyListingData = JoyUtilities::formatAdminGraphData($yearlyData, $yearArr);

        return $yearlyListingData;
    }

    // Admin Dashboard Function
    public static function getSignUpCountByDateRange($startDate, $endDate) {
        $criteria = new CDbCriteria();
        $criteria->select = 'id';
        $criteria->condition = "datecreated BETWEEN :startDate AND :endDate";
        $criteria->params = array(':startDate' => $startDate, ':endDate' => $endDate);
        return $result = self::model()->count($criteria);
    }

    public static function getSignupChartData($startDate, $endDate, $duration) {
        if ($duration == "1") { // Weekly
            return User::getSignupCountWeeklyByDateRange($startDate, $endDate);
        } elseif ($duration == "2") { // Monthly
            return User::getSignupCountMonthlyByDateRange($startDate, $endDate);
        } elseif ($duration == "3") { // Yearly
            return User::getSignupCountYearlyByDateRange($startDate, $endDate);
        }
    }

    // Admin Dashboard function Chart
    public static function getSignupCountWeeklyByDateRange($startDate, $endDate) {
        $weeklyData = Yii::app()->db->createCommand("CALL allUserWeeklySignup ('$startDate', '$endDate')")->queryAll();

        $weekArr = JoyUtilities::getNoOfWeek($startDate, $endDate);
        $weekListingData = JoyUtilities::formatAdminGraphData($weeklyData, $weekArr);

        return $weekListingData;
    }

    public static function getSignupCountMonthlyByDateRange($startDate, $endDate) {
        $monthlyData = Yii::app()->db->createCommand("CALL allUserMonthlySignup ('$startDate', '$endDate')")->queryAll();

        $monthArr = JoyUtilities::getNoOfMonth($startDate, $endDate);
        $monthlyListingData = JoyUtilities::formatAdminGraphData($monthlyData, $monthArr);

        return $monthlyListingData;
    }

    public static function getSignupCountYearlyByDateRange($startDate, $endDate) {
        $yearlyData = Yii::app()->db->createCommand("CALL allUserYearlySignup ('$startDate', '$endDate')")->queryAll();

        $yearArr = JoyUtilities::getNoOfYear($startDate, $endDate);
        $yearlyListingData = JoyUtilities::formatAdminGraphData($yearlyData, $yearArr);

        return $yearlyListingData;
    }

    // Admin Dashboard Function
    public static function getActiveUserCountByDateRange($startDate, $endDate) {
        $criteria = new CDbCriteria();
        $criteria->select = 'id';
        $criteria->condition = "lastlogin BETWEEN :startDate AND :endDate AND active = 1";
        $criteria->params = array(':startDate' => $startDate, ':endDate' => $endDate);
        return $result = self::model()->count($criteria);
    }

    public static function getLoggedinUserChartData($startDate, $endDate, $duration) {
        if ($duration == "1") { // Weekly
            return User::getLoggedinUserCountWeeklyByDateRange($startDate, $endDate);
        } elseif ($duration == "2") { // Monthly
            return User::getLoggedinUserCountMonthlyByDateRange($startDate, $endDate);
        } elseif ($duration == "3") { // Yearly
            return User::getLoggedinUserCountYearlyByDateRange($startDate, $endDate);
        }
    }

    // Admin Dashboard function Chart
    public static function getLoggedinUserCountWeeklyByDateRange($startDate, $endDate) {
        $weeklyData = Yii::app()->db->createCommand("CALL getWeeklyActiveUser ('$startDate', '$endDate')")->queryAll();

        $weekArr = JoyUtilities::getNoOfWeek($startDate, $endDate);
        $weekLoggedinUserData = JoyUtilities::formatAdminGraphData($weeklyData, $weekArr);

        return $weekLoggedinUserData;
    }

    public static function getLoggedinUserCountMonthlyByDateRange($startDate, $endDate) {
        $monthlyData = Yii::app()->db->createCommand("CALL getMonthlyActiveUser ('$startDate', '$endDate')")->queryAll();

        $monthArr = JoyUtilities::getNoOfMonth($startDate, $endDate);
        $monthlyLoggedinUserData = JoyUtilities::formatAdminGraphData($monthlyData, $monthArr);

        return $monthlyLoggedinUserData;
    }

    public static function getLoggedinUserCountYearlyByDateRange($startDate, $endDate) {
        $yearlyData = Yii::app()->db->createCommand("CALL getYearlyActiveUser ('$startDate', '$endDate')")->queryAll();

        $yearArr = JoyUtilities::getNoOfYear($startDate, $endDate);
        $yearlyLoggedinUserData = JoyUtilities::formatAdminGraphData($yearlyData, $yearArr);

        return $yearlyLoggedinUserData;
    }

    // Admin Dashboard Function
    public static function getInactiveUserCountByDefault($startDate, $endDate) {
        $inactiveUserData = Yii::app()->db->createCommand("SELECT (SELECT COUNT(*) FROM `User` WHERE active = 1) - (SELECT COUNT(*) FROM `User` WHERE lastlogin BETWEEN '" . $startDate . "' AND '" . $endDate . "' AND active = 1) AS cnt")->queryRow();
        return $inactiveUserData['cnt'];
    }

    // Admin Dashboard Function
    public static function getInactiveUserCountByDateRange($startDate, $endDate) {
        $inactiveUserData = Yii::app()->db->createCommand("SELECT (SELECT COUNT(*) FROM `User` WHERE active = 1 AND dateactivated <= '" . $endDate . "') - (SELECT COUNT(*) FROM `User` WHERE lastlogin BETWEEN '" . $startDate . "' AND '" . $endDate . "' AND active = 1) AS cnt")->queryRow();
        return $inactiveUserData['cnt'];
    }

    // used in massupload - admin
    public static function getOwnerEmail() {
        $criteria = new CDbCriteria();
        $criteria->select = 'id,email';
        $criteria->with = 'userRoles';
        $criteria->condition = "status= :status AND roleid = :roleId";
        $criteria->params = array(':status' => 1, ':roleId' => 3);
        return $result = CHtml::listData(self::model()->findAll($criteria), 'id', 'email');
    }

    public static function getResetPasswordMail() {
        $criteria = new CDbCriteria();
        $criteria->select = 'id, email';
        $criteria->condition = "password = '' AND resetpassword = 0";
        return $result = self::model()->findAll($criteria);
    }

    /*
     * 
     */

    public static function changePassword($id, $pwd) {

        $cmd = Yii::app()->db->createCommand("Select ");
        $cmd = $cmd->update('User', array(
            'password' => $pwd,), 'id=:id', array(':id' => $id));
    }

    /*
     * returns the model selected of user table.
     * the model has sql run over it
     * sql query-> select fname,lname,email,phonenumber from user where active=1 AND status=1
     */

    public static function fetchUserDetails() {
        $criteria = new CDbCriteria();
        //$criteria->concat = 'fname, lname as name';
        $criteria->select = 'id, fname, lname, email, phonenumber';
        $criteria->condition = 'active=:active AND status=:status';
        $criteria->params = array(':active' => 1, ':status' => 1);
        $model = User::model()->findAll($criteria);
        return $model;
    }

    public static function fetchCompanyUsers($companyid, $roleid = null) {
        $sql = 'select u.id, u.username as name from User u '
                . 'where companyid = ' . $companyid;
        return Yii::app()->db->createCommand($sql)->queryAll();
    }

    public static function fetchCompanyUsersModel($cid) {
        $criteria = new CDbCriteria();
        //$criteria->concat = 'fname, lname as name';
        $criteria->select = 'id, fname, lname, email, phonenumber';
        $criteria->condition = 'active=:active AND status=:status AND companyid =:companyid';
        $criteria->params = array(':active' => 1, ':status' => 1, ':companyid' => $cid);
        $model = User::model()->findAll($criteria);
        return $model;
    }

    public static function checkUniqueUsername($id, $username) {
        $sql= 'SELECT count(*) as cnt from User where username like \'' . $username . '\' and companyid = ' . $id;
        return Yii::app()->db->createCommand($sql)->queryRow();
    }
}
