<?php

/**
 * This is the model class for table "companyleads".
 *
 * The followings are the available columns in table 'companyleads':
 * @property integer $id
 * @property integer $contactid
 * @property integer $brandid
 * @property string $countries
 * @property string $cities
 * @property string $tags
 * @property string $category
 * @property integer $assignedto
 * @property integer $status
 * @property string $description
 * @property integer $companyid
 * @property string $createddate
 * @property string $campaignstartdate
 * @property string $campaignenddate
 * @property string $lastupdated
 *
 * The followings are the available model relations:
 * @property CompanyBrands $brand
 * @property CompanyContacts $contact
 * @property CompanyStatuses $status0
 */
class BaseCompanyleads extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'companyleads';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('contactid, brandid', 'required'),
			array('contactid, brandid, assignedto, status, companyid', 'numerical', 'integerOnly'=>true),
			array('countries, cities, tags', 'length', 'max'=>545),
			array('category', 'length', 'max'=>245),
			array('description, createddate, campaignstartdate, campaignenddate, lastupdated', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, contactid, brandid, countries, cities, tags, category, assignedto, status, description, companyid, createddate, campaignstartdate, campaignenddate, lastupdated', 'safe', 'on'=>'search'),
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
			'contact' => array(self::BELONGS_TO, 'CompanyContacts', 'contactid'),
			'status0' => array(self::BELONGS_TO, 'CompanyStatuses', 'status'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'contactid' => 'Contactid',
			'brandid' => 'Brandid',
			'countries' => 'Countries',
			'cities' => 'Cities',
			'tags' => 'Tags',
			'category' => 'Category',
			'assignedto' => 'Assignedto',
			'status' => 'Status',
			'description' => 'Description',
			'companyid' => 'Companyid',
			'createddate' => 'Createddate',
			'campaignstartdate' => 'Campaignstartdate',
			'campaignenddate' => 'Campaignenddate',
			'lastupdated' => 'Lastupdated',
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
		$criteria->compare('contactid',$this->contactid);
		$criteria->compare('brandid',$this->brandid);
		$criteria->compare('countries',$this->countries,true);
		$criteria->compare('cities',$this->cities,true);
		$criteria->compare('tags',$this->tags,true);
		$criteria->compare('category',$this->category,true);
		$criteria->compare('assignedto',$this->assignedto);
		$criteria->compare('status',$this->status);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('companyid',$this->companyid);
		$criteria->compare('createddate',$this->createddate,true);
		$criteria->compare('campaignstartdate',$this->campaignstartdate,true);
		$criteria->compare('campaignenddate',$this->campaignenddate,true);
		$criteria->compare('lastupdated',$this->lastupdated,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BaseCompanyleads the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
