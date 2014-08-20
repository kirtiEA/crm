<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
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
 *
 * The followings are the available model relations:
 * @property Campaign[] $campaigns
 * @property Emaileventlog[] $emaileventlogs
 * @property Favouritelisting[] $favouritelistings
 * @property Link[] $links
 * @property Listing[] $listings
 * @property Listing[] $listings1
 * @property Listingdraft[] $listingdrafts
 * @property Listingdraft[] $listingdrafts1
 * @property Monitorlylisting[] $monitorlylistings
 * @property Photoproof[] $photoproofs
 * @property Plan[] $plans
 * @property Rfp[] $rfps
 * @property Rfp[] $rfps1
 * @property Sharecampaignlog[] $sharecampaignlogs
 * @property Task[] $tasks
 * @property Useraudiencetag[] $useraudiencetags
 * @property Usercompany[] $usercompanies
 * @property Usercontacts[] $usercontacts
 * @property Userproduct[] $userproducts
 * @property Userrole[] $userroles
 * @property Userzoneassignment[] $userzoneassignments
 */
class User extends CActiveRecord
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
			array('active, status, subscribe', 'numerical', 'integerOnly'=>true),
			array('fname, lname, username, phonenumber', 'length', 'max'=>20),
			array('email', 'length', 'max'=>50),
			array('password', 'length', 'max'=>60),
			array('lastlogin, dateactivated', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, fname, lname, email, username, password, phonenumber, active, status, subscribe, lastlogin, datecreated, datemodified, dateactivated', 'safe', 'on'=>'search'),
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
			'emaileventlogs' => array(self::HAS_MANY, 'Emaileventlog', 'userid'),
			'favouritelistings' => array(self::HAS_MANY, 'Favouritelisting', 'userid'),
			'links' => array(self::HAS_MANY, 'Link', 'userid'),
			'listings' => array(self::HAS_MANY, 'Listing', 'byuserid'),
			'listings1' => array(self::HAS_MANY, 'Listing', 'foruserid'),
			'listingdrafts' => array(self::HAS_MANY, 'Listingdraft', 'byuserid'),
			'listingdrafts1' => array(self::HAS_MANY, 'Listingdraft', 'foruserid'),
			'monitorlylistings' => array(self::HAS_MANY, 'Monitorlylisting', 'addedBy'),
			'photoproofs' => array(self::HAS_MANY, 'Photoproof', 'clickedby'),
			'plans' => array(self::HAS_MANY, 'Plan', 'userid'),
			'rfps' => array(self::HAS_MANY, 'Rfp', 'byuserid'),
			'rfps1' => array(self::HAS_MANY, 'Rfp', 'foruserid'),
			'sharecampaignlogs' => array(self::HAS_MANY, 'Sharecampaignlog', 'userid'),
			'tasks' => array(self::HAS_MANY, 'Task', 'assigneduserid'),
			'useraudiencetags' => array(self::HAS_MANY, 'Useraudiencetag', 'userid'),
			'usercompanies' => array(self::HAS_MANY, 'Usercompany', 'userid'),
			'usercontacts' => array(self::HAS_MANY, 'Usercontacts', 'linkedUserId'),
			'userproducts' => array(self::HAS_MANY, 'Userproduct', 'userid'),
			'userroles' => array(self::HAS_MANY, 'Userrole', 'userid'),
			'userzoneassignments' => array(self::HAS_MANY, 'Userzoneassignment', 'userid'),
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
			'subscribe' => 'Subscribe',
			'lastlogin' => 'Lastlogin',
			'datecreated' => 'Datecreated',
			'datemodified' => 'Datemodified',
			'dateactivated' => 'Dateactivated',
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

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
