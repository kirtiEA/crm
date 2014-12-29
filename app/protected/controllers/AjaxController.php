<?php

class AjaxController extends Controller {

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
                'actions' => array('signup', 'getlisting', 'getmarkers', 'vendordetails', 'retriveplan', 'getsitedetails', 'addinexistingplan', 'addplan', 'addfavorite', 'plandetail', 'deleteplanlisting', 'getmediatypes', 'uploadcontacts', 'vendorcontacts', 'updatevendorcontacts',
                    'PushAvailabilityMailsToQueue', 'MassUploadListingsForVendor', 'fetchvendorsites', 'massuploadsite', 'updatepassword',
                    'invitevendor', 'removeListingFromCampaign', 'updateCampaign', 'forgotpwd', 'verifyresethash',
                    'resetpwd', 'fetchNotifications', 'fetchVendorListing', 'assignCampaignSiteToUser', 'shareCampaignWithEmails',
                    'filterTasks', 'filterAllReports'),
                'users' => array('*'),
            )
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

    public function actionForgotpwd() {
        $email = Yii::app()->request->getParam('email');

        // get the userid from the entered email
        $userModel = User::model()->find(array('condition' => 'email=:email', 'params' => array(':email' => $email), 'select' => 'id'));
        if ($userModel) {
            $userId = $userModel->id;
            // generate the hash
            $hash = sha1(uniqid());
            // generate reset link
            $linkModel = new Link();
            $linkModel->attributes = array('userid' => $userId, 'hash' => $hash, 'type' => 0, 'datecreated' => date('Y-m-d H:i:s'));
            if ($linkModel->save()) {
                // send reset pwd link email to user    
                $resetLink = Yii::app()->createAbsoluteUrl('account/index', array('code' => $hash));
                $mail = new EatadsMailer('forgot-pwd', $email, array('resetLink' => $resetLink));
                $mail->eatadsSend();
                // show success message
                echo 1;
            } else {
                // show error message
                echo 2;
            }
        } else {
            // show error message
            echo 3;
        }
    }

    public function actionVerifyresethash() {
        $hash = Yii::app()->request->getParam('hash');
        $linkModel = Link::model()->find('hash=:hash AND type=:type', array(':hash' => $hash, ':type' => 0));
        if ($linkModel) {
            if ($linkModel->expired == 0) {
                // check if link has not expired
                $timeDiff = (time() - strtotime($linkModel->datecreated)) / 3600;
                //$linkModel->expired = 1;    // expire the link
                //$linkModel->save();         // save the record
                // check link expiration time set in config
                if ($timeDiff < Yii::app()->params['linkexpiry']['forgot']) {
                    echo 1;
                } else {
                    echo 2;
                }
            } else {
                echo 3;
            }
        } else {
            echo 4;
        }
    }

    public function actionResetpwd() {
        $hash = Yii::app()->request->getParam('hash');
        $password = Yii::app()->request->getParam('password');
        $linkModel = Link::model()->find('hash=:hash AND type=:type', array(':hash' => $hash, ':type' => 0));

        // if link not expired
        if ($linkModel) {
            if ($linkModel->expired == 0) {
                // check if link has not expired
                $timeDiff = (time() - strtotime($linkModel->datecreated)) / 3600;
                $linkModel->expired = 1;    // expire the link
                $linkModel->save();         // save the record
                // check link expiration time set in config
                if ($timeDiff < Yii::app()->params['linkexpiry']['forgot']) {
                    // update the password for the user
                    $userModel = User::model()->findByPk($linkModel->userid);
                    // CHANGE THE PASSWORD HASHING METHOD
                    $ph = new PasswordHash(Yii::app()->params['phpass']['iteration_count_log2'], Yii::app()->params['phpass']['portable_hashes']);
                    $userModel->password = $ph->HashPassword($password);
                    $userModel->update();
//                    $userModel->save();
                    // login & redirect from server

                    $identity = new UserIdentity($userModel->email, $password);
                    if ($identity->authenticate()) {
                        $user = Yii::app()->user;
                        $user->login($identity);
                        //$this->redirect($user->returnUrl);
                        echo Yii::app()->getBaseUrl() . '/myCampaigns';
                    } else {
                        echo 5;
                    }
                } else {
                    echo 2;
                }
            } else {
                echo 3;
            }
        } else {
            echo 4;
        }
    }
    
    public function actionfetchCompanyContacts() {
        $sql = "select name as label, id as value from CompanyContacts where companyid = " . Yii::app()->user->cid;
        echo json_encode(Yii::app()->db->createCommand($sql)->queryAll());
    }
    
   public function actionfetchLeadsForStatus() {
       $sql = "SELECT cb.name as brand, DATE_FORMAT(cl.campaignstartdate, '%Y-%m-%d') as campaignstartdate, DATE_FORMAT(cl.campaignenddate, '%Y-%m-%d') as campaignenddate,
            DATE_FORMAT(cl.lastupdated, '%Y-%m-%d') as lastupdated, u.username as user
            FROM CompanyLeads cl
            inner join CompanyBrands cb on cb.id = cl.brandid
            inner join User u on u.id = cl.assignedto
            where cl.status = " . $_POST['id'];
     echo json_encode(Yii::app()->db->createCommand($sql)->queryAll());
   } 
}    