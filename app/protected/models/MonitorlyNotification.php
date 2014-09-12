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

        $query = 'SELECT createddate, miscellaneous 
                FROM MonitorlyNotification mn 
                inner join UserCompany uc on uc.userid = mn.createdby and mn.createdby = ' . $id .
                ' where typeid = 1';

        return Yii::app()->db->createCommand($query)->queryAll();
    }

    public static function showUnsubscribedRequestedVendorsEmail($id) {

        $query = 'SELECT miscellaneous 
                FROM MonitorlyNotification mn 
                inner join UserCompany uc on uc.userid = mn.createdby and mn.createdby = ' . $id .
                ' where typeid = 1';

        return Yii::app()->db->createCommand($query)->queryAll();
    }

}
