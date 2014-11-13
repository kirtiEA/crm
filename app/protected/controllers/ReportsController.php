<?php

class ReportsController extends Controller
{
    
    public function actionDownloadreport()
	{
            if( isset($_POST['campaign'])) {
                $campId = $_POST['campaign'];
                $data = Campaign::fetchCampaignReport($campId);
                $mpdf = Yii::app()->ePdf->mpdf();
                $uploadFilePath = Yii::app()->params['fileUploadPath'].'Reports.pdf';
                //$mpdf=new mPDF(); 
                $mpdf->useOnlyCoreFonts = true;    // false is default
                $mpdf->SetProtection(array('print'));
                $mpdf->SetTitle("Campaign Report");
                $mpdf->SetAuthor("Eatads");
                $mpdf->SetWatermarkText("Monitorly");
                $mpdf->showWatermarkText = true;
                $mpdf->watermark_font = 'DejaVuSansCondensed';
                $mpdf->watermarkTextAlpha = 0.1;
                $mpdf->SetDisplayMode('fullpage');
                //print_r($data);
                //changing order of WriteHTML
                $stylesheet2 = file_get_contents(Yii::getPathOfAlias('webroot.css') . '/reports/main.css');
                
                $mpdf->WriteHTML($stylesheet2, 1);
                $mpdf->WriteHTML($this->renderPartial('download', array('data' => $data), true),2);
                //$stylesheet1 = file_get_contents(Yii::getPathOfAlias('webroot.css') . '/reports/bootstrap.min.css');
                
                //print_r($stylesheet1);die();

               // $mPDF1->WriteHTML($stylesheet1, 1);
                            

                $name = $data['campaign']['name'] . '_' . date('Y-m-d').'.pdf';
                $mpdf->Output($name, EYiiPdf::OUTPUT_TO_DOWNLOAD);
            }
	}
    
