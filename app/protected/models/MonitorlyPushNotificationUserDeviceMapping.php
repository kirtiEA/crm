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
        $sql = 'select id from MonitorlyPushNotificationUserDeviceMapping where userid is null and deviceid = \'' . $did .'\' order by id desc limit 1';
        $combinations1 = Yii::app()->db->createCommand($sql)->queryRow();
        if(!$combinations1) {
            $sql = 'select id, userid from MonitorlyPushNotificationUserDeviceMapping where userid = ' .$uid . ' and deviceid = \'' . $did .'\' order by id desc limit 1';
            $combinations = Yii::app()->db->createCommand($sql)->queryRow();
            if ($combinations) {
                $update ;
                if (empty($combinations['userid'])) {
                    // update the logout date to null
                    $update = 'Update MonitorlyPushNotificationUserDeviceMapping set status=1 , userid ='. $uid .', logoutdate = NULL, where id =' . $combinations['id'];
                } else {
                    // update the logout date to null
                    $update = 'Update MonitorlyPushNotificationUserDeviceMapping set status=1 ,  logoutdate = NULL, where id =' . $combinations['id'];
                }
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
        }else {
            $update = 'Update MonitorlyPushNotificationUserDeviceMapping set status=1 , userid ='. $uid .', logoutdate = NULL where id =' . $combinations1['id'];
            $flag = Yii::app()->db->createCommand($update)->execute();
        }
        //check if uid - did exists ? 
        
        
        //now update all other device id  and logot them out
       $update2 = 'Update MonitorlyPushNotificationUserDeviceMapping set status = 0 ,  logoutdate = \'' . date("Y-m-d H:i:s") . '\' where userid = ' . $uid . ' and  deviceid != \'' . $did . '\' and logoutdate is NULL';
       $test = Yii::app()->db->createCommand($update2)->execute();

        return 1;
     }
     
     public static function appinstall($did, $imei =null) {
         //check for deviceid
         $sql = 'select id from MonitorlyPushNotificationUserDeviceMapping where deviceid = \'' . $did .'\' order by id desc limit 1';
         $combinations = Yii::app()->db->createCommand($sql)->queryRow();
         if (!$combinations) {
            $model = new MonitorlyPushNotificationUserDeviceMapping();
            $model->deviceid = $did;
            if ($imei) {
                $model->imei = $imei;
            }
            $model->logindate = date("Y-m-d H:i:s");
            $model->status = 1;
            $model->save();
         }
     }
}        

