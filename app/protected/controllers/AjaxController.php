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
                    'PushAvailabilityMailsToQueue', 'MassUploadListingsForVendor', 'fetchvendorsites', 'massuploadsite', 'updatepassword', 
                    'invitevendor', 'removeListingFromCampaign', 'updateCampaign', 'forgotpwd', 'verifyresethash', 
                    'resetpwd', 'fetchNotifications','fetchVendorListing'),
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

    public function actionSetPassword() {
        //echo 'hiiii';        die();
        $hash = Yii::app()->request->getParam('hash');
        //echo $hash; die();
        $password = Yii::app()->request->getParam('password');
        //echo $password; 
        $ph = new PasswordHash(Yii::app()->params['phpass']['iteration_count_log2'], Yii::app()->params['phpass']['portable_hashes']);
        $pwd = $ph->HashPassword($password);
        $passwordLink = Link::model()->find('hash=:hash AND type=:type AND expired=:expired', array(':hash' => $hash, ':type' => 1, ':expired' => 0));
        //echo $passwordLink;
        if ($passwordLink) {
            $userModel = User::model()->findByPk($passwordLink->userid);
            //echo $passwordLink->userid; die();
            $userModel->password = $pwd;
            $userModel->active = 1;
            $userModel->status = 1;
            $userModel->save();
            
            $identity = new UserIdentity($userModel->email, $password);
            //print_r($identity);die();
            if ($identity->authenticate()) {
                $user = Yii::app()->user;
                $user->login($identity);
                $passwordLink->expired=1;
                $passwordLink->save();
                echo 1;
                
                //$this->redirect($user->returnUrl);
                //$this->redirect(Yii::app()->getBaseUrl() . '/myCampaigns');
            } else {
                echo 5;
            }
        }
        else{
            /*
             * password has expired
             */
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
                    $userModel->save();
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
                . "WHERE pp.taskid = '$taskId' ";
        if(!$pop) 
            $sql .= "AND DATE_FORMAT(pp.clickedDateTime, '%Y-%m-%d') = '$dueDate' ";        
        $photoProofResult = Yii::app()->db->createCommand($sql)->queryAll();
        $photoProofArr = array();
        foreach ($photoProofResult as $pp) {
            $photoProof = array(
                'id' => $pp['id'],
                'imageName' => JoyUtilities::getAwsFileUrl('big_' . $pp['imageName'], 'listing'),
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
                $getName = UserCompany::model()->findByAttributes(array('userid' => $vendorId));
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
        Yii::app()->user->setFlash('success', 'Sites Added Successfully');
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

    /*
     * invite vendor
     */

    public function actionInviteVendor() {
        $email = Yii::app()->request->getParam('email');
        $id = Yii::app()->user->id;
        if (strlen($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $check = MonitorlyNotification::checkUniqueUnsubscribedVendors($id, $email);
            if (strcasecmp($check['cnt'], '0') == 0) {

                $invite = new MonitorlyNotification();
                $invite->attributes = array('typeid' => 1, 'createddate' => date("Y-m-d H:i:s"), 'createdby' => $id, 'emailtypeid' => 1, 'miscellaneous' => $email);
                $invite->companyid = Yii::app()->user->cid;
                $invite->save();
                //echo Yii::app()->user->email; die();
                $getName = UserCompany::model()->findByAttributes(array('userid' => $id));
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
                $getName = UserCompany::model()->findByAttributes(array('userid' => $id));
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
            $getName = UserCompany::model()->findByAttributes(array('userid' => $getUserId['id']));
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
        $getName = UserCompany::model()->findByAttributes(array('userid' => $id));
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

}
