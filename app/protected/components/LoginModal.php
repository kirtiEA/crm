<?php

class LoginModal extends CWidget {

    public $renderBackground;
    public $header = true;
    public $ipCurrencyCode;
    
    public function init() {        
        
    }

    public function run() {
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
                    Yii::app()->request->redirect($returnUrlParam);
                } else {
                    JoyUtilities::redirectUser(Yii::app()->user->id);
                    Yii::app()->request->redirect(Yii::app()->getBaseUrl() . '/myCampaigns');
                }
            } else {
                $status = 101;
            }
        }

        $this->render('loginModal', array(
            'model' => $model, 
            'status' => $status));        
    }
}