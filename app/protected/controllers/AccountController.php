<?php

class AccountController extends Controller
{

        public function actionIndex()
	{
            //check if logged in redirect to home
//        if(!Yii::app()->user->isGuest) {
//            $this->redirect(Yii::app()->user->returnUrl);
//        }
        $returnUrlParam = Yii::app()->request->getQuery('rurl');
        $model = new LoginForm('signin');
            //$model->setscenario('signin');   // set scenario for rules validation
		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes = $_POST['LoginForm'];
                        // validate user input and redirect to the previous page if valid
                        if ($model->validate() && $model->login())
                        {
                            if(!empty($returnUrlParam)) {                    
                                $this->redirect($returnUrlParam);
                            } else {
                                JoyUtilities::redirectUser(Yii::app()->user->id);
                                $this->redirect(Yii::app()->getBaseUrl() . '/campaign');
                            }

                        }
		}
		$this->renderPartial('index',array('model'=>$model));
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