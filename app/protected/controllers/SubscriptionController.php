<?php

class SubscriptionController extends Controller {
    
    //private $nid;

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */

//    public function init() {
//        if (Yii::app()->user->isGuest) {
//            $this->redirect(Yii::app()->createUrl('account'));
//        }
//    }

    /**
     * @return array action filters
     
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }*/

    public function accessRules() {
        return array(
            array('allow', // allow all users to perform actions
                'actions' => array('index', 'createvendor'),
                'users' => array('*'),
            )
        );
    }
    
    public function actionIndex() {
        $model = new MonitorlySubscription();
        $vendorList = array();
        $nid = Yii::app()->request->getParam('nid');
        $model->nid = $nid;
        foreach (UserCompany::model()->findAllByAttributes(array('status' => 1)) as $value) {
            array_push($vendorList, array('id' => $value->id, 'value' => $value->name));
        }
        $this->renderPartial('index', array(
            'model' => $model,
            'vendorList' => json_encode($vendorList),
            'nid' => $nid,
        ));
    }

    public function actionCreateVendor() {
        $model = new MonitorlySubscription();
        //$model->setScenario('subscribe');
        if (isset($_POST['MonitorlySubscription'])) {
            if (strlen($_POST['MonitorlySubscription']['email']) && filter_var($_POST['MonitorlySubscription']['email'], FILTER_VALIDATE_EMAIL)) {
                //$model->nid = Yii::app()->request->getParam('nid');   
                $model->companyname = $_POST['MonitorlySubscription']['companyname'];
                $model->email = $_POST['MonitorlySubscription']['email'];
                $model->phonenumber = $_POST['MonitorlySubscription']['phonenumber'];
                $model->nid = $_POST['MonitorlySubscription']['nid'];
                $model->createddate = date("Y-m-d H:i:s");
                //print_r($model->attributes);
                //print_r($_POST);
                //if($model->validate())
                echo $model->save(FALSE);

//                $id = Yii::app()->user->id;
//                $email = Yii::app()->user->emailid;
//                $invite = new MonitorlyNotification();
//                $invite->attributes = array('typeid' => "", 'createddate' => date("Y-m-d H:i:s"), 'createdby' => $id, 'emailtypeid' => 2);
//                $invite->save();
//                $mail = new EatadsMailer('accepted-invite', $email, array('resetLink' => ""), array('shruti@eatads.com'));
//                $mail->eatadsSend();

//                echo "id=".$model->id ;
                //echo '<pre>';
                //              print_r($model->attributes);
                
                $this->redirect(Yii::app()->getBaseUrl(true) . '/account');
            }
        }
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
