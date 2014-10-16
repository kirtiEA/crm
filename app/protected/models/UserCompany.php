<?php

Yii::import('application.models.base.BaseUserCompany');

class UserCompany extends BaseUserCompany {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function fetchVendorsList($cid) {
        $sql = 'select (select count(id) from Listing where approved = 1 and status = 1 and companyid = uc.id) as cnt,uc.id,uc.name  from RequestedCompanyVendor rcv
inner join UserCompany uc on uc.id = rcv.vendorcompanyid and uc.status = 1
where rcv.companyid = ' .$cid .' and rcv.accepteddate is not null

            union  select count(*) as cnt, uc.id as id, uc.name 
from Listing  l
            inner join UserCompany uc on uc.id = l.companyid and uc.status = 1 and l.status = 1 and uc.id = ' . $cid;
        $command = Yii::app()->db->createCommand($sql);
        $data = $command->queryAll();
        return $data;
    }

    public static function fetchVendorsInCampaign($campaignId) {
        $sql = 'select count(distinct t.siteid) as count, uc.id, uc.name from Task t
        inner join Listing l on l.id = t.siteid and l.status =1
        inner join UserCompany uc on uc.id = l.companyid and uc.status = 1
        where t.status =1 and t.campaignid = ' . $campaignId . ' group by uc.id, uc.name';
        $command = Yii::app()->db->createCommand($sql);
        $data = $command->queryAll();
        return $data;
    }

    public static function fetchVendorEmail($param) {
        $sql = 'SELECT u.email FROM UserCompany uc inner join User u on u.id =uc.userid where uc.status = 1 and uc.id = '.$param;
        return Yii::app()->db->createCommand($sql)->queryRow();
    }
    
    public static function fetchCompanyName($cId) {
        $row = self::model()->findByPk($cId, array('select'=>'name'));
        return $row->name;
    }
}
