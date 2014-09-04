<?php

/**
 * This is the model class for table "MonitorlyListing".
 *
 * The followings are the available columns in table 'MonitorlyListing':
 * @property integer $id
 * @property string $name
 * @property string $geoLat
 * @property string $geoLng
 * @property integer $mediaTypeId
 * @property string $zone
 * @property integer $vendorId
 * @property integer $addedBy
 * @property string $createdDate
 * @property string $modifiedDate
 * @property string $locality
 *
 * The followings are the available model relations:
 * @property Mediatype $mediaType
 * @property User $addedBy0
 * @property Task[] $tasks
 */
class BaseMonitorlyListing extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'MonitorlyListing';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('mediaTypeId, vendorId, addedBy', 'numerical', 'integerOnly' => true),
            array('name, zone, locality', 'length', 'max' => 245),
            array('geoLat, geoLng', 'length', 'max' => 9),
            array('createdDate, modifiedDate', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, geoLat, geoLng, mediaTypeId, zone, vendorId, addedBy, createdDate, modifiedDate, locality', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'mediaType' => array(self::BELONGS_TO, 'Mediatype', 'mediaTypeId'),
            'addedBy0' => array(self::BELONGS_TO, 'User', 'addedBy'),
            'tasks' => array(self::HAS_MANY, 'Task', 'siteid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'geoLat' => 'Geo Lat',
            'geoLng' => 'Geo Lng',
            'mediaTypeId' => 'Media Type',
            'zone' => 'Zone',
            'vendorId' => 'Vendor',
            'addedBy' => 'Added By',
            'createdDate' => 'Created Date',
            'modifiedDate' => 'Modified Date',
            'locality' => 'Locality',
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
        $criteria->compare('geoLat', $this->geoLat, true);
        $criteria->compare('geoLng', $this->geoLng, true);
        $criteria->compare('mediaTypeId', $this->mediaTypeId);
        $criteria->compare('zone', $this->zone, true);
        $criteria->compare('vendorId', $this->vendorId);
        $criteria->compare('addedBy', $this->addedBy);
        $criteria->compare('createdDate', $this->createdDate, true);
        $criteria->compare('modifiedDate', $this->modifiedDate, true);
        $criteria->compare('locality', $this->locality, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return MonitorlyListing the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
