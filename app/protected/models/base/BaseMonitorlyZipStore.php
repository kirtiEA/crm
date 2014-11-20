<?php

/**
 * This is the model class for table "MonitorlyZipStore".
 *
 * The followings are the available columns in table 'MonitorlyZipStore':
 * @property integer $id
 * @property integer $campaignid
 * @property string $filename
 * @property integer $recreateflag
 * @property string $createddate
 */
class BaseMonitorlyZipStore extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'MonitorlyZipStore';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('campaignid, filename, recreateflag, createddate', 'required'),
			array('campaignid, recreateflag', 'numerical', 'integerOnly'=>true),
			array('filename', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, campaignid, filename, recreateflag, createddate', 'safe', 'on'=>'search'),
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
			'campaignid' => 'Campaignid',
			'filename' => 'Filename',
			'recreateflag' => 'Recreateflag',
			'createddate' => 'Createddate',
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
		$criteria->compare('campaignid',$this->campaignid);
		$criteria->compare('filename',$this->filename,true);
		$criteria->compare('recreateflag',$this->recreateflag);
		$criteria->compare('createddate',$this->createddate,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BaseMonitorlyZipStore the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
