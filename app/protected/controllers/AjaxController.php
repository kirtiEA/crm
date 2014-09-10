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
                'actions' => array('signup', 'getlisting', 'getmarkers', 'vendordetails', 'retriveplan', 'getsitedetails', 'addinexistingplan', 'addplan', 'addfavorite', 'plandetail', 'deleteplanlisting', 'getmediatypes', 'uploadcontacts', 'vendorcontacts', 'updatevendorcontacts',
                    'PushAvailabilityMailsToQueue', 'MassUploadListingsForVendor', 'fetchvendorsites', 'massuploadsite', 'updatepassword', 'invitevendor', 'removeListingFromCampaign', 'updateCampaign'),
                'users' => array('*'),
            )
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

    public function actionFetchppimages() {
        $taskId = Yii::app()->request->getParam('taskid');
        $dueDate = Yii::app()->request->getParam('duedate');
        $sql = "SELECT pp.id, pp.imageName, pp.clickedDateTime, pp.clickedLat, pp.clickedLng, CONCAT(u.fname, u.lname) as clickedBy, pp.installation, "
                . "pp.lighting, pp.obstruction, pp.comments "
                . "FROM PhotoProof pp "
                . "LEFT JOIN User u ON u.id=pp.clickedBy "
                . "WHERE pp.taskid = '$taskId' "
                . "AND DATE_FORMAT(pp.clickedDateTime, '%Y-%m-%d') = '$dueDate' ";
        $photoProofResult = Yii::app()->db->createCommand($sql)->queryAll();
        $photoProofArr = array();
        foreach ($photoProofResult as $pp) {
            $photoProof = array(
                'id' => $pp['id'],
                'imageName' => JoyUtilities::getAwsFileUrl('big_' . $pp['imageName'], 'listing'),
                'clickedDateTime' => $pp['clickedDateTime'],
                'clickedLat' => $pp['clickedLat'],
                'clickedLng' => $pp['clickedLng'],
                'clickedBy' => $pp['clickedBy'],
                'installation' => $pp['installation'],
                'lighting' => $pp['lighting'],
                'obstruction' => $pp['obstruction'],
                'comments' => $pp['comments'],
            );
            array_push($photoProofArr, $photoProof);
        }

        //$imagePath = JoyUtilities::getAwsFileUrl('tiny_'.$data->filename, 'listing');
        echo json_encode($photoProofArr);
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
        foreach ($mtResult as $value) {
            $mediaTypes[$value->id] = strtolower($value->name);
        }
        // fetch all lighting
        $lightings = array_map('strtolower', Listing::getLighting());
        //print_r($lightings); die();


        $vendorId = Yii::app()->request->getParam('vendorid');
        $byUserId = Yii::app()->user->id;
        $data = json_decode(Yii::app()->request->getParam('data'));

        $companyResult = UserCompany::model()->findByPk($vendorId, array('select' => 'userid'));
        $forUserId = $companyResult->userid;
        foreach ($data as $value) {
          //  echo $value->id . ',' . strcmp($vendorId, Yii::app()->user->cid);
            $mediaTypeId = array_search(strtolower($value->mediatype), $mediaTypes);
            $lightingId = array_search(strtolower($value->lighting), $lightings);
            //$productType = UserProduct::getUserProductType(552);//$forUserId);

            $address = $value->locality . ',' . $value->city;
            $addressGeocode = JoyUtilities::geocode($address);
            $countryId = 1;
            $stateId = 1;
            $cityId = 1;
            //echo (json_encode($addressGeocode)) . '<pre>'; 
            if ($addressGeocode) {
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


            if (Yii::app()->user->cid == $vendorId) {
                $status = 1;
                $approved = 1;
            } else {
                $status = 0;
                $approved = 0;
                $invite = new MonitorlyNotification();
                $invite->attributes = array('typeid' => "", 'createddate' => date("Y-m-d H:i:s"), 'createdby' => $vendorId, 'emailtypeid' => 3);
                $invite->save();
                $email = UserCompany::fetchVendorEmail($vendorId);
                $mail = new EatadsMailer('approve-sites', $email['email'], array('resetLink' => ""), array('sales@eatads.com'));
                $mail->eatadsSend();
            }

            $listingModel = new Listing;
            $listingModel->byuserid = (int) $byUserId;
            $listingModel->foruserid = (int) $forUserId;
            $listingModel->companyId = (int) $vendorId;


            $listingModel->name = $value->name;
            $listingModel->site_code = $value->site_code;
            $listingModel->length = (int) $value->length;
            $listingModel->width = (int) $value->width;
            $listingModel->area = (int) ($value->length * $value->width);

            $listingModel->product_type = 2;
            $listingModel->status = $status;
            $listingModel->approved = $approved;

            $listingModel->locality = $value->locality;
            $listingModel->countryid = (int) $countryId;
            $listingModel->stateid = (int) $stateId;
            $listingModel->cityid = (int) $cityId;
            
            if (!array_key_exists('lat', $addressGeocode)) {
                $addressGeocode['lat'] = 0.0;
            }
            if (!array_key_exists('lng', $addressGeocode)) {
                $addressGeocode['lng'] = 0.0;
            }
            $listingModel->geolat = $addressGeocode['lat'];
            $listingModel->geolng = $addressGeocode['lng'];
            $listingModel->accurate_geoloc = 0;

            $listingModel->lightingid = (int) $lightingId;
            $listingModel->mediatypeid = (int) $mediaTypeId;

            $listingModel->basecurrencyid = 11;   // 11 for India

            $listingModel->datemodified = date('Y-m-d H:i:s');
            
            if (empty($value->id)) {
                $listingModel->datecreated = date('Y-m-d H:i:s');
                $listingModel->save();
            } else if (!empty($value->id) && strcmp($listingModel->companyId, Yii::app()->user->cid) ==0){
                //$listingModel->id = $value->id;
               // $model = Listing::model()->findByPk($value->id);
//                 $model->byuserid = (int) $byUserId;
//                $model->foruserid = (int) $forUserId;
//                $model->companyId = (int) $vendorId;

                $model = array();
                $model['name'] = $value->name;
                $model['site_code'] = $value->site_code;
                $model['length'] = (int) $value->length;
                $model['width'] = (int) $value->width;
                $model['area'] = (int) ($value->length * $value->width);

                $model['product_type'] = 2;
//                $model->status = $status;
//                $model->approved = $approved;

                $model['locality'] = $value->locality;
                if ($countryId != 0) {
                    $model['countryid'] = (int) $countryId;
                }
                if ($stateId != 0) {
                    $model['stateid'] = (int) $stateId;
                }
                if ($cityId != 0) {
                    $model['cityid'] = (int) $cityId;
                }

                
                
                $model['geolat'] = $addressGeocode['lat'];
                $model['geolng'] = $addressGeocode['lng'];
       //         $model['accurate_geoloc'] = 0;

                $model['lightingid'] = (int) $lightingId;
                $model['mediatypeid'] = (int) $mediaTypeId;

//                $model->basecurrencyid = 11;   // 11 for India

                $model['datemodified'] = date('Y-m-d H:i:s');
                
//                $model->update();
                Listing::model()->updateByPk($value->id, $model);
            }
//            usleep(250000);
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
        if (isset($_POST['id']) && isset($_POST['pwd'])) {

            //echo 'entered here';
            $id = $_POST['id'];
            $pwd = $_POST['pwd'];
            //print_r($pwd);die();
            $model = User::model()->findByPk($id);
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
        echo json_encode(UserCompany::fetchVendorsList(Yii::app()->user->cid));
    }

    public function actionFetchVendorListing() {
        if ($_POST['cid'] && $_POST['id']) {
            echo json_encode(Listing::getListingsForCompany($_POST['id'], $_POST['cid']));
        }
    }

    public function actionUpdateCampaign() {
        if ($_POST['cid']) {

            $add = json_decode($_POST['add']);
            $campaign = Campaign::model()->findByPk($_POST['cid']);

            $diff = strtotime($campaign->attributes['endDate']) - strtotime($campaign->attributes['startDate']);
            if ($campaign['type'] != $_POST['type']) {
                $tasks = Task::fetchAllSitesInCampaign($_POST['cid']);
                for ($i = 0; $i < count($tasks); $i++) {
                    array_push($add, $tasks[$i]['siteid']);
                }
                $add = array_unique($add);

                Task::deleteAllTaskForCampaign($_POST['cid']);
                Campaign::model()->updateByPk($campaign['id'], array('type' => $_POST['type']));
            }

            if ($_POST['type'] == 1) {
                $vendorIds = json_decode($_POST['pop']);
                if (count($add) > 0) {
                    for ($i = 0; $i < count($add); $i++) {
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

                for ($i = 0; $i < count($vendorIds); $i++) {
                    $companyid;
                    $assignedcompanyid;
                    $inputVendorIds = explode('_', $vendorIds[$i]);
                    if (strcasecmp($inputVendorIds[1], '0') == 0) {
                        $companyid = $inputVendorIds[0];
                        $assignedcompanyid = Yii::app()->user->cid;
                    } else {
                        $companyid = $inputVendorIds[0];
                        $assignedcompanyid = $inputVendorIds[1];
                    }
                    print_r($companyid . ' SDF ' . $assignedcompanyid . ' sfds ' . $_POST['cid'] . '   ');
                    print_r(Task::updateTasksForPop($_POST['cid'], $companyid, $assignedcompanyid));
                }
                echo '200';
            } else if ($_POST['type'] == 2) {
//print_r($add);
                if (count($add) > 0) {
                    for ($i = 0; $i < count($add); $i++) {
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

                if (count($add) > 0) {
                    for ($i = 0; $i < count($add); $i++) {
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

                if ($vendorIds || count($vendorIds) == 0) {
// array_push($vendorIds, '0');
                    Task::updateTaskPopWhenNoVendorSelected(Yii::app()->user->cid, $_POST['cid']);
                }
                for ($i = 0; $i < count($vendorIds); $i++) {
                    $date = strtotime($campaign->attributes['startDate']);
                    $companyid;
                    $assignedcompanyid;
                    $inputVendorIds = explode('_', $vendorIds[$i]);
                    if (strcasecmp($inputVendorIds[1], '0') == 0) {
                        $companyid = $inputVendorIds[0];
                        $assignedcompanyid = Yii::app()->user->cid;
                    } else {
                        $companyid = $inputVendorIds[0];
                        $assignedcompanyid = $inputVendorIds[1];
                    }
                    print_r($companyid . ' SDF ' . $assignedcompanyid . ' sfds ' . $_POST['cid'] . '   ' . $date . ' ');
                    print_r(Task::updateTasksForPop($_POST['cid'], $companyid, $assignedcompanyid, date("Y-m-d H:i:s", $date)));
                }
                echo '200';
            }

            $rem = json_decode($_POST['rm']);
            for ($i = 0; $i < count($rem); $i++) {
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
            for ($i = 0; $i < count($vendors); $i++) {
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
        if ($_POST['cid'] && $_POST['sid']) {
            echo Task::removeListingFromCampaign($_POST['cid'], $_POST['sid']);
        }
    }

    public function actionfetchCampaigns() {
        if ($_POST['type']) {
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
            if ($flag == 1) {
                echo json_encode(Task::fetchTaskDetails($_POST['tid']));
            }
        }
    }

    public function actiongetListing() {
        $type = $_POST['type'];
        $start = $_POST['start'];

        //$rows = $_POST['rows'];
        if ($type == 1) {
            //for all my accepted vendors listings
            $data = Listing::getListingsForAcceptedVendors(Yii::app()->user->cid, $start);
            $result = array();
            foreach ($data as $key => $value) {
                $value['lighting'] = Listing::getLighting($value['lightingid']);
                if ($value['sizeunitid'] == 0) {
                    $value['sizeunit'] = Listing::getSizeUnit(1);
                } else {
                    $value['sizeunit'] = Listing::getSizeUnit($value['sizeunitid']);
                }
                array_push($result, $value);
            }
            echo json_encode($result);
        } else if ($type == 3) {
            
        }
    }

    public function actiongetmarkers() {
        $type = $_POST['type'];
        if ($type == 1) {
            $data = Listing::getListingsForAcceptedVendors(Yii::app()->user->cid, 0);
            $result = array();
            foreach ($data as $key => $value) {
                $result[0] = $value['id'];
                $result[1] = $value['lat'];
                $result[2] = $value['lng'];
            }
            echo json_encode($result);
        } else if ($type == 3) {
            $data = Listing::getSitesTobeApprovedMarkers(Yii::app()->user->cid, null);
            $result = array();
            foreach ($data as $key => $value) {
                $result[0] = $value['id'];
                $result[1] = $value['lat'];
                $result[2] = $value['lng'];
            }
            echo json_encode($result);
        }
    }

    /*
     * invite vendor
     */

    public function actionInviteVendor() {
        $email = Yii::app()->request->getParam('email');
      
        if (strlen($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $id = Yii::app()->user->id;
            //$mail=  Yii::app()->user->email;  
            $invite = new MonitorlyNotification();
            $invite->attributes = array('typeid' => 1, 'createddate' => date("Y-m-d H:i:s"), 'createdby' => $id, 'emailtypeid' => 1);
            $invite->save();
            $resetLink = Yii::app()->getBaseUrl(true) . '/subscription?nid=' . $invite->id;
            $mail = new EatadsMailer('invite', $email, array('resetLink' => $resetLink), array('sales@eatads.com'));
            $mail->eatadsSend();
        } else {
            echo 0;
            //wrong email address den do something
        }
    }

    public function actionRequestedVendor() {
        if (isset($_POST['vendorid']) && isset($_POST['companyid'])) {
            $companyid = $_POST['companyid'];
            $vendorcompanyid = $_POST['vendorid'];
            $check = RequestedCompanyVendor::checkUniqueVendor($companyid, $vendorcompanyid);
            if (strcasecmp($check['cnt'], '0') == 0) {
                $model = new RequestedCompanyVendor();
                $model->attributes = array(
                    'companyid' => Yii::app()->user->cid,
                    'createdby' => Yii::app()->user->id,
                    'createddate' => date("Y-m-d H:i:s"),
                    'vendorcompanyid' => $vendorcompanyid,
                );
                $model->save();
                
                $invite = new MonitorlyNotification();
                $email = UserCompany::fetchVendorEmail($vendorcompanyid);
               // print_r($email['email']); die();
                //$email = "root@localhost.com";
                $resetlink = Yii::app()->getBaseUrl(true) . '/waitingApproval';
                $invite->attributes = array('typeid' => "", 'createddate' => date("Y-m-d H:i:s"), 'createdby' => $companyid, 'emailtypeid' => 2);
                $invite->createdby = Yii::app()->user->id;
                $invite->createddate = date("Y-m-d H:i:s");
                $invite->save();
                $mail = new EatadsMailer('request-vendor', $email['email'], array('resetLink' => $resetlink), array('sales@eatads.com'));
                $mail->eatadsSend();
                echo '200';
            } else {
                echo 'Vendor already invited';
            }
        }
    }

    public function actionAcceptRequest() {
        if (isset($_POST['vendorcompanyid']) && isset($_POST['id']) && isset($_POST['emailid'])) {
            $vcid = $_POST['vendorcompanyid'];
            $id = $_POST['id'];
            $email = $_POST['emailid'];
            //echo $email; die();
            $model = RequestedCompanyVendor::model()->findByPk($id);
            $model->acceptedby = $vcid;
            $model->accepteddate = date("Y-m-d H:i:s");
            $model->save();
            $invite = new MonitorlyNotification();
            //$email = UserCompany::fetchVendorEmail($vendorcompanyid);
            //$resetlink = Yii::app()->getBaseUrl(true) . '/waitingApproval';
            $invite->attributes = array('typeid' => "", 'createddate' => date("Y-m-d H:i:s"), 'createdby' => Yii::app()->user->id, 'emailtypeid' => 2);
            $invite->save();
            $mail = new EatadsMailer('invite-accepted', $email, array('resetLink' => ""), array('shruti@eatads.com'));
            $mail->eatadsSend();
            echo 200;
        }
    }

    public function actionApproveListingRequest() {
        if ($_POST['id']) {
            Listing::updateListing($_POST['id']);
        }
    }

}
