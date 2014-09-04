<?php

/**
 * This is the model class for table "Task".
 *
 * The followings are the available columns in table 'Task':
 * @property integer $id
 * @property integer $campaignid
 * @property integer $assigneduserid
 * @property integer $assignedCompanyId
 * @property integer $siteid
 * @property string $dueDate
 * @property integer $taskDone
 * @property integer $problem
 * @property string $createdDate
 * @property string $modifiedDate
 * @property integer $status
 * @property integer $pop
 * @property integer $createdBy
 *
 * The followings are the available model relations:
 * @property PhotoProof[] $photoProofs
 * @property User $createdBy0
 * @property Campaign $campaign
 * @property Listing $site
 * @property User $assigneduser
 */
class BaseTask extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'Task';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('pop', 'required'),
			array('campaignid, assigneduserid, assignedCompanyId, siteid, taskDone, problem, status, pop, createdBy', 'numerical', 'integerOnly'=>true),
			array('dueDate, createdDate, modifiedDate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, campaignid, assigneduserid, assignedCompanyId, siteid, dueDate, taskDone, problem, createdDate, modifiedDate, status, pop, createdBy', 'safe', 'on'=>'search'),
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
			'photoProofs' => array(self::HAS_MANY, 'PhotoProof', 'taskid'),
			'createdBy0' => array(self::BELONGS_TO, 'User', 'createdBy'),
			'campaign' => array(self::BELONGS_TO, 'Campaign', 'campaignid'),
			'site' => array(self::BELONGS_TO, 'Listing', 'siteid'),
			'assigneduser' => array(self::BELONGS_TO, 'User', 'assigneduserid'),
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
			'assigneduserid' => 'Assigneduserid',
			'assignedCompanyId' => 'Assigned Company',
			'siteid' => 'Siteid',
			'dueDate' => 'Due Date',
			'taskDone' => 'Task Done',
			'problem' => 'Problem',
			'createdDate' => 'Created Date',
			'modifiedDate' => 'Modified Date',
			'status' => 'Status',
			'pop' => 'Pop',
			'createdBy' => 'Created By',
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
		$criteria->compare('assigneduserid',$this->assigneduserid);
		$criteria->compare('assignedCompanyId',$this->assignedCompanyId);
		$criteria->compare('siteid',$this->siteid);
		$criteria->compare('dueDate',$this->dueDate,true);
		$criteria->compare('taskDone',$this->taskDone);
		$criteria->compare('problem',$this->problem);
		$criteria->compare('createdDate',$this->createdDate,true);
		$criteria->compare('modifiedDate',$this->modifiedDate,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('pop',$this->pop);
		$criteria->compare('createdBy',$this->createdBy);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BaseTask the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
