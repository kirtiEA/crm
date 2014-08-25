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
        // logged in userId
        $userId = 552; // Yii::app()->user->id;
        $model = new MonitorlyListing();
        //$model->setscenario('signin');   // set scenario for rules validation        

        // collect user input data
        if (isset($_POST['site-submit'])) {
            $date = date("Y-m-d H:i:s");
            
            // check if vendor id given else create a new vendor
            $vendorName = $_POST['site-vendor'];
            $vendorId = $_POST['site-vendorId'];
            if(is_numeric($vendorId)) {
                $modelUC = new UserCompany;
                $modelUC->userid = $userId;
                $modelUC->name = $vendorName;
                // THIS WAY USER CAN HAVE MULTIPLE COMPANY
            }
            
            $model->name = $_POST['site-name'];
            $model->mediaTypeId = $_POST['site-mediatypeid'];
            $model->locality = $_POST['site-locality'];
            $model->geoLat = $_POST['site-lat'];
            $model->geoLng = $_POST['site-lng'];
            $model->createdDate = $date;
            $model->modifiedDate = $date;
            $model->save();
            print_r($model->getErrors());            
            echo '<pre>';print_r($_POST);print_r($model->getAttributes()); die();
            
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
        $mediaTypes = CHtml::listData(Mediatype::model()->findAll(), 'id', 'name');
        $criteria = new CDbCriteria;
        $criteria->select='t.id,t.name';  // only select the 'title' column
        $criteria->join ='LEFT JOIN UserProduct up ON up.userid = t.userid ';        
        $criteria->condition='up.productid=:productid';
        $criteria->params=array(':productid'=>2);   // productid 2 for monitorly companies
        $vendorListArray = array();        
        foreach(UserCompany::model()->findAll($criteria) as $value) {            
            array_push($vendorListArray, array('id'=>$value->id, 'value'=>$value->name));            
        }        
        //echo '<pre>'; echo json_encode($vendorListArray); die();        
        $this->render('index', array(
                        'mediaTypes'=>$mediaTypes,
                        'vendorList'=>json_encode($vendorListArray)
                    ));
    }

    public function actionMassupload() {
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'
        $this->render('massupload');
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

}
