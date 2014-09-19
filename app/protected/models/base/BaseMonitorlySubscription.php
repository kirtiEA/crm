<?php

/**
 * This is the model class for table "monitorlysubscription".
 *
 * The followings are the available columns in table 'monitorlysubscription':
 * @property integer $id
 * @property integer $nid
 * @property string $companyname
 * @property string $email
 * @property string $phonenumber
 * @property string $createddate
 */
class   BaseMonitorlySubscription extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'MonitorlySubscription';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id, nid, companyname, email, phonenumber, createddate', 'required'),
            array('id, nid', 'numerical', 'integerOnly' => true),
            array('companyname, email', 'length', 'max' => 50),
            array('phonenumber', 'length', 'max' => 20),
            //array('companyname,email,phonenumber', // allows to a create a new user
              //  'required', 'on' => 'subscribe', 'message' => 'All fileds are Required'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, nid, companyname, email, phonenumber, createddate', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'nid' => 'Nid',
            'companyname' => 'Companyname',
            'email' => 'Email',
            'phonenumber' => 'Phonenumber',
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
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('nid', $this->nid);
        $criteria->compare('companyname', $this->companyname, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('phonenumber', $this->phonenumber, true);
        $criteria->compare('createddate', $this->createddate, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return BaseMonitorlySubscription the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
