<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
Yii::import('application.models.base.BaseRequestedcompanyvendor');

class Requestedcompanyvendor extends BaseRequestedcompanyvendor {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function showRequestedVendors($companyid) {
        $cmd = Yii::app()->db->createCommand();
        $cmd->select('usercompany.id, user.email, rv.createddate, rv.accepeteddate');
        $cmd->from('requestedcompanyvendor rv');
        $cmd->join('usercompany uc', 'uc.id = rv.vendorcompanyid');
        $cmd->join('user u', 'u.id = uc.id');
        $cmd->where('$companyid=:companyid', array(':companyid' => $companyid));
//        $query = 'select uc.name as name, u.email as vendoradmin, rv.createddate, rv.accepteddate 
//            from requestedcompanyvendor rv
//            inner join UserCompany uc on uc.id = rv.vendorcompanyid
//            inner join User u on u.id = uc.userid
//            and rv.companyid = :companyid, array (':companyid= $companyid')';
//        print_r($cmd);
        return $cmd;
    }

}
