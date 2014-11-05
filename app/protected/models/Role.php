<?php

Yii::import('application.models.base.BaseRole');

class Role extends BaseRole
{	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public static function getRole()
        {
            $model= Role::model()->findAll();
            return $model;
        }
}
