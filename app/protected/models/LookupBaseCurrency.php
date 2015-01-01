<?php

Yii::import('application.models.base.BaseLookupBaseCurrency');

class LookupBaseCurrency extends BaseLookupBaseCurrency
{
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('currency_code', 'required'),
			array('currency_code', 'length', 'max'=>5),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, currency_code', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LookupBaseCurrency the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
    public static function getBaseCurrencyList()
    {
        return self::model()->findAll();
    }
    
    public static function getCurrencyIdByIp()
    {
        $sql = 'SELECT  lbc.id, ipn.country AS countrycode, currencycode FROM ip2nation AS ipn
                    INNER JOIN `LookupCountryCurrency` AS lcc ON ipn.country = lcc.countrycode
                    LEFT JOIN `LookupBaseCurrency` AS lbc ON lbc.currency_code = lcc.currencycode
                    WHERE ip < INET_ATON("'.$_SERVER['REMOTE_ADDR'].'") 
                    ORDER BY ip DESC LIMIT 0,1;';
        return $result = Yii::app()->db->createCommand($sql)->queryRow();        
    }
    
    public static function getCurrencyName($currencyId)
    {
        $result = self::model()->findByPk($currencyId, array('select'=>'currency_code'));
        return $result->currency_code;
    }

    public static function isBaseCurrencyExist($baseCurrency) {
        $criteria = new CDbCriteria();
        $criteria->select = 'id';
        $criteria->condition = 'currency_code=:currencycode';
        $criteria->params = array(':currencycode' => $baseCurrency);

        $data = self::model()->find($criteria);
        if($data) {
            return $data->id;
        } else {
            return false;
        }
    }
}
