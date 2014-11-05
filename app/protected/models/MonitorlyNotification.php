<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
Yii::import('application.models.base.BaseMonitorlyNotification');

class MonitorlyNotification extends BaseMonitorlyNotification {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function showUnsubscribedRequestedVendors($id) {

        $query = 'SELECT DATE_FORMAT(createddate,\'%d %M %Y\') as createddate, miscellaneous,
            mn.lastViewedDate as accepteddate
                FROM MonitorlyNotification mn 
                inner join UserCompany uc on uc.userid = mn.createdby and uc.status = 1 and  mn.createdby = ' . $id .
                ' where typeid = 1 and mn.lastViewedDate is null';

        return Yii::app()->db->createCommand($query)->queryAll();
    }

    public static function showUnsubscribedRequestedVendorsEmail($id) {

        $query = 'SELECT miscellaneous 
                FROM MonitorlyNotification mn 
                inner join UserCompany uc on uc.userid = mn.createdby and uc.status = 1 and mn.createdby = ' . $id .
                ' where typeid = 1 and mn.lastViewedDate is null';

        return Yii::app()->db->createCommand($query)->queryAll();
    }

    public static function checkUniqueUnsubscribedVendors($id, $email) {
        $query = 'SELECT count(*)as cnt
                FROM MonitorlyNotification mn 
                inner join UserCompany uc on uc.userid = mn.createdby and uc.status = 1 and mn.createdby = ' . $id .
                ' where typeid = 1 and miscellaneous like \'' . $email . '\'';
        return Yii::app()->db->createCommand($query)->queryRow();
    }
    
    public static function fetchNotifications($cid) {
        $sql = 'SELECT mn.typeid,uc.name, mn.createddate FROM MonitorlyNotification mn
inner join UserCompany uc on uc.id = mn.companyid and uc.status = 1
where notifiedcompanyid = ' . $cid;
        return Yii::app()->db->createCommand($query)->queryAll();
    }
}
