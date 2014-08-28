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
    public function rules()
    {
            return array(
                
                array('name, startDate, endDate', 'required', 'on' => 'insert', 'message' => 'All Fields are required' ),
            );
    }
    
    /*
     * fetch campaigns for the company based on active/upcoming/expired
     * pass active=>1, upcoming=>2, expired=>3
     */
    public static function fetchCompanyCampaignsName($companyid, $type=1) {
        $sql = 'select name, startDate, endDate, (select count(t.id) from Task t where t.campaignid  = c.id) as count from Campaign c where c.companyid = ' . $companyid;
        switch ($type) {
            case 1:
                $sql = $sql . ' and endDate >= \'' . date("Y-m-d H:i:s") . '\' and startDate <= \'' . date("Y-m-d H:i:s") . '\'';
                break;
            case 2:
                $sql = $sql . ' and endDate >= \'' . date("Y-m-d H:i:s") . '\' and startDate >= \'' . date("Y-m-d H:i:s") . '\'';
                break;
            case 3:
                $sql = $sql . ' and endDate <= \'' . date("Y-m-d H:i:s") . '\' and startDate <= \'' . date("Y-m-d H:i:s") . '\'';
                 break;   
            default:
                break;
        }
        $command = Yii::app()->db->createCommand($sql);
        $data = $command->queryAll();
        return $data;
    }
}

