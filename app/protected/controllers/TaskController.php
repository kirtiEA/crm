<?php

class TaskController extends Controller
{
	public function actionFetchtasks()
	{
		$this->render('fetchtasks');
	}
        
        public function actionIndex()
	{
            $model = new FilterForm();
            $tasks;
            if(isset($_POST['FilterForm'])) {
                //$model->attributes = $_POST['FilterForm'];
              //  print_r($_POST['FilterForm']); 
                $cids = $_POST['FilterForm']['campaignids'];
                $uids = $_POST['FilterForm']['userids'];
                $campaigns = null;
                $userids = null;
                if (!empty($cids) && $cids != 'null') {
                    $campaigns = implode(',', json_decode(str_replace('"', '', $cids)));
                }
                if (!empty($uids) && $uids != 'null') {
                    $userids = implode(',', json_decode(str_replace('"', '', $uids)));
                }
                
                $tasks = Task::fetchTaskList(Yii::app()->user->cid,$campaigns, $userids);
            } else {
                $tasks = Task::fetchTaskList(Yii::app()->user->cid);
            }
            
            $campaigns = Campaign::fetchCompanyCampaignsName(Yii::app()->user->cid, 4);
            //print_r(Yii::app()->user->cid);
            $users = User::fetchCompanyUsers(Yii::app()->user->cid);
            
            $this->render('index', array('tasks' => $tasks, 'campaigns' => $campaigns,'users'=> $users, 'model' => $model ));
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