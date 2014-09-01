<?php

/**
 * This is the model class for table "listing".
 *
 * The followings are the available columns in table 'listing':
 * @property integer $id
 * @property integer $product_type
 * @property string $site_code
 * @property integer $byuserid
 * @property integer $foruserid
 * @property string $name
 * @property string $length
 * @property string $width
 * @property string $area
 * @property integer $sizeunitid
 * @property integer $basecurrencyid
 * @property string $price
 * @property string $weeklyprice
 * @property integer $wpstatus
 * @property integer $pricedurationid
 * @property string $otherdata
 * @property integer $countryid
 * @property integer $stateid
 * @property integer $cityid
 * @property string $locality
 * @property string $geolat
 * @property string $geolng
 * @property integer $accurate_geoloc
 * @property integer $zoomlevel
 * @property integer $lightingid
 * @property integer $mediatypeid
 * @property string $description
 * @property integer $approved
 * @property integer $status
 * @property integer $pscore
 * @property integer $reach
 * @property integer $solr
 * @property string $datecreated
 * @property string $datemodified
 * @property integer $companyId
 * @property integer $parent_listing_id
 * @property string $availabilitydate
 * @property string $tag1
 * @property string $tag2
 * @property string $tag3
 * @property string $availability_start
 * @property string $availability_end
 * @property integer $min_period_num
 * @property integer $min_period_unit
 * @property string $contact_name
 * @property string $contact_phone
 * @property string $contact_email
 * @property integer $rate_per_unit
 * @property string $search_keywords
 * @property integer $area_code
 * @property string $postal
 * @property integer $loop_length
 * @property integer $daily_spots
 * @property string $operating_hours
 * @property string $screen_size
 * @property integer $dwell_time
 * @property string $other_type
 * @property integer $temp_status
 * @property integer $new_mig_status
 *
 * The followings are the available model relations:
 * @property Favouritelisting[] $favouritelistings
 * @property FloorplanListings[] $floorplanListings
 * @property User $byuser
 * @property Area $city
 * @property Area $country
 * @property User $foruser
 * @property Mediatype $mediatype
 * @property Area $state
 * @property Lookupbasecurrency $basecurrency
 * @property Listingaudiencetag[] $listingaudiencetags
 * @property Listingimage[] $listingimages
 * @property ParentlistingFloorplanMapping[] $parentlistingFloorplanMappings
 * @property Planlisting[] $planlistings
 */
