<?php

class AjaxController extends Controller {

    private function fetchUserReturnUrl() {
        
    }

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }
    
    public function accessRules() {
        return array(
            array('allow', // allow all users to perform actions
                'actions' => array('signup' ,'getlisting', 'getmarkers', 'vendordetails', 'retriveplan', 'getsitedetails', 'addinexistingplan', 'addplan', 'addfavorite', 'plandetail', 'deleteplanlisting','getmediatypes', 'uploadcontacts', 'vendorcontacts', 'updatevendorcontacts',
                    'PushAvailabilityMailsToQueue', 'MassUploadListingsForVendor'),
                'users' => array('*'),
            ),
        );
    }
    
    public function actionLogin() {
        $username = Yii::app()->request->getParam('usrn');
        $password = Yii::app()->request->getParam('pass');


        if (!Yii::app()->user->isGuest) {
            $returnUrl = fetchUserReturnUrl();
        } else {
            $model = new LoginForm;
            $model->setscenario('signin');   // set scenario for rules validation
            // if it is ajax validation request
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }

            // collect user input data
            if (isset($_POST['LoginForm'])) {
                $_POST['LoginForm'] = JoyUtilities::cleanInput($_POST['LoginForm']);
                $model->attributes = $_POST['LoginForm'];
                // validate user input and redirect to the previous page if valid
                if ($model->validate() && $model->login()) {
                    if (!empty($returnUrlParam)) {
                        $this->redirect($returnUrlParam);
                    } else {
                        JoyUtilities::redirectUser(Yii::app()->user->id);
                        $this->redirect(Yii::app()->user->returnUrl);
                    }
                }
            }
        }

        // return after login url
        echo $returnUrl;
    }

    public function actionAddsitetocampaign() {
        $this->render('addsitetocampaign');
    }

    public function actionAssignzonetouser() {
        $this->render('assignzonetouser');
    }

    public function actionManagesites() {
        $this->render('managesites');
    }

    public function actionSiteautocomplete() {
        $this->render('siteautocomplete');
    }

    public function actionUpdatetaskassignment() {
        $this->render('updatetaskassignment');
    }

    /*
     * update user password
     */
    public function actionUpdatepassword() {
        if(isset($_POST['id']) && isset($_POST['pwd']))
	{
            //echo 'entered here';
                $id=$_POST['id'];
                $pwd=$_POST['pwd'];
                //print_r($pwd);die();
                $model=  User::model()->findByPk($id);
                $ph = new PasswordHash(Yii::app()->params['phpass']['iteration_count_log2'], Yii::app()->params['phpass']['portable_hashes']);
                $password = $ph->HashPassword($pwd);
                $result = $ph->CheckPassword($pwd, $model->password);   
                //echo $result;
                if ($result) {
                    // Authorized
                } else {
                    // Error: Unauthorized
                }
		User::model()->changePassword($id, $password);
        }
        
    }
    
    /*
     * Create new user
     */
    /*public function actionCreate()
	{
		$model=new User;
                print_r($model); die();
                $role = Role::model()->findByPk(5);
                $model->datecreated=date("Y-m-d H:i:s");
                $model->datemodified=date("Y-m-d H:i:s");
                
                //print_r($today);die();
                //$userRole = Userrole::model()->insertRoles();
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if((isset($_POST['uname'])) && (isset($_POST['pwd'])) && (isset($_POST['phn'])))
		{
                    $uname=$_POST['uname'];
                    $pwd=$_POST['pwd'];
                    $phn=$_POST['phn'];
                    $name= strtok($name, " ");
                    $fname=$name[0];
                    $lname=$name[1];
                    $model->fname=$fname;
                    $model->lname=$lname;
                    $model->phonenumber=$phn;
                    $ph = new PasswordHash(Yii::app()->params['phpass']['iteration_count_log2'], Yii::app()->params['phpass']['portable_hashes']);
                    $password = $ph->HashPassword($pwd);
                    //User::model()->insertUser($model);
                    $result = $ph->CheckPassword($pwd, $model->password);   
                    //echo $result;
                    if ($result) {
                        // Authorized
                    } else {
                        // Error: Unauthorized
                    }
                    $model->password=$password;
                    Userrole::model()->insertRoles($model->id,$role);
                }

		$this->render('create',array(
			'model'=>$model,
                        'role'=>$role,
		));
	}
    */
    // Uncomment the following methods and override them if needed
    /*
      public function filters()
      {
      // return the filter configuration for this controller, e.g.:
      return array(
      'inlineFilterName',
      array(
      'class'=>'path.to.FilterClass',
      'propertyName'=>'propertyValue',
      ),
      );
      }

      public function actions()
      {
      // return external action classes, e.g.:
      return array(
      'action1'=>'path.to.ActionClass',
      'action2'=>array(
      'class'=>'path.to.AnotherActionClass',
      'propertyName'=>'propertyValue',
      ),
      );
      }
     */
}
