<?php

class AccountController extends Controller {

    public function init() {
        Yii::app()->theme = 'static';
        $this->layout = "//layouts/static_page";
    }

    public function actionIndex() {
        $returnUrlParam = Yii::app()->request->getQuery('rurl');
        $forgotPwdCode = Yii::app()->request->getQuery('code');

        $model = new LoginForm('signin');
        //$model->setscenario('signin');   // set scenario for rules validation
        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        $status = 200;
        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid

            if ($model->validate() && $model->login()) {
                if (!empty($returnUrlParam)) {
                    $this->redirect($returnUrlParam);
                } else {
                    JoyUtilities::redirectUser(Yii::app()->user->id);
                    $this->redirect(Yii::app()->getBaseUrl() . '/myCampaigns');
                }
            } else {
                $status = 101;
            }
        }


        $modelSub = new MonitorlySubscription();
        $vendorList = array();
        $nid = Yii::app()->request->getParam('nid');
        $modelSub->nid = $nid;
//        foreach (UserCompany::model()->findAll() as $value) {
//            array_push($vendorList, array('id' => $value->id, 'value' => $value->name));
//        }

        $this->render('index', array('modelSub' => $modelSub,
            'vendorList' => json_encode($vendorList),
            'nid' => $nid,
            'model' => $model, 'status' => $status,
            'forgotPwdCode' => $forgotPwdCode,
            'type' => 1));
    }

    public function actionPricing() {
        $this->render('pricing');
    }

    public function actionContactus() {
        $this->render('contactus');
    }

    public function actionSignup() {
        $modelSub = new MonitorlySubscription();
        $vendorList = array();
        $nid = Yii::app()->request->getParam('nid');
        $modelSub->nid = $nid;
        $this->render('signup', array('modelSub' => $modelSub,
            'vendorList' => json_encode($vendorList),
            'nid' => $nid,
            'type' => 2));
    }

    public function actionTerms() {
        $this->render('terms');
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    public function actionCreateVendor() {
       //die('sfsdfsdfsfssf');
        $model = new MonitorlySubscription();
        //$model->setScenario('subscribe');
        echo 'hello'; die();
        if (isset($_POST['MonitorlySubscription'])) {
            echo 'hi';die();
            if (strlen($_POST['MonitorlySubscription']['email']) && filter_var($_POST['MonitorlySubscription']['email'], FILTER_VALIDATE_EMAIL)) {
                //$model->nid = Yii::app()->request->getParam('nid');   
                 print_r($_POST['MonitorlySubscription']); die();
                $model->companyname = $_POST['MonitorlySubscription']['companyname'];
                $model->email = $_POST['MonitorlySubscription']['email'];
                $model->phonenumber = $_POST['MonitorlySubscription']['phonenumber'];
                $model->nid = $_POST['MonitorlySubscription']['nid'];
                $model->createddate = date("Y-m-d H:i:s");
                //print_r($model->attributes);
                //print_r($_POST);
                //if($model->validate())
//                $id = Yii::app()->user->id;
//                $email = Yii::app()->user->emailid;
//                $invite = new MonitorlyNotification();
//                $invite->attributes = array('typeid' => "", 'createddate' => date("Y-m-d H:i:s"), 'createdby' => $id, 'emailtypeid' => 2);
//                $invite->save();
//                $mail = new EatadsMailer('accepted-invite', $email, array('resetLink' => ""), array('shruti@eatads.com'));
//                $mail->eatadsSend();
//                echo "id=".$model->id ;
                //echo '<pre>';
                //              print_r($model->attributes);
                $flag = 1;
                if (strlen($_POST['MonitorlySubscription']['companyname']) == 0 && strlen($_POST['MonitorlySubscription']['phonenumber']) == 0) {
                    Yii::app()->user->setFlash('success', 'All feilds are required');

                    $flag = 0;
                } else if (strlen($_POST['MonitorlySubscription']['companyname']) == 0) {
                    Yii::app()->user->setFlash('success', 'Company Name is required');
                    $flag = 0;
//$this->redirect(Yii::app()->getBaseUrl() . '/account/signup');
                } else if (strlen($_POST['MonitorlySubscription']['phonenumber']) == 0) {
                    Yii::app()->user->setFlash('success', 'Mobile number is required');
                    $flag = 0;
                    // $this->redirect(Yii::app()->getBaseUrl() . '/account/signup');
                } else if(strlen($_POST['MonitorlySubscription']['companyname']) != 0 && strlen($_POST['MonitorlySubscription']['phonenumber']) != 0){
                    Yii::app()->user->setFlash('success', 'Thank you for subscribing. We will get back to you shortly.');
                    //  $this->redirect(Yii::app()->getBaseUrl() . '/account');
                }

echo $_POST['MonitorlySubscription']['type'];die();
                if ($flag == 1) {

                    $model->save();
                }
                echo $_POST['MonitorlySubscription']['type'];die();
//                    if (strcasecmp($_POST['MonitorlySubscription']['type'], '1')  == 0) {
//                        $this->redirect(Yii::app()->getBaseUrl() . '/account');
//                    } else if (strcasecmp($_POST['MonitorlySubscription']['type'], '2')  == 0) {
//                        $this->redirect(Yii::app()->getBaseUrl() . '/account/signup');
//                    }

//                echo 1;
            }
        }
    }

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
