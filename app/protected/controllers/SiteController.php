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

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {        
        $this->render('index');
    }

    public function actionAddVendor() {
        $vendorList = array();
        foreach(UserCompany::model()->findAll() as $value) {
            array_push($vendorList, array('id' => $value->id, 'value' => $value->name));
            //array_push($vendorList, array('name'=>$value->name));
        }
        
        // fetch media types
        $mtResult = Mediatype::model()->findAll();
        $mediaType = array();
        foreach($mtResult as $value) {
            array_push($mediaType, $value->name);
        }
        
        $this->render('addvendor', array(
                        'vendorList'=>json_encode($vendorList),
                        'mediaType'=>json_encode($mediaType),
                        'lightingType'=>  json_encode(array_values(Listing::getLighting()))
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
        $this->render('pendingsites', array('lists' => $result));
    }
}