	public function actionFetchreport()
	{
		$this->render('fetchreport');
	}
        public function init() {
            if(Yii::app()->user->isGuest) {           
                $this->redirect(Yii::app()->createUrl('account'));
            }
        }
        public function actionDownload() {
            $this->renderPartial('download');
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
                    . " WHERE t.status = 1 and t.pop=1 AND t.assignedCompanyid=$cId "
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
                    . "WHERE t.status = 1 and  t.assignedCompanyid=$cId "
                    . "AND DATE(t.dueDate) <= CURRENT_DATE() and u.username is not null and u.username != '' ";
            $filters = Yii::app()->db->createCommand($sql2)->queryAll();
            foreach($filters as $fl) {
                
                
                if(!isset($campaignIdList[$fl['cid']])) {
                    $campaignIdList[$fl['cid']] = $fl['campaign'];
                    //shared campaigns to  be included
                  //  print_r($campaignIdList);
                }
                if(!isset($assignedToList[$fl['uid']])) {
                    $assignedToList[$fl['uid']] = $fl['assignedto'];
                }                
            }
            $campaignsSharedWithMe = MonitorlyCampaignShare::model()->findAllByAttributes(array('email' => Yii::app()->user->email));
            $sharedcampaigns = array();
            if (!empty($campaignsSharedWithMe)) {
                $sharedCampId = array();
                foreach ($campaignsSharedWithMe as $key => $shared) {
                  //  print_r($shared);
                    array_push($sharedCampId, $shared['campaignid']);
                }
                $sharedcampaigns = Campaign::fetchCampaignsOnIds(implode(',', $sharedCampId));
            }
            foreach ($sharedcampaigns as $camp) {
                $campaignIdList[$camp['id']] = $camp['name'];
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
            } else if (Yii::app()->request->getParam('cid')) {
                $campaignIds = Yii::app()->request->getParam('cid');
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
                    . " WHERE  t.status = 1 and t.assignedCompanyid=$cId "                    
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
            //print_r($tasks);die();
            //push campaign ids shared with me to this
            $campaigns = array();
            $campaignsSharedWithMe = MonitorlyCampaignShare::model()->findAllByAttributes(array('email' => Yii::app()->user->email));
//            print_r($campaignsSharedWithMe);die();
            if (!empty($campaignsSharedWithMe)) {
                $sharedCampId = array();
                foreach ($campaignsSharedWithMe as $key => $shared) {
                   //print_r($shared);
                    array_push($sharedCampId, $shared['campaignid']);
                }
                $campaignsIdsStr = implode(',', $sharedCampId);
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
                    . " WHERE  t.status = 1 and t.assignedCompanyid != $cId "                    
                    . " AND l.status=1 ";
                    if(!is_null($sdate) && !is_null($edate)) {
                        $sql .= " AND DATE(t.dueDate) BETWEEN '$sdate' AND '$edate' ";
                    } else {
                        $sql .= " AND DATE(t.dueDate) <= CURRENT_DATE() ";
                    }
                    if(!is_null($campaignIds) && strlen($campaignIds)) {
                        $sql .= " AND c.id IN ($campaignIds) ";
                    } else {
                        $sql .= " AND c.id IN ($campaignsIdsStr) ";
                    }
                    if(!is_null($assignedTo) && strlen($assignedTo)) {
                        $sql .= " AND t.assigneduserid IN ($assignedTo) ";
                    }
                    $sql .= " GROUP BY t.id ";
                    $sql .= " ORDER BY t.dueDate DESC ";
                    $data = Yii::app()->db->createCommand($sql)->queryAll();
                    foreach ($data as $d) {
                        array_push($tasks, $d);
                    }
            }

            
            $campaignIdList = array();
            $assignedToList = array();
            $sql2 = "SELECT c.id as cid, c.name as campaign, u.id as uid, CONCAT(u.fname,' ', u.lname) as assignedto "
                    . "FROM Task t "
                    . "LEFT JOIN Campaign c ON c.id=t.campaignid "
                    . "LEFT JOIN User u ON u.id=t.assigneduserid "
                    . "WHERE  t.status = 1 and  t.assignedCompanyid=$cId "
                    . "AND DATE(t.dueDate) <= CURRENT_DATE() and u.username is not null and u.username != '' ";
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
          //  print_r($tasks);die('asdas');
            $campaignsSharedWithMe = MonitorlyCampaignShare::model()->findAllByAttributes(array('email' => Yii::app()->user->email));
            $sharedcampaigns = array();
            if (!empty($campaignsSharedWithMe)) {
                $sharedCampId = array();
                foreach ($campaignsSharedWithMe as $key => $shared) {
 //                   print_r($shared);
                    array_push($sharedCampId, $shared['campaignid']);
                }
                $sharedcampaigns = Campaign::fetchCampaignsOnIds(implode(',', $sharedCampId));
              //  print_r($sharedcampaigns);
            }
            foreach ($sharedcampaigns as $camp) {
                $campaignIdList[$camp['id']] = $camp['name'];
            }
            $this->render('all', array('tasks'=>$tasks, 'campaignIdList'=>$campaignIdList, 'assignedToList'=> $assignedToList, 'selectedCampaignIds' => $campaignIds));
  //          $uploadFilePath = Yii::app()->params['fileUploadPath'].'Reports.pdf';
            
            /*$html = '<div class="high-res-images">'.
                '<h2 class="section-heading">High Resolution Images</h2>'.
                '<br>'.
                '<h4>To get high resolution images in zip file click on the button below</h4>'.
                '<br>'.
                //'<button>DOWNLOAD HI-RES IMAGES</button>'.
                //'<button class="btn btn-primary btn-primary-lg">DOWNLOAD HI-RES IMAGES</button>'.
                '<br><br>'.
                '<h4>For low resolution summary photos, see the pages below.</h4>'.
            '</div>';*/

                        
            
            
            
//            $campId = 22;    // coke
//            // get campaign report details
//            $data = Campaign::fetchCampaignReport($campId);
//            echo '<pre>';
//           print_r($data);
            
            
            /*# HTML2PDF has very similar syntax
            $html2pdf = Yii::app()->ePdf->HTML2PDF();            
            $html2pdf->writeHTML($this->renderPartial('download', array('data' => $data), true));
            $html2pdf->Output($uploadFilePath, EYiiPdf::OUTPUT_TO_FILE);*/
            
            /*$html2pdf->WriteHTML($stylesheet, 1);
            //$html2pdf->writeHTML($html);            
            //$html2pdf->WriteHTML($this->renderPartial('download', array('path' => Yii::getPathOfAlias('webroot.css')), true));
            //$html2pdf->Output($uploadFilePath, EYiiPdf::OUTPUT_TO_FILE);            
            //print_r($var); die();*/
            
            # mPDF
//            $mPDF1 = Yii::app()->ePdf->mpdf();
//
//            # You can easily override default constructor's params
//            //$mPDF1 = Yii::app()->ePdf->mpdf('', 'A5');
//
//            # render (full page)            
//            $mPDF1->WriteHTML($this->renderPartial('download', array('data' => $data), true));
//            
//            # Load a stylesheet            
//            $stylesheet1 = file_get_contents(Yii::getPathOfAlias('webroot.css') . '/reports/bootstrap.min.css');            
//            $stylesheet2 = file_get_contents(Yii::getPathOfAlias('webroot.css') . '/reports/main.css');
//            
//            $mPDF1->WriteHTML($stylesheet1, 1);
//            $mPDF1->WriteHTML($stylesheet2, 1);            
//            
//            # Renders image
//            //$mPDF1->WriteHTML(CHtml::image(Yii::getPathOfAlias('webroot.css') . '/bg.gif' ));
//
//            # Outputs ready PDF
//            $mPDF1->Output($uploadFilePath, EYiiPdf::OUTPUT_TO_FILE);                     
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