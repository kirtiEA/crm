<?php

Yii::import('application.models.base.BaseArea');

class Area extends BaseArea
{
    public $cnt;
    public $count;
    
    public static function model($className = __CLASS__) 
    {
        return parent::model($className);
    }
        
    public function setLocationArray($location) {
        $country = strtolower($location['country']);
        $countryShort = substr(strtoupper($location['countryCode']), 0, 2);
        $state = strtolower($location['state']);
        $city = strtolower($location['city']);


        // check if country exists        
        if ($country != '' && $country != null) {
            $countryId = Area::checkAreaExists($country, 'c', null, $countryShort);
        }
        // check if state exists
        if (is_numeric($countryId) && $state != '' && $state != null) {
            $stateId = Area::checkAreaExists($state, 's', $countryId);
        }
        // check if city exists
        if (is_numeric($stateId) && $city != '' && $city != null) {
            $cityId = Area::checkAreaExists($city, 'ci', $stateId);
        }
        $area = array('c' => $countryId,
            's' => $stateId,
            'ci' => $cityId);
        return $area;
    }
    public static function getCountryCitySitemap()
    {
        $criteria = new CDbCriteria;
        
        // will fetch those with listings only
        $criteria->select = 't.id, t.name, IFNULL(COUNT(l.id),0) as cnt';
        $criteria->having =  " COUNT(l.id) > 0";
        $criteria->order = 'cnt DESC';
        $criteria->join = 'LEFT JOIN Listing l on l.countryid = t.id';
        $criteria->condition = "t.type = 'c' AND l.solr=1";
        $criteria->group = 't.id';
        
        $model = Area::model()->findAll($criteria);        
        $countryCityArr = array();
        foreach($model as $country) {
            
            // find cities for the country, having listing            
            $criteria2 = new CDbCriteria;
            $criteria2->select = 't.id, t.name, IFNULL(COUNT(l.id),0) as cnt, t.parentid';
            $criteria2->having =  " COUNT(l.id) > 0";
            $criteria2->order = 'cnt DESC';        
            $criteria2->join = 'LEFT JOIN Listing l on l.cityid = t.id';
            $criteria2->condition = "t.type = 'ci' AND l.solr=1";            
            $criteria2->addCondition("t.parentid IN (SELECT id FROM `Area` WHERE type='s' AND parentid ={$country->id})");
            $criteria2->group = 't.id';
            $model2 = Area::model()->findAll($criteria2);
            
            $cityArr = array();
            foreach($model2 as $cities) {                
                array_push($cityArr, array('id'=>$cities->id, 'name'=>$cities->name, 'parentid'=>$cities->parentid));
            }            
            $countryArr = array('id'=>$country->id, 
                                'country'=>$country->name,
                                'count' => $country->cnt,
                                'cities'=>$cityArr);
                        
            array_push($countryCityArr, $countryArr);
        }                
        return $countryCityArr;
    }
    
    /**
     * Returns priority country list for home search
     */
    public function getPriorityCountryOptions($limit=0)
    {
        // 1 - get all countryies list
        /*$criteria = new CDbCriteria;
        $criteria->select = 'id, name';
        $criteria->addCondition('type="c"');        
        $countryArrAll = CHtml::listData(self::model()->findAll($criteria), 'id', 'name');        */
        //print_r($countryArrAll);
        
        // 2 - country list with >1 listing count, only from solr, sorted by count
        $criteria = new CDbCriteria;
        $criteria->select = 't.id, t.name, COUNT(tu.id) as cnt';
        $criteria->join = ' LEFT JOIN `Listing` AS `tu` ON tu.countryid = t.id';
        $criteria->group = ' t.name ';
        $criteria->having = 'COUNT(tu.id) > 0';        
        $criteria->order = ' COUNT(tu.id) DESC';
        $criteria->addCondition("t.type='c' AND tu.solr=1");            
        $countryArr1 = CHtml::listData(self::model()->findAll($criteria), 'id', 'name');        
        
        // = 1 - 2 
        /*$countryArr2 = array_diff($countryArrAll, $countryArr1);
        asort($countryArr2);
        
        $countryArr1['']= '------------------------------------';
        $countryArr = $countryArr1 + $countryArr2; */
        return $countryArr1;
    }
    
