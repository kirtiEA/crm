<?php

class DashboardController extends Controller
{
    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }
	public function actionIndex()
	{
		//fetch sales people
		$sales = User::fetchCompanyUsers(Yii::app()->user->cid, 6);

                //fetch brands
		$brands = CompanyBrands::model()->findAllByAttributes(array('companyid' => Yii::app()->user->cid, 'status' => 1));
                
                //fetch tags
                $tags  = CompanyTags::model()->findAllByAttributes(array('companyid' => Yii::app()->user->cid, 'status' => 1));
                
                $buckets = CompanyStatuses::model()->findAllByAttributes(array('companyid' => Yii::app()->user->cid, 'status' => 1));
                
		$this->render('index', array(
                    'sales' => $sales,
                    'brands' => $brands,
                    'tags' => $tags,
                    'buckets' => $buckets,
                ));
	}
       
        
        public function actionCreateLead() {
            $brandid = $_POST['brandid'];
            $contactid = $_POST['contactid'];
            $description = $_POST['description'];
            $assignedto = $_POST['assignedto'];
            $tags = $_POST['tags'];
            $budget = $_POST['budget'];
            $sdate = $_POST['sdate'];
            $edate = $_POST['edate'];
            
            $lead = new CompanyLeads();
            $lead->brandid = $brandid;
            $lead->contactid = $contactid;
            $lead->assignedto = $assignedto;
            $lead->tags = $tags;
            $lead->description = $description;
            $lead->companyid = Yii::app()->user->cid;
            $lead->status = 1;
            $lead->createddate = date("Y-m-d H:i:s");
            $lead->lastupdated = date("Y-m-d H:i:s");
            $lead->campaignstartdate = date("Y-m-d H:i:s", strtotime($sdate));
            $lead->campaignenddate = date("Y-m-d H:i:s", strtotime($edate));
            
            $lead->save();
            echo 1;
        }
        
}
