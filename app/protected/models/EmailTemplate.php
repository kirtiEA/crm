<?php

Yii::import('application.models.base.BaseEmailTemplate');

class EmailTemplate extends BaseEmailTemplate
{	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
    /**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('subject, content, title, datecreated, ', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('subject, title, uservariables', 'length', 'max'=>100),
			array('slug', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, subject, content, title, status, slug, uservariables, datecreated, datemodified', 'safe', 'on'=>'search'),
		);
	}
        
        public static function getMailContentBySlug($slug){
            $criteria = new CDbCriteria();
            $criteria->select = 'id, subject, content, title, status, slug, uservariables';
            $criteria->condition = "slug = :slug  ";
            $criteria->params = array(':slug' => $slug);
            $result = self::model()->find($criteria);
            return $result;
        }
}