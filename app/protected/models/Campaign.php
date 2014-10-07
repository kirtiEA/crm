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
                $sql = $sql . ' and endDate >= \'' . date("Y-m-d H:i:s") . '\' and startDate <= \'' . date("Y-m-d H:i:s") . '\'';
                break;
            case 2:
                $sql = $sql . ' and endDate >= \'' . date("Y-m-d H:i:s") . '\' and startDate >= \'' . date("Y-m-d H:i:s") . '\'';
                break;
            case 3:
                $sql = $sql . ' and endDate <= \'' . date("Y-m-d H:i:s") . '\' and startDate <= \'' . date("Y-m-d H:i:s") . '\'';
                 break;   
            case 4:
                $sql = $sql . ' and ((endDate >= \'' . date("Y-m-d H:i:s") . '\' and startDate <= \'' . date("Y-m-d H:i:s") . '\') ' . ' or (endDate >= \'' . date("Y-m-d H:i:s") . '\' and startDate >= \'' . date("Y-m-d H:i:s") . '\'))';
                break;
            default:
                break;
        }
        $command = Yii::app()->db->createCommand($sql);
        $data = $command->queryAll();
        return $data;
    }
}

