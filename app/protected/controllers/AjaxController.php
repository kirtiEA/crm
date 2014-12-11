<?php

class AjaxController extends Controller {

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
                    'PushAvailabilityMailsToQueue', 'MassUploadListingsForVendor', 'fetchvendorsites', 'massuploadsite', 'updatepassword',
                    'invitevendor', 'removeListingFromCampaign', 'updateCampaign', 'forgotpwd', 'verifyresethash',
                    'resetpwd', 'fetchNotifications', 'fetchVendorListing', 'assignCampaignSiteToUser', 'shareCampaignWithEmails',
                    'filterTasks', 'filterAllReports'),
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

    public function actionForgotpwd() {
        $email = Yii::app()->request->getParam('email');

        // get the userid from the entered email
        $userModel = User::model()->find(array('condition' => 'email=:email', 'params' => array(':email' => $email), 'select' => 'id'));
        if ($userModel) {
            $userId = $userModel->id;
            // generate the hash
            $hash = sha1(uniqid());
            // generate reset link
            $linkModel = new Link();
            $linkModel->attributes = array('userid' => $userId, 'hash' => $hash, 'type' => 0, 'datecreated' => date('Y-m-d H:i:s'));
            if ($linkModel->save()) {
                // send reset pwd link email to user    
                $resetLink = Yii::app()->createAbsoluteUrl('account/index', array('code' => $hash));
                $mail = new EatadsMailer('forgot-pwd', $email, array('resetLink' => $resetLink));
                $mail->eatadsSend();
                // show success message
                echo 1;
            } else {
                // show error message
                echo 2;
            }
        } else {
            // show error message
            echo 3;
        }
    }

    public function actionVerifyresethash() {
        $hash = Yii::app()->request->getParam('hash');
        $linkModel = Link::model()->find('hash=:hash AND type=:type', array(':hash' => $hash, ':type' => 0));
        if ($linkModel) {
            if ($linkModel->expired == 0) {
                // check if link has not expired
                $timeDiff = (time() - strtotime($linkModel->datecreated)) / 3600;
                //$linkModel->expired = 1;    // expire the link
                //$linkModel->save();         // save the record
                // check link expiration time set in config
                if ($timeDiff < Yii::app()->params['linkexpiry']['forgot']) {
                    echo 1;
                } else {
                    echo 2;
                }
            } else {
                echo 3;
            }
        } else {
            echo 4;
        }
    }

    public function actionResetpwd() {
        $hash = Yii::app()->request->getParam('hash');
        $password = Yii::app()->request->getParam('password');
        $linkModel = Link::model()->find('hash=:hash AND type=:type', array(':hash' => $hash, ':type' => 0));

        // if link not expired
        if ($linkModel) {
            if ($linkModel->expired == 0) {
                // check if link has not expired
                $timeDiff = (time() - strtotime($linkModel->datecreated)) / 3600;
                $linkModel->expired = 1;    // expire the link
                $linkModel->save();         // save the record
                // check link expiration time set in config
                if ($timeDiff < Yii::app()->params['linkexpiry']['forgot']) {
                    // update the password for the user
                    $userModel = User::model()->findByPk($linkModel->userid);
                    // CHANGE THE PASSWORD HASHING METHOD
                    $ph = new PasswordHash(Yii::app()->params['phpass']['iteration_count_log2'], Yii::app()->params['phpass']['portable_hashes']);
                    $userModel->password = $ph->HashPassword($password);
                    $userModel->update();
//                    $userModel->save();
                    // login & redirect from server

                    $identity = new UserIdentity($userModel->email, $password);
                    if ($identity->authenticate()) {
                        $user = Yii::app()->user;
                        $user->login($identity);
                        //$this->redirect($user->returnUrl);
                        echo Yii::app()->getBaseUrl() . '/myCampaigns';
                    } else {
                        echo 5;
                    }
                } else {
                    echo 2;
                }
            } else {
                echo 3;
            }
        } else {
            echo 4;
        }
    }

    public function actionFetchppimages() {
        $taskId = Yii::app()->request->getParam('taskid');
        $dueDate = Yii::app()->request->getParam('duedate');
        $pop = Yii::app()->request->getParam('pop');
        $sql = "SELECT pp.id, pp.imageName, pp.clickedDateTime, pp.clickedLat, pp.clickedLng, CONCAT(u.fname, u.lname) as clickedBy, "
                . "pp.installation, pp.lighting, pp.obstruction, pp.comments, l.name as siteName, c.name as campaignName "
                . "FROM PhotoProof pp "
                . "LEFT JOIN User u ON u.id=pp.clickedBy "
                . "LEFT JOIN Task t ON t.id=pp.taskid "
                . "LEFT JOIN Campaign c ON c.id=t.campaignid "
                . "LEFT JOIN Listing l ON l.id=t.siteid "
                . "WHERE pp.taskid = '$taskId' "
                . "ORDER BY pp.clickedDateTime DESC ";
        //if (!$pop)
            //$sql .= "AND DATE_FORMAT(pp.clickedDateTime, '%Y-%m-%d') = '$dueDate' ";
        $photoProofResult = Yii::app()->db->createCommand($sql)->queryAll();
        $photoProofArr = array();
        foreach ($photoProofResult as $pp) {
            $photoProof = array(
                'id' => $pp['id'],
                'imageName' => JoyUtilities::getAwsFileUrl('big_' . $pp['imageName'], 'listing'),
                'originalImageName' => JoyUtilities::getAwsFileUrl( $pp['imageName'], 'listing'),
                'siteName' => $pp['siteName'],
                'campaignName' => $pp['campaignName'],
                'clickedDateTime' => $pp['clickedDateTime'],
                'clickedLat' => $pp['clickedLat'],
                'clickedLng' => $pp['clickedLng'],
                'clickedBy' => $pp['clickedBy'],
                'installation' => array_filter(explode(',', $pp['installation'])),
                'lighting' => array_filter(explode(',', $pp['lighting'])),
                'obstruction' => array_filter(explode(',', $pp['obstruction'])),
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
                . "WHERE l.status=1 AND l.companyId = '$vendorId' ";
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
//        print_r($mediaTypes);
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
          //  echo '<pre>';
           // print_r($value->mediatype . ' -- ' . $mediaTypeId);
            $lightingId = array_search(strtolower($value->lighting), $lightings);
            //$productType = UserProduct::getUserProductType(552);//$forUserId);

            $address = $value->locality . ',' . $value->city;
            $addressGeocode = JoyUtilities::geocode($address);
            $countryId = 1;
            $stateId = 2;
            $cityId = 3;
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
                $getName = UserCompany::model()->findByAttributes(array('userid' => $vendorId,'status' => 1));
                //echo $getName['name'] ; die();
                $agencyName = $getName['name'];
                $resetlink = Yii::app()->getBaseUrl(true) . '/site/myPendingSites';
                $invite->attributes = array('typeid' => 4, 'createddate' => date("Y-m-d H:i:s"), 'createdby' => $vendorId, 'emailtypeid' => 4);
                $invite->companyid = Yii::app()->user->cid;
                $invite->notifiedcompanyid = $vendorId;
                $invite->createdby = Yii::app()->user->id;
                $invite->save();
                $email = UserCompany::fetchVendorEmail($vendorId);
                //$email['email']
                $mail = new EatadsMailer('approve-sites', 'gaurav@eatads.com', array('resetLink' => $resetlink, 'agencyName' => $agencyName), array('sales@eatads.com'), $agencyName, Yii::app()->user->email);
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
            } else if (!empty($value->id) && strcmp($listingModel->companyId, Yii::app()->user->cid) == 0) {
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

        Yii::app()->user->setFlash('successconst', 'Sites Added Successfully');
        //Yii::app()->user->setFlash('success', 'Sites Added Successfully');
        echo true;
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
            Yii::app()->user->setFlash('success', 'Password Changed Successfully');
            echo '200';
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
                        $task->pop = 1;
                        $task->createdBy = Yii::app()->user->id;
                        $task->campaignid = $_POST['cid'];
                        $task->siteid = $add[$i];
                        $task->status = 1;
                        $task->createdDate = date("Y-m-d H:i:s");
                        $task->createdBy = Yii::app()->user->id;
                        $task->dueDate = date("Y-m-d H:i:s", $date);
                        $task->save();
                    }
                }

                if ($vendorIds || count($vendorIds) == 0) {
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
                    if (strcasecmp(Yii::app()->user->cid, $assignedcompanyid) == 0) {
                        //trigger a mail
                    }
                    Task::updateTasksForPop($_POST['cid'], $companyid, $assignedcompanyid);
                }
                Yii::app()->user->setFlash('success', 'Campaign updated successfully');
                echo '200';
            } else if ($_POST['type'] == 2) {
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
                            $task->createdDate = date("Y-m-d H:i:s");
                            $task->createdBy = Yii::app()->user->id;
                            $task->save();
                            $date = strtotime('+1 day', $date);
                        }
                    }
                }
                Yii::app()->user->setFlash('success', 'Campaign updated successfully');
                echo '200';
            } else if ($_POST['type'] == 3) {
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
                            $task->createdDate = date("Y-m-d H:i:s");
                            $task->createdBy = Yii::app()->user->id;
                            $task->save();
                            $date = strtotime('+1 day', $date);
                        }
                    }
                }

                if ($vendorIds || count($vendorIds) == 0) {
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
                    if (strcasecmp(Yii::app()->user->cid, $assignedcompanyid) == 0) {
                        //trigger a mail
                    }
                    Task::updateTasksForPop($_POST['cid'], $companyid, $assignedcompanyid, date("Y-m-d H:i:s", $date));
                }
                Yii::app()->user->setFlash('success', 'Campaign updated successfully');
                echo '200';
            }

            $rem = json_decode($_POST['rm']);
            for ($i = 0; $i < count($rem); $i++) {
                Task::removeListingFromCampaign($_POST['cid'], $rem[$i]);
            }
        }
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
        } else if ($type == 2) {
            //for all my accepted vendors listings
            $data = Listing::getListingsForCompany(Yii::app()->user->cid, $start);
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
        } else if ($type == 2) {
            $data = Listing::getListingsForCompanyNew(Yii::app()->user->cid, 0);
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
    
    public function actionCheckEmail() {
        $email = Yii::app()->$_POST['email'];
        //echo $email;        die();
        $chk = User::model()->findByAttributes(array('email' => $email));
        if($chk)
            echo 1;
        else
            echo 2;
    }

    /*
     * invite vendor
     */

    public function actionInviteVendor() {
        $email = Yii::app()->request->getParam('email');
        $id = Yii::app()->user->id;
        //$cid = Yii::app()->user->cid;
        if (strlen($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $chk = User::model()->findByAttributes(array('email' => $email));
            $requestedCompanyId = $chk['companyid'];
            //echo $requestedCompanyId;
            $rcvModel = RequestedCompanyVendor::model()->find('createdby=:createdby AND vendorcompanyid=:vendorcompanyid', 
                    array(':createdby' => $id, ':vendorcompanyid' => $requestedCompanyId)
            );
            
            if (!$rcvModel['acceptedby']) {
                $check = MonitorlyNotification::checkUniqueUnsubscribedVendors($id, $email);
                if (strcasecmp($check['cnt'], '0') == 0) {

                    $invite = new MonitorlyNotification();
                    $invite->attributes = array('typeid' => 1, 'createddate' => date("Y-m-d H:i:s"), 'createdby' => $id, 'emailtypeid' => 1, 'miscellaneous' => $email);
                    $invite->companyid = Yii::app()->user->cid;
                    $invite->save();
                    //echo Yii::app()->user->email; die();
                    $getName = UserCompany::model()->findByAttributes(array('userid' => $id, 'status' => 1));
                    //echo $getName['name'] ; die();
                    $agencyName = $getName['name'];
                    $resetLink = Yii::app()->getBaseUrl(true) . '/account/signup?nid=' . $invite->id;
                    $mail = new EatadsMailer('invite', $email, array('resetLink' => $resetLink, 'agencyName' => $agencyName), array('sales@eatads.com'), $agencyName, Yii::app()->user->email);
                    //echo $mail->; die();
                    $mail->eatadsSend();
                    Yii::app()->user->setFlash('success', 'Vendor Invited Successfully');
                    echo '200';
                } else {
                    Yii::app()->user->setFlash('error', 'Vendor already invited');
                    echo '200';
                }
            } else {
                Yii::app()->user->setFlash('error', 'You are already connected to this user');
                echo '200';
            }
        } else {
            Yii::app()->user->setFlash('error', 'Please enter email in correct format');
            echo '200';
        }
    }

    public function actionRequestedVendor() {
        if (isset($_POST['vendorid']) && isset($_POST['companyid'])) {
            $id = Yii::app()->user->id;
            $vendorcompanyid = $_POST['vendorid'];
            $check = RequestedCompanyVendor::checkUniqueVendor($id, $vendorcompanyid);
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
                $getName = UserCompany::model()->findByAttributes(array('userid' => $id, 'status' => 1));
                //echo $getName['name'] ; die();
                $agencyName = $getName['name'];
                $resetlink = Yii::app()->getBaseUrl(true) . '/waitingApproval';
                $invite->attributes = array('typeid' => 2, 'createddate' => date("Y-m-d H:i:s"), 'createdby' => $id, 'emailtypeid' => 2);
                $invite->createdby = Yii::app()->user->id;
                $invite->createddate = date("Y-m-d H:i:s");
                $invite->companyid = Yii::app()->user->cid;
                $invite->notifiedcompanyid = $vendorcompanyid;
                $invite->save();
                $mail = new EatadsMailer('request-vendor', $email['email'], array('resetLink' => $resetlink, 'agencyName' => $agencyName), array('sales@eatads.com'), $agencyName, Yii::app()->user->email);
                $mail->eatadsSend();
                Yii::app()->user->setFlash('success', 'Vendor Requested Successfully');
                echo '200';
            } else {
                Yii::app()->user->setFlash('error', 'Vendor already requested');
                echo '200';
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
            $vendorName = UserCompany::model()->findByPk(Yii::app()->user->cid);
            //print_r($vendorName['name']);
            $resetlink = Yii::app()->getBaseUrl(true) . '/myCampaigns';
            $invite->attributes = array('typeid' => 3, 'createddate' => date("Y-m-d H:i:s"), 'createdby' => Yii::app()->user->id, 'emailtypeid' => 3);
            $invite->companyid = Yii::app()->user->cid;
            $invite->notifiedcompanyid = $vcid;
            $invite->save();
            $mail = new EatadsMailer('request-accepted', $email, array('resetLink' => $resetlink, 'vendorName' => $vendorName['name']), array('shruti@eatads.com'), $vendorName['name'], Yii::app()->user->email);
            $mail->eatadsSend();
            $getUserId = User::model()->findByAttributes(array('email' => $email));
            //print_r($getUserId['id']); die();
            //print_r($getUserId);
            $getName = UserCompany::model()->findByAttributes(array('userid' => $getUserId['id'], 'status' => 1));
            $agencyName = $getName['name'];
            //print_r($agencyName); die();
            $inviteVendors = Yii::app()->getBaseUrl(true) . '/vendor';
            //print_r($inviteVendors); die();
            $mail = new EatadsMailer('invite-accepted', Yii::app()->user->email, array('resetLink' => $inviteVendors, 'agencyName' => $agencyName), array('shruti@eatads.com'), $vendorName['name'], Yii::app()->user->email);
            $mail->eatadsSend();
            Yii::app()->user->setFlash('success', 'Request accepted Successfully');
            echo 200;
        }
    }

    public function actionRemindAll() {
        $companyid = Yii::app()->user->cid;
        $id = Yii::app()->user->id;
        $getName = UserCompany::model()->findByAttributes(array('userid' => $id, 'status' => 1));
        $agencyName = $getName['name'];
        $remindAllEmails = RequestedCompanyVendor::showRequestedVendorsEmail($companyid);
        foreach ($remindAllEmails as $value) {
            //echo $value['vendoradmin'];
            $resetlink = Yii::app()->getBaseUrl(true) . '/vendor';
            $mail = new EatadsMailer('remind-all', $value['vendoradmin'], array('resetLink' => $resetlink, 'agencyName' => $agencyName), array('sales@eatads.com'), $agencyName, Yii::app()->user->email);
            $mail->eatadsSend();
        }
        $unsubscribedEmails = MonitorlyNotification::showUnsubscribedRequestedVendorsEmail($id);
        //print_r($unsubscribedEmails);
        foreach ($unsubscribedEmails as $value) {
            //print_r($value['miscellaneous']);

            $nid = MonitorlyNotification::model()->findByAttributes(array('miscellaneous' => $value['miscellaneous']));
            //print_r($nid['id']);
            $resetLink = Yii::app()->getBaseUrl(true) . '/account/signup?nid=' . $nid['id'];
            $mail = new EatadsMailer('remind-all', $value['miscellaneous'], array('resetLink' => $resetLink, 'agencyName' => $agencyName), array('sales@eatads.com'), $agencyName, Yii::app()->user->email);
            $mail->eatadsSend();
        }
        Yii::app()->user->setFlash('success', 'Reminder mail sent');
        echo '200';
    }

    public function actionApproveListingRequest() {
        if ($_POST['id']) {
            Listing::updateListing($_POST['id']);
//                    $invite = new MonitorlyNotification();
//        $invite->attributes = array('typeid' => "", 'createddate' => date("Y-m-d H:i:s"), 'createdby' => Yii::app()->user->cid, 'emailtypeid' => 4);
//        $invite->save();
//        $email = "";
//        $mail = new EatadsMailer('site-accepted', $email, array('resetLink' => ""), array('sales@eatads.com'));
//        $mail->eatadsSend();
        }
    }

    public function actionfetchNotifications() {
        $notifications = MonitorlyNotification::fetchNotifications(Yii::app()->user->cid);
        $result = array();
        foreach ($notifications as $noti) {
            switch ($noti['typeid']) {
                case 1:
                    break;
                case 2:
                    break;
                case 3:
                    break;
                case 4:
                    break;
                case 5:
                    break;
                case 6:
                    break;
                case 7:
                    break;
                default:
                    break;
            }
        }
        echo json_encode($result);
    }

    public function actionassignCampaignSiteToUser() {
//        echo $_POST['cid'] . ' _ '. $_POST['sid'] . ' _ '. $_POST['uid'];
        if (isset($_POST['cid']) && isset($_POST['sid']) && isset($_POST['uid'])) {
          //echo '';
            $flag = Task::updateAssignTaskforaSite($_POST['sid'], $_POST['cid'], $_POST['uid']);
           //echo ' _  ' + $flag;
           if ($flag > 0) {
               Yii::app()->user->setFlash('success', 'Campaign Successfully Updated');
               echo '200';
           }
        }
    }
    
    public function actionShareCampaignWithEmails() {
        if (isset($_POST['id']) && isset($_POST['emails'])) {
            $goodEmails = array();
            $badEmails = array();
            $campaign = Campaign::model()->findByPk($_POST['id']);
            if (!empty($campaign)) {
                $emails = explode(',', $_POST['emails']);
                if (count($emails) > 0) {
                    foreach ($emails as $email) {
                        if (strlen($email) && filter_var(trim($email), FILTER_VALIDATE_EMAIL)) {
                            array_push($goodEmails, $email);
                        } else {
                            if(strlen($email)) {
                                array_push($badEmails, $email);
                            }
                        }
                    }
                    if (count($badEmails) == 0) {
                        foreach ($goodEmails as $email) {
                            $alreadyShared = MonitorlyCampaignShare::model()->findByAttributes(array('email' => $email, 'campaignid' => $_POST['id']));
                            if (empty($alreadyShared)) {
                                $shareCampaign = new MonitorlyCampaignShare();
                                $shareCampaign->campaignid = $_POST['id'];
                                $shareCampaign->email = $email;
                                $chk = User::model()->findByAttributes(array('email' => $email));
                                if ($chk && !empty($chk)) {
                                    $shareCampaign->userid = $chk['id'];
                                }
                                $shareCampaign->createdby = Yii::app()->user->id;
                                $shareCampaign->createddate = date("Y-m-d H:i:s");
                                $shareCampaign->save();
                            }

                            //send mail
                            $getName = UserCompany::model()->findByAttributes(array('userid' => Yii::app()->user->id, 'status' => 1));
                            //echo $getName['name'] ; die();
                            $agencyName = $getName['name'];
                            $resetLink = Yii::app()->getBaseUrl(true) . '/shared/'. str_replace(' ', '_', $agencyName)  .'/' . $campaign->id.'/'. str_replace(' ', '_', $campaign['name'] . '/') ;
                            $sDate = new DateTime($campaign['startDate']);
                            $eDate = new DateTime($campaign['endDate']);
                            $mail = new EatadsMailer('share-campaign', $email, array('resetLink' => $resetLink,'agencyName' => $agencyName ,'CampaignName' => $campaign['name'], 'startDate' => $sDate->format('d M Y'), 'endDate' => $eDate->format('d M Y')), array('sales@eatads.com'), $agencyName, Yii::app()->user->email);
                            //echo $mail->; die();
                            $mail->eatadsSend();
                            Yii::app()->user->setFlash('success', 'Campaign Shared Successfully');
                    }
                    }
                    echo implode(',', $badEmails) ;
                }
            }
        }
    }
    
    
    public function actionFilterTasks() {
            $cids ;
            $uids;
            $sdate = null;
            $edate = null;
            $start =0;
            $limit = 10;
            if (isset($_POST['campaignids'])) {
                $cids = $_POST['campaignids'];
            }
            if (isset($_POST['userids'])) {
                $uids = $_POST['userids'];
            }
            $campaigns = null;
            $userids = null;
            if (!empty($cids) && $cids != 'null') {
                $campaigns = implode(',', json_decode(str_replace('"', '', $cids)));
            }
            if (!empty($uids) && $uids != 'null') {
                $userids = implode(',', json_decode(str_replace('"', '', $uids)));
            }
            
            if (isset($_POST['start'])) {
               $start = $_POST['start'];

            }
            
            if (isset($_POST['sdate']) && !empty($_POST['sdate']))
                $sdate = date('Y-m-d', strtotime($_POST['sdate']));
            if (isset($_POST['edate']) && !empty($_POST['edate']))
                $edate = date('Y-m-d', strtotime($_POST['edate']));
            
            if (isset($_POST['start']) && !empty($_POST['start'])) {
                $start = $_POST['start'];
            }

            if (isset($_POST['limit']) && !empty($_POST['start'])) {
                $limit = $_POST['limit'];
            }
            $tasks = Task::fetchTaskList(Yii::app()->user->cid, $campaigns, $userids, $sdate, $edate,$start, $limit );
            
            echo json_encode($tasks);
    }
    
 public function actionFilterAllReports() {
            $cId = null;
            if (!Yii::app()->user->isGuest) {
                $cId = Yii::app()->user->cid;
            }
            $start = 0;
            $limit = 100;
            $sdate = null; 
            $edate = null;
            $campaignIds = null;
            $assignedTo = null;
            
            if(isset($_POST['sdate']) && $_POST['sdate']!='') {
//                $sdate = $_POST['sdate'];
                //$sdate = str_replace('/', '-', $sdate);
                $sdate = date("Y-m-d", strtotime($_POST['sdate']));                
            }
            if(isset($_POST['edate']) && $_POST['edate']!='') {    
//                $edate = $_POST['edate'];
                //$edate = str_replace('/', '-', $edate);
                $edate = date("Y-m-d", strtotime($_POST['edate']));
            }
            
            if(isset($_POST['start']) && $_POST['start']!='') {    
                $start = $_POST['start'];
            }
            
            if(isset($_POST['limit']) && $_POST['limit']!='') {    
                $limit = $_POST['limit'];
            }
            
            if(isset($_POST['campaignids']) && $_POST['campaignids']!='null') {
                $campaignIds = implode(',', json_decode(str_replace('"', '', $_POST['campaignids'])));                
            } else if (Yii::app()->request->getParam('cid')) {
                $campaignIds = Yii::app()->request->getParam('cid');
            }
            if(isset($_POST['userids']) && $_POST['userids']!='null') {                
                $assignedTo = implode(',', json_decode(str_replace('"', '', $_POST['userids'])));                
            }

            $tasks = Campaign::fetchReports(null, $campaignIds, $sdate, $edate,$assignedTo,  $cId, $start, $limit);
         //   print_r($tasks);die();
            if (!is_null($campaignIds)) {
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
            }
            $newtasks = array();
            foreach ($tasks as $t) {
                $t['duedateNew'] = date('d/m/Y', strtotime($t['duedate']));
                if ($t['status'] == 0) {
                    $t['class'] = 'danger';
                    
                    if($t['duedate'] < date('Y-m-d'))
                        $t['problemstatus'] = 'Missed';
                    else 
                        $t['problemstatus'] = 'Pending';
                } else {
                    if ($t['problem']) {
                        $t['problemImage'] = Yii::app()->request->baseUrl . '/images/warning.png';
//                        $status = '<img src="' . Yii::app()->request->baseUrl . '/images/warning.png">';
                    } else {
                        $t['problemImage'] = Yii::app()->request->baseUrl . '/images/ok.png';
//                        $status = '<img src="' . Yii::app()->request->baseUrl . '/images/ok.png">';
                    }
                }
                array_push($newtasks, $t);  
            }
            echo json_encode($newtasks);
    }
    
    
    
    
    public function actionShareCampaignZipImages() {
        if (isset($_POST['id']) && isset($_POST['emails'])) {
            $goodEmails = array();
            if (!Yii::app()->user->isGuest) {
                array_push($goodEmails, Yii::app()->user->email);
            }
            
            $badEmails = array();
            $campaign = Campaign::model()->findByPk($_POST['id']);
            if (!empty($campaign)) {
                $emails = explode(',', $_POST['emails']);
                if (count($emails) > 0) {
                    foreach ($emails as $email) {
                        if (strlen($email) && filter_var(trim($email), FILTER_VALIDATE_EMAIL)) {
                            array_push($goodEmails, $email);
                        } else {
                            if(strlen($email)) {
                                array_push($badEmails, $email);
                            }
                        }
                    }
                    if (count($badEmails) == 0) {
//                        foreach ($goodEmails as $email) {
//                            
//                        }
                        
                        $data = array("emails" => implode(',', array_unique($goodEmails)));
                        $data_json = json_encode($data);
//                        echo '<pre>';
//                        print_r($data);
//                        echo http_build_query($data). ' ';
                      $url = Yii::app()->getBaseUrl(true) . '/api/zip/' . $_POST['id'];
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data_json)));
                         curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                        curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_TIMEOUT, 2);
                         $response  = curl_exec($ch);
                        curl_close($ch);

                        Yii::app()->user->setFlash('success', 'You will shortly receive a mail with a link to download images.');
                    }
                    echo implode(',', $badEmails) ;
                }
            }
        }
    }
    
    
    private static function createTaskForASite($cid, $add) {
        $campaign = Campaign::model()->findByPk($cid);
    //    print_r($campaign);
        //This is till we have first all days monitoring
        $diff = strtotime($campaign->attributes['endDate']) - strtotime($campaign->attributes['startDate']);
//        if ($campaign['type'] != $_POST['type']) {
//            $tasks = Task::fetchAllSitesInCampaign($_POST['cid']);
//            for ($i = 0; $i < count($tasks); $i++) {
//                array_push($add, $tasks[$i]['siteid']);
//            }
//            $add = array_unique($add);
//
//            Task::deleteAllTaskForCampaign($_POST['cid']);
//            Campaign::model()->updateByPk($campaign['id'], array('type' => $_POST['type']));
//        }
        
//        if (count($add) > 0) {
//            for ($i = 0; $i < count($add); $i++) {
                $date = strtotime($campaign->attributes['startDate']);
                while ((strtotime($campaign->attributes['endDate']) - $date) >= 0) {
                    $task = new Task();
                    $task->assignedCompanyId = Yii::app()->user->cid;
                    $task->campaignid = $_POST['cid'];
                    $task->siteid = $add;
                    $task->status = 1;
                    $task->dueDate = date("Y-m-d H:i:s", $date);
                    $task->pop = 0;
                    $task->createdDate = date("Y-m-d H:i:s");
                    $task->createdBy = Yii::app()->user->id;
                    $task->save();
                    $date = strtotime('+1 day', $date);
                }
                return 1;
            //}
       // }
    }
    
    public function actionMassuploadsiteForCampaign() {
        $lids = array();
        $cid = Yii::app()->request->getParam('cid');
        
        // fetch all media types to match
       // $mtResult = Mediatype::model()->findAll();
//        $mediaTypes = array();
//        foreach ($mtResult as $value) {
//             $mediaTypes[$value->id] = strtolower($value->name);
//        }
//        print_r($mediaTypes);
        // fetch all lighting
     //   $lightings = array_map('strtolower', Listing::getLighting());
        //print_r($lightings); die();


        $vendorId = Yii::app()->user->cid;        
        $byUserId = Yii::app()->user->id;
        $data = json_decode(Yii::app()->request->getParam('data'));

//        $companyResult = UserCompany::model()->findByPk($vendorId, array('select' => 'userid'));
        $forUserId = $byUserId;
        foreach ($data as $value) {
            $mediaTypeId = 1;
            $lightingId = 1;
           // print_r($value->locality);
            //  echo $value->id . ',' . strcmp($vendorId, Yii::app()->user->cid);
          //  $mediaTypeId = array_search(strtolower($value->mediatype), $mediaTypes);
          //  echo '<pre>';
           // print_r($value->mediatype . ' -- ' . $mediaTypeId);
        //    $lightingId = array_search(strtolower($value->lighting), $lightings);
            //$productType = UserProduct::getUserProductType(552);//$forUserId);

            $address = $value->locality . ',' . $value->city;
            $addressGeocode = JoyUtilities::geocode($address);
            $countryId = 1;
            $stateId = 2;
            $cityId = 3;
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


            $status = 1;
            $approved = 1;
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

            if (empty($value->id) && !empty($value->name) && !empty($value->locality) && !empty($value->city)) {
                $listingModel->datecreated = date('Y-m-d H:i:s');
                $listingModel->save();
                array_push($lids, $listingModel->id);
                //create task for the days
              $flag = AjaxController::createTaskForASite($cid, $listingModel->id);
                $userid = User::model()->findByAttributes(array('username' => $value->monitor));
                //print_r($userid['id']);
                if ($flag && $userid->attributes['id'] ) {
                    //assign task to user
                    Task::updateAssignTaskforaSite($listingModel->id, $cid, $userid->attributes['id']);
                }
            } else if (!empty($value->id) && strcmp($listingModel->companyId, Yii::app()->user->cid) == 0) {
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

        Yii::app()->user->setFlash('successconst', 'Campaign Created Successfully');
        //Yii::app()->user->setFlash('success', 'Sites Added Successfully');
        echo true;
    }
    
    
    //create campaign
    
    public function actionCreateNewCampaign() {
        $model = new Campaign();
        $model->setScenario('insert');
        if (isset($_POST['Campaign']) && !empty($_POST['Campaign']['name']) && !empty($_POST['Campaign']['startDate']) && !empty($_POST['Campaign']['endDate'])) {
            $model->attributes = $_POST['Campaign'];
            $sdate = $model->startDate;
            $edate = $model->endDate;
            //print_r($model->validate());
            if ($model->validate()) {
                $model->createdBy = Yii::app()->user->id;
                $model->companyid = Yii::app()->user->cid;
                $model->createdDate = date("Y-m-d H:i:s");
                $model->startDate = date("Y-m-d H:i:s", strtotime($model->startDate));
                $model->endDate = date("Y-m-d H:i:s", strtotime($model->endDate));
                $model->save();
            }
            $link = Yii::app()->getBaseUrl(true) . '/myCampaigns/upcoming';
            //Send a mail to admin for 
            $mail = new EatadsMailer('create-campaign', Yii::app()->user->email, array('resetLink' => $link, 'CampaignName' => $model->name, 'startDate' => $sdate, 'endDate' => $edate));
           // $mail->eatadsSend();
            echo $model->id;
        }
    }
    
    
}
