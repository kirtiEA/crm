<?php

Yii::import('application.models.base.BaseListing');

class Listing extends BaseListing {

    public $min, $max;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, length, width, sizeunitid, basecurrencyid, price, pricedurationid, lightingid, mediatypeid', 'required', 'on' => 'createListing'),
            array('sizeunitid, pricedurationid, countryid, stateid, cityid, accurate_geoloc, zoomlevel, lightingid, mediatypeid, status, pscore, reach, solr', 'numerical', 'integerOnly' => true, 'on' => 'createListing'),
            array('name', 'length', 'max' => 255, 'on' => 'createListing'),
            array('description', 'length', 'max' => 1200, 'on' => 'createListing'),
            array('otherdata', 'length', 'max' => 50, 'on' => 'createListing'),
            array('reach', 'numerical', 'min' => 1000, 'max' => 10000000, 'on' => 'createListing'),
            array('price', 'numerical', 'integerOnly' => true, 'min' => 1, 'on' => 'createListing'),
            array('length, width', 'numerical', 'min' => 0.1, 'on' => 'createListing'),
            array('length, width', 'length', 'max' => 11, 'on' => 'createListing'),
            array('price', 'length', 'max' => 10, 'on' => 'createListing', 'message' => '{attribute} should be max 10 digits.'),
            array('length, width', 'match', 'pattern' => '/^[0-9]{1,7}(\.[0-9]{1,3})?$/', 'on' => 'createListing', 'message' => '{attribute} should be max 10 digit (7 before decimal and 3 after decimal).'),
            //array('price', 'match', 'pattern'=>'/^[0-9]{1,8}(\.[0-9]{1,2})?$/', 'on' => 'createListing', 'message'=>'{attribute} should be max 10 digits.'),            
            array('locality', 'length', 'max' => 100, 'on' => 'createListing'),
            array('geolat', 'length', 'allowEmpty' => true, 'max' => 10, 'on' => 'createListing', 'message' => 'Please provide a valid latitude.'),
            array('geolat, geolng', 'type', 'allowEmpty' => true, 'type' => 'float', 'on' => 'createListing'),
            array('geolat', 'compare', 'operator' => '<=', 'compareValue' => 90, 'message' => 'Please provide a valid latitude.', 'allowEmpty' => true, 'on' => 'createListing'),
            array('geolat', 'compare', 'operator' => '>=', 'compareValue' => -90, 'message' => 'Please provide a valid latitude.', 'allowEmpty' => true, 'on' => 'createListing'),
            array('geolat', 'match', 'pattern' => '/^[0-9.-]*$/', 'on' => 'createListing', 'message' => 'Please provide a valid latitude.'),
            array('geolng', 'length', 'allowEmpty' => true, 'max' => 11, 'on' => 'createListing', 'message' => 'Please provide a valid longitude.'),
            array('geolng', 'compare', 'operator' => '<=', 'compareValue' => 180, 'message' => 'Please provide a valid longitude.', 'allowEmpty' => true, 'on' => 'createListing'),
            array('geolng', 'compare', 'operator' => '>=', 'compareValue' => -180, 'message' => 'Please provide a valid longitude.', 'allowEmpty' => true, 'on' => 'createListing'),
            array('geolng', 'match', 'pattern' => '/^[0-9.-]*$/', 'on' => 'createListing', 'message' => 'Please provide a valid longitude.'),
            //array('geolat', 'in','range'=>range(-90,90), 'message'=>'Please provide a valid latitude', 'on'=>'createListing'),
            array('description, weeklyprice, accurate_geoloc', 'safe', 'on' => 'createListing'),
            

