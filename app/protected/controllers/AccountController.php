<?php

class AccountController extends Controller {

    public function init() {

        if (Yii::app()->user->isGuest || Yii::app()->controller->id == 'account') {
            Yii::app()->theme = 'static';
            $this->layout = "//layouts/static_page";
            if (!Yii::app()->user->isGuest) {
                Yii::app()->user->logout();
            }
        }    
//        else {
//            $this->redirect(Yii::app()->createUrl('myCampaigns'));
//        }
//        Yii::app()->theme = 'static';
//        $this->layout = "//layouts/static_page";
    }

    public function actionIndex() {
        $returnUrlParam = Yii::app()->request->getQuery('rurl');

        $forgotPwdCode = Yii::app()->request->getQuery('code');
        $setPwdHash = Yii::app()->request->getParam('set');

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
                    $this->redirect(Yii::app()->getBaseUrl() . '/dashboard');
                }
            } else {
                $status = 101;
            }
        }
        $modelSub = new SubscriptionForm();

        $vendorList = array();
//        $nid = Yii::app()->request->getParam('nid');
//        $modelSub->nid = $nid;
        foreach (UserCompany::model()->findAllByAttributes(array('status' => 1)) as $value) {
            array_push($vendorList, array('id' => $value->id, 'value' => $value->name));
        }

        $this->render('index', array('modelSub' => $modelSub,
            'vendorList' => json_encode($vendorList),
            'model' => $model, 'status' => $status,
            'forgotPwdCode' => $forgotPwdCode,
            'type' => 1,
            'setPwdHash' => $setPwdHash));
    }

    public function actionPricing() {
        $model = new LoginForm('signin');
        $this->render('pricing', array('model' => $model));
    }

    public function actionContactus() {
        $model = new LoginForm('signin');
        $this->render('contactus', array('model' => $model));
    }

    public function actionValidate() {
        $hash = Yii::app()->request->getParam('set');
        //echo $hash; die();
        $passwordLink = Link::model()->find('hash=:hash AND type=:type AND expired=:expired', array(':hash' => $hash, ':type' => 1, ':expired' => 0));
//        print_r($passwordLink);die();
        if ($passwordLink) {
            $userModel = User::model()->findByPk($passwordLink->userid);
            //echo $passwordLink->userid;
            $userModel->active = 1;
            $userModel->status = 1;
            $userModel->save();
//            $identity = new UserIdentity($userModel->email, false); //new UserIdentity($userModel->email, FALSE);
//            print_r($identity);
            //die();
//                            $user = Yii::app()->user;
//                $user->login($identity);
//                $this->redirect(Yii::app()->getBaseUrl() . '/myCampaigns');die();
//            echo $identity->authenticate();die('sdfs');
            $identity=new LoginForm();
            
            if ($identity->loginWithoutPassword($userModel->email)) {
//                $user = Yii::app()->user;
//                $user->login($identity);
                $passwordLink->expired = 1;
                $passwordLink->save();
                $this->redirect(Yii::app()->getBaseUrl() .  '/myCampaigns');
//                echo 1;
            } else {
                echo 5;
            }
        } else {
            /*
             * password has expired
             */
            echo 2;
        }
    }

    public function actionSignup() {
        $modelSub = new SubscriptionForm();
        $setPwdHash = Yii::app()->request->getParam('set');
        //echo $setPwdHash; die();
        $vendorList = array();
        foreach (UserCompany::model()->findAllByAttributes(array('status' => 1)) as $value) {
            array_push($vendorList, array('id' => $value->id, 'value' => $value->name));
        }
        $nid = Yii::app()->request->getParam('nid');
        if (!empty($nid)) {
            $notification = MonitorlyNotification::model()->findByPk($nid);
            $modelSub->email = $notification->miscellaneous;
        }

        $modelSub->nid = $nid;


        $model = new LoginForm('signin');

        $this->render('signup', array('modelSub' => $modelSub,
            'vendorList' => json_encode($vendorList),
            'nid' => $nid,
            'setPwdHash' => $setPwdHash,
            array('model' => $model),
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
            //echo  $_POST['SubscriptionForm']['email'] . ' fsdfsd';die();
            if (strlen($_POST['SubscriptionForm']['email']) && filter_var($_POST['SubscriptionForm']['email'], FILTER_VALIDATE_EMAIL)) {
                //$_POST['SubscriptionForm']['nid'] && 
                $noti = MonitorlyNotification::model()->findByPk($_POST['SubscriptionForm']['nid']);
                //check user with the email exists
                $user = User::model()->findByAttributes(array('email' => $_POST['SubscriptionForm']['email'], 'status' => 1));
                if (empty($user)) {
                    $model = new User();
                    $model->setscenario('create');
                    //$model->username = strtolower($_POST['User']['username']);
                    $model->email = $_POST['SubscriptionForm']['email'];
                    $model->phonenumber = $_POST['SubscriptionForm']['phonenumber'];
                    $model->datecreated = date("Y-m-d H:i:s");
                    $model->datemodified = date("Y-m-d H:i:s");
                    //$model->active = 1;
                    $model->fname = $_POST['SubscriptionForm']['email'];

//                        $model->companyid = Yii::app()->user->cid;

                    $pwd = $_POST['SubscriptionForm']['password'];
                    $ph = new PasswordHash(Yii::app()->params['phpass']['iteration_count_log2'], Yii::app()->params['phpass']['portable_hashes']);
                    $password = $ph->HashPassword($pwd);
                    $result = $ph->CheckPassword($pwd, $model->password);
                    $model->password = $password;

                    //if (!($_POST['SubscriptionForm']['companyid'])) {
                        $model->save(false);
                        //role set role as 6
                        $role = Role::model()->findByPk(1);
//                            UserRole::model()->insertRoles($model->id, $role->id);
                        UserRole::model()->insertRoles($model->id, $role->id);
                        $comp = null;
                        if (!($_POST['SubscriptionForm']['companyid'])) {
                            //create company
                            $comp = new UserCompany;
                            $comp->name = $_POST['SubscriptionForm']['companyname'];
                            //echo $_POST['SubscriptionForm']['companyname']; die();
                            //$comp->alias = UserCompany::companyNameAlias(JoyUtilities::createAlias($_POST['UserCompany']['name']));
                            $comp->countryid = 1;
                            $comp->stateid = 2;
                            $comp->cityid = 3;
                            $comp->userid = $model->id;
                            $comp->save(false);
                        } else {
                           $comp = UserCompany::model()->findByPk($_POST['SubscriptionForm']['companyid']);     
                        }
                        
                        $model->companyid = $comp->id;
                        //print_r($comp); die();
                        $model->save(false);
                        // User::model()->updateByPk($model->id, array('companyid' => $comp->id));
                        Yii::app()->user->setFlash('success', 'Your account has been created successfully');
                        $hash = sha1(uniqid());
                        //echo $hash;
                        $passwordLink = new Link();
                        $passwordLink->attributes = array('userid' => $model->id, 'hash' => $hash, 'datecreated' => date('Y-m-d H:i:s'), 'type' => '1');
                        $resetlink1 = Yii::app()->getBaseUrl(true) . '/account/validate?set=' . $hash;
                        if ($_POST['SubscriptionForm']['nid']) {
                            /*
                             * update nid
                             * 
                             */
                            MonitorlyNotification::model()->updateByPk($_POST['SubscriptionForm']['nid'], array('lastViewedDate' => date("Y-m-d H:i:s")));

                            /*
                             * insert into requested vendor
                             */

                            $model1 = new RequestedCompanyVendor();
                            $model1->attributes = array(
                                'companyid' => $noti->companyid,
                                'createdby' => Yii::app()->user->id,
                                'createddate' => date("Y-m-d H:i:s"),
                                'vendorcompanyid' => $comp->id,
                                'acceptedby' => Yii::app()->user->id,
                                'accepteddate' => date("Y-m-d H:i:s"),
                            );
                            $model1->save();
                            $resetlink = Yii::app()->getBaseUrl(true) . '/myCampaigns';
                            $vendorName = UserCompany::model()->findByPk($comp->id);
                            $emailToUser = User::model()->findByPk($noti->createdby);
                            //$mail = new EatadsMailer('request-accepted', $emailToUser->email, array('resetLink' => $resetlink, 'vendorName' => $vendorName['name']), array(''), $vendorName['name'], $model->email);
                            $mail = new EatadsMailer('request-accepted', 'sales@eatads.com', array('resetLink' => $resetlink, 'vendorName' => $vendorName['name']), array(''), $vendorName['name'], $model->email);
                            $mail->eatadsSend();
                            
                            
                            
                            $mail2admin = new EatadsMailer('sign-up-admin', 'sales@eatads.com', array('userType' => $model->email, 'fname' => $comp->name, 'lname' => $model->phonenumber), array(''), $vendorName['name'], $model->email);
                            $mail2admin->eatadsSend();
                            if ($passwordLink->save()) {
                                //$mail = new EatadsMailer('new-user', $_POST['SubscriptionForm']['email'], array('resetLink' => $resetlink1), array('shruti@eatads.com'), 'EatAds Admin');
                                $mail = new EatadsMailer('new-user', 'sales@eatads.com', array('resetLink' => $resetlink1), array('sales@eatads.com'), 'EatAds Admin');
                                $mail->eatadsSend();
                                
                                $mail2admin = new EatadsMailer('sign-up-admin', 'sales@eatads.com', array('userType' => $model->email, 'fname' => $comp->name, 'lname' => $model->phonenumber));
                                $mail2admin->eatadsSend();
                                Yii::app()->user->setFlash('success', 'Thank you for registering for Monitorly. We will get back to you shortly confirming your account.');
                                $this->redirect(Yii::app()->getBaseUrl() . '/account/signup');
                            }
                        } else {
                            if ($passwordLink->save()) {
                                //$mail = new EatadsMailer('new-user', $_POST['SubscriptionForm']['email'], array('resetLink' => $resetlink1), array('shruti@eatads.com'), 'EatAds Admin');
                                $mail = new EatadsMailer('new-user', 'sales@eatads.com', array('resetLink' => $resetlink1), array('sales@eatads.com'), 'EatAds Admin');
                                $mail->eatadsSend();
                                $mail2admin = new EatadsMailer('sign-up-admin', 'sales@eatads.com', array('userType' => $model->email, 'fname' => $comp->name, 'lname' => $model->phonenumber));
                                $mail2admin->eatadsSend();
                                Yii::app()->user->setFlash('success', 'Thank you for registering for Monitorly. We will get back to you shortly confirming your account.');
                                $this->redirect(Yii::app()->getBaseUrl() . '/account/signup');
                            }
                        }
//                    } else {
//                        Yii::app()->user->setFlash('success', 'User already exists with this email');
//                        $this->redirect(Yii::app()->getBaseUrl() . '/account/signup');
//                    }
                } else {

                    Yii::app()->user->setFlash('success', 'User already exists with this email');
                    $this->redirect(Yii::app()->getBaseUrl() . '/account/signup');
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
                    Yii::app()->user->setFlash('success', 'Thank you for registering for Monitorly. We will get back to you shortly confirming your account.');
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
        if (isset($_POST['SubscriptionForm'])) {
            //print_r($_POST['SubscriptionForm'])   . ' fsdfsd';die();
            if (strlen($_POST['SubscriptionForm']['email']) && filter_var($_POST['SubscriptionForm']['email'], FILTER_VALIDATE_EMAIL)) {
                //$noti = MonitorlyNotification::model()->findByPk($_POST['SubscriptionForm']['nid']);
                //check user with the email exists
                $user = User::model()->findByAttributes(array('email' => $_POST['SubscriptionForm']['email'], 'status' => 1));
                if (empty($user)) {
                    $model = new User();
                    $model->setscenario('create');
                    //$model->username = strtolower($_POST['User']['username']);
                    $model->email = $_POST['SubscriptionForm']['email'];
                    $model->phonenumber = $_POST['SubscriptionForm']['phonenumber'];
                    $model->datecreated = date("Y-m-d H:i:s");
                    $model->datemodified = date("Y-m-d H:i:s");
                    $model->active = 0;
                    $model->status = 0;
                    $model->fname = $_POST['SubscriptionForm']['email'];
//                        $model->companyid = Yii::app()->user->cid;
//                    $pwd = $_POST['SubscriptionForm']['password'];
//                    $ph = new PasswordHash(Yii::app()->params['phpass']['iteration_count_log2'], Yii::app()->params['phpass']['portable_hashes']);
//                    $password = $ph->HashPassword($pwd);
//                    $result = $ph->CheckPassword($pwd, $model->password);
//                    $model->password = $password;

                    if (!empty($_POST['SubscriptionForm']['companyid'])) {
                        $model->companyid = $_POST['SubscriptionForm']['companyid'];
                        $model->save(false);
                        //role set role as 6
                        $role = Role::model()->findByPk(1);
//                            UserRole::model()->insertRoles($model->id, $role->id);
                        UserRole::model()->insertRoles($model->id, $role->id);
                        // User::model()->updateByPk($model->id, array('companyid' => $comp->id));
                        Yii::app()->user->setFlash('success', 'User created successfully');
                        $hash = sha1(uniqid());
                        //echo $hash;
                        $passwordLink = new Link();
                        $passwordLink->attributes = array('userid' => $model->id, 'hash' => $hash, 'datecreated' => date('Y-m-d H:i:s'), 'type' => '1');
                        if ($passwordLink->save()) {
                            $resetlink = Yii::app()->getBaseUrl(true) . '/?set=' . $hash;
                            //print_r($resetlink); // $resetlink;
                            $mail = new EatadsMailer('new-user', $_POST['SubscriptionForm']['email'], array('resetLink' => $resetlink), array('shruti@eatads.com'), 'EatAds Admin');
                            $mail->eatadsSend();
                            Yii::app()->user->setFlash('success', 'Thank you for subscribing. We will get back to you shortly.');
                            $this->redirect(Yii::app()->getBaseUrl() . '/account');
                        }
                        //$this->redirect(Yii::app()->getBaseUrl() . '/myCampaigns');
                    } else {
                        $role = Role::model()->findByPk(1); //admin
                        //print_r($model);die();
                        $model->save(false);
                        UserRole::model()->insertRoles($model->id, $role->id);
                        //create company
                        $comp = new UserCompany;
                        //$model->setScenario('createProfile');
                        $comp->name = $_POST['SubscriptionForm']['companyname'];
                        //$comp->alias = UserCompany::companyNameAlias(JoyUtilities::createAlias($_POST['UserCompany']['name']));
                        $comp->countryid = 1;
                        $comp->stateid = 2;
                        $comp->cityid = 3;
                        $comp->userid = $model->id;
                        $comp->save(false);
                        User::model()->updateByPk($model->id, array('companyid' => $comp->id));
                        Yii::app()->user->setFlash('success', 'User created successfully');
                        $hash = sha1(uniqid());
                        $passwordLink = new Link();
                        //print_r($passwordLink->getErrors())   . ' fsdfsd';die();
                        $passwordLink->attributes = array('userid' => $model->id, 'hash' => $hash, 'datecreated' => date('Y-m-d H:i:s'), 'type' => '1');
                        if ($passwordLink->save(false)) {

                            $resetlink = Yii::app()->getBaseUrl(true) . '?set=' . $hash;
                            //echo $resetlink;
                            $mail = new EatadsMailer('new-user', $_POST['SubscriptionForm']['email'], array('resetLink' => $resetlink), array('shruti@eatads.com'), 'EatAds Admin');
                            $mail->eatadsSend();
                            Yii::app()->user->setFlash('success', 'Thank you for subscribing. We will get back to you shortly.');
                            $this->redirect(Yii::app()->getBaseUrl() . '/account');
                        }
                        //$this->redirect(Yii::app()->getBaseUrl() . '/myCampaigns');
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
                //$model->nid = $_POST['SubscriptionForm']['nid'];
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
