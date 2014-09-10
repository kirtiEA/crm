<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
Yii::import('application.models.base.BaseMonitorlyNotification');

class MonitorlyNotification extends BaseMonitorlyNotification
{
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    
}
