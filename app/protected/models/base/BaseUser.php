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
 *
 * The followings are the available model relations:
 * @property Email[] $emails
 * @property Email[] $emails1
 * @property FavouriteListing[] $favouriteListings
 * @property Link[] $links
 * @property Listing[] $listings
 * @property Listing[] $listings1
 * @property Permission[] $permissions
 * @property UserAudienceTag[] $userAudienceTags
 * @property UserCompany[] $userCompanies
 * @property UserRole[] $userRoles
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
			array('fname, lname, email, password, phonenumber, active, datecreated', 'required'),
			array('active, status, subscribe', 'numerical', 'integerOnly'=>true),
			array('fname, lname, username, phonenumber', 'length', 'max'=>20),
			array('email', 'length', 'max'=>50),
			array('password', 'length', 'max'=>60),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, fname, lname, email, username, password, phonenumber, active, status, subscribe, lastlogin, datecreated, datemodified', 'safe', 'on'=>'search'),
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
			'emails' => array(self::HAS_MANY, 'Email', 'byuserid'),
			'emails1' => array(self::HAS_MANY, 'Email', 'foruserid'),
			'favouriteListings' => array(self::HAS_MANY, 'FavouriteListing', 'userid'),
			'links' => array(self::HAS_MANY, 'Link', 'userid'),
			'listings' => array(self::HAS_MANY, 'Listing', 'byuserid'),
			'listings1' => array(self::HAS_MANY, 'Listing', 'foruserid'),
			'permissions' => array(self::HAS_MANY, 'Permission', 'userid'),
			'userAudienceTags' => array(self::HAS_MANY, 'UserAudienceTag', 'userid'),
			'userCompanies' => array(self::HAS_MANY, 'UserCompany', 'userid'),
			'userRoles' => array(self::HAS_MANY, 'UserRole', 'userid'),
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
