<?php

/**
 * This is the model class for table "userrole".
 *
 * The followings are the available columns in table 'userrole':
 * @property integer $id
 * @property integer $userid
 * @property integer $roleid
 *
 * The followings are the available model relations:
 * @property Role $role
 * @property User $user
 */
class Userrole extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'UserRole';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('userid, roleid', 'required'),
			array('userid, roleid', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, userid, roleid', 'safe', 'on'=>'search'),
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
			'role' => array(self::BELONGS_TO, 'Role', 'roleid'),
			'user' => array(self::BELONGS_TO, 'User', 'userid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'userid' => 'Userid',
			'roleid' => 'Roleid',
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
		$criteria->compare('userid',$this->userid);
		$criteria->compare('roleid',$this->roleid);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Userrole the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
       
        /*
         * update the Userrole table in database according to the
         * userId on which updation operation is being performed.
         */
        public static function updateRoles($id,$role)
        {
            //do something to update user roles table
            $cmd = Yii::app()->db->createCommand();

            $cmd = $cmd->update('userrole', array(
                       'userid'=> $id, 'roleid'=>$role),
                       'userid=:uid',
                        array(':uid'=>$id));

        }
     
        /*
         * insert new value in the Userrole table in database
         * for the new user being created
         */
        public function insertRoles($id,$role) {
            $cmd = Yii::app()->db->createCommand();
            $cmd = $cmd->insert('userrole', array(
                        'userid'=>$id,
                        'roleid'=>$role 
            ));
        }
}
