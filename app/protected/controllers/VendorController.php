<?php

class VendorController extends Controller {

    public function init() {
        if (Yii::app()->user->isGuest) {
            $this->redirect(Yii::app()->createUrl('account'));
        }
    }

    public function actionIndex() {
        $vendorList = array();
        foreach (UserCompany::model()->findAllByAttributes(array('status' => 1)) as $value) {
            array_push($vendorList, array('id' => $value->id, 'value' => $value->name));
        }
        $cid = Yii::app()->user->cid;
        $id = Yii::app()->user->id;
        //echo $id;
        $model = RequestedCompanyVendor::showRequestedVendors($cid);
        $unsubscribedVendors = MonitorlyNotification::showUnsubscribedRequestedVendors($id);
//        echo '<pre>';
//        print_r($model);die();
        $this->render('index', array(
            'vendorList' => json_encode($vendorList),
            'model' => $model,
            'unsubscribedVendors' => $unsubscribedVendors,
            'id' => $cid,
        ));
    }

    // Uncomment the following methods and override them if needed
    /*
      public function filters()
      {
      // return the filter configuration for this controller, e.g.:
      return array(
      'inlineFilterName',
      array(
      'class'=>'path.to.FilterClass',
      'propertyName'=>'propertyValue',
      ),
      );
      }

      public function actions()
      {
      // return external action classes, e.g.:
      return array(
      'action1'=>'path.to.ActionClass',
      'action2'=>array(
      'class'=>'path.to.AnotherActionClass',
      'propertyName'=>'propertyValue',
      ),
      );
      }
     */
}
