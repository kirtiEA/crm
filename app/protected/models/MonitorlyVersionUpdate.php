<?php

Yii::import('application.models.base.BaseMonitorlyVersionUpdate');
class MonitorlyVersionUpdate extends BaseMonitorlyVersionUpdate {
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public static function updateLastVersion() {
        $sql = 'Update MonitorlyVersionUpdate set enddate = \'' . date("Y-m-d H:i:s") . '\' where enddate is null';
        return Yii::app()->db->createCommand($sql)->execute();
    }
}        