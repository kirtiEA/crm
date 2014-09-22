<?php

class SiteController extends Controller {

    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    public function init() {        
        if (Yii::app()->user->isGuest) {
            if(Yii::app()->controller->id != 'account') {
                $this->redirect(Yii::app()->createUrl('account'));
            }            
        }        
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        $data = Listing::getListingsForAcceptedVendors(Yii::app()->user->cid, 0);
        $arr = array();
        foreach ($data as $key => $value) {
            $result = array();
            $result[0] = $value['name'] . ', ' . $value['address'];
            $result[1] = $value['lat'];
            $result[2] = $value['lng'];
            $result[3] = $value['id'];
            array_push($arr, $result);
        }
        $this->render('index', array('markers' => json_encode($arr)));
    }

    public function actionAddVendor() {
        $vendorList = array();
        $result = UserCompany::fetchVendorsList(Yii::app()->user->cid);
        foreach($result as $value) {
            array_push($vendorList, array('id' => $value['id'], 'value' => $value['name'] . ' (' . $value['cnt'] .')'));

        }

        // fetch media types
        $mtResult = Mediatype::model()->findAll();
        $mediaType = array();
        foreach ($mtResult as $value) {
            array_push($mediaType, $value->name);
        }

        $this->render('addvendor', array(
            'vendorList' => json_encode($vendorList),
            'mediaType' => json_encode($mediaType),
            'lightingType' => json_encode(array_values(Listing::getLighting()))
        ));
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the contact page
     */
    public function actionContact() {
        $model = new ContactForm;
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {
                $name = '=?UTF-8?B?' . base64_encode($model->name) . '?=';
                $subject = '=?UTF-8?B?' . base64_encode($model->subject) . '?=';
                $headers = "From: $name <{$model->email}>\r\n" .
                        "Reply-To: {$model->email}\r\n" .
                        "MIME-Version: 1.0\r\n" .
                        "Content-Type: text/plain; charset=UTF-8";

                mail(Yii::app()->params['adminEmail'], $subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact', array('model' => $model));
    }

    /**
     * Displays the login page
     */
    public function actionLogin() {
        $model = new LoginForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->returnUrl);
        }
        // display the login form
        $this->render('login', array('model' => $model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    public function actionPending() {
        
    }

    public function actionMyPendingSites() {
        $data = Listing::getSitesTobeApproved(Yii::app()->user->cid);
        
        $result = array();
        foreach ($data as $key => $value) {
            $value['lighting'] = Listing::getLighting($value['lightingid']);
            if ($value['sizeunitid'] == 0) {
                $value['sizeunit'] = Listing::getSizeUnit(1);
            } else {
                $value['sizeunit'] = Listing::getSizeUnit($value['sizeunitid']);
            }

            $value['thumbnail'] = null;
            array_push($result, $value);
        }
        //  print_r($result);die();
          $arr = array();
            foreach ($data as $key => $value) {
                $result1 = array();
                $result1[0] = $value['name'];
                $result1[1] = $value['lat'];
                $result1[2] = $value['lng'];
                $result1[3] = $value['id'];
                array_push($arr, $result1);
                
            }

        $this->render('pendingsites', array('lists' => $result, 'markers' => json_encode($arr)));
    }
    
    public function actionMySites() {
         $data = Listing::getListingsForAcceptedVendors(Yii::app()->user->cid, 0);
           $arr = array();
            foreach ($data as $key => $value) {
                $result = array();
                $result[0] = $value['name'] . ', ' . $value['address'];
                $result[1] = $value['lat'];
                $result[2] = $value['lng'];
                $result[3] = $value['id'];
                array_push($arr, $result);
                
            }
//            echo json_encode($result);
        $this->render('mysites', array('markers' => json_encode($arr)));

    }

}
