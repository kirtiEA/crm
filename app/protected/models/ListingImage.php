<?php

/**
 * This is the model class for table "ListingImage".
 *
 * The followings are the available columns in table 'ListingImage':
 * @property integer $id
 * @property integer $listingid
 * @property integer $status
 * @property string $filename
 * @property string $filename_old
 * @property string $caption
 * @property integer $new_status
 *
 * The followings are the available model relations:
 * @property Listing $listing
 */
class ListingImage extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ListingImage';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('listingid, filename, filename_old', 'required'),
			array('listingid, status, new_status', 'numerical', 'integerOnly'=>true),
			array('filename', 'length', 'max'=>50),
			array('caption', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, listingid, status, filename, filename_old, caption, new_status', 'safe', 'on'=>'search'),
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
			'listing' => array(self::BELONGS_TO, 'Listing', 'listingid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'listingid' => 'Listingid',
			'status' => 'Status',
			'filename' => 'Filename',
			'filename_old' => 'Filename Old',
			'caption' => 'Caption',
			'new_status' => 'New Status',
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
		$criteria->compare('listingid',$this->listingid);
		$criteria->compare('status',$this->status);
		$criteria->compare('filename',$this->filename,true);
		$criteria->compare('filename_old',$this->filename_old,true);
		$criteria->compare('caption',$this->caption,true);
		$criteria->compare('new_status',$this->new_status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ListingImage the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
