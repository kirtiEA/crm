<?php

Yii::import('application.models.base.BaseCompanyContacts');

class CompanyContacts extends BaseCompanyContacts {
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public static function fetchCompanyContacts($id) {
        $sql = "select * from CompanyContacts where companyid = $id";
        return Yii::app()->db->createCommand($sql)->queryAll();
    }
}	