<?php

Yii::import('application.models.base.BaseTask');
class Task extends BaseTask {
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public static function removeListingFromCampaign($cid, $lid) {
        $listings = Yii::app()->db->createCommand('update Task set status =0 where campaignid = ' . $cid .' and siteid = ' . $lid)->execute();
        return $listings;
    }
    
    public static function fetchTaskList($companyid, $campaignId = null, $assignedUserId = null, $startDate = null, $endDate = null, $start = null, $limit = null ) {
        $sql = 'select l.name, length, width,mt.name as mediatype, locality, t.id, DATE_FORMAT(t.dueDate,\'%d %M %Y\') as dueDate, c.name as campaignname, 
        u.id as assigneduserid, 
        case when u.username is null then "UnAssigned"
             else u.username
        end as assignedusername from Task t 
        inner join Campaign c on c.id = t.campaignid
        inner join Listing l on l.id = t.siteid 
        inner join MediaType mt on mt.id = l.mediatypeid
        left outer join User u on u.id = t.assigneduserid
        where t.status =1 and DATE_FORMAT(dueDate, \'%Y-%m-%d\') >= CURRENT_DATE and assignedCompanyId = ' . $companyid .' AND l.status=1 ';
        if ($campaignId) {
            $sql = $sql . ' and  campaignid in (' . $campaignId . ')';
        }
        if ($assignedUserId) {
            $sql = $sql . ' and  assigneduserid in (' . $assignedUserId . ')';
        }
        if ($startDate) {
            $sql = $sql . ' and DATE_FORMAT(dueDate, \'%Y-%m-%d\') >= \'' . $startDate . '\'';
        }
        if ($endDate) {
            $sql = $sql . ' and DATE_FORMAT(dueDate, \'%Y-%m-%d\') <= \'' . $endDate . '\'';
        }
        
        $sql = $sql . ' order by t.dueDate ASC limit '. $start .','. $limit;
       $tasks = Yii::app()->db->createCommand($sql)->queryAll();
       return $tasks;
    }
    
    public static function fetchTaskDetails($tid) {
        $sql = 'select l.name, length, width,mt.name as mediatype, locality, t.id, DATE_FORMAT(t.dueDate,\'%d %M %Y\') as dueDate, c.name as campaignname, 
        u.id as assigneduserid, u.username as assignedusername from Task t 
        inner join Campaign c on c.id = t.campaignid
        inner join Listing l on l.id = t.siteid 
        inner join MediaType mt on mt.id = l.mediatypeid
        left outer join User u on u.id = t.assigneduserid
        where t.status =1 and t.id = ' . $tid;
        $tasks = Yii::app()->db->createCommand($sql)->queryRow();
        return $tasks;
    }
    
    public static function updateTasksForPop($campaignid,$companyid, $assignedCompanyId, $date = null) {
        $sql = 'Update Task as task, (select tt.id from Task tt
inner join Listing l on l.id = tt.siteid and l.companyid ='. $companyid . '
where tt.status = 1 and tt.campaignid = ' . $campaignid . ') as t
set pop = 1 , assignedCompanyId =  ' . $assignedCompanyId . ' where task.id = t.id';
if ($date != null) {
    $sql = $sql . ' and task.dueDate = \'' . $date .'\'';
}        
        return Yii::app()->db->createCommand($sql)->execute();
    }
    
    public static function updateTaskPopWhenNoVendorSelected($cid, $campaignId) {
        $sql = 'Update Task set assignedCompanyId =' . $cid . ' where campaignid=' . $campaignId . ' and  pop = 1';
        return Yii::app()->db->createCommand($sql)->execute();
    }
    
    public static function deleteAllTaskForCampaign($cid) {
        $sql = 'Delete from Task where campaignid = '. $cid;
        return Yii::app()->db->createCommand($sql)->execute();
    }
    
    public static function fetchAllSitesInCampaign($cid) {
        $sql = 'Select siteid from Task where status = 1 and campaignid = ' . $cid;
        return Yii::app()->db->createCommand($sql)->queryAll();
    }
    
    public static function fetcUsersAssignedToSite($siteid, $campaignid,$companyid) {
        $sql = 'select u.id,u.username, count(t.id) as cnt  from Task t
        inner join User u on u.id = t.assigneduserid
        where t.siteid = ' . $siteid . ' and t.campaignid = '. $campaignid .' and  t.assignedCompanyId = ' . $companyid .'
        group by u.id order by cnt desc limit 1';
        return Yii::app()->db->createCommand($sql)->queryRow();
    }
    
    public static function updateAssignTaskforaSite($siteid, $campaignid, $userid) {
        $sql = 'Update Task set assigneduserid = ' . $userid . ' where DATE(dueDate) >= CURRENT_DATE and campaignid=' . $campaignid . ' and siteid = ' . $siteid;
//        return $sql ;
        return Yii::app()->db->createCommand($sql)->execute();
    }
}
