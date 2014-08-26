<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


class JoyUtilities 
{
    const ROLE_ADMIN = '1';
    const ROLE_MEDIA_BUYER = '2';
    const ROLE_MEDIA_OWNER = '3';
    const ROLE_THIRD_PARTY = '4';
    public static $weeklyArr = array("Mon" => 0, "Tue" => 0, "Wed" => 0, "Thu" => 0, "Fri" => 0, "Sat" => 0, "Sun" => 0);
    public static $monthArr = array("Jan" => 0, "Feb" => 0, "Mar" => 0, "Apr" => 0, "May" => 0, "June" => 0, "July" => 0, "Aug" => 0, "Sept" => 0, "Oct" => 0, "Nov" => 0, "Dec" => 0 );
    
    /*
    public static function redirectUser($userId)
    {
        // redirect based on user role
        $roleId = Yii::app()->user->roleId;
        if($roleId) {            
            if($roleId==1) {
                Yii::app()->user->setReturnUrl(array('/admin/home'));
            } else if($roleId==3 || $roleId==4 || $roleId==2) {
                $userType = JoyUtilities::userType($roleId);
                Yii::app()->user->setReturnUrl(array('/'. $userType .'/dashboard'));
            }
        }
    }
    */
    public static function redirectUser($userId)
    {
        // redirect based on user role
        $roleId = Yii::app()->user->roleId;
        if($roleId) {            
            if($roleId==1) {
                Yii::app()->user->setReturnUrl(array('/admin/home'));
            } else if($roleId==3) {
                Yii::app()->user->setReturnUrl(array('/dashboard/v/'. $userId));
            } else if($roleId==2) {
                Yii::app()->user->setReturnUrl(array('/dashboard/b/'. $userId));
            } else {                
                Yii::app()->user->setReturnUrl(array('/dashboard/s/'. $userId));
            }
        }
    }

    public static function isUserActiveFromSession() {
        if(Yii::app()->user->active && Yii::app()->user->status) {
            return true;
        } else {
            return false;
        }
    }
    
    public static function isUserActive($userId) {
        $userData = User::getUserAttributeById($userId, 'active, status');
        if($userData->active && $userData->status) {
            return true;
        } else {
            return false;
        }
    }
    
    public static function getUserRoleId($userId)
    {        
        // ref - http://www.yiiframework.com/doc/guide/1.1/en/database.query-builder        
        // get user role id 
        $role = Yii::app()->db->createCommand()
                    ->select('roleid')
                    ->from('UserRole')
                    ->where('userid=:userid', array(':userid'=>$userId))
                    ->queryRow();
        // redirect based on user role
        if($role)
            return $role['roleid'];                    
        else 
            return false;
    }
            
    public static function getUserRole($userId)
    {        
        // ref - http://www.yiiframework.com/doc/guide/1.1/en/database.query-builder        
        // get user role id 
        $roleId = Yii::app()->user->roleId;
        $role = self::userType($roleId);        
        if(strlen($role)<1) {
            $role = 'User';
        } else {
            $role = ucwords(str_replace('-', ' ', $role));
        }
        return $role;
    }
    
    public static function cleanInput($array) {
        if(!is_array($array)) {
            return trim($array);
        }
        foreach($array as $key=>$value) {
            if($key != "password" && $key != "confirmPassword") {
                $array[$key] = trim($value);
            }
        }
        return $array;
    }
    
    public static function createAlias($str) {
        $str = trim($str);
        $junkChar = array("'", "&", ",", "$", "+", ",", "/", ":", "=", "?", "@", ".", "(", ")", "[", "]", "*", "&nbsp;", "&amp;", "%A0");
        //$str = str_replace($junkChar, "", $str); // replace special character
        $str = str_replace($junkChar, "", html_entity_decode($str)); // replace special character
        $str = str_replace(" ", "-", $str); // replace space with hyphen
        $str = str_replace("--", "-", $str); // replace double hyphen with single hyphen      
        return strtolower($str);
    }
    
//    public static function getCompanyUrl($companyId) {
//        $data = UserCompany::getCompanyUrlData($companyId);
//        $url = Yii::app()->urlManager->createUrl('seller/index', array(
//            'id' => $data['alias'],
//                ));
//        return $url;
//    }
    
