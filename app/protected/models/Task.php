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
    
    public static function fetchTaskList($campaignId = null, $assignedUserId = null, $startDate = null, $endDate = null ) {
        $sql = 'select l.name, length, width,mt.name as mediatype, locality, t.id, DATE_FORMAT(t.dueDate,\'%d %M %Y\') as dueDate, c.name as campaignname, 
        u.id as assigneduserid, u.username as assignedusername from Task t 
        inner join Campaign c on c.id = t.campaignid
        inner join Listing l on l.id = t.siteid 
        inner join MediaType mt on mt.id = l.mediatypeid
        left outer join User u on u.id = t.assigneduserid
        where t.status =1';
        if ($campaignId) {
            $sql = $sql . ' and  campaignid in (' . $campaignId . ')';
        }
        if ($assignedUserId) {
            $sql = $sql . ' and  assigneduserid in (' . $assignedUserId . ')';
        }
                
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
set assignedCompanyId =  ' . $assignedCompanyId . ' where task.id = t.id';
if ($date != null) {
    $sql = $sql . ' and task.dueDate = ' . $date;
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
}
