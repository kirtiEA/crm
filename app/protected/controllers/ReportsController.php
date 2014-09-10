<?php

class ReportsController extends Controller
{
	public function actionFetchreport()
	{
		$this->render('fetchreport');
	}
        public function init() {
            if(Yii::app()->user->isGuest) {           
                $this->redirect(Yii::app()->createUrl('account'));
            }
        }
        public function actionIndex()
	{
            $sdate = null; 
            $edate = null;
            if(isset($_POST['filter_submit'])) {
                $sdate = date("Y-m-d G:i:s", strtotime($_POST['sdate']));
                $edate = date("Y-m-d G:i:s", strtotime($_POST['edate']));
                //echo '<pre>'; print_r($_POST); die();
            }
            $cId = Yii::app()->user->cid;
            $sql = "SELECT t.id, c.id as cid, c.name as campaign, l.name as site, mt.name as mediatype, t.dueDate as duedate, "
                    . "t.taskDone as status, t.problem, u.id as uid, CONCAT(u.fname,' ', u.lname) as assignedto, t.pop "
                    . "FROM Task t "
                    . "LEFT JOIN Campaign c ON c.id=t.campaignid "
                    . "LEFT JOIN Listing l ON l.id=t.siteid "
                    . "LEFT JOIN MediaType mt ON mt.id=l.mediaTypeId "
                    . "LEFT JOIN User u ON u.id=t.assigneduserid "
                    . "WHERE t.pop=1 AND t.assignedCompanyid=$cId ";
            if(!is_null($sdate) && !is_null($edate)) {
                $sql .= " AND dueDate BETWEEN '$sdate' AND '$edate' ";
            }
         //   echo $sql;
            $tasks = Yii::app()->db->createCommand($sql)->queryAll();
            //$campaignList = array();
            //$userList = array();                        
            
            //echo '<pre>'; print_r($userList); print_r($campaignList); die();
            
            $this->render('index', array('tasks'=>$tasks));
	}
        
        public function actionAll()
	{
            $cId = Yii::app()->user->cid;
            $sql = "SELECT t.id, c.name as campaign, l.name as site, mt.name as mediatype, t.dueDate as duedate, "
                    . "t.taskDone as status, t.problem, CONCAT(u.fname,' ', u.lname) as assignedto, t.pop "
                    . "FROM Task t "
                    . "LEFT JOIN Campaign c ON c.id=t.campaignid "
                    . "LEFT JOIN Listing l ON l.id=t.siteid "
                    . "LEFT JOIN MediaType mt ON mt.id=l.mediaTypeId "
                    . "LEFT JOIN User u ON u.id=t.assigneduserid "
                    . "WHERE t.assignedCompanyid=$cId ";
            if($sdate && $edate) 
                $sql .= " AND dueDate BETWEEN $sdate AND $edate ";
            $tasks = Yii::app()->db->createCommand($sql)->queryAll();            
            $popList = array();
            
            foreach($tasks as $t) {
                
            }
            
            //echo '<pre>'; print_r($tasks); die();
            
            $this->render('all', array('tasks'=>$tasks));
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