class Listing extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'listing';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('byuserid, foruserid, name, length, width, area, sizeunitid, basecurrencyid, price, weeklyprice, pricedurationid, otherdata, countryid, stateid, cityid, locality, zoomlevel, lightingid, mediatypeid, pscore, datecreated, datemodified, companyId, parent_listing_id, availabilitydate, temp_status', 'required'),
            array('product_type, byuserid, foruserid, sizeunitid, basecurrencyid, wpstatus, pricedurationid, countryid, stateid, cityid, accurate_geoloc, zoomlevel, lightingid, mediatypeid, approved, status, pscore, reach, solr, companyId, parent_listing_id, min_period_num, min_period_unit, rate_per_unit, area_code, loop_length, daily_spots, dwell_time, temp_status, new_mig_status', 'numerical', 'integerOnly' => true),
            array('name, tag1, tag2, tag3, contact_name, contact_phone, contact_email, postal, operating_hours, screen_size, other_type', 'length', 'max' => 255),
            array('length, width, area, price', 'length', 'max' => 10),
            array('site_code', 'length', 'max'=>45),
            array('weeklyprice', 'length', 'max' => 20),
            array('otherdata', 'length', 'max' => 50),
            array('locality', 'length', 'max' => 100),
            array('geolat, geolng', 'length', 'max' => 9),
            array('description, availability_start, availability_end, search_keywords', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, product_type, byuserid, foruserid, name, length, width, area, sizeunitid, basecurrencyid, price, weeklyprice, wpstatus, pricedurationid, otherdata, countryid, stateid, cityid, locality, geolat, geolng, accurate_geoloc, zoomlevel, lightingid, mediatypeid, approved, description, status, pscore, reach, solr, datecreated, datemodified, companyId, parent_listing_id, availabilitydate, tag1, tag2, tag3, availability_start, availability_end, min_period_num, min_period_unit, contact_name, contact_phone, contact_email, rate_per_unit, search_keywords, area_code, postal, loop_length, daily_spots, operating_hours, screen_size, dwell_time, other_type, temp_status, new_mig_status', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'favouritelistings' => array(self::HAS_MANY, 'Favouritelisting', 'listingid'),
            'floorplanListings' => array(self::HAS_MANY, 'FloorplanListings', 'listing_id'),            
            'byuser' => array(self::BELONGS_TO, 'User', 'byuserid'),
            'city' => array(self::BELONGS_TO, 'Area', 'cityid'),
            'country' => array(self::BELONGS_TO, 'Area', 'countryid'),
            'foruser' => array(self::BELONGS_TO, 'User', 'foruserid'),
            'mediatype' => array(self::BELONGS_TO, 'Mediatype', 'mediatypeid'),
            'state' => array(self::BELONGS_TO, 'Area', 'stateid'),
            'basecurrency' => array(self::BELONGS_TO, 'Lookupbasecurrency', 'basecurrencyid'),
            'listingaudiencetags' => array(self::HAS_MANY, 'Listingaudiencetag', 'listingid'),
            'listingimages' => array(self::HAS_MANY, 'Listingimage', 'listingid'),
            'parentlistingFloorplanMappings' => array(self::HAS_MANY, 'ParentlistingFloorplanMapping', 'parentListing_id'),
            'planlistings' => array(self::HAS_MANY, 'Planlisting', 'listingid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'product_type' => 'Product Type',
            'site_code' => 'Site Code',
            'byuserid' => 'Byuserid',
            'foruserid' => 'Foruserid',
            'name' => 'Name',
            'length' => 'Length',
            'width' => 'Width',
            'area' => 'Area',
            'sizeunitid' => 'Sizeunitid',
            'basecurrencyid' => 'Basecurrencyid',
            'price' => 'Price',
            'weeklyprice' => 'Weeklyprice',
            'wpstatus' => 'Wpstatus',
            'pricedurationid' => 'Pricedurationid',
            'otherdata' => 'Otherdata',
            'countryid' => 'Countryid',
            'stateid' => 'Stateid',
            'cityid' => 'Cityid',
            'locality' => 'Locality',
            'geolat' => 'Geolat',
            'geolng' => 'Geolng',
            'accurate_geoloc' => 'Accurate Geoloc',
            'zoomlevel' => 'Zoomlevel',
            'lightingid' => 'Lightingid',
            'mediatypeid' => 'Mediatypeid',
            'description' => 'Description',
            'approved' => 'Approved',
            'status' => 'Status',
            'pscore' => 'Pscore',
            'reach' => 'Reach',
            'solr' => 'Solr',
            'datecreated' => 'Datecreated',
            'datemodified' => 'Datemodified',
            'companyId' => 'Company',
            'parent_listing_id' => 'Parent Listing',
            'availabilitydate' => 'Availabilitydate',
            'tag1' => 'Tag1',
            'tag2' => 'Tag2',
            'tag3' => 'Tag3',
            'availability_start' => 'Availability Start',
            'availability_end' => 'Availability End',
            'min_period_num' => 'Min Period Num',
            'min_period_unit' => 'Min Period Unit',
            'contact_name' => 'Contact Name',
            'contact_phone' => 'Contact Phone',
            'contact_email' => 'Contact Email',
            'rate_per_unit' => 'Rate Per Unit',
            'search_keywords' => 'Search Keywords',
            'area_code' => 'Area Code',
            'postal' => 'Postal',
            'loop_length' => 'Loop Length',
            'daily_spots' => 'Daily Spots',
            'operating_hours' => 'Operating Hours',
            'screen_size' => 'Screen Size',
            'dwell_time' => 'Dwell Time',
            'other_type' => 'Other Type',
            'temp_status' => 'Temp Status',
            'new_mig_status' => 'New Mig Status',
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
        $criteria->compare('product_type', $this->product_type);
        $criteria->compare('site_code',$this->site_code,true);
        $criteria->compare('byuserid', $this->byuserid);
        $criteria->compare('foruserid', $this->foruserid);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('length', $this->length, true);
        $criteria->compare('width', $this->width, true);
        $criteria->compare('area', $this->area, true);
        $criteria->compare('sizeunitid', $this->sizeunitid);
        $criteria->compare('basecurrencyid', $this->basecurrencyid);
        $criteria->compare('price', $this->price, true);
        $criteria->compare('weeklyprice', $this->weeklyprice, true);
        $criteria->compare('wpstatus', $this->wpstatus);
        $criteria->compare('pricedurationid', $this->pricedurationid);
        $criteria->compare('otherdata', $this->otherdata, true);
        $criteria->compare('countryid', $this->countryid);
        $criteria->compare('stateid', $this->stateid);
        $criteria->compare('cityid', $this->cityid);
        $criteria->compare('locality', $this->locality, true);
        $criteria->compare('geolat', $this->geolat, true);
        $criteria->compare('geolng', $this->geolng, true);
        $criteria->compare('accurate_geoloc', $this->accurate_geoloc);
        $criteria->compare('zoomlevel', $this->zoomlevel);
        $criteria->compare('lightingid', $this->lightingid);
        $criteria->compare('mediatypeid', $this->mediatypeid);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('approved',$this->approved);
        $criteria->compare('status', $this->status);
        $criteria->compare('pscore', $this->pscore);
        $criteria->compare('reach', $this->reach);
        $criteria->compare('solr', $this->solr);
        $criteria->compare('datecreated', $this->datecreated, true);
        $criteria->compare('datemodified', $this->datemodified, true);
        $criteria->compare('companyId', $this->companyId);
        $criteria->compare('parent_listing_id', $this->parent_listing_id);
        $criteria->compare('availabilitydate', $this->availabilitydate, true);
        $criteria->compare('tag1', $this->tag1, true);
        $criteria->compare('tag2', $this->tag2, true);
        $criteria->compare('tag3', $this->tag3, true);
        $criteria->compare('availability_start', $this->availability_start, true);
        $criteria->compare('availability_end', $this->availability_end, true);
        $criteria->compare('min_period_num', $this->min_period_num);
        $criteria->compare('min_period_unit', $this->min_period_unit);
        $criteria->compare('contact_name', $this->contact_name, true);
        $criteria->compare('contact_phone', $this->contact_phone, true);
        $criteria->compare('contact_email', $this->contact_email, true);
        $criteria->compare('rate_per_unit', $this->rate_per_unit);
        $criteria->compare('search_keywords', $this->search_keywords, true);
        $criteria->compare('area_code', $this->area_code);
        $criteria->compare('postal', $this->postal, true);
        $criteria->compare('loop_length', $this->loop_length);
        $criteria->compare('daily_spots', $this->daily_spots);
        $criteria->compare('operating_hours', $this->operating_hours, true);
        $criteria->compare('screen_size', $this->screen_size, true);
        $criteria->compare('dwell_time', $this->dwell_time);
        $criteria->compare('other_type', $this->other_type, true);
        $criteria->compare('temp_status', $this->temp_status);
        $criteria->compare('new_mig_status', $this->new_mig_status);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Listing the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return array of price duration or value if id is passed
     */
    public static function getLighting($lightingId = null) {
        // PLEASE DO NOT CHANGE. WILL IMPACT ON MASSUPLOAD VALIDATAION
        $lighting = array(
            '1' => 'No lighting',
            '2' => 'Front lit',
            '3' => 'Back lit'
        );
        if ($lightingId) {
            return $lighting[$lightingId];
        } else {
            return $lighting;
        }
    }

}