    public static function getCompanyUrl($userId) {        
        $url = Yii::app()->urlManager->createUrl('seller/index', array(
            'userid' => $userId,
                ));
        return $url;
    }
    
    public static function userType($roleId = 0) {
        if(!$roleId) {
            return '';
        } elseif($roleId == 1) {
            return 'admin';
        } elseif($roleId == 2) {
            return 'media-buyer';
        } elseif($roleId == 3) {
            return 'media-owner';
        } elseif($roleId == 4) {
            return 'media-service-providers';
        }
    }
    
    /*
    public static function getHomeUrl() {
        $roleId = 0;
        if (!Yii::app()->user->isGuest) {
            $roleId = Yii::app()->user->roleId;
        }
        $userType = self::userType($roleId);
        return Yii::app()->getHomeUrl() . '' . $userType;
    }
    */
    
    public static function getHomeUrl() {
        $roleId = 0;
        if (!Yii::app()->user->isGuest) {
            $roleId = Yii::app()->user->roleId;
        }        
        $userType = self::userType($roleId);
        if($userType == 'admin')
            return Yii::app()->getHomeUrl() . '' . $userType;
        else
            return Yii::app()->getHomeUrl();
    }

    // Return dashbaord url. 
    // Return nothing if user is not logged in
    /*
    public static function getDashboardUrl() {
        $roleId = 0;
        if (!Yii::app()->user->isGuest) {
            $roleId = Yii::app()->user->roleId;
            $userType = self::userType($roleId);
            if($userType != 'admin') {
                return Yii::app()->getHomeUrl() . '' . $userType .'/dashboard';
            } else {
                return Yii::app()->getHomeUrl() . '' . $userType .'/home';
            }
        }
    }
    */
    
    public static function getDashboardUrl() {
        $roleId = Yii::app()->user->roleId;
        $userId = Yii::app()->user->id;
        if($roleId) {            
            if($roleId==1) {
                return Yii::app()->getHomeUrl() . 'admin/home';
            } else if($roleId==3) {                
                return Yii::app()->getHomeUrl() . 'dashboard/v/' . $userId;
            } else if($roleId==2) {
                return Yii::app()->getHomeUrl() . 'dashboard/b/' . $userId;
            } else {                
                return Yii::app()->getHomeUrl() . 'dashboard/s/' . $userId;
            }
        }
    }
    
   public static function getVendorShowcaseUrl($url) {
       return Yii::app()->getHomeUrl() . $url;
   } 
    public static function getListingUrl($listingId) {
        $listingUrlData = Listing::getListingUrlData($listingId);
        
        $url = Yii::app()->urlManager->createUrl('listing/view', array(
                    'id' => $listingUrlData['listingid'],
                    'country' => $listingUrlData['country'],
                    'state' => $listingUrlData['state'],
                    'city' => $listingUrlData['city'],
                    'listingname' => $listingUrlData['listingName'],
                ));
        
        $urlData['listingData'] = $listingUrlData;
        $urlData['url'] = $url;
        
        return $urlData;
    }
    
    public static function formatNumber($number, $decimal=0)
    {
        return number_format($number, $decimal);    // length width 1,234.49 x 5,877.37  
    }
    
    public static function getAwsFileUrl($fileName, $fileType='listing', $duration=null) {
        if($fileName == ''){
            return false;
        }
        $s3Obj = new EatadsS3();
        return $s3Obj->getFileUrl($fileType.'/'.$fileName, $duration);
    }
    
    public static function deleteAwsFile($fileName, $fileType='listing') {
        $s3Obj = new EatadsS3();
        return $s3Obj->deleteFile($fileType.'/'.$fileName);
    }
    
