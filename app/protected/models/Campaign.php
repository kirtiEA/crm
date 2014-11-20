<?php

Yii::import('application.models.base.BaseCampaign');

class Campaign extends BaseCampaign {
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
     /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            array('name, startDate, endDate', 'required', 'on' => 'insert', 'message' => 'All Fields are required'),
        );
    }
    
    /*
     * fetch campaigns for the company based on active/upcoming/expired
     * pass active=>1, upcoming=>2, expired=>3, active/upcoming =>4
     */
    public static function fetchCompanyCampaignsName($companyid, $type=1) {
        $sql = 'select c.id, name, startDate, endDate, (select count(distinct tt.siteid) from Task tt where tt.campaignid  = c.id and tt.status = 1) as count from Campaign c where c.companyid = ' . $companyid;
        switch ($type) {
            case 1:
                $sql = $sql . ' and DATE(endDate) >= CURRENT_DATE() and DATE(startDate) <= CURRENT_DATE()';
                break;
            case 2:
                $sql = $sql . ' and DATE(endDate) >= CURRENT_DATE() and DATE(startDate) >= CURRENT_DATE()';
                break;
            case 3:
                $sql = $sql . ' and DATE(endDate) <= CURRENT_DATE() and DATE(startDate) <= CURRENT_DATE()';
                 break;   
            case 4:
                $sql = $sql . ' and ((DATE(endDate) >= CURRENT_DATE() and DATE(startDate) <= CURRENT_DATE()) ' . ' or (DATE(endDate) >= CURRENT_DATE() and DATE(startDate) >= CURRENT_DATE()))';
                break;
            default:
                break;
        }
        $command = Yii::app()->db->createCommand($sql);
        $data = $command->queryAll();
        return $data;
    }
    
    /*
     * fetch campaigns for the company based on active/upcoming/expired
     * pass active=>1, upcoming=>2, expired=>3, active/upcoming =>4
     */
    public static function fetchAssignedToMecampaigns($companyid, $type=1) {
        $sql = 'select c.id, name, startDate, endDate, (select count(distinct tt.siteid) from Task tt where tt.campaignid  = c.id and tt.status = 1) as count from Campaign c where c.id in (select campaignid from Task where assignedCompanyId = '. $companyid .' and status = 1 group by campaignid) ' ;
        switch ($type) {
            case 1:
                $sql = $sql . ' and DATE(endDate) >= CURRENT_DATE() and DATE(startDate) <= CURRENT_DATE()';
                break;
            case 2:
                $sql = $sql . ' and DATE(endDate) >= CURRENT_DATE() and DATE(startDate) >= CURRENT_DATE()';
                break;
            case 3:
                $sql = $sql . ' and DATE(endDate) <= CURRENT_DATE() and DATE(startDate) <= CURRENT_DATE()';
                 break;   
            case 4:
                $sql = $sql . ' and ((DATE(endDate) >= CURRENT_DATE() and DATE(startDate) <= CURRENT_DATE()) ' . ' or (DATE(endDate) >= CURRENT_DATE() and DATE(startDate) >= CURRENT_DATE()))';
                break;
            default:
                break;
        }
        $command = Yii::app()->db->createCommand($sql);
        $data = $command->queryAll();
        return $data;
    }
    

    public static function fetchCampaignReport($campId) {
        // c date, generated date, sites, city and no of sites in cities,
        $model = self::model()->findByPk($campId);        
        $campArr = array(
            'name' => $model->name,
            'sdate' => $model->startDate,
            'edate' => $model->endDate,
            'createdDate' => $model->createdDate
        );        
        
        // all task list
        $sql = "SELECT t.id, l.name as sitename, CONCAT(l.locality,', ', a.name) as location, l.name, a.name as city, "
                . "t.dueDate, t.taskDone as status, t.problem, mt.name as mediatype, IFNULL(COUNT(pp.id),0) as photocount, "
                . "t.assigneduserid, CONCAT(u.fname,' ', u.lname) as assigneduser, u.companyid, uc.name as usercompany "
                . "FROM Task t "
                //. "LEFT JOIN Task t ON t.id=pp.taskid "
                . "LEFT JOIN Listing l ON l.id=t.siteid "
                . "LEFT JOIN PhotoProof pp ON pp.taskid=t.id "
                . "LEFT JOIN MediaType mt ON mt.id=l.mediatypeid "
                . "LEFT JOIN Area a ON a.id=l.cityid "
                . "LEFT JOIN User u ON u.id=t.assigneduserid "
                . "LEFT JOIN UserCompany uc ON uc.id=u.companyid "
                . "WHERE t.campaignid = $campId AND t.status=1 AND l.status=1 "
                . "GROUP BY t.id "
                . "ORDER BY t.dueDate DESC ";        
        $siteArr = Yii::app()->db->createCommand($sql)->queryAll();
        
        //echo $sql;die();//.Yii::app()->user->cid.'<br /><pre>';          
        $photoArr = array();
        for($i=0; $i<count($siteArr); $i++) {
            if($siteArr[$i]['assigneduserid']== null) {
                $siteArr[$i]['assignedto'] = 'Unassigned';
            } else {
                if(!Yii::app()->user->isGuest && (Yii::app()->user->cid == $siteArr[$i]['companyid'])) {
                    $siteArr[$i]['assignedto'] = ucwords($siteArr[$i]['assigneduser']);
                } else {
                    $siteArr[$i]['assignedto'] = ucwords($siteArr[$i]['usercompany']);
                }
            }
            unset($siteArr[$i]['assigneduser']);
            unset($siteArr[$i]['usercompany']);
            unset($siteArr[$i]['companyid']);
            unset($siteArr[$i]['assigneduserid']);
            
            if($siteArr[$i]['photocount']) {
                // fetch photo of the tasks
                $sql3 = "SELECT l.name, pp.imageName, pp.clickedDateTime, pp.clickedLat, pp.clickedLng, pp.direction, pp.installation, "
                        . "pp.lighting, pp.obstruction, pp.comments "
                        . "FROM PhotoProof pp "
                        . "LEFT JOIN Task t ON t.id=pp.taskid "
                        . "LEFT JOIN Listing l ON l.id=t.siteid "
                        . "WHERE pp.imageName is not null and taskid= {$siteArr[$i]['id']} ";
                $ppArr = Yii::app()->db->createCommand($sql3)->queryAll();
                array_push($photoArr, $ppArr);
            }
        }        
        //print_r($siteArr);
        //die();
        
        // sites in cities
        $sql2 = "SELECT t.siteid, a.name AS city, l.cityid "
                . "FROM Task t "
                . "LEFT JOIN Listing l ON l.id = t.siteid "
                . "LEFT JOIN Area a ON a.id = l.cityid "
                . "WHERE t.campaignid=$campId "
                . "AND t.status=1 "
                . "AND l.status=1 "
                . "GROUP BY t.siteid ";
        $sitesInCities = Yii::app()->db->createCommand($sql2)->queryAll();
        
        $siteCountArr = array();
        $finalArr = array();
        foreach($sitesInCities as $key => $value) {
            if(isset($siteCountArr[$value['city']])) { 
                $siteCountArr[$value['city']]++;
                //print_r($sites);
            } else {
                $siteCountArr[$value['city']] = 1;
            }
        }
        
        foreach ($siteCountArr as $key => $value) {
            $str = $key . ' (' . $value . ') ';
            array_push($finalArr, $str);
        }
        
        $data = array(
            'campaign' => $campArr,            
            'sitesInCities' => implode(',', $finalArr),
            'sites' => $siteArr,
            'photos' => $photoArr
        );        
        return $data;
    }
    
    
        public static function fetchCampaignsOnIds($ids) {
        $sql = 'select c.id, name, startDate, endDate, (select count(distinct tt.siteid) from Task tt where tt.campaignid  = c.id and tt.status = 1) as count from Campaign c where c.id in ('. $ids .')' ;
        $command = Yii::app()->db->createCommand($sql);
        $data = $command->queryAll();
        return $data;
    }
    
    public static function fetchReports($pop=null,$campaignIds=null,$sdate=null,$edate=null,$assignedTo=null,$companyId=null, $start =null, $limit =null) {
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
                    . " WHERE  t.status = 1 "                    
                    . " AND l.status=1 ";
            
            if (!is_null($pop) && !empty($pop)) {
                $sql .= " and t.pop=$pop ";
            }
            if (!is_null($companyId) && !empty($companyId)) {
                $sql .= " AND t.assignedCompanyid=$companyId ";
            }        
            if(!is_null($sdate) && !is_null($edate)) {
                $sql .= " AND DATE(t.dueDate) BETWEEN '$sdate' AND '$edate' ";
            } else {
                $sql .= " AND DATE(t.dueDate) <= CURRENT_DATE() ";
            }
            if(!is_null($campaignIds) && strlen($campaignIds)) {
                $sql .= " AND c.id IN ($campaignIds) ";
            }
            if(!is_null($assignedTo) && strlen($assignedTo)) {
                $sql .= " AND t.assigneduserid IN ($assignedTo) ";
            }
             $sql .= " GROUP BY t.id ";
             $sql .= " ORDER BY t.dueDate DESC";
            $tasks = Yii::app()->db->createCommand($sql)->queryAll();
            return $tasks;
    }
    
    public static function fiterReportTasks($companyId=null, $campaignIds =null) {
        $sql = "SELECT c.id as cid, c.name as campaign, u.id as uid, CONCAT(u.fname,' ', u.lname) as assignedto "
                    . "FROM Task t "
                    . "LEFT JOIN Campaign c ON c.id=t.campaignid "                    
                    . "LEFT JOIN User u ON u.id=t.assigneduserid "
                    . "WHERE t.status = 1 "
                    . "AND DATE(t.dueDate) <= CURRENT_DATE() and u.username is not null and u.username != '' ";
        if (!is_null($companyId) && !empty($companyId)) {
                $sql .= " AND t.assignedCompanyid=$companyId";
            } 
            if(!is_null($campaignIds) && strlen($campaignIds)) {
                $sql .= " AND c.id IN ($campaignIds) ";
            }
        $filters = Yii::app()->db->createCommand($sql)->queryAll();
        return $filters;    
    }
}

