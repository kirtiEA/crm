<?php

class MyCampaignsController extends Controller {
    
    public function init() {
        if (Yii::app()->user->isGuest) {
            $this->redirect(Yii::app()->createUrl('account'));
        }
    }

    public function actionExpired() {

        $model = new Campaign();
        /*
         * fetch campaigns of the Company
         */
        $campaigns = Campaign::fetchCompanyCampaignsName(Yii::app()->user->cid, 3);
        $finalCampaigns = array();
        //              print_r($campaigns); die();
        foreach ($campaigns as $key => $value) {
            $vendors = UserCompany::fetchVendorsInCampaign($value['id']);
            //echo count($vendors);
//                    print_r($vendors);die();
            $result = array();
            for ($i = 0; $i < count($vendors); $i++) {
                //echo $vendors[$i]['name'] . ' ww ' . $vendors[$i]['id'];
                $listings = Listing::getListingsForCampaign($vendors[$i]['id'], $value['id']);
                $temp = $vendors[$i];
                $temp['listings'] = $listings;
//                        print_r($temp);die();
                array_push($result, $temp);
            }
            $sDate = new DateTime($value['startDate']);
            $eDate = new DateTime($value['endDate']);

            $val = array(
                'id' => $value['id'],
                'name' => $value['name'],
                'startDate' => $sDate->format('d M Y'),
                'endDate' => $eDate->format('d M Y'),
                'count' => $value['count'],
                'sites' => $result
            );

            array_push($finalCampaigns, $val);
        }
        /*
         * fetch Vendors list that needs to be published
         */
        $this->render('expired', array('model' => $model, 'campaigns' => $finalCampaigns));
    }

    public function actionIndex() {
        $model = new Campaign();
        /*
         * fetch campaigns of the Company
         */
        $campaigns = Campaign::fetchCompanyCampaignsName(Yii::app()->user->cid);
        $finalCampaigns = array();
        //              print_r($campaigns); die();
        foreach ($campaigns as $key => $value) {
            $vendors = UserCompany::fetchVendorsInCampaign($value['id']);
            //echo count($vendors);
//                    print_r($vendors);die();
            $result = array();
            for ($i = 0; $i < count($vendors); $i++) {
                //echo $vendors[$i]['name'] . ' ww ' . $vendors[$i]['id'];
                $listings = Listing::getListingsForCampaign($vendors[$i]['id'], $value['id']);
                $temp = $vendors[$i];
                $temp['listings'] = $listings;
//                        print_r($temp);die();
                array_push($result, $temp);
            }
            $sDate = new DateTime($value['startDate']);
            $eDate = new DateTime($value['endDate']);

            $val = array(
                'id' => $value['id'],
                'name' => $value['name'],
                'startDate' => $sDate->format('d M Y'),
                'endDate' => $eDate->format('d M Y'),
                'count' => $value['count'],
                'sites' => $result
            );

            array_push($finalCampaigns, $val);
        }
        /*
         * fetch Vendors list that needs to be published
         */
        $this->render('index', array('model' => $model, 'campaigns' => $finalCampaigns));
    }

    public function actionUpcoming() {

        $model = new Campaign();
        /*
         * fetch campaigns of the Company
         */
        $campaigns = Campaign::fetchCompanyCampaignsName(Yii::app()->user->cid, 2);
        $finalCampaigns = array();
        //              print_r($campaigns); die();
        foreach ($campaigns as $key => $value) {
            $vendors = UserCompany::fetchVendorsInCampaign($value['id']);
            //echo count($vendors);
//                    print_r($vendors);die();
            $result = array();
            for ($i = 0; $i < count($vendors); $i++) {
                //echo $vendors[$i]['name'] . ' ww ' . $vendors[$i]['id'];
                $listings = Listing::getListingsForCampaign($vendors[$i]['id'], $value['id']);
                $temp = $vendors[$i];
                $temp['listings'] = $listings;
//                        print_r($temp);die();
                array_push($result, $temp);
            }
            $sDate = new DateTime($value['startDate']);
            $eDate = new DateTime($value['endDate']);

            $val = array(
                'id' => $value['id'],
                'name' => $value['name'],
                'startDate' => $sDate->format('d M Y'),
                'endDate' => $eDate->format('d M Y'),
                'count' => $value['count'],
                'sites' => $result
            );

            array_push($finalCampaigns, $val);
        }
        /*
         * fetch Vendors list that needs to be published
         */
        $this->render('upcoming', array('model' => $model, 'campaigns' => $finalCampaigns));
    }

    
    public function actionCreate() {
            $model = new Campaign();
                /*
                 * fetch campaigns of the Company
                 */
                $campaigns = Campaign::fetchCompanyCampaignsName(Yii::app()->user->cid,2);
                $finalCampaigns = array();
  //              print_r($campaigns); die();
                $users = User::fetchCompanyUsers(Yii::app()->user->cid);
                $usernames = [];
                foreach ($users as $user) {
                    array_push($usernames, $user['name']);
                }
                foreach ($campaigns as $key => $value) {
                    $vendors = UserCompany::fetchVendorsInCampaign($value['id']);
                    //echo count($vendors);
//                    print_r($vendors);die();
                    $result = array();
                    for($i =0; $i < count($vendors) ; $i++) {
                        //echo $vendors[$i]['name'] . ' ww ' . $vendors[$i]['id'];
                        $listings = Listing::getListingsForCampaign($vendors[$i]['id'], $value['id']);
                        //$temp = $vendors[$i];
                        $listingsFinal = array();
                        foreach ($listings as $list) {
                            $usersperlisting = Task::fetcUsersAssignedToSite($list['id'], $value['id'], Yii::app()->user->cid);
                           // print_r($usersperlisting);die();
                            $list['assignedusers'] = $usersperlisting;
                            array_push($result, $list);
                        }
                        
                       // $temp['listings'] = $listingsFinal;
//                        $temp['listings'] = $listings;
//                        print_r($temp);die();
                       // array_push($result, $listingsFinal);
                    }
                    $sDate = new DateTime($value['startDate']);
                    $eDate = new DateTime($value['endDate']);
                    
                    $val = array(
                        'id' => $value['id'],
                        'name' => $value['name'],
                        'startDate' => $sDate->format('d M Y'),
                        'endDate' => $eDate->format('d M Y'),
                        'count' => $value['count'],
                        'sites' => $result,
                        );
                        
                        array_push($finalCampaigns, $val);
                }
        /*
         * fetch Vendors list that needs to be published
         */
        $this->render('newcampaign', array('model' => $model, 'campaigns' => $finalCampaigns,'users' => $usernames));
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
