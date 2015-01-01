<?php

/**
 * This is the model class for table "CompanyContacts".
 *
 * The followings are the available columns in table 'CompanyContacts':
 * @property integer $id
 * @property string $name
 * @property string $createddate
 * @property integer $createdby
 * @property integer $status
 * @property integer $companyid
 * @property string $fname
 * @property string $lname
 * @property string $phone1
 * @property string $phone2
 * @property string $mobile
 * @property string $email1
 * @property string $email2
 * @property string $fax
 * @property string $address
 * @property string $website
 * @property integer $brandid
 *
 * The followings are the available model relations:
 * @property CompanyBrands $brand
 * @property UserCompany $company
 * @property CompanyLeads[] $companyLeads
 * @property ContactBrandsMapping[] $contactBrandsMappings
 */
class BaseCompanyContacts extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'CompanyContacts';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('createdby, status, companyid, brandid', 'numerical', 'integerOnly'=>true),
			array('name, fname, lname', 'length', 'max'=>245),
			array('phone1, phone2, mobile, fax', 'length', 'max'=>45),
			array('email1, email2, website', 'length', 'max'=>145),
			array('createddate, address', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, createddate, createdby, status, companyid, fname, lname, phone1, phone2, mobile, email1, email2, fax, address, website, brandid', 'safe', 'on'=>'search'),
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
			'brand' => array(self::BELONGS_TO, 'CompanyBrands', 'brandid'),
			'company' => array(self::BELONGS_TO, 'UserCompany', 'companyid'),
			'companyLeads' => array(self::HAS_MANY, 'CompanyLeads', 'contactid'),
			'contactBrandsMappings' => array(self::HAS_MANY, 'ContactBrandsMapping', 'contactid'),
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
			'createddate' => 'Createddate',
			'createdby' => 'Createdby',
			'status' => 'Status',
			'companyid' => 'Companyid',
			'fname' => 'Fname',
			'lname' => 'Lname',
			'phone1' => 'Phone1',
			'phone2' => 'Phone2',
			'mobile' => 'Mobile',
			'email1' => 'Email1',
			'email2' => 'Email2',
			'fax' => 'Fax',
			'address' => 'Address',
			'website' => 'Website',
			'brandid' => 'Brandid',
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
		$criteria->compare('createddate',$this->createddate,true);
		$criteria->compare('createdby',$this->createdby);
		$criteria->compare('status',$this->status);
		$criteria->compare('companyid',$this->companyid);
		$criteria->compare('fname',$this->fname,true);
		$criteria->compare('lname',$this->lname,true);
		$criteria->compare('phone1',$this->phone1,true);
		$criteria->compare('phone2',$this->phone2,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('email1',$this->email1,true);
		$criteria->compare('email2',$this->email2,true);
		$criteria->compare('fax',$this->fax,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('website',$this->website,true);
		$criteria->compare('brandid',$this->brandid);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BaseCompanyContacts the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
