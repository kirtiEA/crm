<?php

Yii::import('application.models.base.BaseUserProduct');

class UserProduct extends BaseUserProduct {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function getUserProductType($userId) {
        $result = self::model()->findByAttributes(array('userid' => $userId), array('select' => 'productid'));
        // default return 1 
        if ($result)
            return $result->productid;
        else
            return 1;
    }

}
