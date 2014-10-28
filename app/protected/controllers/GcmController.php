<?php

class GcmController extends Controller
{
    public function init() {
        if (Yii::app()->user->isGuest || strcasecmp(Yii::app()->user->email, 'admin@eatads.com') !=0 ) {
            $this->redirect(Yii::app()->createUrl('account'));
        }
    }
	public function actionIndex()
	{
            if (isset($_POST['version']) && $_POST['version'] != 'null') {
                //update the last version 
                MonitorlyVersionUpdate::updateLastVersion();
                //insert a new row
                
                $model = new MonitorlyVersionUpdate();
                $model->version = $_POST['version'];
                $model->startdate = date("Y-m-d H:i:s");
                 $model->save();
                Yii::app()->user->setFlash('success', 'Version updated');
                
                //fetch all the device ids and push the update
                $deviceids = MonitorlyPushNotificationUserDeviceMapping::findUniqueDeviceIds();
                //print_r(json_encode($finaldeviceids));die();
               $gcmPushNotification = new GcmPushNotification($model->version, $deviceids);
               $gcmPushNotification->pushMessage();
            }
            
            $versions = MonitorlyVersionUpdate::model()->findAll(array("order" => 'id DESC'));
            $this->render('index', array('versions' => $versions));
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