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
        $modelSub = new SubscriptionForm();
        
        $vendorList = array();
        foreach (UserCompany::model()->findAll() as $value) {
            array_push($vendorList, array('id' => $value->id, 'value' => $value->name));
        }
        $nid = Yii::app()->request->getParam('nid');
        if(!empty($nid)) {
            $notification = MonitorlyNotification::model()->findByPk($nid);
            $modelSub->email =  $notification->miscellaneous;
        }
        
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
    
    public function actionCreateVendorAccount() {

            if (isset($_POST['SubscriptionForm'])) {
                //echo  $_POST['SubscriptionForm']['nid'] . ' fsdfsd';die();
                if ($_POST['SubscriptionForm']['nid'] && strlen($_POST['SubscriptionForm']['email']) && filter_var($_POST['SubscriptionForm']['email'], FILTER_VALIDATE_EMAIL)) {
                  //check user with the email exists
                  $user = User::model()->findByAttributes(array('email' => $_POST['SubscriptionForm']['email'], 'status' =>1));
                  if (empty($user)) {
                       $model = new User();
                        $model->setscenario('create');
                       //$model->username = strtolower($_POST['User']['username']);
                        $model->email = $_POST['SubscriptionForm']['email'];
                        $model->phonenumber = $_POST['SubscriptionForm']['phonenumber'];
                        $model->datecreated = date("Y-m-d H:i:s");
                        $model->datemodified = date("Y-m-d H:i:s");
                        $model->active = 1;
                        $model->fname = $_POST['SubscriptionForm']['email'];
//                        $model->companyid = Yii::app()->user->cid;

                        $pwd = $_POST['SubscriptionForm']['password'];
                        $ph = new PasswordHash(Yii::app()->params['phpass']['iteration_count_log2'], Yii::app()->params['phpass']['portable_hashes']);
                        $password = $ph->HashPassword($pwd);
                        $result = $ph->CheckPassword($pwd, $model->password);
                        $model->password = $password;
                        
                        if (!empty($_POST['SubscriptionForm']['companyid'])) {
                            $model->companyid = $_POST['SubscriptionForm']['companyid'];
                             $model->save(false);
                            //role set role as 6
                            $role = Role::model()->findByPk(1);
//                            UserRole::model()->insertRoles($model->id, $role->id);
                            print_r(UserRole::model()->insertRoles($model->id, $role->id));die();
                        } else {
                            $role = Role::model()->findByPk(1);//admin
                            //print_r($model);die();
                            $model->save(false);
                            UserRole::model()->insertRoles($model->id, $role->id);
                            //create company
                            $comp = new UserCompany;
                            //$model->setScenario('createProfile');
                            $comp->name = $_POST['SubscriptionForm']['companyname'];
                            //$comp->alias = UserCompany::companyNameAlias(JoyUtilities::createAlias($_POST['UserCompany']['name']));
                            $comp->countryid = 1;
                            $comp->stateid =2;
                            $comp->cityid =3;
                            $comp->userid = $model->id;
                            $comp->save(false);
                            User::model()->updateByPk($model->id, array('companyid' => $comp->id));
                            Yii::app()->user->setFlash('success', 'User created successfully');
                            $identity = new UserIdentity($model->email, $pwd);
                            if ($identity->authenticate()) {
                                $user = Yii::app()->user;
                                $user->login($identity);
                                $this->redirect(Yii::app()->getBaseUrl() .'/myCampaigns');
                            } else {
                                Yii::app()->user->setFlash('success', 'User already exists with this email');
                                $this->redirect(Yii::app()->getBaseUrl() . '/account');
                            }
                        }
                  } else {
                      Yii::app()->user->setFlash('success', 'User already exists with this email');
                      $this->redirect(Yii::app()->getBaseUrl() . '/account');
                  }
                  //create user
                  //check if company already exists
                    //if yes add as a different role
                    //else add as an admin
                  //assign role
                  //update user with the  companyid
                  //login user  
                } else {
                    $model = new MonitorlySubscription();
                    $model->companyname = $_POST['SubscriptionForm']['companyname'];
                    $model->email = $_POST['SubscriptionForm']['email'];
                    $model->phonenumber = $_POST['SubscriptionForm']['phonenumber'];
                    $model->nid = $_POST['SubscriptionForm']['nid'];
                    $model->createddate = date("Y-m-d H:i:s");
                    $flag = 1;
//               echo $model->save(false);die('sdfsd');
                    if (strlen($_POST['SubscriptionForm']['companyname']) == 0 && strlen($_POST['SubscriptionForm']['phonenumber']) == 0) {
                        Yii::app()->user->setFlash('success', 'All feilds are required');

                        $flag = 0;
                    } else if (strlen($_POST['SubscriptionForm']['companyname']) == 0) {
                        Yii::app()->user->setFlash('success', 'Company Name is required');
                        $flag = 0;
    //$this->redirect(Yii::app()->getBaseUrl() . '/account/signup');
                    } else if (strlen($_POST['SubscriptionForm']['phonenumber']) == 0) {
                        Yii::app()->user->setFlash('success', 'Mobile number is required');
                        $flag = 0;
                        // $this->redirect(Yii::app()->getBaseUrl() . '/account/signup');
                    } else if (strlen($_POST['SubscriptionForm']['companyname']) != 0 && strlen($_POST['SubscriptionForm']['phonenumber']) != 0) {
                        Yii::app()->user->setFlash('success', 'Thank you for subscribing. We will get back to you shortly.');
                        //  $this->redirect(Yii::app()->getBaseUrl() . '/account');
//                        $vendorName = MonitorlyNotification::model()->findByPk($_POST['SubscriptionForm']['nid']);
//                        $createdbyid = $vendorName['createdby'];
//                        $email = User::model()->findByPk($createdbyid);
//                        $emailid = $email['email'];
                        //echo $emailid.' '.; die();
//                        $resetlink = Yii::app()->getBaseUrl(true) . '/myCampaigns';
//                        $mail = new EatadsMailer('request-accepted', $emailid, array('resetLink' => $resetlink, $_POST['MonitorlySubscription']['companyname']), array('shruti@eatads.com'), $_POST['MonitorlySubscription']['companyname'], $_POST['MonitorlySubscription']['email']);
//                        $mail->eatadsSend();
//                        $getName = UserCompany::model()->findByAttributes(array('userid' => $id));
//                        $agencyName = $getName['name'];
//                        $inviteVendors = Yii::app()->getBaseUrl(true) . '/vendor';
//                        $mail = new EatadsMailer('invite-accepted', $_POST['MonitorlySubscription']['email'], array('resetLink' => $inviteVendors, 'agencyName' => $agencyName), array('shruti@eatads.com'), $vendorName['name'], Yii::app()->user->email);
//                        $mail->eatadsSend();
//                        Yii::app()->user->setFlash('success', 'Request accepted Successfully');
                    }
                //   echo $_POST['MonitorlySubscription']['type'];              die();

                    if ($flag == 1) {
                        //                  print_r($model->validate());die();
                        // echo ; die();
                        echo $model->save(false);
                        //              print_r($model->attributes);die();
                        //            print_r($model->getErrors());die();
                    }
                    // echo $_POST['MonitorlySubscription']['type'];                die('45666');
                    if (strcasecmp($_POST['MonitorlySubscription']['type'], '1') == 0) {
                        $this->redirect(Yii::app()->getBaseUrl() . '/account');
                    } else if (strcasecmp($_POST['MonitorlySubscription']['type'], '2') == 0) {
                        $this->redirect(Yii::app()->getBaseUrl() . '/account/signup');
                    }
                }
            } else {
                $this->redirect(Yii::app()->getBaseUrl() . '/account');
            }
        
            
    }

    public function actionCreateVendor() {
        $model = new MonitorlySubscription();
        if (isset($_POST['MonitorlySubscription'])) {
            if (strlen($_POST['MonitorlySubscription']['email']) && filter_var($_POST['MonitorlySubscription']['email'], FILTER_VALIDATE_EMAIL)) {
                $model->companyname = $_POST['MonitorlySubscription']['companyname'];
                $model->email = $_POST['MonitorlySubscription']['email'];
                $model->phonenumber = $_POST['MonitorlySubscription']['phonenumber'];
                $model->nid = $_POST['MonitorlySubscription']['nid'];
                $model->createddate = date("Y-m-d H:i:s");
                //          $id = Yii::app()->user->id;
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
//               echo $model->save(false);die('sdfsd');
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
                } else if (strlen($_POST['MonitorlySubscription']['companyname']) != 0 && strlen($_POST['MonitorlySubscription']['phonenumber']) != 0) {
                    $vendorName = MonitorlyNotification::model()->findByPk($_POST['MonitorlySubscription']['nid']);
                    $createdbyid = $vendorName['createdby'];
                    $email = User::model()->findByPk($createdbyid);
                    $emailid = $email['email'];
                    //$agencyName = 
                    //echo $emailid.' '. $_POST['MonitorlySubscription']['companyname']. ' '. $_POST['MonitorlySubscription']['email']; die();
                    $resetlink = Yii::app()->getBaseUrl(true) . '/myCampaigns';
                    $mail = new EatadsMailer('request-accepted', $emailid, array('resetLink' => $resetlink, 'vendorName' => $_POST['MonitorlySubscription']['companyname']), array('shruti@eatads.com'), $_POST['MonitorlySubscription']['companyname'], $_POST['MonitorlySubscription']['email']);
                    $mail->eatadsSend();
                    Yii::app()->user->setFlash('success', 'Thank you for subscribing. We will get back to you shortly.');
                }
                //   echo $_POST['MonitorlySubscription']['type'];              die();

                if ($flag == 1) {
                    //                  print_r($model->validate());die();
                    // echo ; die();
                    echo $model->save(false);
                    //              print_r($model->attributes);die();
                    //            print_r($model->getErrors());die();
                }
                // echo $_POST['MonitorlySubscription']['type'];                die('45666');
                if (strcasecmp($_POST['MonitorlySubscription']['type'], '1') == 0) {
                    $this->redirect(Yii::app()->getBaseUrl() . '/account');
                } else if (strcasecmp($_POST['MonitorlySubscription']['type'], '2') == 0) {
                    $this->redirect(Yii::app()->getBaseUrl() . '/account/signup');
                }
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
