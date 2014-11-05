<?php

/**
 * This is the model class for table "UserCompany".
 *
 * The followings are the available columns in table 'UserCompany':
 * @property integer $id
 * @property integer $userid
 * @property string $name
 * @property string $alias
 * @property string $description
 * @property string $logo
 * @property string $websiteurl
 * @property string $phonenumber
 * @property integer $countryid
 * @property integer $stateid
 * @property integer $cityid
 * @property integer $area_status
 * @property string $address1
 * @property string $address2
 * @property string $postalcode
 * @property string $facebookprofile
 * @property string $twitterhandle
 * @property string $linkedinprofile
 * @property string $googleplusprofile
 * @property string $backup_country
 * @property string $backup_city
 * @property integer $availability_auto_mail_trigger
 * @property integer $status
 */
class BaseUserCompany extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'UserCompany';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('userid, name, alias, websiteurl, phonenumber, countryid, stateid, cityid, address1, address2, postalcode, facebookprofile, twitterhandle, linkedinprofile, googleplusprofile, availability_auto_mail_trigger', 'required'),
			array('userid, countryid, stateid, cityid, area_status, availability_auto_mail_trigger, status', 'numerical', 'integerOnly'=>true),
			array('name, websiteurl, address1, address2', 'length', 'max'=>50),
			array('alias', 'length', 'max'=>55),
			array('logo, facebookprofile, linkedinprofile, googleplusprofile', 'length', 'max'=>100),
			array('phonenumber, postalcode, twitterhandle', 'length', 'max'=>20),
			array('backup_country, backup_city', 'length', 'max'=>255),
			array('description', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, userid, name, alias, description, logo, websiteurl, phonenumber, countryid, stateid, cityid, area_status, address1, address2, postalcode, facebookprofile, twitterhandle, linkedinprofile, googleplusprofile, backup_country, backup_city, availability_auto_mail_trigger, status', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'alias' => 'Alias',
			'description' => 'Description',
			'logo' => 'Logo',
			'websiteurl' => 'Websiteurl',
			'phonenumber' => 'Phonenumber',
			'countryid' => 'Countryid',
			'stateid' => 'Stateid',
			'cityid' => 'Cityid',
			'area_status' => '0-default,1-updated,2-error',
			'address1' => 'Address1',
			'address2' => 'Address2',
			'postalcode' => 'Postalcode',
			'facebookprofile' => 'Facebookprofile',
			'twitterhandle' => 'Twitterhandle',
			'linkedinprofile' => 'Linkedinprofile',
			'googleplusprofile' => 'Googleplusprofile',
			'backup_country' => 'Backup Country',
			'backup_city' => 'Backup City',
			'availability_auto_mail_trigger' => 'Availability Auto Mail Trigger',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('alias',$this->alias,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('logo',$this->logo,true);
		$criteria->compare('websiteurl',$this->websiteurl,true);
		$criteria->compare('phonenumber',$this->phonenumber,true);
		$criteria->compare('countryid',$this->countryid);
		$criteria->compare('stateid',$this->stateid);
		$criteria->compare('cityid',$this->cityid);
		$criteria->compare('area_status',$this->area_status);
		$criteria->compare('address1',$this->address1,true);
		$criteria->compare('address2',$this->address2,true);
		$criteria->compare('postalcode',$this->postalcode,true);
		$criteria->compare('facebookprofile',$this->facebookprofile,true);
		$criteria->compare('twitterhandle',$this->twitterhandle,true);
		$criteria->compare('linkedinprofile',$this->linkedinprofile,true);
		$criteria->compare('googleplusprofile',$this->googleplusprofile,true);
		$criteria->compare('backup_country',$this->backup_country,true);
		$criteria->compare('backup_city',$this->backup_city,true);
		$criteria->compare('availability_auto_mail_trigger',$this->availability_auto_mail_trigger);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BaseUserCompany the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
