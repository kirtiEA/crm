<?php

/**
 * This is the model class for table "PhotoProof".
 *
 * The followings are the available columns in table 'PhotoProof':
 * @property integer $id
 * @property integer $taskid
 * @property string $imageName
 * @property string $clickedDateTime
 * @property double $clickedLat
 * @property double $clickedLong
 * @property integer $siteProblemId
 * @property integer $clickedby
 * @property string $createdDate
 * @property string $modifiedDate
 *
 * The followings are the available model relations:
 * @property Taskproblemdetails $siteProblem
 * @property Task $task
 * @property User $clickedby0
 */
class PhotoProof extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'PhotoProof';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('taskid, siteProblemId, clickedby', 'numerical', 'integerOnly'=>true),
			array('clickedLat, clickedLong', 'numerical'),
			array('imageName', 'length', 'max'=>45),
			array('clickedDateTime, createdDate, modifiedDate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, taskid, imageName, clickedDateTime, clickedLat, clickedLong, siteProblemId, clickedby, createdDate, modifiedDate', 'safe', 'on'=>'search'),
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
			'siteProblem' => array(self::BELONGS_TO, 'Taskproblemdetails', 'siteProblemId'),
			'task' => array(self::BELONGS_TO, 'Task', 'taskid'),
			'clickedby0' => array(self::BELONGS_TO, 'User', 'clickedby'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'taskid' => 'Taskid',
			'imageName' => 'Image Name',
			'clickedDateTime' => 'Clicked Date Time',
			'clickedLat' => 'Clicked Lat',
			'clickedLong' => 'Clicked Long',
			'siteProblemId' => 'Site Problem',
			'clickedby' => 'Clickedby',
			'createdDate' => 'Created Date',
			'modifiedDate' => 'Modified Date',
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
		$criteria->compare('taskid',$this->taskid);
		$criteria->compare('imageName',$this->imageName,true);
		$criteria->compare('clickedDateTime',$this->clickedDateTime,true);
		$criteria->compare('clickedLat',$this->clickedLat);
		$criteria->compare('clickedLong',$this->clickedLong);
		$criteria->compare('siteProblemId',$this->siteProblemId);
		$criteria->compare('clickedby',$this->clickedby);
		$criteria->compare('createdDate',$this->createdDate,true);
		$criteria->compare('modifiedDate',$this->modifiedDate,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PhotoProof the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
