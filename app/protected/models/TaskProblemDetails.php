<?php

/**
 * This is the model class for table "TaskProblemDetails".
 *
 * The followings are the available columns in table 'TaskProblemDetails':
 * @property integer $id
 * @property string $installations
 * @property string $lighting
 * @property string $obstruction
 * @property string $comments
 * @property string $createdDate
 * @property string $modifiedDate
 *
 * The followings are the available model relations:
 * @property Photoproof[] $photoproofs
 */
class TaskProblemDetails extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'taskproblemdetails';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('installations, lighting', 'length', 'max'=>255),
			array('obstruction', 'length', 'max'=>245),
			array('comments', 'length', 'max'=>225),
			array('createdDate, modifiedDate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, installations, lighting, obstruction, comments, createdDate, modifiedDate', 'safe', 'on'=>'search'),
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
			'photoproofs' => array(self::HAS_MANY, 'Photoproof', 'siteProblemId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'installations' => 'Installations',
			'lighting' => 'Lighting',
			'obstruction' => 'Obstruction',
			'comments' => 'Comments',
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
		$criteria->compare('installations',$this->installations,true);
		$criteria->compare('lighting',$this->lighting,true);
		$criteria->compare('obstruction',$this->obstruction,true);
		$criteria->compare('comments',$this->comments,true);
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
	 * @return Taskproblemdetails the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
