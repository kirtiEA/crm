<?php

/**
 * This is the model class for table "User".
 *
 * The followings are the available columns in table 'User':
 * @property integer $id
 * @property string $fname
 * @property string $lname
 * @property string $email
 * @property string $username
 * @property string $password
 * @property string $phonenumber
 * @property integer $active
 * @property integer $status
 * @property integer $subscribe
 * @property string $lastlogin
 * @property string $datecreated
 * @property string $datemodified
 * @property string $dateactivated
 * @property integer $companyid
 *
 * The followings are the available model relations:
 * @property Campaign[] $campaigns
 * @property EmailEventLog[] $emailEventLogs
 * @property FavouriteListing[] $favouriteListings
 * @property Link[] $links
 * @property Listing[] $listings
 * @property Listing[] $listings1
 * @property ListingDraft[] $listingDrafts
 * @property ListingDraft[] $listingDrafts1
 * @property MonitorlyListing[] $monitorlyListings
 * @property PhotoProof[] $photoProofs
 * @property Plan[] $plans
 * @property Rfp[] $rfps
 * @property Rfp[] $rfps1
 * @property ShareCampaignLog[] $shareCampaignLogs
 * @property Task[] $tasks
 * @property UserCompany $company
 * @property UserAudienceTag[] $userAudienceTags
 * @property UserCompany[] $userCompanies
 * @property UserContacts[] $userContacts
 * @property UserRole[] $userRoles
 * @property UserZoneAssignment[] $userZoneAssignments
 * @property Userproduct[] $userproducts
 */
class BaseUser extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'User';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('fname, lname, email, password, phonenumber, active, datecreated, datemodified', 'required'),
			array('active, status, subscribe, companyid', 'numerical', 'integerOnly'=>true),
			array('fname, lname, username, phonenumber', 'length', 'max'=>20),
			array('email', 'length', 'max'=>50),
			array('password', 'length', 'max'=>60),
			array('lastlogin, dateactivated', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, fname, lname, email, username, password, phonenumber, active, status, subscribe, lastlogin, datecreated, datemodified, dateactivated, companyid', 'safe', 'on'=>'search'),
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
			'campaigns' => array(self::HAS_MANY, 'Campaign', 'createdBy'),
			'emailEventLogs' => array(self::HAS_MANY, 'EmailEventLog', 'userid'),
			'favouriteListings' => array(self::HAS_MANY, 'FavouriteListing', 'userid'),
			'links' => array(self::HAS_MANY, 'Link', 'userid'),
			'listings' => array(self::HAS_MANY, 'Listing', 'byuserid'),
			'listings1' => array(self::HAS_MANY, 'Listing', 'foruserid'),
			'listingDrafts' => array(self::HAS_MANY, 'ListingDraft', 'byuserid'),
			'listingDrafts1' => array(self::HAS_MANY, 'ListingDraft', 'foruserid'),
			'monitorlyListings' => array(self::HAS_MANY, 'MonitorlyListing', 'addedBy'),
			'photoProofs' => array(self::HAS_MANY, 'PhotoProof', 'clickedby'),
			'plans' => array(self::HAS_MANY, 'Plan', 'userid'),
			'rfps' => array(self::HAS_MANY, 'Rfp', 'byuserid'),
			'rfps1' => array(self::HAS_MANY, 'Rfp', 'foruserid'),
			'shareCampaignLogs' => array(self::HAS_MANY, 'ShareCampaignLog', 'userid'),
			'tasks' => array(self::HAS_MANY, 'Task', 'assigneduserid'),
			'company' => array(self::BELONGS_TO, 'UserCompany', 'companyid'),
			'userAudienceTags' => array(self::HAS_MANY, 'UserAudienceTag', 'userid'),
			'userCompanies' => array(self::HAS_MANY, 'UserCompany', 'userid'),
			'userContacts' => array(self::HAS_MANY, 'UserContacts', 'linkedUserId'),
			'userRoles' => array(self::HAS_MANY, 'UserRole', 'userid'),
			'userZoneAssignments' => array(self::HAS_MANY, 'UserZoneAssignment', 'userid'),
			'userproducts' => array(self::HAS_MANY, 'Userproduct', 'userid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'fname' => 'Fname',
			'lname' => 'Lname',
			'email' => 'Email',
			'username' => 'Username',
			'password' => 'Password',
			'phonenumber' => 'Phonenumber',
			'active' => 'Active',
			'status' => 'Status',
			'subscribe' => 'email subscription; 1=>subscribed',
			'lastlogin' => 'Lastlogin',
			'datecreated' => 'Datecreated',
			'datemodified' => 'Datemodified',
			'dateactivated' => 'Dateactivated',
			'companyid' => 'Companyid',
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
		$criteria->compare('fname',$this->fname,true);
		$criteria->compare('lname',$this->lname,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('phonenumber',$this->phonenumber,true);
		$criteria->compare('active',$this->active);
		$criteria->compare('status',$this->status);
		$criteria->compare('subscribe',$this->subscribe);
		$criteria->compare('lastlogin',$this->lastlogin,true);
		$criteria->compare('datecreated',$this->datecreated,true);
		$criteria->compare('datemodified',$this->datemodified,true);
		$criteria->compare('dateactivated',$this->dateactivated,true);
		$criteria->compare('companyid',$this->companyid);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BaseUser the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