    /**
     * Returns priority state list for home search
     */
    public static function getPriorityStateOptions($parentId, $limit=0)
    {     
        $criteria = new CDbCriteria;
        $criteria->select = 't.id, t.name, COUNT(tu.id) as cnt';
        $criteria->join = ' LEFT JOIN `Listing` AS `tu` ON tu.stateid = t.id';
        $criteria->group = ' t.name ';
        $criteria->having = 'COUNT(tu.id) > 0';
        $criteria->order = ' COUNT(tu.id) DESC';
        $criteria->addCondition("t.type='s' AND tu.solr=1 AND t.parentid={$parentId}");
        $stateArr = CHtml::listData(self::model()->findAll($criteria), 'id', 'name');
        return $stateArr;
    }
    /**
     * Returns priority city list for home search
     */
    public static function getPriorityCityOptions($parentId, $limit=0)
    {
        $criteria = new CDbCriteria;
        $criteria->select = 't.id, t.name, COUNT(tu.id) as cnt';
        $criteria->join = ' LEFT JOIN `Listing` AS `tu` ON tu.cityid = t.id';
        $criteria->group = ' t.name ';
        $criteria->having = 'COUNT(tu.id) > 0';
        $criteria->order = ' COUNT(tu.id) DESC';
        $criteria->addCondition("t.type='ci' AND tu.solr=1 AND t.parentid={$parentId}");
        $cityArr = CHtml::listData(self::model()->findAll($criteria), 'id', 'name');
        return $cityArr;
    }
    /**
     * Returns priority country list model
     */
    public static function getPriorityCountryList($limit=0, $hasListing=false)
    {
        // country priority list based on solr
        $criteria = new CDbCriteria;
        
        if($hasListing) {       // will fetch those with listings only
            $criteria->select = 't.id, t.alias, t.name, IFNULL(count(l.id),0) as count';
            $criteria->having =  " count(l.id) > 0";
            $criteria->order = 'count DESC';
        } else {                // will fetch all
            $criteria->select = 't.id, t.alias, t.name, IFNULL(count(l.id),0) as count';
            $criteria->order = 'count DESC, name';
        }
        $criteria->join = 'LEFT JOIN Listing l on l.countryid = t.id';
        $criteria->condition = "t.type = 'c' AND l.solr=1";
        $criteria->group = 't.id';
        //$criteria->params = array(":type" => "c");
        
        if($limit) {
            $criteria->limit = Yii::app()->params['countries_home'];
        }
        $model = Area::model()->findAll($criteria);
        return $model;
    }
    
    /**
     * Returns country list
     */
    public function getCountryOptions($limit = 0) 
    {
        if($limit) {
            $cond = array("condition" => "type =  'c'", "order" => "name", "limit" => $limit);
        } else {
            $cond = array("condition" => "type =  'c'", "order" => "name");
        }
        return CHtml::listData(Area::model()->findAll($cond), 'id', 'name');
    }


    /**
    * Returns state list
    */
    public function getStateOptions($countryId = null) 
    {
        $condition = "type = 's'";
        if($countryId!=null) {
            $condition .= " AND parentid = $countryId";
        }
        return CHtml::listData(Area::model()->findAll(array("condition"=>$condition,"order"=>"name")), 'id', 'name');
    }
    
    /**
    * Returns city list
    */
    public function getCityOptions($stateid = null) 
    {
        $condition = "type = 'ci'";
        if($stateid!=null) {
            $condition .= " AND parentid = $stateid";
        }
        return CHtml::listData(Area::model()->findAll(array("condition"=>$condition, "order"=>"name")), 'id', 'name');
    }
    
    /*
     * Get Countries for Manage Listing 
     */
    public static function getCountriesInManage()
    {    	
        $cond = array("condition" => "type =  'c'", 
        		"order" => "name",
        		"select" => "id, name");        
        $data = Area::model()->findAll($cond);
        $result = array();
        foreach($data as $country) {
        	$temp = array(
        		'countryid' => $country->id,
        		'name' => $country->name
        	);        	
        	array_push($result, $temp);
        }        
        return json_encode($result);        
    }
    /*
     * Get States for Manage Listing
    */
    public static function getStatesInManage()
    {
    	$cond = array("condition" => "type =  's'", 
        		"order" => "name",
        		"select" => "id, name, parentid");        
        $data = Area::model()->findAll($cond);
        $result = array();
        foreach($data as $state) {
        	$temp = array(
        		'stateid' => $state->id,
        		'name' => $state->name,
        		'countryid' => $state->parentid	
        	);        	
        	array_push($result, $temp);
        }        
        return json_encode($result);
    }
    /*
     * Get Countries for Manage Listing
    */
    public static function getCitiesInManage()
    {
    	$cond = array("condition" => "type =  'ci'",
    			"order" => "name",
    			"select" => "id, name, parentid");
    	$data = Area::model()->findAll($cond);
    	$result = array();
    	foreach($data as $city) {
    		$temp = array(
    				'cityid' => $city->id,
    				'name' => $city->name,
    				'stateid' => $city->parentid
    		);
    		array_push($result, $temp);
    	}
    	return json_encode($result);
    }
       
    
    /**
     * Returns area name
     */
    public function getAreaName($areaId)
    {
        $area = self::model()->findByPk($areaId, array('select'=>'name'));
        if($area) {
            return $area->name;
        } else {
            return false;
        }
    }
    
