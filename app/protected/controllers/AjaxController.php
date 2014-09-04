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
                    'PushAvailabilityMailsToQueue', 'MassUploadListingsForVendor', 'fetchvendorsites', 'massuploadsite','updatepassword','invitevendor','removeListingFromCampaign', 'updateCampaign'),),
 
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
    public function actionUpdatePassword() {
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
            
//            array_merge($add,json_decode($_POST['add']));
            /*
             * find out the number of days the  campaign will run
             * for each day add each of the listing id and save
             */
            $campaign = Campaign::model()->findByPk($_POST['cid']);
            
           // print_r($add);
            
            $diff = strtotime($campaign->attributes['endDate']) - strtotime($campaign->attributes['startDate']);
            if ($campaign['type'] != $_POST['type']) {
                $tasks = Task::fetchAllSitesInCampaign($_POST['cid']);
                for ($i=0 ; $i < count($tasks); $i++) {
                    array_push($add, $tasks[$i]['siteid']);
                }
                $add = array_unique($add);
                
                Task::deleteAllTaskForCampaign($_POST['cid']);
                Campaign::model()->updateByPk($campaign['id'], array('type' => $_POST['type']));
            }
            
            if ($_POST['type'] == 1) {
                $vendorIds = json_decode($_POST['pop']);
                if (count($add) > 0) {
                        for ($i=0; $i < count($add); $i++) {
                        $date = strtotime($campaign->attributes['startDate']);
 
                        $task = new Task();
                        //$task->assignedCompanyId = Yii::app()->user->cid;
                        $task->pop = 1;
                        $task->createdBy = Yii::app()->user->id;
                        $task->campaignid = $_POST['cid'];
                        $task->siteid = $add[$i];
                        $task->status = 1;
                        $task->dueDate = date("Y-m-d H:i:s", $date);
                        $task->save();
                    }
                }
                
                if ($vendorIds || count($vendorIds) == 0) {
                   // array_push($vendorIds, '0');
                   Task::updateTaskPopWhenNoVendorSelected(Yii::app()->user->cid, $_POST['cid']);
                }
                
                for ($i=0; $i < count($vendorIds); $i++) {
                    $companyid;
                    $assignedcompanyid;
                    if (strcasecmp(explode('_', $vendorIds[$i])[1], '0') == 0) {
                        $companyid = explode('_', $vendorIds[$i])[0];
                        $assignedcompanyid =Yii::app()->user->cid;
                    } else {
                        $companyid = explode('_', $vendorIds[$i])[0];
                        $assignedcompanyid =explode('_', $vendorIds[$i])[1];
                    }
                   // print_r($companyid);
                    Task::updateTasksForPop($_POST['cid'], $companyid,$assignedcompanyid);
                }
                echo '200';
            } else if ($_POST['type'] == 2) {
                //print_r($add);
                if (count($add) > 0) {
                    for ($i=0; $i < count($add); $i++) {
                        $date = strtotime($campaign->attributes['startDate']);
                        while ((strtotime($campaign->attributes['endDate']) - $date) >= 0) {
                            $task = new Task();
                            $task->assignedCompanyId = Yii::app()->user->cid;
                            $task->campaignid = $_POST['cid'];
                            $task->siteid = $add[$i];
                            $task->status = 1;
                            $task->dueDate = date("Y-m-d H:i:s", $date);
                            $task->pop = 0;
                            $task->save();
                            $date = strtotime('+1 day', $date);
                        }
                    }
                }
 
                echo '200';
            } else if ($_POST['type'] == 3) {
                //print_r($add);
                $vendorIds = json_decode($_POST['pop']);
                if ($vendorIds || count($vendorIds) == 0) {
                   // array_push($vendorIds, '0');
                   Task::updateTaskPopWhenNoVendorSelected(Yii::app()->user->cid, $_POST['cid']);
                }
                if (count($add) > 0) {
                    for ($i=0; $i < count($add); $i++) {
                        $date = strtotime($campaign->attributes['startDate']);
                        while ((strtotime($campaign->attributes['endDate']) - $date) >= 0) {
                            $task = new Task();
                            $task->assignedCompanyId = Yii::app()->user->cid;
                            $task->campaignid = $_POST['cid'];
                            $task->siteid = $add[$i];
                            $task->status = 1;
                            $task->dueDate = date("Y-m-d H:i:s", $date);
                            $task->pop = 0;
                            $task->save();
                            $date = strtotime('+1 day', $date);
                        }
                    }
                }
                
                
                 for ($i=0; $i < count($vendorIds); $i++) {
                     $date = strtotime($campaign->attributes['startDate']);
                    $companyid;
                    $assignedcompanyid;
                    if (strcasecmp(explode('_', $vendorIds[$i])[1], '0') == 0) {
                        $companyid = explode('_', $vendorIds[$i])[0];
                        $assignedcompanyid =Yii::app()->user->cid;
                    } else {
                        $companyid = explode('_', $vendorIds[$i])[0];
                        $assignedcompanyid =explode('_', $vendorIds[$i])[1];
                    }
 
                    Task::updateTasksForPop($_POST['cid'], $companyid,$assignedcompanyid, $date);
                }
                echo '200';
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
        //$this->redirect(Yii::app()->createUrl('/campaign'));
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
    
//    public function actiongetlisting() {
//        $type = $_POST['type'];
//        
//    }
    
//    public function actionGetListing() {
//
//        echo json_encode($data);
//    }
    
    public function actiongetListing() {
 
        $metaKeyword = $pageTitle = '';
        // default solrUrl
        $solrParams = array('fq' => '');
        //companyId
        $companyid = $_POST['companyid'];
        //echo $companyid;die();
        // filter media type 
 
        $mediaTypeParam = '';
        if (!empty($_POST['mediatypeid'])) {
            $mediaTypeParam = $_POST['mediatypeid'];
            $mediaTypeId = null;
            if (!empty($mediaTypeParam) && is_array($mediaTypeId = explode(",", $mediaTypeParam))) {
                //$solrParams['fq'] .= (!empty($solrParams['fq'])) ? ' AND ' : '';
                $solrParams['fq'] .= '(';
                foreach ($mediaTypeId as $mt) {
                    if (is_numeric($mt))     // to remove 'multiselect-all'
                        $solrParams['fq'] .= ' mediatypeid:' . $mt . ' OR';
                }
                $solrParams['fq'] = rtrim($solrParams['fq'], 'OR');
                $solrParams['fq'] .= ')';
            }
        }
 
 
 
        //companyid
        $solrParams['fq'] .= (!empty($solrParams['fq'])) ? ' AND ' : '';
        $solrParams['fq'] .= ' companyid:' . $companyid;
 
        //lightingid
        $lightTypeParam = '';
        if (!empty($_POST['lightingid'])) {
            $lightTypeParam = $_POST['lightingid'];
            $lightTypeId = null;
            if (!empty($lightTypeParam) && is_array($lightTypeId = explode(",", $lightTypeParam))) {
                //$solrParams['fq'] .= (!empty($solrParams['fq'])) ? ' AND ' : '';
                $solrParams['fq'] .= ' AND (';
                foreach ($lightTypeId as $mt) {
                    if (is_numeric($mt))     // to remove 'multiselect-all'
                        $solrParams['fq'] .= ' lightingid:' . $mt . ' OR';
                }
                $solrParams['fq'] = rtrim($solrParams['fq'], 'OR');
                $solrParams['fq'] .= ')';
            }
        }
 
 
        // filter price slider 
        $priceSlider = '';
        if (!empty($_POST['priceslider'])) {
            $priceSlider = explode(':', $_POST['priceslider']);
            if (count($priceSlider) > 1) {
                // base on currency selected conv to usd to compare weeklyprice
                $newMinPrice = round(Yii::app()->openexchanger->convertCurrency($priceSlider[0], $this->ipCurrencyCode, 'INR'));
                $newMaxPrice = round(Yii::app()->openexchanger->convertCurrency($priceSlider[1], $this->ipCurrencyCode, 'INR'));
                //$criteria['condition']  .= ' AND weeklyprice between '.$priceSlider[0]. ' AND '. $priceSlider[1];
                $solrParams['fq'] .= (!empty($solrParams['fq'])) ? ' AND ' : '';
                $solrParams['fq'] .= 'weeklyprice:[' . $newMinPrice . ' TO ' . $newMaxPrice . ']';
                //print_r($solrParams);die();
            }
        }
 
 
        // proximity
        $proximity = is_numeric(Yii::app()->request->getQuery('proximity')) ? (int) Yii::app()->request->getQuery('proximity') : Yii::app()->params['proximity'];
        // geoloc
        if (!empty($_POST['Lat']) && !empty($_POST['Lng'])) {
            $geoloc = $_POST['Lat'] . ',' . $_POST['Lng'];
            if (!empty($geoloc)) {
                $solrParams['fq'] .= " AND {!geofilt pt=$geoloc sfield=geoloc d=$proximity}";
            }
        }
 
 
 
//Sorting
        if (!empty($_POST['sort'])) {
            $filter = '';
            if ($_POST['sort'] === 'Price') {
                $filter = 'weeklyprice asc';
            } else if ($_POST['sort'] === 'Popularity') {
                $filter = 'pscore desc';
            } else if ($_POST['sort'] === 'Most Recent') {
                $filter = 'datemodified desc';
            }
            $solrParams['sort'] = $filter;
        }
 
        // solr query 
        $textSearch = '';
        $solrQuery = '';
        if (!empty($_POST['textsearch'])) {
            $textSearch = $_POST['textsearch'];
            if (!empty($textSearch)) {
                $solrQuery = "name:*{$textSearch}* OR description:*{$textSearch}* OR mediatype:*{$textSearch}* OR audiencetag:*{$textSearch}*";
            } else {
                $solrQuery = '*:*';
            }
        } else {
            $solrQuery = '*:*';
        }
 
 
        //$solrParams['rows'] = 5;
        // get listing from Solr                
        //$result = Yii::app()->listingSearch->get($solrQuery, 0, 50000, $solrParams);
        // load from 0 if markers already loaded is not in $_GET
        $marker_loaded = (int) Yii::app()->request->getQuery('marker_loaded');
        $marker_loaded = ($marker_loaded > 0) ? $marker_loaded : 0;
 
        // how many to load - next_toload_count not there then default load count
        $next_toload_count = (int) Yii::app()->request->getQuery('next_toload_count');
        $init_markers = ($next_toload_count > 0) ? $next_toload_count : Yii::app()->params['init_markers'];
 
 
        $solrParams['wt'] = 'json';
        //$params['json.nl'] = 'map';
        //$solrParams['fl'] = 'id,lat,lng,ea';
        $solrParams['q'] = $solrQuery;
        if (!empty($_POST['start'])) {
            $solrParams['start'] =  $_POST['start'];
        } else {
            $solrParams['start'] =  0;
        }
         // $marker_loaded; //0;
        $solrParams['rows'] = 30; // $init_markers; //50000;
 
        $qp = http_build_query($solrParams, null, '&');
 
        // >>> curl query
        $ch = curl_init();
        $url = Yii::app()->params['solrCurl'] . $qp;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);            // Include header in result? (0 = yes, 1 = no)            
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Should cURL return or print out the data? (true = return, false = print)
        //curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $result = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($result);
        $finalresult = array();
        $data = array();
        foreach ($res->response->docs as $doc) {
            $singleDocs = array();
            $doc->thumbnail = JoyUtilities::getAwsFileUrl('small_' . $doc->filename, 'listing');
            $doc->type = $doc->mediatype;
 
            if (!empty($_POST['userid'])) {
                $favListModal = FavouriteListing::model()->findByAttributes(array('userid' => $_POST['userid'], 'listingid' => '' . $doc->id));
                if ($favListModal) {
                    $doc->is_favByUser = 1;
                }
            }
 
            $singleDocs = (array) $doc;
            array_push($data, $singleDocs);
        }
 
        $finalresult['SiteListing'] = $data;
        echo json_encode($data);
    }
    
    
    public function actiongetmarkers() {
        $metaKeyword = $pageTitle = '';
        // default solrUrl
        $solrParams = array('fq' => '');
        //companyId
        $companyid = $_POST['companyid'];
 
        // filter media type 
 
        $mediaTypeParam = '';
        if (!empty($_POST['mediatypeid'])) {
            $mediaTypeParam = $_POST['mediatypeid'];
            $mediaTypeId = null;
            if (!empty($mediaTypeParam) && is_array($mediaTypeId = explode(",", $mediaTypeParam))) {
                //$solrParams['fq'] .= (!empty($solrParams['fq'])) ? ' AND ' : '';
                $solrParams['fq'] .= '(';
                foreach ($mediaTypeId as $mt) {
                    if (is_numeric($mt))     // to remove 'multiselect-all'
                        $solrParams['fq'] .= ' mediatypeid:' . $mt . ' OR';
                }
                $solrParams['fq'] = rtrim($solrParams['fq'], 'OR');
                $solrParams['fq'] .= ')';
            }
        }
 
 
 
        //companyid
        $solrParams['fq'] .= (!empty($solrParams['fq'])) ? ' AND ' : '';
        $solrParams['fq'] .= ' companyid:' . $companyid;
 
        //lightingid
        $lightTypeParam = '';
        if (!empty($_POST['lightingid'])) {
            $lightTypeParam = $_POST['lightingid'];
            $lightTypeId = null;
            if (!empty($lightTypeParam) && is_array($lightTypeId = explode(",", $lightTypeParam))) {
                //$solrParams['fq'] .= (!empty($solrParams['fq'])) ? ' AND ' : '';
                $solrParams['fq'] .= ' AND (';
                foreach ($lightTypeId as $mt) {
                    if (is_numeric($mt))     // to remove 'multiselect-all'
                        $solrParams['fq'] .= ' lightingid:' . $mt . ' OR';
                }
                $solrParams['fq'] = rtrim($solrParams['fq'], 'OR');
                $solrParams['fq'] .= ')';
            }
        }
 
 
        // filter price slider 
        $priceSlider = '';
        if (!empty($_POST['priceslider'])) {
            $priceSlider = explode('-', $_POST['priceslider']);
            if (count($priceSlider) > 1) {
                // base on currency selected conv to usd to compare weeklyprice
                $newMinPrice = round(Yii::app()->openexchanger->convertCurrency($priceSlider[0], $this->ipCurrencyCode, 'USD'));
                $newMaxPrice = round(Yii::app()->openexchanger->convertCurrency($priceSlider[1], $this->ipCurrencyCode, 'USD'));
                //$criteria['condition']  .= ' AND weeklyprice between '.$priceSlider[0]. ' AND '. $priceSlider[1];
                $solrParams['fq'] .= (!empty($solrParams['fq'])) ? ' AND ' : '';
                $solrParams['fq'] .= 'weeklyprice:[' . $newMinPrice . ' TO ' . $newMaxPrice . ']';
            }
        }
 
 
        // proximity
        $proximity = is_numeric(Yii::app()->request->getQuery('proximity')) ? (int) Yii::app()->request->getQuery('proximity') : Yii::app()->params['proximity'];
 
        // geoloc
        if (!empty($_POST['Lat']) && !empty($_POST['Lng'])) {
            $geoloc = $_POST['Lat'] . ',' . $_POST['Lng'];
            if (!empty($geoloc)) {
                $solrParams['fq'] .= " AND {!geofilt pt=$geoloc sfield=geoloc d=$proximity}";
            }
        }
 
 
//Sorting
        if (!empty($_POST['sort'])) {
            $filter = '';
            if ($_POST['sort'] === 'Price') {
                $filter = 'weeklyprice asc';
            } else if ($_POST['sort'] === 'Popularity') {
                $filter = 'pscore desc';
            } else if ($_POST['sort'] === 'Most Recent') {
                $filter = 'datemodified desc';
            }
            $solrParams['sort'] = $filter;
        }
 
        // solr query 
        $textSearch = '';
        $solrQuery = '';
        if (!empty($_POST['textsearch'])) {
            $textSearch = $_POST['textsearch'];
            if (!empty($textSearch)) {
                $solrQuery = "name:*{$textSearch}* OR description:*{$textSearch}* OR mediatype:*{$textSearch}* OR audiencetag:*{$textSearch}*";
            } else {
                $solrQuery = '*:*';
            }
        } else {
            $solrQuery = '*:*';
        }
 
 
        //$solrParams['rows'] = 5;
        // get listing from Solr                
        //$result = Yii::app()->listingSearch->get($solrQuery, 0, 50000, $solrParams);
        // load from 0 if markers already loaded is not in $_GET
        $marker_loaded = (int) Yii::app()->request->getQuery('marker_loaded');
        $marker_loaded = ($marker_loaded > 0) ? $marker_loaded : 0;
 
        // how many to load - next_toload_count not there then default load count
        $next_toload_count = (int) Yii::app()->request->getQuery('next_toload_count');
        $init_markers = ($next_toload_count > 0) ? $next_toload_count : Yii::app()->params['init_markers'];
 
 
        $solrParams['wt'] = 'json';
        //$params['json.nl'] = 'map';
        $solrParams['fl'] = 'id,lat,lng,ea';
        $solrParams['q'] = $solrQuery;
        $solrParams['start'] = 0; // $marker_loaded; //0;
        $solrParams['rows'] = 500000; // $init_markers; //50000;
 
        $qp = http_build_query($solrParams, null, '&');
 
        // >>> curl query
        $ch = curl_init();
        $url = Yii::app()->params['solrCurl'] . $qp;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);            // Include header in result? (0 = yes, 1 = no)            
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Should cURL return or print out the data? (true = return, false = print)
        //curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $result = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($result);
        $markerlist = array();
 
    //        $markerlist['Markerslist'] = $res->response->docs;
        $cnt = json_encode(count($res->response->docs));
        //Change the result json  into array
 
        for ($i=0; $i < $cnt; $i++) {
            //echo "json_encode();die()";
             $tempResponse = $res->response->docs[$i];
            // //print_r($tempResponse['id']);die();
             //echo json_encode($tempResponse);
            $arr = array();
            $arr[0] = $tempResponse->id;
            $arr[1] = $tempResponse->lat;
            $arr[2] = $tempResponse->lng;
            $arr[3] = $tempResponse->ea;
 
            array_push($markerlist, $arr);
        }
        echo json_encode($markerlist);
    }
    
    /*
     * invite vendor
     */
    public function actionInviteVendor() {
        $email = Yii::app()->request->getParam('email');
//      print_r($_POST); 
        if(strlen($email) && filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $id=Yii::app()->user->id;
            //$mail=  Yii::app()->user->email;  
            $invite= new Monitorlynotification();
            $invite->attributes = array('typeid'=>1,'createddate'=>date("Y-m-d H:i:s"),'createdby'=>$id,'emailtypeid'=>1);
            $invite->save();
            $resetLink= Yii::app()->getBaseUrl(true).'/subscription?nid='.$invite->id;
            $mail = new EatadsMailer('invite', $email, array('resetLink'=>$resetLink), array('sales@eatads.com'));
            $mail->eatadsSend();
            
        }
        else{
            echo 0;
            //wrong email address den do something
        }
    }
    
}