<?php

/**
 * This is the model class for table "monitorlynotification".
 *
 * The followings are the available columns in table 'monitorlynotification':
 * @property integer $id
 * @property integer $typeid
 * @property string $createddate
 * @property integer $createdby
 * @property integer $emailtypeid
 * @property string $miscellaneous
 * @property string $lastViewedDate
 * @property integer $companyid
 * @property integer $notifiedcompanyid
 *
 * The followings are the available model relations:
 * @property User $createdby0
 */
class BaseMonitorlyNotification extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'MonitorlyNotification';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('createddate, createdby', 'required'),
			array('typeid, createdby, emailtypeid, companyid, notifiedcompanyid', 'numerical', 'integerOnly'=>true),
			array('miscellaneous', 'length', 'max'=>255),
			array('lastViewedDate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, typeid, createddate, createdby, emailtypeid, miscellaneous, lastViewedDate, companyid, notifiedcompanyid', 'safe', 'on'=>'search'),
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
			'createdby0' => array(self::BELONGS_TO, 'User', 'createdby'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'typeid' => '1-invite vendor, 2-remind vendor',
			'createddate' => 'Createddate',
			'createdby' => 'Createdby',
			'emailtypeid' => 'Emailtypeid',
			'miscellaneous' => 'Miscellaneous',
			'lastViewedDate' => 'Last Viewed Date',
			'companyid' => 'Companyid',
			'notifiedcompanyid' => 'Notifiedcompanyid',
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
		$criteria->compare('typeid',$this->typeid);
		$criteria->compare('createddate',$this->createddate,true);
		$criteria->compare('createdby',$this->createdby);
		$criteria->compare('emailtypeid',$this->emailtypeid);
		$criteria->compare('miscellaneous',$this->miscellaneous,true);
		$criteria->compare('lastViewedDate',$this->lastViewedDate,true);
		$criteria->compare('companyid',$this->companyid);
		$criteria->compare('notifiedcompanyid',$this->notifiedcompanyid);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BaseMonitorlyNotification the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
