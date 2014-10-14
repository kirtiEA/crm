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
            $cId = Yii::app()->user->cid;
            $sdate = null; 
            $edate = null;
            $campaignIds = null;
            $assignedTo = null;
            
            if(isset($_POST['sdate']) && $_POST['sdate']!='') {
                $sdate = $_POST['sdate'];
                //$sdate = str_replace('/', '-', $sdate);
                $sdate = date("Y-m-d", strtotime($sdate));                
            }
            if(isset($_POST['edate']) && $_POST['edate']!='') {    
                $edate = $_POST['edate'];
                //$edate = str_replace('/', '-', $edate);
                $edate = date("Y-m-d", strtotime($edate));
            }
            if(isset($_POST['campaignids']) && $_POST['campaignids']!='null') {
                $campaignIds = implode(',', json_decode(str_replace('"', '', $_POST['campaignids'])));                
            }
            if(isset($_POST['assignedto']) && $_POST['assignedto']!='null') {                
                $assignedTo = implode(',', json_decode(str_replace('"', '', $_POST['assignedto'])));                
            }
            $sql = "SELECT t.id, c.id as cid, c.name as campaign, l.name as site, mt.name as mediatype, t.dueDate as duedate, "
                    . " CONCAT(l.locality, ', ', a.name) as location, "
                    . " t.taskDone as status, t.problem, u.id as uid, CONCAT(u.fname,' ', u.lname) as assignedto, t.pop "
                    . " FROM Task t "
                    . " LEFT JOIN Campaign c ON c.id=t.campaignid "
                    . " LEFT JOIN Listing l ON l.id=t.siteid "
                    . " LEFT JOIN MediaType mt ON mt.id=l.mediaTypeId "
                    . " LEFT JOIN User u ON u.id=t.assigneduserid "
                    . " LEFT JOIN Area a ON a.id=l.cityid "
                    . " WHERE t.pop=1 AND t.assignedCompanyid=$cId "
                    . " AND l.status=1 ";
            if(!is_null($sdate) && !is_null($edate)) {
                $sql .= " AND DATE(t.dueDate) BETWEEN '$sdate' AND '$edate' ";
            } else {
                $sql .= " AND DATE(t.dueDate) <= CURRENT_DATE() ";
            }
            if(!is_null($campaignIds) && strlen($campaignIds)) {
                $sql .= " AND c.id IN ($campaignIds) ";
            }
            if(!is_null($assignedTo) && strlen($assignedTo)) {
                $sql .= " AND t.assigneduserid IN ($assignedTo) ";
            }
            $sql .= "ORDER BY t.dueDate DESC ";
            
            $tasks = Yii::app()->db->createCommand($sql)->queryAll();
            $campaignIdList = array();
            $assignedToList = array();
            $sql2 = "SELECT c.id as cid, c.name as campaign, u.id as uid, CONCAT(u.fname,' ', u.lname) as assignedto "
                    . "FROM Task t "
                    . "LEFT JOIN Campaign c ON c.id=t.campaignid "                    
                    . "LEFT JOIN User u ON u.id=t.assigneduserid "
                    . "WHERE t.assignedCompanyid=$cId "
                    . "AND DATE(t.dueDate) <= CURRENT_DATE() ";
            $filters = Yii::app()->db->createCommand($sql2)->queryAll();
            foreach($filters as $fl) {
                //echo '<pre>';
                
                if(!isset($campaignIdList[$fl['cid']])) {
                    $campaignIdList[$fl['cid']] = $fl['campaign'];
                }
                if(!isset($assignedToList[$fl['uid']])) {
                    $assignedToList[$fl['uid']] = $fl['assignedto'];
                }                
            }            
            $this->render('index', array('tasks'=>$tasks, 'campaignIdList'=>$campaignIdList, 'assignedToList'=> $assignedToList));
	}
        
        public function actionAll()
	{
            
            $cId = Yii::app()->user->cid;
            $sdate = null; 
            $edate = null;
            $campaignIds = null;
            $assignedTo = null;
            
            if(isset($_POST['sdate']) && $_POST['sdate']!='') {
                $sdate = $_POST['sdate'];
                //$sdate = str_replace('/', '-', $sdate);
                $sdate = date("Y-m-d", strtotime($sdate));                
            }
            if(isset($_POST['edate']) && $_POST['edate']!='') {    
                $edate = $_POST['edate'];
                //$edate = str_replace('/', '-', $edate);
                $edate = date("Y-m-d", strtotime($edate));
            }
            
            if(isset($_POST['campaignids']) && $_POST['campaignids']!='null') {
                $campaignIds = implode(',', json_decode(str_replace('"', '', $_POST['campaignids'])));                
            }
            if(isset($_POST['assignedto']) && $_POST['assignedto']!='null') {                
                $assignedTo = implode(',', json_decode(str_replace('"', '', $_POST['assignedto'])));                
            }
            
            $sql = "SELECT t.id, c.id as cid, c.name as campaign, l.name as site, mt.name as mediatype, t.dueDate as duedate, "
                    . " CONCAT(l.locality, ', ', a.name) as location, "
                    . " t.taskDone as status, t.problem, u.id as uid, CONCAT(u.fname,' ', u.lname) as assignedto, t.pop, IFNULL(COUNT(pp.id),0) as photocount "
                    . " FROM Task t "
                    . " LEFT JOIN Campaign c ON c.id=t.campaignid "
                    . " LEFT JOIN Listing l ON l.id=t.siteid "
                    . " LEFT JOIN MediaType mt ON mt.id=l.mediaTypeId "
                    . " LEFT JOIN User u ON u.id=t.assigneduserid "
                    . " LEFT JOIN PhotoProof pp ON pp.taskid=t.id "
                    . " LEFT JOIN Area a ON a.id=l.cityid "
                    . " WHERE t.assignedCompanyid=$cId "                    
                    . " AND l.status=1 ";
            if(!is_null($sdate) && !is_null($edate)) {
                $sql .= " AND DATE(t.dueDate) BETWEEN '$sdate' AND '$edate' ";
            } else {
                $sql .= " AND DATE(t.dueDate) <= CURRENT_DATE() ";
            }
            if(!is_null($campaignIds) && strlen($campaignIds)) {
                $sql .= " AND c.id IN ($campaignIds) ";
            }
            if(!is_null($assignedTo) && strlen($assignedTo)) {
                $sql .= " AND t.assigneduserid IN ($assignedTo) ";
            }
            $sql .= " GROUP BY t.id ";
            $sql .= " ORDER BY t.dueDate DESC ";
            //echo $sql; die();
            $tasks = Yii::app()->db->createCommand($sql)->queryAll();
            $taskArr = array();
            foreach ($tasks as $tk) {
                
            }
            
            $campaignIdList = array();
            $assignedToList = array();
            $sql2 = "SELECT c.id as cid, c.name as campaign, u.id as uid, CONCAT(u.fname,' ', u.lname) as assignedto "
                    . "FROM Task t "
                    . "LEFT JOIN Campaign c ON c.id=t.campaignid "
                    . "LEFT JOIN User u ON u.id=t.assigneduserid "
                    . "WHERE t.assignedCompanyid=$cId "
                    . "AND DATE(t.dueDate) <= CURRENT_DATE() ";
            $filters = Yii::app()->db->createCommand($sql2)->queryAll();
            foreach($filters as $fl) {
                //echo '<pre>';
                
                if(!isset($campaignIdList[$fl['cid']])) {
                    $campaignIdList[$fl['cid']] = $fl['campaign'];
                }
                if(!isset($assignedToList[$fl['uid']])) {
                    $assignedToList[$fl['uid']] = $fl['assignedto'];
                }                
            }
            $this->render('all', array('tasks'=>$tasks, 'campaignIdList'=>$campaignIdList, 'assignedToList'=> $assignedToList));
            
            
            
            $uploadFilePath = Yii::app()->params['fileUploadPath'].'Reports.pdf';
            # mPDF
            $mPDF1 = Yii::app()->ePdf->mpdf();
            # You can easily override default constructor's params
            //$mPDF1 = Yii::app()->ePdf->mpdf('', 'A5');
            # render (full page)
            $mPDF1->WriteHTML($this->renderPartial('all', array('tasks'=>$tasks, 'campaignIdList'=>$campaignIdList, 'assignedToList'=> $assignedToList), true));
            //$mPDF1->WriteHTML('<table><tr>Hello World</tr></table>');
            # Load a stylesheet
            //$stylesheet = file_get_contents(Yii::getPathOfAlias('webroot.css') . '/main.css');
            //$mPDF1->WriteHTML($stylesheet, 1);
            # renderPartial (only 'view' of current controller)
            //$mPDF1->WriteHTML($this->renderPartial('index', array(), true));
            # Renders image
            //$mPDF1->WriteHTML(CHtml::image(Yii::getPathOfAlias('webroot.css') . '/bg.gif' ));
            # Outputs ready PDF
            $mPDF1->Output($uploadFilePath, EYiiPdf::OUTPUT_TO_FILE);
            
            # HTML2PDF has very similar syntax
            /*$html2pdf = Yii::app()->ePdf->HTML2PDF();
            $html2pdf->writeHTML('<table><tr>Hello World</tr></table>');
            //$html2pdf->WriteHTML($this->renderPartial('all', array(), true));
            $html2pdf->Output(); */
            die();
            
                        
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