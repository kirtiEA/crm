<?php

/**
 * This is the model class for table "Campaign".
 *
 * The followings are the available columns in table 'Campaign':
 * @property integer $id
 * @property string $name
 * @property string $startDate
 * @property string $endDate
 * @property string $createdDate
 * @property string $modifiedDate
 * @property integer $createdBy
 * @property integer $companyid
 * @property integer $type
 * @property string $campaignDates
 *
 * The followings are the available model relations:
 * @property User $createdBy0
 * @property MonitorlyCampaignShare[] $monitorlyCampaignShares
 */
class BaseCampaign extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'Campaign';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('createdBy, companyid, type', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>45),
			array('campaignDates', 'length', 'max'=>500),
			array('startDate, endDate, createdDate, modifiedDate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, startDate, endDate, createdDate, modifiedDate, createdBy, companyid, type, campaignDates', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'createdBy0' => array(self::BELONGS_TO, 'User', 'createdBy'),
			'monitorlyCampaignShares' => array(self::HAS_MANY, 'MonitorlyCampaignShare', 'campaignid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'startDate' => 'Start Date',
			'endDate' => 'End Date',
			'createdDate' => 'Created Date',
			'modifiedDate' => 'Modified Date',
			'createdBy' => 'Created By',
			'companyid' => 'Companyid',
			'type' => '1=>POP only; 2=> Daily Monitorling only; 3=> POP and Daily Monitoring',
			'campaignDates' => 'Campaign Dates',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('startDate',$this->startDate,true);
		$criteria->compare('endDate',$this->endDate,true);
		$criteria->compare('createdDate',$this->createdDate,true);
		$criteria->compare('modifiedDate',$this->modifiedDate,true);
		$criteria->compare('createdBy',$this->createdBy);
		$criteria->compare('companyid',$this->companyid);
		$criteria->compare('type',$this->type);
		$criteria->compare('campaignDates',$this->campaignDates,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BaseCampaign the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
