<?php

/**
 * This is the model class for table "area".
 *
 * The followings are the available columns in table 'area':
 * @property integer $id
 * @property string $name
 * @property string $alias
 * @property string $type
 * @property string $short_code
 * @property integer $parentid
 *
 * The followings are the available model relations:
 * @property Area $parent
 * @property Area[] $areas
 * @property Listing[] $listings
 * @property Listing[] $listings1
 * @property Listing[] $listings2
 * @property Usercompany[] $usercompanies
 * @property Usercompany[] $usercompanies1
 * @property Usercompany[] $usercompanies2
 */
class Area extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'area';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, alias, type, parentid', 'required'),
            array('parentid', 'numerical', 'integerOnly' => true),
            array('name, alias', 'length', 'max' => 50),
            array('type, short_code', 'length', 'max' => 2),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, alias, type, short_code, parentid', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'parent' => array(self::BELONGS_TO, 'Area', 'parentid'),
            'areas' => array(self::HAS_MANY, 'Area', 'parentid'),
            'listings' => array(self::HAS_MANY, 'Listing', 'cityid'),
            'listings1' => array(self::HAS_MANY, 'Listing', 'countryid'),
            'listings2' => array(self::HAS_MANY, 'Listing', 'stateid'),
            'usercompanies' => array(self::HAS_MANY, 'Usercompany', 'cityid'),
            'usercompanies1' => array(self::HAS_MANY, 'Usercompany', 'countryid'),
            'usercompanies2' => array(self::HAS_MANY, 'Usercompany', 'stateid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'alias' => 'Alias',
            'type' => 'Type',
            'short_code' => 'Short Code',
            'parentid' => 'Parentid',
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
        $criteria->compare('name', $this->name, true);
        $criteria->compare('alias', $this->alias, true);
        $criteria->compare('type', $this->type, true);
        $criteria->compare('short_code', $this->short_code, true);
        $criteria->compare('parentid', $this->parentid);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Area the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /*
     * check if Area exists if not then create and return id
     */

    public static function checkAreaExists($areaName, $type, $parentId = null, $shortCode = null) {
        /* $criteria = new CDbCriteria;
          $criteria->select='id';  // only select the 'title' column
          $condition='name=:name AND type=:type';
          $params=array(':name'=>strtolower($areaName), ':type'=>$type); */
        if ($parentId) {
            $result = self::model()->findByAttributes(array('name' => $areaName, 'type' => $type, 'parentid' => $parentId), array('select' => 'id'));
        } else {
            $result = self::model()->findByAttributes(array('name' => $areaName, 'type' => $type), array('select' => 'id'));
        }
        if ($result) {
            // record exists
            return $result->id;
        } else {
            // record don't exists
            $areaModel = new Area;
            if ($parentId != null && is_numeric($parentId)) {
                // if parent id is passed
                $areaModel->parentid = $parentId;
            } else {
                // parent id is not passed (only scenario is for country)
                $areaMaxId = self::getAreaMaxId() + 1;
                $areaModel->id = $areaMaxId;        // set the id as well
                $areaModel->parentid = $areaMaxId;  // set the parent id
                if ($shortCode != '' || $shortCode != null) {
                    $areaModel->short_code = $shortCode;
                }
            }
            $areaModel->name = ucwords(strtolower(trim($areaName)));
            $areaModel->type = strtolower(trim($type));
            $areaModel->alias = JoyUtilities::createAlias($areaName);
            $areaModel->save();
            return $areaModel->primaryKey;          // return id
        }
    }

}
