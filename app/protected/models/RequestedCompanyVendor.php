<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
Yii::import('application.models.base.BaseRequestedCompanyVendor');

class RequestedCompanyVendor extends BaseRequestedCompanyVendor {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function showRequestedVendors($companyid) {

        $query = 'select uc.name as name, u.email as vendoradmin,  DATE_FORMAT(rv.createddate,\'%d %M %Y\') as createddate, rv.accepteddate 
            from RequestedCompanyVendor rv
            inner join UserCompany uc on uc.id = rv.vendorcompanyid
            inner join User u on u.id = uc.userid
            and rv.companyid = ' . $companyid;


        return Yii::app()->db->createCommand($query)->queryAll();
    }

    public static function showRequestedVendorsEmail($companyid) {

        $query = 'select u.email as vendoradmin
            from RequestedCompanyVendor rv
            inner join UserCompany uc on uc.id = rv.vendorcompanyid
            inner join User u on u.id = uc.userid
            where rv.accepteddate is null and rv.companyid = ' . $companyid;
        return Yii::app()->db->createCommand($query)->queryAll();
    }

    public static function showWaitingRequests($cid) {
        $query = 'select rv.id as id, uc.name as name, u.email as vendoradmin, DATE_FORMAT(rv.createddate,\'%d %M %Y\') as createddate, rv.accepteddate 
                    from RequestedCompanyVendor rv 
                    inner join UserCompany uc on uc.id = rv.companyid 
                    inner join User u on u.id = uc.userid and rv.vendorcompanyid =' . $cid . ' and rv.acceptedby is NULL';

        return Yii::app()->db->createCommand($query)->queryAll();
    }
    
    public static function showAcceptedRequests($cid) {
        $query = 'select rv.id as id, uc.name as name, u.email as vendoradmin, DATE_FORMAT(rv.createddate,\'%d %M %Y\') as createddate, rv.accepteddate 
                    from RequestedCompanyVendor rv 
                    inner join UserCompany uc on uc.id = rv.companyid 
                    inner join User u on u.id = uc.userid and rv.vendorcompanyid =' . $cid ;

        return Yii::app()->db->createCommand($query)->queryAll();
    }

    public static function checkUniqueVendor($id, $cvid) {
        $sql = 'SELECT count(*) as cnt from RequestedCompanyVendor where createdby = ' . $id . ' and vendorcompanyid = ' . $cvid;
        return Yii::app()->db->createCommand($sql)->queryRow();
    }

}
