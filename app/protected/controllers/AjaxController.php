<?php

class AjaxController extends Controller {

    private function fetchUserReturnUrl() {
        
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

    public function actionFetchVendorSites() {
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
        $lightings = array_map('strtolower', Listing::getLighting()); ;
        //print_r($lightings); die();
        
        
        $forUserId = Yii::app()->request->getParam('foruserid');
        $byUserId = Yii::app()->request->getParam('byuserid');
        $data = json_decode(Yii::app()->request->getParam('data'));
        
        foreach($data as $value) {
            //echo $value->mediatype;
            $mediaTypeId = array_search(strtolower($value->mediatype), $mediaTypes);            
            $lightingId = array_search(strtolower($value->lighting), $lightings);
            $productType = UserProduct::getUserProductType(552);//$forUserId);
            
            $address = $value->locality . ',' . $value->city;
            $addressGeocode = JoyUtilities::geocode($address);
            if($addressGeocode) {
                // check if country exists        
                if ($addressGeocode['country'] != '' && $addressGeocode['country'] != null) {
                    $countryId = Area::checkAreaExists($addressGeocode['country'], 'c', null, $addressGeocode['countryCode']);
                }
                echo $countryId; die();
                // check if state exists
                if (is_numeric($countryId) && $addressGeocode['state'] != '' && $addressGeocode['state'] != null) {
                    $stateId = Area::checkAreaExists($addressGeocode['state'], 's', $countryId);
                }
                // check if city exists
                if (is_numeric($stateId) && $addressGeocode['city'] != '' && $addressGeocode['city'] != null) {
                    $cityId = Area::checkAreaExists($addressGeocode['city'], 'ci', $stateId);
                }                
            }
            
            die();
            
            $listingModel = new Listing;
            $listingModel->name = $value->name;
            $listingModel->site_code = $value->site_code;
            $listingModel->length = $value->length;
            $listingModel->width = $value->width;
            $listingModel->area = $value->length * $value->width;
            
            $listingModel->product_type = $productType;
            $listingModel->status = 0;
            $listingModel->approved = 0;
            
            $listingModel->locality = '?';
            $listingModel->country = '?';
            $listingModel->state = '?';
            $listingModel->city = '?';
            
            $listingModel->geolat = '?';
            $listingModel->geolng = '?';
            $listingModel->accurate_geoloc = '?';
            
            $listingModel->lightingid = $lightingId;
            $listingModel->mediatypeid = $mediaTypeId;
            
            $listingModel->datemodified = date('Y-m-d H:i:s');
            $listingModel->datecreated = date('Y-m-d H:i:s');
            
            
            
            
            
            
            
            die();
        }
        
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
