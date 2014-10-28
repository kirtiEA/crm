<?php

/**
 * This is the model class for table "MonitorlyPushNotificationUserDeviceMapping".
 *
 * The followings are the available columns in table 'MonitorlyPushNotificationUserDeviceMapping':
 * @property integer $id
 * @property integer $userid
 * @property string $deviceid
 * @property string $logindate
 * @property string $logoutdate
 * @property integer $status
 */
class BaseMonitorlyPushNotificationUserDeviceMapping extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'MonitorlyPushNotificationUserDeviceMapping';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('userid, deviceid, logindate, status', 'required'),
			array('userid, status', 'numerical', 'integerOnly'=>true),
			array('deviceid', 'length', 'max'=>1000),
			array('logoutdate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, userid, deviceid, logindate, logoutdate, status', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'userid' => 'Userid',
			'deviceid' => 'Deviceid',
			'logindate' => 'Logindate',
			'logoutdate' => 'Logoutdate',
			'status' => 'Status',
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
		$criteria->compare('userid',$this->userid);
		$criteria->compare('deviceid',$this->deviceid,true);
		$criteria->compare('logindate',$this->logindate,true);
		$criteria->compare('logoutdate',$this->logoutdate,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BaseMonitorlyPushNotificationUserDeviceMapping the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
