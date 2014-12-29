<?php

class DashboardController extends Controller
{
	public function actionIndex()
	{
//		echo Yii::app()->user->cid;die();
		//fetch sales people
		$sales = User::fetchCompanyUsers(Yii::app()->user->cid, 6);
		//print_r($sales);die('sdfs');
		//fetch brands
		$brands = CompanyBrands::model()->findAllByAttributes(array('companyid' => Yii::app()->user->cid, 'status' => 1));
                //print_r($brands);die('sdfs');
                //fetch tags
                $tags  = CompanyTags::model()->findAllByAttributes(array('companyid' => Yii::app()->user->cid, 'status' => 1));
		$this->render('index', array(
                    'sales' => $sales,
                    'brands' => $brands,
                    'tags' => $tags
                ));
	}
}