    public static function deleteAwsListingFile($fileName) {
        $s3Obj = new EatadsS3();
        $fileType = 'listing';      // folder name in AWS S3
        $files = array("{$fileType}/{$fileName}",
                        "{$fileType}/big_{$fileName}",
                        "{$fileType}/small_{$fileName}",
                        "{$fileType}/tiny_{$fileName}");
        return $s3Obj->deleteMultiFiles($files);        
    }
    
    public function chopString($str, $length = 45) {
        if(strlen($str) <= $length || strlen($str) <= $length + 3 ){
            return $str;
        } else {
            return substr($str, 0, $length)."...";
        }
    }
    
    public static function getBrowser() {
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version = "";

        //First get the platform?
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
        } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        } elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        }

        // Next get the name of the useragent yes seperately and for good reason
        if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        } elseif (preg_match('/Firefox/i', $u_agent)) {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        } elseif (preg_match('/Chrome/i', $u_agent)) {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        } elseif (preg_match('/Safari/i', $u_agent)) {
            $bname = 'Apple Safari';
            $ub = "Safari";
        } elseif (preg_match('/Opera/i', $u_agent)) {
            $bname = 'Opera';
            $ub = "Opera";
        } elseif (preg_match('/Netscape/i', $u_agent)) {
            $bname = 'Netscape';
            $ub = "Netscape";
        }

        // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
                ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }

        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
                $version = $matches['version'][0];
            } else {
                $version = $matches['version'][1];
            }
        } else {
            $version = $matches['version'][0];
        }

        // check if we have a number
        if ($version == null || $version == "") {
            $version = "?";
        }

        return array(
            'userAgent' => $u_agent,
            'name' => $bname,
            'version' => $version,
            'platform' => $platform,
            'pattern' => $pattern
        );
    }
    
    public static function geocode($address)
	{		
        // GEOCODE
        $address = urlencode($address);
        $protocol = Yii::app()->params['protocol'];
		$geoCodeURL = $protocol.'maps.googleapis.com/maps/api/geocode/json?address='.$address.'&sensor=false';
        //$geoCodeURL = 'https://maps.googleapis.com/maps/api/geocode/json?key='.Yii::app()->params['gmapApiKey'].'&address='.$address.'&sensor=false';

        $sublocality = array();
		$data = json_decode(file_get_contents($geoCodeURL)); 
        
        $country = $state = $city = '';
        if(isset($data->results[0] )) {
            foreach($data->results[0]->address_components as $address_component){			
                if(in_array('country', $address_component->types)){		    	
                    $country = $address_component->long_name;
                    $countryCode = $address_component->short_name;		        
                } else if(in_array('route', $address_component->types)) {		    	
                    $address = $address_component->long_name;                		        
                } else if(in_array('sublocality', $address_component->types)) {                
                    if(!isset($sublocality[0])) {
                        array_push($sublocality, $address_component->long_name);
                        continue;                    
                    }
                    if(isset($sublocality[0]) && !isset($sublocality[1])) {
                        array_push($sublocality, $address_component->long_name);
                        continue;
                    }
                    if(isset($sublocality[0]) && isset($sublocality[1]) && !isset($sublocality[2])) {
                        array_push($sublocality, $address_component->long_name);
                        continue;
                    }                
                } else if(in_array('administrative_area_level_1', $address_component->types)) {
                    $state = $address_component->long_name;                
                } else if(in_array('locality', $address_component->types)) {
                    $city = $address_component->long_name;                
                } else if(in_array('administrative_area_level_2', $address_component->types) && strlen($city)==0) {
                    $city = $address_component->long_name;                
                }		
            }
            $lat = $data->results[0]->geometry->location->lat;
            $lng = $data->results[0]->geometry->location->lng;            
        }
        $location = array();
        if($country!='') {
            if($state=='') {
               $state = $country;
            }
            if($city=='') {
                $city = $state;
            }
            if(count($sublocality)==2) {
                array_unshift($sublocality, '');
            } elseif(count($sublocality)==1) {
                array_unshift($sublocality, '');
                array_unshift($sublocality, '');
            } elseif(count($sublocality)==0) {            
                array_push($sublocality, '');
                array_push($sublocality, '');
                array_push($sublocality, $city);
            }            
            $location = array(
                'country' => $country,
                'countryCode' => $countryCode,
                'state' => $state,
                'city' => $city,
                'lat' => round($lat, 6),
                'lng' => round($lng, 6),
                'sublocality1' => $sublocality[0],
                'sublocality2' => $sublocality[1],
                'sublocality' => $sublocality[2]
            );
            
            return $location;
        } else {
            return $location;
        }
    }
    public static function reverseGeocode($lat, $lng)
	{
        // REVERSE GEOCODE
        // call geoencoding api with param json for output         
        $geoCodeURL = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$lng&sensor=false";
        //$geoCodeURL = "https://maps.googleapis.com/maps/api/geocode/json?key=".Yii::app()->params['gmapApiKey']."&latlng=$lat,$lng&sensor=false"; 

        $data = json_decode(file_get_contents($geoCodeURL));
        /*        
        $ch = curl_init($geoCodeURL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $data = json_decode(curl_exec($ch));
        curl_close($ch);*/

        $country = $state = $city = $countryCode = '';
        $sublocality = array();
 
        if(isset($data->results[0])) {
            foreach($data->results[0]->address_components as $address_component){			
                if(in_array('country', $address_component->types)){		    	
                    $country = $address_component->long_name;
                    $countryCode = $address_component->short_name;		        
                } else if(in_array('route', $address_component->types)) {		    	
                    $address = $address_component->long_name;                		        
                } else if(in_array('sublocality', $address_component->types)) {                
                    if(!isset($sublocality[0])) {
                        array_push($sublocality, $address_component->long_name);
                        continue;                    
                    }
                    if(isset($sublocality[0]) && !isset($sublocality[1])) {
                        array_push($sublocality, $address_component->long_name);
                        continue;
                    }
                    if(isset($sublocality[0]) && isset($sublocality[1]) && !isset($sublocality[2])) {
                        array_push($sublocality, $address_component->long_name);
                        continue;
                    }                
                } else if(in_array('administrative_area_level_1', $address_component->types)) {
                    $state = $address_component->long_name;                
                } else if(in_array('locality', $address_component->types)) {
                    $city = $address_component->long_name;                
                } else if(in_array('administrative_area_level_2', $address_component->types) && strlen($city)==0) {
                    $city = $address_component->long_name;                
                }                
            }
        }
        // country, state, city not empty 
        if($state == '') {
            $state = $country;
        }
        if($city == '') {
            $city = $state;
        }
        if(count($sublocality)==2) {
            array_unshift($sublocality, '');
        } elseif(count($sublocality)==1) {
            array_unshift($sublocality, '');
            array_unshift($sublocality, '');
        } elseif(count($sublocality)==0) {            
            array_push($sublocality, '');
            array_push($sublocality, '');
            array_push($sublocality, $city);
        }
        $location = array(
            'country' => ucwords($country),
            'countryCode' => strtoupper($countryCode),
            'state' => ucwords($state),
            'city' => ucwords($city),
            'lat' => $lat,
            'lng' => $lng,
            'sublocality1' => $sublocality[0],
            'sublocality2' => $sublocality[1],
            'sublocality' => $sublocality[2]
        );
        return $location;
	}
    
    public static function formatAdminGraphData($dbData, $dummyData){
        // assing db data in dummy data array
        foreach ($dbData as $key => $val) {
            $dummyData[$val['xaxis']] = (float)$val['cnt'];
        }
        $finalData[0] = array_keys($dummyData);
        $finalData[1] = array_values($dummyData);
        return $finalData;
    }
    
    public static function week_text_alter(&$item1, $key, $prefix)
    {
        $item1 = $prefix['pre']. $item1 . $prefix['post'];
    }
    
    public static function getNoOfWeek($startDate, $endDate){
        // convert date in valid format
        $startDate = date("Y-m-d", strtotime($startDate));
        $endDate = date("Y-m-d", strtotime($endDate));
        $yearEndDay = 31;
        
        $weekArr = array();
        $startYear = date("Y", strtotime($startDate));
        $endYear = date("Y", strtotime($endDate));
        if($startYear != $endYear) {
            $newStartDate = $startDate;

            for($i = $startYear; $i <= $endYear; $i++) {
                
                if($endYear == $i) {
                    $newEndDate = $endDate;
                } else {
                    $newEndDate = $i."-12-".$yearEndDay;
                }
                $startWeek = date("W", strtotime($newStartDate));
                $endWeek = date("W", strtotime($newEndDate));
                if($endWeek == 1){
                    $endWeek = date("W", strtotime($i."-12-".($yearEndDay-7)));
                }
                $tempWeekArr = range($startWeek, $endWeek);
                array_walk($tempWeekArr, "JoyUtilities::week_text_alter", array('pre' => 'Week ', 'post' => " '". substr($i, 2, 2) ));
                $weekArr = array_merge($weekArr, $tempWeekArr);
                
                $newStartDate = date("Y-m-d", strtotime($newEndDate . "+1 days"));
            }
        } else {
            $startWeek = date("W", strtotime($startDate));
            $endWeek = date("W", strtotime($endDate));
            $endWeekMonth = date("m", strtotime($endDate));
            if($endWeek == 1 && $endWeekMonth == 12){
                $endWeek = date("W", strtotime($endYear."-12-".($yearEndDay-7)));
            }
            $weekArr = range($startWeek, $endWeek);
            array_walk($weekArr, "JoyUtilities::week_text_alter", array('pre' => 'Week ', 'post' => " '". substr($startYear, 2, 2)));
        }
        $weekArr = array_fill_keys($weekArr, 0);
        return $weekArr;
    }
    
    public static function year_text_alter(&$item1, $key, $prefix)
    {
        $date = "2014-".$item1."-01";
        $item1 = date("M", strtotime($date)) . " '" .$prefix;
    }
    
    function getNoOfMonth($startDate, $endDate){
        // convert date in valid format
        $startDate = date("Y-m-d", strtotime($startDate));
        $endDate = date("Y-m-d", strtotime($endDate));
        
        $yearArr = array();
        $startYear = date("Y", strtotime($startDate));
        $endYear = date("Y", strtotime($endDate));
        if($startYear != $endYear) {
            $newStartDate = $startDate;

            for($i = $startYear; $i <= $endYear; $i++) {
                $yearEndDay = 31;
                if($endYear == $i) {
                    $newEndDate = $endDate;
                } else {
                    $newEndDate = $i."-12-".$yearEndDay;
                }
                $startMonth = date("m", strtotime($newStartDate));
                $endMonth = date("m", strtotime($newEndDate));
                
                $tempYearArr = range($startMonth, $endMonth);
                array_walk($tempYearArr, "JoyUtilities::year_text_alter", date('y', strtotime($newStartDate)));
                $yearArr = array_merge($yearArr, $tempYearArr);
                
                $newStartDate = date("Y-m-d", strtotime($newEndDate . "+1 days"));
            }
        } else {
            $startMonth = date("m", strtotime($startDate));
            $endMonth = date("m", strtotime($endDate));
            $yearArr = range($startMonth, $endMonth);
            array_walk($yearArr, "JoyUtilities::year_text_alter", date('y', strtotime($startDate)));
        }
        $yearArr = array_fill_keys($yearArr, 0);
        return $yearArr;
    }
    
    function getNoOfYear($startDate, $endDate){
        $startYear = date("Y", strtotime($startDate));
        $endYear = date("Y", strtotime($endDate));
        for($i = $startYear; $i <= $endYear; $i++) {
            $yearArr[strval($i)] = 0;
        }
        return $yearArr;
    }
        
    public static function pushToQueue($arr) {
        $sqs = new EatadsSqs();
        return $sqs->generateQueueMessage($arr);
    }
}