    /**
     * Returns country name by countryId
     */
    public function getCountryName($counteryId)
    {
        $area = self::model()->findByPk($counteryId, array('select'=>'name', 'condition' => "type = 'c'"));
        if($area) {
            return $area->name;
        } else {
            return false;
        }
    }
    
    /*
     * check if Area exists if not then create and return id
     */
    public static function checkAreaExists($areaName, $type, $parentId=null, $shortCode=null)
    {
        /*$criteria = new CDbCriteria;
        $criteria->select='id';  // only select the 'title' column
        $condition='name=:name AND type=:type';
        $params=array(':name'=>strtolower($areaName), ':type'=>$type);*/        
        if($parentId) {
            $result = self::model()->findByAttributes(array('name'=>$areaName, 'type'=>$type, 'parentid'=>$parentId), array('select'=>'id'));        
        } else {
            $result = self::model()->findByAttributes(array('name'=>$areaName, 'type'=>$type), array('select'=>'id'));        
        }
        if($result) {
            // record exists
            return $result->id;
        } else {            
            // record don't exists
            $areaModel = new Area;
            if($parentId!=null && is_numeric($parentId)) {
                // if parent id is passed
                $areaModel->parentid = $parentId;
            } else {
                // parent id is not passed (only scenario is for country)
                $areaMaxId = self::getAreaMaxId()+1; 
                $areaModel->id = $areaMaxId;        // set the id as well
                $areaModel->parentid = $areaMaxId;  // set the parent id
                if($shortCode!='' || $shortCode!=null) {
                    $areaModel->short_code = $shortCode;                    
                }
            }
            $areaModel->name = ucwords(strtolower(trim($areaName)));
            $areaModel->type = strtolower(trim($type)); 
            $areaModel->alias = JoyUtilities::createAlias($areaName); 
            $areaModel->save();            
            return $areaModel->primaryKey;          // return id
        }
    }
    /*
     * return @areaMaxId
     */
    private static function getAreaMaxId()
    {        
        $criteria = new CDbCriteria;        
        $criteria->select = 'max(id) as id';    
        $row = self::model()->find($criteria);
        $areaMaxId = $row['id'];
        return $areaMaxId;
    }
    

    /**
     * Returns area id by country short code
     */
    public function getAreaIdByShortCode($alias)
    {
        $condition = "short_code = '". $alias ."'";
        $result = self::model()->find(array("condition"=>$condition));
        if($result)
            return $result->id;
        else
            return 0;
    }
    
    /**
     * Returns area id by area alias
     */
    public function getAreaIdByAlias($alias)
    {
        $condition = "alias = '". $alias ."'";
        $result = self::model()->find(array("condition"=>$condition));
        if($result)
            return $result->id;
        else
            return 0;
    }
    
    // getShortCodeByCountry($countryName) in Ip2nationCountries
    
    public static function isCountryExist($countyName) {
        $criteria = new CDbCriteria();
        $criteria->select = 'id';
        $criteria->condition = 'name=:name AND type=:type';
        $criteria->params = array(':name' => $countyName, ':type' => 'c');

        $data = self::model()->find($criteria);
        if($data) {
            return $data->id;
        } else {
            return false;
        }
    }
    
    public static function isStateExist($countryId, $stateName) {
        $criteria = new CDbCriteria();
        $criteria->select = 'id';
        $criteria->condition = 'name=:name AND type=:type AND parentid =:parentId';
        $criteria->params = array(':name' => $stateName, ':type' => 's', ':parentId' => $countryId);

        $data = self::model()->find($criteria);
        if($data) {
            return $data->id;
        } else {
            return false;
        }
    }
    
    public static function isCityExist($stateId, $cityName) {
        $criteria = new CDbCriteria();
        $criteria->select = 'id';
        $criteria->condition = 'name=:name AND type=:type AND parentid =:parentId';
        $criteria->params = array(':name' => $cityName, ':type' => 'ci', ':parentId' => $stateId);

        $data = self::model()->find($criteria);
        if($data) {
            return $data->id;
        } else {
            return false;
        }
    }
    
    public static function getListingCountInCountryStateCity ($countryId) {
        $command = Yii::app()->db->createCommand('SELECT t.id, t.name AS country, st.name AS state, ct.name AS city, COUNT(ct.id) AS city_count, l.countryid, l.stateid, l.cityid
                                                    FROM `Listing` AS l
                                                    INNER JOIN `Area` t ON l.countryid = t.id
                                                    INNER JOIN `Area` st ON l.stateid = st.id
                                                    INNER JOIN `Area` ct ON l.cityid = ct.id
                                                    WHERE l.solr=1 AND countryid = '. $countryId .'
                                                    GROUP BY city
                                                    ORDER BY state, city_count DESC')->queryAll();
        
        
        return $command;
        
    }
}