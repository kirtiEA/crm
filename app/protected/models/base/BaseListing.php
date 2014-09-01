<?php

/**
 * This is the model class for table "Listing".
 *
 * The followings are the available columns in table 'Listing':
 * @property integer $id
 * @property integer $byuserid
 * @property integer $foruserid
 * @property string $name
 * @property string $length
 * @property string $width
 * @property integer $sizeunitid
 * @property integer $basecurrencyid
 * @property string $price
 * @property string $weeklyprice
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
 * @property integer $status
 * @property integer $pscore
 * @property integer $reach
 * @property integer $solr
 * @property string $datecreated
 * @property string $datemodified
 * @property integer companyId
 * @property integer parentId
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
 *
 * The followings are the available model relations:
 * @property FavouriteListing[] $favouriteListings
 * @property User $byuser
 * @property Area $city
 * @property Area $country
 * @property User $foruser
 * @property MediaType $mediatype
 * @property Area $state
 * @property LookupBaseCurrency $basecurrency
 * @property ListingAudienceTag[] $listingAudienceTags
 * @property ListingImage[] $listingImages
 * @property MediaPlan[] $mediaPlans
 * @property PlanListing[] $planListings
 */
class BaseListing extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'Listing';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('byuserid, foruserid, name, length, width, sizeunitid, basecurrencyid, price, weeklyprice, pricedurationid, otherdata, countryid, stateid, cityid, locality, zoomlevel, lightingid, mediatypeid, pscore, datecreated, datemodified, companyId,parent_listing_id', 'required'),
            array('byuserid, foruserid, sizeunitid, basecurrencyid, pricedurationid, countryid, stateid, cityid, accurate_geoloc, zoomlevel, lightingid, mediatypeid, status, pscore, reach, solr, min_period_num, min_period_unit, rate_per_unit, area_code, loop_length, daily_spots, dwell_time,companyId, parent_listing_id', 'numerical', 'integerOnly'=>true),
            array('name, tag1, tag2, tag3, contact_name, contact_phone, contact_email, postal, operating_hours, screen_size, other_type', 'length', 'max'=>255),
            array('length, width, price', 'length', 'max'=>10),
            array('weeklyprice', 'length', 'max'=>20),
            array('otherdata', 'length', 'max'=>50),
            array('locality', 'length', 'max'=>100),
            array('geolat, geolng', 'length', 'max'=>9),
            array('description, availability_start, availability_end, search_keywords', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, byuserid, foruserid, name, length, width, sizeunitid, basecurrencyid, price, weeklyprice, pricedurationid, otherdata, countryid, stateid, cityid, locality, geolat, geolng, accurate_geoloc, zoomlevel, lightingid, mediatypeid, description, status, pscore, reach, solr, datecreated, datemodified, tag1, tag2, tag3, availability_start, availability_end, min_period_num, min_period_unit, contact_name, contact_phone, contact_email, rate_per_unit, search_keywords, area_code, postal, loop_length, daily_spots, operating_hours, screen_size, dwell_time, other_type, companyId, availabilitydate', 'safe', 'on'=>'search'),
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
            'favouriteListings' => array(self::HAS_MANY, 'FavouriteListing', 'listingid'),            
            'byuser' => array(self::BELONGS_TO, 'User', 'byuserid'),
            'city' => array(self::BELONGS_TO, 'Area', 'cityid'),
            'country' => array(self::BELONGS_TO, 'Area', 'countryid'),
            'foruser' => array(self::BELONGS_TO, 'User', 'foruserid'),
            'mediatype' => array(self::BELONGS_TO, 'MediaType', 'mediatypeid'),
            'state' => array(self::BELONGS_TO, 'Area', 'stateid'),
            'basecurrency' => array(self::BELONGS_TO, 'LookupBaseCurrency', 'basecurrencyid'),
            'listingAudienceTags' => array(self::HAS_MANY, 'ListingAudienceTag', 'listingid'),
            'listingImages' => array(self::HAS_MANY, 'ListingImage', 'listingid'),
            'mediaPlans' => array(self::HAS_MANY, 'MediaPlan', 'listingid'),
            'planListings' => array(self::HAS_MANY, 'PlanListing', 'listingid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'byuserid' => 'Byuserid',
            'foruserid' => 'Foruserid',
            'name' => 'Name',
            'length' => 'Length',
            'width' => 'Width',
            'sizeunitid' => 'Sizeunitid',
            'basecurrencyid' => 'Basecurrencyid',
            'price' => 'Price',
            'weeklyprice' => 'Weeklyprice',
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
            'status' => 'Status',
            'pscore' => 'Pscore',
            'reach' => 'Reach',
            'solr' => 'Solr',
            'datecreated' => 'Datecreated',
            'datemodified' => 'Datemodified',
            'companyId' => 'Company',
            'parent_listing_id' => 'ParentListing',
            'availabilitydate' => 'availabilitydate',
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
        $criteria->compare('byuserid',$this->byuserid);
        $criteria->compare('foruserid',$this->foruserid);
        $criteria->compare('name',$this->name,true);
        $criteria->compare('length',$this->length,true);
        $criteria->compare('width',$this->width,true);
        $criteria->compare('sizeunitid',$this->sizeunitid);
        $criteria->compare('basecurrencyid',$this->basecurrencyid);
        $criteria->compare('price',$this->price,true);
        $criteria->compare('weeklyprice',$this->weeklyprice,true);
        $criteria->compare('pricedurationid',$this->pricedurationid);
        $criteria->compare('otherdata',$this->otherdata,true);
        $criteria->compare('countryid',$this->countryid);
        $criteria->compare('stateid',$this->stateid);
        $criteria->compare('cityid',$this->cityid);
        $criteria->compare('locality',$this->locality,true);
        $criteria->compare('geolat',$this->geolat,true);
        $criteria->compare('geolng',$this->geolng,true);
        $criteria->compare('accurate_geoloc',$this->accurate_geoloc);
        $criteria->compare('zoomlevel',$this->zoomlevel);
        $criteria->compare('lightingid',$this->lightingid);
        $criteria->compare('mediatypeid',$this->mediatypeid);
        $criteria->compare('description',$this->description,true);
        $criteria->compare('status',$this->status);
        $criteria->compare('pscore',$this->pscore);
        $criteria->compare('reach',$this->reach);
        $criteria->compare('solr',$this->solr);
        $criteria->compare('datecreated',$this->datecreated,true);
        $criteria->compare('datemodified',$this->datemodified,true);
        $criteria->compare('companyId',$this->companyId);
        $criteria->compare('parent_listing_id',$this->parent_listing_id);
        $criteria->compare('availabilitydate',$this->availabilitydate);
        $criteria->compare('tag1',$this->tag1,true);
        $criteria->compare('tag2',$this->tag2,true);
        $criteria->compare('tag3',$this->tag3,true);
        $criteria->compare('availability_start',$this->availability_start,true);
        $criteria->compare('availability_end',$this->availability_end,true);
        $criteria->compare('min_period_num',$this->min_period_num);
        $criteria->compare('min_period_unit',$this->min_period_unit);
        $criteria->compare('contact_name',$this->contact_name,true);
        $criteria->compare('contact_phone',$this->contact_phone,true);
        $criteria->compare('contact_email',$this->contact_email,true);
        $criteria->compare('rate_per_unit',$this->rate_per_unit);
        $criteria->compare('search_keywords',$this->search_keywords,true);
        $criteria->compare('area_code',$this->area_code);
        $criteria->compare('postal',$this->postal,true);
        $criteria->compare('loop_length',$this->loop_length);
        $criteria->compare('daily_spots',$this->daily_spots);
        $criteria->compare('operating_hours',$this->operating_hours,true);
        $criteria->compare('screen_size',$this->screen_size,true);
        $criteria->compare('dwell_time',$this->dwell_time);
        $criteria->compare('other_type',$this->other_type,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Listing the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}