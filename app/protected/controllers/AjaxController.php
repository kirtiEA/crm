<?php

class AjaxController extends Controller {

    private function fetchUserReturnUrl() {
        
    }

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }
    
    public function accessRules() {
        return array(
            array('allow', // allow all users to perform actions
                'actions' => array('signup' ,'getlisting', 'getmarkers', 'vendordetails', 'retriveplan', 'getsitedetails', 'addinexistingplan', 'addplan', 'addfavorite', 'plandetail', 'deleteplanlisting','getmediatypes', 'uploadcontacts', 'vendorcontacts', 'updatevendorcontacts',
                    'PushAvailabilityMailsToQueue', 'MassUploadListingsForVendor', 'fetchvendorsites', 'massuploadsite'),
                'users' => array('*'),
            ),
        );
    }
    
    public function actionLogin() {
        $username = Yii::app()->request->getParam('usrn');
        $password = Yii::app()->request->getParam('pass');


        if (!Yii::app()->user->isGuest) {
            $returnUrl = fetchUserReturnUrl();
        } else {
            $model = new LoginForm;
            $model->setscenario('signin');   // set scenario for rules validation
            // if it is ajax validation request
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }

            // collect user input data
            if (isset($_POST['LoginForm'])) {
                $_POST['LoginForm'] = JoyUtilities::cleanInput($_POST['LoginForm']);
                $model->attributes = $_POST['LoginForm'];
                // validate user input and redirect to the previous page if valid
                if ($model->validate() && $model->login()) {
                    if (!empty($returnUrlParam)) {
                        $this->redirect($returnUrlParam);
                    } else {
                        JoyUtilities::redirectUser(Yii::app()->user->id);
                        $this->redirect(Yii::app()->user->returnUrl);
                    }
                }
            }
        }

        // return after login url
        echo $returnUrl;
    }

    public function actionFetchvendorsites() {        
        $vendorId = Yii::app()->request->getParam('vendorid');
        $sql = "SELECT l.id, l.site_code, mt.name as mediatype, a.name as city, l.locality, l.name, l.length, l.width, l.lightingid "
                . "FROM Listing l "
                . "LEFT JOIN Area a ON a.id=l.cityid "
                . "LEFT JOIN MediaType mt ON mt.id=l.mediatypeid "
                . "WHERE l.companyId = '$vendorId' ";
        $result = Yii::app()->db->createCommand($sql)->queryAll();
        //echo json_encode($result); die();
        //$result = Listing::model()->findAllByAttributes(array('companyId' => $vendorId));
        
        //print_r($result);
        if ($result) {
            $listArray = array();
            foreach ($result as $value) {                
                $list = array(
                    'id' => $value['id'],
                    'site_code' => $value['site_code'],
                    'mediatype' => $value['mediatype'],
                    'city' => $value['city'],
                    'locality' => $value['locality'],
                    'name' => $value['name'],
                    'length1' => $value['length'],
                    'width' => $value['width'],
                    'lighting' => Listing::getLighting($value['lightingid']),
                    //'vendorId' => $vendorId,
                    //'modifiedDate' => $value['datemodified']
                );
                array_push($listArray, $list);
            }
            if (count($listArray))
                echo json_encode($listArray);
            else
                echo json_encode(NULL);
        } else {
            echo json_encode(NULL);
        }
    }

    public function actionMassuploadsite() {
        // fetch all media types to match
        $mtResult = Mediatype::model()->findAll();
        $mediaTypes = array();
        foreach($mtResult as $value) {
            $mediaTypes[$value->id] = strtolower($value->name);
        }
        // fetch all lighting
        $lightings = array_map('strtolower', Listing::getLighting());        
        //print_r($lightings); die();
        
        
        $vendorId = Yii::app()->request->getParam('vendorid');
        $byUserId = Yii::app()->request->getParam('byuserid');
        $data = json_decode(Yii::app()->request->getParam('data'));
        
        $companyResult = UserCompany::model()->findByPk($vendorId, array('select'=>'userid'));        
        $forUserId = $companyResult->userid;
        
        foreach($data as $value) {
            //echo $value->mediatype;
            $mediaTypeId = array_search(strtolower($value->mediatype), $mediaTypes);            
            $lightingId = array_search(strtolower($value->lighting), $lightings);
            //$productType = UserProduct::getUserProductType(552);//$forUserId);
            
            $address = $value->locality . ',' . $value->city;
            $addressGeocode = JoyUtilities::geocode($address);
            //print_r($addressGeocode); die();
            if($addressGeocode) {
                // check if country exists        
                if ($addressGeocode['country'] != '' && $addressGeocode['country'] != null) {
                    $countryId = Area::checkAreaExists($addressGeocode['country'], 'c', null, $addressGeocode['countryCode']);
                }
                
                // check if state exists
                if (is_numeric($countryId) && $addressGeocode['state'] != '' && $addressGeocode['state'] != null) {
                    $stateId = Area::checkAreaExists($addressGeocode['state'], 's', $countryId);
                }
                
                // check if city exists
                if (is_numeric($stateId) && $addressGeocode['city'] != '' && $addressGeocode['city'] != null) {
                    $cityId = Area::checkAreaExists($addressGeocode['city'], 'ci', $stateId);
                }                
            }
            
            $listingModel = new Listing;
            $listingModel->byuserid = (int)$byUserId;
            $listingModel->foruserid = (int)$forUserId;
            $listingModel->companyId = (int)$vendorId;
            
            
            $listingModel->name = $value->name;
            $listingModel->site_code = $value->site_code;
            $listingModel->length = (int)$value->length;
            $listingModel->width = (int)$value->width;
            $listingModel->area = (int)($value->length * $value->width);
            
            $listingModel->product_type = 2;
            $listingModel->status = 0;
            $listingModel->approved = 0;
            
            $listingModel->locality = $value->locality;
            $listingModel->countryid = (int)$countryId;
            $listingModel->stateid = (int)$stateId;
            $listingModel->cityid = (int)$cityId;
            
            $listingModel->geolat = $addressGeocode['lat'];
            $listingModel->geolng = $addressGeocode['lng'];
            $listingModel->accurate_geoloc = 0;
            
            $listingModel->lightingid = (int)$lightingId;
            $listingModel->mediatypeid = (int)$mediaTypeId;
            
            $listingModel->basecurrencyid = 11;   // 11 for India
            
            $listingModel->datemodified = date('Y-m-d H:i:s');
            $listingModel->datecreated = date('Y-m-d H:i:s');
            $listingModel->save();
            
        }
        echo true;        
    }

    public function actionAddsitetocampaign() {
        $this->render('addsitetocampaign');
    }

    public function actionAssignzonetouser() {
        $this->render('assignzonetouser');
    }

    public function actionManagesites() {
        $this->render('managesites');
    }

    public function actionSiteautocomplete() {
        $this->render('siteautocomplete');
    }

    public function actionUpdatetaskassignment() {
        $this->render('updatetaskassignment');
    }

    /*
     * update user password
     */
    public function actionUpdatepassword() {
        if(isset($_POST['id']) && isset($_POST['pwd']))
	{
            //echo 'entered here';
                $id=$_POST['id'];
                $pwd=$_POST['pwd'];
                //print_r($pwd);die();
                $model=  User::model()->findByPk($id);
                $ph = new PasswordHash(Yii::app()->params['phpass']['iteration_count_log2'], Yii::app()->params['phpass']['portable_hashes']);
                $password = $ph->HashPassword($pwd);
                $result = $ph->CheckPassword($pwd, $model->password);   
                //echo $result;
                if ($result) {
                    // Authorized
                } else {
                    // Error: Unauthorized
                }
		User::model()->changePassword($id, $password);
        }
        
    }
    
    public function actionVendorsList() {
        echo json_encode(UserCompany::fetchVendorsList());
    }
    
    public function actionFetchVendorListing() {
       if($_POST['cid'] && $_POST['id']) {
           echo json_encode(Listing::getListingsForCompany($_POST['id'], $_POST['cid'])); 
       }
    }
    
    public function actionUpdateCampaign() {
        if($_POST['cid']) {
            //echo $_POST['add'] . ' -- --- ' . $_POST['rm'];
            $add = json_decode($_POST['add']);
            /*
             * find out the number of days the  campaign will run
             * for each day add each of the listing id and save
             */
            $campaign = Campaign::model()->findByPk($_POST['cid']);
            $diff = strtotime($campaign->attributes['endDate']) - strtotime($campaign->attributes['startDate']);
            echo floor($diff/(60*60*24));
            for ($i=0; $i < count($add); $i++) {
                $date = strtotime($campaign->attributes['startDate']);
                while ((strtotime($campaign->attributes['endDate']) - $date) >= 0) {
                    $task = new Task();
                    $task->assignedCompanyId = Yii::app()->user->cid;
                    $task->campaignid = $_POST['cid'];
                    $task->siteid = $add[$i];
                    $task->status = 1;
                    $task->dueDate = date("Y-m-d H:i:s", $date);
                    $task->save();
                    $date = strtotime('+1 day', $date);
                }
            }
            
            /*
             * remove sites from  campaign
             * 
             */
            $rem = json_decode($_POST['rm']);
            for ($i=0; $i < count($rem); $i++) {
                Task::removeListingFromCampaign($_POST['cid'], $rem[$i]);   
            }
            
        }
    }
    
    public function actionCampaignDetails() {
        if ($_POST['cid']) {
            $vendors = UserCompany::fetchVendorsInCampaign($_POST['cid']);
           // echo count($vendors);
            $result = array();
            for($i =0; $i < count($vendors) ; $i++) {
                //echo $vendors[$i]['name'] . ' ww ' . $vendors[$i]['id'];
                $listings = Listing::getListingsForCampaign($vendors[$i]['id'], $_POST['cid']);
                $temp = $vendors[$i];
                $temp['listings'] = $listings;
                array_push($result, $temp);
            }
            echo json_encode($result);
        }
    }
    
    public function actionRemoveListingFromCampaign() {
        if($_POST['cid'] && $_POST['sid']) {
            echo Task::removeListingFromCampaign($_POST['cid'] , $_POST['sid']);
        }
    }
    
    public function actionfetchCampaigns() {
        if($_POST['type']) {
             $campaigns = Campaign::fetchCompanyCampaignsName(Yii::app()->user->cid, $_POST['type']);
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
                echo json_encode($finalCampaigns);
        }
    }
    
    public function actionassignTaskToUser() {
        if ($_POST['uid'] && $_POST['tid']) {
            $flag = Task::model()->updateByPk($_POST['tid'], array("assigneduserid" => $_POST['uid']));
            if($flag == 1) {
                echo json_encode(Task::fetchTaskDetails($_POST['tid']));
            }
        }
    }
    
    public function actionfilterTask() {
        
    }
    /*
     * Create new user
     */
    /*public function actionCreate()
	{
		$model=new User;
                print_r($model); die();
                $role = Role::model()->findByPk(5);
                $model->datecreated=date("Y-m-d H:i:s");
                $model->datemodified=date("Y-m-d H:i:s");
                
                //print_r($today);die();
                //$userRole = Userrole::model()->insertRoles();
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if((isset($_POST['uname'])) && (isset($_POST['pwd'])) && (isset($_POST['phn'])))
		{
                    $uname=$_POST['uname'];
                    $pwd=$_POST['pwd'];
                    $phn=$_POST['phn'];
                    $name= strtok($name, " ");
                    $fname=$name[0];
                    $lname=$name[1];
                    $model->fname=$fname;
                    $model->lname=$lname;
                    $model->phonenumber=$phn;
                    $ph = new PasswordHash(Yii::app()->params['phpass']['iteration_count_log2'], Yii::app()->params['phpass']['portable_hashes']);
                    $password = $ph->HashPassword($pwd);
                    //User::model()->insertUser($model);
                    $result = $ph->CheckPassword($pwd, $model->password);   
                    //echo $result;
                    if ($result) {
                        // Authorized
                    } else {
                        // Error: Unauthorized
                    }
                    $model->password=$password;
                    Userrole::model()->insertRoles($model->id,$role);
                }

		$this->render('create',array(
			'model'=>$model,
                        'role'=>$role,
		));
	}
    */
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
