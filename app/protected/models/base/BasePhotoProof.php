<?php

/**
 * This is the model class for table "photoproof".
 *
 * The followings are the available columns in table 'photoproof':
 * @property integer $id
 * @property integer $taskid
 * @property string $imageName
 * @property string $clickedDateTime
 * @property double $clickedLat
 * @property double $clickedLng
 * @property integer $direction
 * @property string $installation
 * @property string $lighting
 * @property string $obstruction
 * @property string $comments
 * @property integer $clickedBy
 * @property string $createdDate
 * @property string $modifiedDate
 *
 * The followings are the available model relations:
 * @property Task $task
 * @property User $clickedby
 */
class BasePhotoProof extends CActiveRecord
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
			array('taskid, direction, clickedBy', 'numerical', 'integerOnly'=>true),
			array('clickedLat, clickedLng', 'numerical'),
			array('imageName', 'length', 'max'=>45),
			array('installation, lighting, obstruction, comments', 'length', 'max'=>255),
			array('clickedDateTime, createdDate, modifiedDate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, taskid, imageName, clickedDateTime, clickedLat, clickedLng, direction, installation, lighting, obstruction, comments, clickedBy, createdDate, modifiedDate', 'safe', 'on'=>'search'),
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
			'task' => array(self::BELONGS_TO, 'Task', 'taskid'),
			'clickedby' => array(self::BELONGS_TO, 'User', 'clickedby'),
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
			'clickedLng' => 'Clicked Lng',
                        'direction' => 'Camera Facing Direction',
			'installation' => 'Installation',
			'lighting' => 'Lighting',
			'obstruction' => 'Obstruction',
			'comments' => 'Comments',
			'clickedBy' => 'Clicked By',
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
		$criteria->compare('clickedLng',$this->clickedLng);
                $criteria->compare('direction',$this->direction);
		$criteria->compare('installation',$this->installation,true);
		$criteria->compare('lighting',$this->lighting,true);
		$criteria->compare('obstruction',$this->obstruction,true);
		$criteria->compare('comments',$this->comments,true);
		$criteria->compare('clickedBy',$this->clickedBy);
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
