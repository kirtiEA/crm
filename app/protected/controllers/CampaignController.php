<?php

class CampaignController extends Controller {

    public function init() {
        if (Yii::app()->user->isGuest) {
            $this->redirect(Yii::app()->createUrl('account'));
        }
    }

    public function actionIndex() {
        $model = new Campaign();
        /*
         * fetch campaigns of the Company
         */
        $campaigns = Campaign::fetchCompanyCampaignsName(Yii::app()->user->cid);
        $finalCampaigns = array();
        foreach ($campaigns as $key => $value) {
            $sDate = new DateTime($value['startDate']);
            $eDate = new DateTime($value['endDate']);
            $val = array(
                'id' => $value['id'],
                'name' => $value['name'],
                'startDate' => $sDate->format('d M Y'),
                'endDate' => $eDate->format('d M Y'),
                'count' => $value['count']
            );
            array_push($finalCampaigns, $val);
        }
        /*
         * fetch Vendors list that needs to be published
         */
        $this->render('index', array('model' => $model, 'campaigns' => json_encode($finalCampaigns)));
    }

    public function actionFetchcampaign() {
        $this->render('fetchcampaign');
    }

    public function actionCreate() {
        $model = new Campaign();
        $model->setScenario('insert');
        if (isset($_POST['Campaign'])) {
            $model->attributes = $_POST['Campaign'];
            //print_r($model->validate());
            if ($model->validate()) {
                $model->createdBy = Yii::app()->user->id;
                $model->companyid = Yii::app()->user->cid;
                $model->createdDate = date("Y-m-d H:i:s");
                $model->startDate = date("Y-m-d H:i:s", strtotime($model->startDate));
                $model->endDate = date("Y-m-d H:i:s", strtotime($model->endDate));
                $model->save();
            }
            /*
             * Add flash message for success
             */
            $this->redirect(Yii::app()->getBaseUrl() . '/myCampaigns/upcoming');
        }
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
