<?php

/**
 * This is the model class for table "requestedcompanyvendor".
 *
 * The followings are the available columns in table 'requestedcompanyvendor':
 * @property integer $id
 * @property integer $companyid
 * @property integer $createdby
 * @property string $createddate
 * @property integer $vendorcompanyid
 * @property integer $acceptedby
 * @property string $accepteddate
 *
 * The followings are the available model relations:
 * @property User $acceptedby0
 * @property Usercompany $company
 * @property Usercompany $vendorcompany
 * @property User $createdby0
 */
class BaseRequestedcompanyvendor extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'requestedcompanyvendor';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('companyid, createdby, vendorcompanyid, acceptedby', 'numerical', 'integerOnly'=>true),
			array('createddate, accepteddate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, companyid, createdby, createddate, vendorcompanyid, acceptedby, accepteddate', 'safe', 'on'=>'search'),
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
			'acceptedby0' => array(self::BELONGS_TO, 'User', 'acceptedby'),
			'company' => array(self::BELONGS_TO, 'Usercompany', 'companyid'),
			'vendorcompany' => array(self::BELONGS_TO, 'Usercompany', 'vendorcompanyid'),
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
			'companyid' => 'Companyid',
			'createdby' => 'Createdby',
			'createddate' => 'Createddate',
			'vendorcompanyid' => 'Vendorcompanyid',
			'acceptedby' => 'Acceptedby',
			'accepteddate' => 'Accepteddate',
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
		$criteria->compare('companyid',$this->companyid);
		$criteria->compare('createdby',$this->createdby);
		$criteria->compare('createddate',$this->createddate,true);
		$criteria->compare('vendorcompanyid',$this->vendorcompanyid);
		$criteria->compare('acceptedby',$this->acceptedby);
		$criteria->compare('accepteddate',$this->accepteddate,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Requestedcompanyvendor the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