            array('byuserid, foruserid, name, length, width, sizeunitid, basecurrencyid, price, pricedurationid, lightingid, mediatypeid, reach, datecreated', 'required', 'on' => 'updateListing'),
            array('byuserid, foruserid, sizeunitid, pricedurationid, countryid, stateid, cityid, zoomlevel, lightingid, mediatypeid, status, pscore, reach, solr', 'numerical', 'integerOnly' => true, 'on' => 'updateListing'),
            array('name', 'length', 'max' => 255, 'on' => 'updateListing'),
            array('description', 'length', 'max' => 1200, 'on' => 'updateListing'),
            array('otherdata', 'length', 'max' => 50, 'on' => 'updateListing'),
            array('reach', 'numerical', 'min' => 1000, 'max' => 10000000, 'on' => 'updateListing'),
            array('price', 'numerical', 'integerOnly' => true, 'min' => 1, 'on' => 'updateListing'),
            array('length, width', 'numerical', 'min' => 0.1, 'on' => 'updateListing'),
            array('length, width', 'length', 'max' => 11, 'on' => 'updateListing'),
            array('length, width', 'match', 'pattern' => '/^[0-9]{1,7}(\.[0-9]{1,3})?$/', 'on' => 'updateListing', 'message' => '{attribute} should be max 10 digit (7 before decimal and 3 after decimal).'),
            array('price', 'length', 'max' => 10, 'on' => 'updateListing', 'message' => '{attribute} should be max 10 digits.'),
            array('locality', 'length', 'max' => 100, 'on' => 'updateListing'),
            array('geolat, geolng', 'type', 'allowEmpty' => true, 'type' => 'float', 'on' => 'updateListing'),
            array('geolat', 'length', 'max' => 10, 'on' => 'updateListing', 'message' => 'Please provide a valid latitude.'),
            array('geolat', 'compare', 'operator' => '<=', 'compareValue' => 90, 'message' => 'Please provide a valid latitude.', 'on' => 'updateListing'),
            array('geolat', 'compare', 'operator' => '>=', 'compareValue' => -90, 'message' => 'Please provide a valid latitude.', 'on' => 'updateListing'),
            array('geolat', 'match', 'pattern' => '/^[0-9.-]*$/', 'on' => 'updateListing', 'message' => 'Please provide a valid latitude.'),
            array('geolng', 'length', 'max' => 11, 'on' => 'updateListing', 'message' => 'Please provide a valid longitude.'),
            array('geolng', 'compare', 'operator' => '<=', 'compareValue' => 180, 'message' => 'Please provide a valid longitude.', 'on' => 'updateListing'),
            array('geolng', 'compare', 'operator' => '>=', 'compareValue' => -180, 'message' => 'Please provide a valid longitude.', 'on' => 'updateListing'),
            array('geolng', 'match', 'pattern' => '/^[0-9.-]*$/', 'on' => 'updateListing', 'message' => 'Please provide a valid longitude.'),
            array('description, weeklyprice, accurate_geoloc', 'safe', 'on' => 'updateListing'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, byuserid, foruserid, name, length, width, sizeunitid, basecurrencyid, price, weeklyprice, pricedurationid, otherdata, countryid, stateid, cityid, locality, geolat, geolng, zoomlevel, lightingid, mediatypeid, description, status, pscore, reach, datecreated, datemodified', 'safe', 'on' => 'search'),
        );
    }

    public function attributeLabels() {
        return array(
            'name' => 'Listing Title',
            'id' => 'ID',
            'byuserid' => 'Byuserid',
            'foruserid' => 'Foruserid',
            'length' => 'Length',
            'width' => 'Width',
            'sizeunitid' => 'Size unit',
            'basecurrencyid' => 'Base currency',
            'price' => 'Price',
            'weeklyprice' => 'Weeklyprice',
            'pricedurationid' => 'Price duration',
            'otherdata' => 'Other data',
            'countryid' => 'Country',
            'stateid' => 'State',
            'cityid' => 'City',
            'locality' => 'Locality',
            'geolat' => 'Latitude',
            'geolng' => 'Longitude',
            'accurate_geoloc' => 'Accurate Geoloc',
            'zoomlevel' => 'Zoomlevel',
            'lightingid' => 'Lighting',
            'mediatypeid' => 'Media type',
            'description' => 'Description',
            'status' => 'Status',
            'pscore' => 'Pscore',
            'reach' => 'Reach',
            'solr' => 'Solr',
            'datecreated' => 'Datecreated',
            'datemodified' => 'Datemodified',
            'tag1' => 'Tag1',
            'tag2' => 'Tag2',
            'tag3' => 'Tag3',
            'availability_start' => 'Availability Start',
            'availability_end' => 'Availability End',
            'min_period_num' => 'Min Period Num',
            'min_period_unit' => 'Min Period Unit',
            'contact_name' => 'Contact Name',
            'contact_phone' => 'Contact Phone',
            'contact_email' => 'Contact Email',
            'rate_per_unit' => 'Rate Per Unit',
            'search_keywords' => 'Search Keywords',
            'area_code' => 'Area Code',
            'postal' => 'Postal',
            'loop_length' => 'Loop Length',
            'daily_spots' => 'Daily Spots',
            'operating_hours' => 'Operating Hours',
            'screen_size' => 'Screen Size',
            'dwell_time' => 'Dwell Time',
            'other_type' => 'Other Type',
        );
    }

    public static function updateSolr($listingId) {
        $lt = Yii::app()->db->createCommand("CALL getListingForSolr({$listingId});")->queryRow();
        $lt['sizeunit'] = Listing::getSizeUnit($lt['sizeunitid']);
        $lt['priceduration'] = Listing::getPriceDuration($lt['pricedurationid']);
        unset($lt['pricedurationid']);
        $lt['datemodified'] = date('Y-m-d', strtotime($lt['datemodified'])) . 'T' . date('H:i:s', strtotime($lt['datemodified'])) . 'Z';
        $lt['reach'] = empty($lt['reach']) ? 0 : $lt['reach'];
        /*echo '<pre>';
        print_r($lt);
        die();*/
        Yii::app()->listingSearch->updateOne($lt);
        // update listing solr field to 1
        $listRow = Listing::model()->findByPk($listingId);
        $listRow->solr = 1;
        $listRow->datemodified = date("Y-m-d H:i:s");
        $listRow->save();       // save solr status to indexed
        
        return $lt;
    }
    
    
    
    /**
     * #return who created user id
     */
    public static function getListingUserId($listingId) {
        $listModel = self::model()->findByPk($listingId, array('select' => 'foruserid'));
        return $listModel->foruserid;
    }

    /**
     * @return array of size units or value if id is passed
     */
    public static function getSizeUnit($sizeUnitId = null) {
        // PLEASE DO NOT CHANGE. WILL IMPACT ON MASSUPLOAD VALIDATAION
        $sizeUnit = array(
            '1' => 'Ft',
            '2' => 'M',
        );
        if ($sizeUnitId) {
            return $sizeUnit[$sizeUnitId];
        } else {
            return $sizeUnit;
        }
    }

    /**
     * @return array of price duration or value if id is passed
     */
    public static function getPriceDuration($priceDurationId = null) {
        // PLEASE DO NOT CHANGE. WILL IMPACT ON MASSUPLOAD VALIDATAION
        $priceDuration = array(
            '1' => 'Daily',
            '2' => 'Weekly',
            '3' => 'Monthly',
            '4' => 'Quarterly',
            '5' => 'Yearly'
        );
        if ($priceDurationId) {
            return $priceDuration[$priceDurationId];
        } else {
            return $priceDuration;
        }
    }

    /**
     * @return array of price duration or value if id is passed
     */
    public static function getLighting($priceDurationId = null) {
        // PLEASE DO NOT CHANGE. WILL IMPACT ON MASSUPLOAD VALIDATAION
        $priceDuration = array(
            '1' => 'No lighting',
            '2' => 'Front lit',
            '3' => 'Back lit'
        );
        if ($priceDurationId) {
            return $priceDuration[$priceDurationId];
        } else {
            return $priceDuration;
        }
    }

    /**
     * @return array of popularity score or value if id is passed
     */
    public static function getPopurityScore($popularityName = null) {
        $popularityScore = array(
            'ViewDetail' => 30,
            'ContactSeller' => 30,
            'RFP' => 50,
            'Favorite' => 30,
            'Spam' => -50,
        );
        if ($popularityName) {
            return $popularityScore[$popularityName];
        } else {
            return $popularityScore;
        }
    }

    /**
     * @return new score of a listing
     */
    public static function setPopularityScore($ListingId, $popularityName) {
        $popularityScore = self::getPopurityScore($popularityName);
        $listingRow = Listing::model()->findByPk($ListingId);
        $listingRow->pscore += $popularityScore;
        if ($listingRow->save()) {
            return $listingRow->pscore;
        } else {
            return false;
        }
    }

    // Return Listing Count by userId
    // $staus may be - 'active', 'inactive', 'all'(by default)
    public static function getListingCountByUserId($userId, $status = 'all') {
        if ($status == "active") {
            $cond = " AND status = 1";
        } elseif ($status == "inactive") {
            $cond = " AND status = 0";
        }

        $criteria = new CDbCriteria();
        $criteria->select = 'id';
        $criteria->condition = "foruserid = :userId  " . $cond;
        $criteria->params = array(':userId' => $userId);
        $result = self::model()->count($criteria);
        return $result;
    }

    /**
     * 
     */
    public static function calculateWeeklyPrice($price, $priceduration, $baseCurrencyId) {
        Yii::app()->openexchanger->exchangeRates;
        $weeklyPrice = null;
        switch ($priceduration) {
            case 1: $weeklyPrice = round(($price * 7), 2);
                break;

            case 3: $weeklyPrice = round((($price / 30) * 7), 2);
                break;

            case 4: $weeklyPrice = round(((($price / 3) / 30) * 7), 2);
                break;

            case 5: $weeklyPrice = round(((($price / 12) / 30) * 7), 2);
                break;

            default : $weeklyPrice = $price;
        }
        // return calculated weeklyprice in USD
        return Yii::app()->openexchanger->convertCurrency($weeklyPrice, LookupBaseCurrency::getCurrencyName($baseCurrencyId), 'USD');
    }

    /*
     * Get the listing counts for a media type and user
     */

    public static function getListingCount($mediaTypeId, $userId, $status = 1) {
        $criteria = new CDbCriteria();
        $criteria->select = 'id';
        $criteria->condition = "mediatypeid = :mediatypeid AND foruserid = :userId AND status=:status";
        $criteria->params = array(':mediatypeid' => $mediaTypeId, ':userId' => $userId, ':status' => $status);
        return $result = self::model()->count($criteria);
    }

    public static function getMinMaxPrice() {        
        $criteria = new CDbCriteria();
        $criteria->select = 'MIN(weeklyprice) as `min`, MAX(weeklyprice) as `max`';
        $criteria->condition = "solr = :solr AND status=:status";
        $criteria->params = array(':solr' => 1, ':status' => 1);
        $result = self::model()->find($criteria);
        // CHANGE TO -5% TO +5%
        // IF MIN = MAX, THEN SET MIN = LOWEST PRICE SET BY ADMIN
        return array('min' => floor($result['min'] * 0.95),
                    'max' => ceil($result['max'] * 1.05));
    }

    // Admin Dashboard function
    public static function getListCountByDateRange($startDate, $endDate) {
        $criteria = new CDbCriteria();
        $criteria->select = 'id';
        $criteria->condition = "datecreated BETWEEN :startDate AND :endDate AND status = 1";
        $criteria->params = array(':startDate' => $startDate, ':endDate' => $endDate);
        return $result = self::model()->count($criteria);
    }

    public static function getListingChartData($startDate, $endDate, $duration) {
        if ($duration == "1") { // Weekly
            return Listing::getListCountWeeklyByDateRange($startDate, $endDate);
        } elseif ($duration == "2") { // Monthly
            return Listing::getListCountMonthlyByDateRange($startDate, $endDate);
        } elseif ($duration == "3") { // Yearly
            return Listing::getListCountYearlyByDateRange($startDate, $endDate);
        }
    }

    // Admin Dashboard function Chart
    public static function getListCountWeeklyByDateRange($startDate, $endDate) {
        $weeklyData = Yii::app()->db->createCommand("CALL getWeeklyListing ('$startDate', '$endDate')")->queryAll();

        $weekArr = JoyUtilities::getNoOfWeek($startDate, $endDate);
        $weekListingData = JoyUtilities::formatAdminGraphData($weeklyData, $weekArr);

        return $weekListingData;
    }

    public static function getListCountMonthlyByDateRange($startDate, $endDate) {
        $monthlyData = Yii::app()->db->createCommand("CALL getMonthlyListing ('$startDate', '$endDate')")->queryAll();

        $monthArr = JoyUtilities::getNoOfMonth($startDate, $endDate);
        $monthlyListingData = JoyUtilities::formatAdminGraphData($monthlyData, $monthArr);

        return $monthlyListingData;
    }

    public static function getListCountYearlyByDateRange($startDate, $endDate) {
        $yearlyData = Yii::app()->db->createCommand("CALL getYearlyListing ('$startDate', '$endDate')")->queryAll();

        $yearArr = JoyUtilities::getNoOfYear($startDate, $endDate);
        $yearlyListingData = JoyUtilities::formatAdminGraphData($yearlyData, $yearArr);

        return $yearlyListingData;

//    }    
//    
//    public static function updateSolrStatusByListingId($listingId, $solarStatus = 0) {
//        return self::model()->updateByPk($listingId, array('solr' => $solarStatus));
//    }
    

    }

    public static function updateSolrStatusByListingId($listingId, $solarStatus = 0) {
        return self::model()->updateByPk($listingId, array('solr' => $solarStatus));
    }        
        
    public static function hasListing($userId, $listingId) {
        // check for userid and list id
        $criteria = new CDbCriteria();        
        $criteria->condition = "foruserid = :foruserid AND id = :id AND status=:status";
        $criteria->params = array(':foruserid' => $userId, 'id'=>$listingId, ':status' => 1);
        $result = self::model()->count($criteria);
        return $result;
    }
    public static function isSolrListing($listingId) {
        // check if listing is in solr and status is not deleted
        $criteria = new CDbCriteria();
        $criteria->select = 'solr';
        $criteria->condition = "id = :id AND status=:status";
        $criteria->params = array('id'=>$listingId, ':status' => 1);
        $result = self::model()->find($criteria);
        if($result) {
            return $result->solr;
        } else {
            return 0;
        }
    }
    
    public static function getListingUrlData($listingId) {
        $command = Yii::app()->db->createCommand('SELECT c.alias as country, s.alias as state, ci.alias as city, l.name as name, l.id FROM `Listing` AS l
                                                    INNER JOIN `Area` AS c ON l.countryid = c.id
                                                    INNER JOIN `Area` AS s ON l.stateid = s.id
                                                    INNER JOIN `Area` AS ci ON l.cityid = ci.id
                                                    WHERE l.id = '.$listingId)->queryRow();
        $listingUrlData['country'] = $command['country'];
        $listingUrlData['state'] = $command['state'];
        $listingUrlData['city'] = $command['city'];
        $listingUrlData['listingid'] = $command['id'];
        $listingUrlData['listingName'] = JoyUtilities::createAlias($command['name']);
        
        return $listingUrlData;
    }
    
    
    public static function getListingGeolocForCompany($companyid) {
        $listings = Yii::app()->db->createCommand('select id, geolat, geolng from Listing where status =1 and companyId ='.$companyid)->queryAll();
        return $listings;
    }
    
    public static function getListingsForCompany($companyid,$campaignid =null) {
        $listings = Yii::app()->db->createCommand('SELECT l.id, foruserid, l.name, length, width, sizeunitid, basecurrencyid, price, locality, geolat, geolng, accurate_geoloc,  lightingid,  description,  pscore, reach, 
            l.availabilitydate, lbc.currency_code,c.name as country,s.name as state,city.name as city,mt.name as mediatype
            FROM Listing l inner join LookupBaseCurrency lbc on lbc.id = l.basecurrencyid
            inner join Area c on c.id = l.countryid inner join Area s on s.id = l.stateid
            inner join Area city on city.id = l.cityid inner join MediaType mt on mt.id = l.mediatypeid
            WHERE l.status =1 and companyId = '.$companyid)->queryAll();
        
        for ($i =0; $i < count($listings); $i++) {
//            if (!empty($listings[$i]['sizeunitid'])) {
//                $listings[$i]['sizeunit'] = Listing::getSizeUnit($listings[$i]['sizeunitid']);
//                $listings[$i]['listingimages'] = ListingImage::getListingImageName($listings[$i]['id']);
//            }
            if ($campaignid) {
                $cnt = Task::model()->countByAttributes(array('campaignid' => $campaignid, 'status' => 1, 'siteid' => $listings[$i]['id'])); 
                if ($cnt > 0) {
                    $listings[$i]['is_onCampaign'] = $cnt;
                }
                    
            }
        }
        return $listings;
    }
    
    public static function getListingDetails($id, $userid) {
        $listing = Yii::app()->db->createCommand('SELECT l.id, l.name as Name, length as Height, width as Width, price as Price, locality as Address, geolat as Lat, geolng as Lng, lightingid,  description, sizeunitid,
        lbc.currency_code as basecurrency, mt.name as MediaType
        FROM Listing l inner join LookupBaseCurrency lbc on lbc.id = l.basecurrencyid
        inner join MediaType mt on mt.id = l.mediatypeid
        WHERE l.status =1 and l.id = '.$id)->queryRow();
        if (!empty($listing['sizeunitid'])) {
            $listing['sizeunit'] = Listing::getSizeUnit($listing['sizeunitid']);
        }
        $lt = ListingImage::getListingImageNameOnly($listing['id']);
        $images = array();
        foreach ($lt as $tt) {
            array_push($images, JoyUtilities::getAwsFileUrl('big_'.$tt->filename, 'listing'));
        }
        
        $listing['siteImages'] = $images;
        if (!empty($userid)) {
            $favListModal = FavouriteListing::model()->findByAttributes(array('userid' => $userid, 'listingid' => $listing['id']));
            if ($favListModal) {
                $listing['is_favByUser'] = 1;
            }
        }

        $listing['Lighting'] = Listing::getLighting($listing['lightingid']);
        echo json_encode($listing);
    }
    
     public static function getListingsForCampaign($companyid, $campaignid) {
        $listings = Yii::app()->db->createCommand('select distinct t.siteid,l.id,l.name, length, width,mt.name as mediatype, locality,
            t.assignedCompanyId
            from Task t 
inner join Listing l on l.id = t.siteid and l.status = 1 and companyid = ' . $companyid . '
inner join MediaType mt on mt.id = l.mediatypeid
where t.status =1 and t.campaignid = ' . $campaignid)->queryAll();
        return $listings;
    }
    
    
    public static function getListings($type) {
        
    }
}
