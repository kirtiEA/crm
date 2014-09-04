<?php

class ReportsController extends Controller
{
	public function actionFetchreport()
	{
		$this->render('fetchreport');
	}

        public function actionIndex()
	{
            $sql = "SELECT t.id, c.name as campaign, ml.name as site, mt.name as mediatype, t.dueDate as duedate, "
                    . "t.taskDone as status, t.problem, CONCAT(u.fname,' ', u.lname) as assignedto "
                    . "FROM Task t "
                    . "LEFT JOIN Campaign c ON c.id=t.campaignid "
                    . "LEFT JOIN MonitorlyListing ml ON ml.id=t.siteid "
                    . "LEFT JOIN MediaType mt ON mt.id=ml.mediaTypeId "
                    . "LEFT JOIN User u ON u.id=t.assigneduserid";
            $tasks = Yii::app()->db->createCommand($sql)->queryAll();
            //echo '<pre>'; print_r($tasks); die();
            
            $this->render('index', array('tasks'=>$tasks));
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