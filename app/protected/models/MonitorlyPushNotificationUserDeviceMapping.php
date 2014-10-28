<?php

Yii::import('application.models.base.BaseMonitorlyPushNotificationUserDeviceMapping');
class MonitorlyPushNotificationUserDeviceMapping extends BaseMonitorlyPushNotificationUserDeviceMapping {
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public static function findUniqueDeviceIds() {
        $sql = 'Select distinct(deviceid) from MonitorlyPushNotificationUserDeviceMapping';
        return Yii::app()->db->createCommand($sql)->queryAll();
    }
    
    public static function updateUniqueDeviceIdForUser($uid, $did) {
        //check if uid - did exists ? 
                                                         $sql = 'select id from MonitorlyPushNotificationUserDeviceMapping where userid = ' .$uid . ' and deviceid = \'' . $did .'\' order by id desc limit 1';
        $combinations = Yii::app()->db->createCommand($sql)->queryRow();
        if ($combinations) {
            // update the logout date to null
            $update = 'Update MonitorlyPushNotificationUserDeviceMapping set status=1 ,  logoutdate = NULL where id =' . $combinations['id'];
            $flag = Yii::app()->db->createCommand($update)->execute();
        } else {
            $model = new MonitorlyPushNotificationUserDeviceMapping();
            $model->userid = $uid;
            $model->deviceid = $did;
            $model->logindate = date("Y-m-d H:i:s");
            $model->status = 1;
            $model->save();
            //print_r($model);
        }
        
        //now update all other device id  and logot them out
       $update2 = 'Update MonitorlyPushNotificationUserDeviceMapping set status = 0 ,  logoutdate = \'' . date("Y-m-d H:i:s") . '\' where userid = ' . $uid . ' and  deviceid != \'' . $did . '\' and logoutdate is NULL';
       $test = Yii::app()->db->createCommand($update2)->execute();

        return 1;
     }
}        

