<?php

class SubscriptionController extends Controller {
    
    //private $nid;

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function actionIndex() {
        $model = new Monitorlysubscription();
        $vendorList = array();
        $nid = Yii::app()->request->getParam('nid');
        $model->nid = $nid;
        foreach (UserCompany::model()->findAll() as $value) {
            array_push($vendorList, array('id' => $value->id, 'value' => $value->name));
        }
        $this->render('index', array(
            'model' => $model,
            'vendorList' => json_encode($vendorList),
            'nid' => $nid,
        ));
    }

    public function actionCreateVendor() {
        $model = new Monitorlysubscription();
        //$model->setScenario('subscribe');
        if (isset($_POST['Monitorlysubscription'])) {
            if (strlen($_POST['Monitorlysubscription']['email']) && filter_var($_POST['Monitorlysubscription']['email'], FILTER_VALIDATE_EMAIL)) {
                //$model->nid = Yii::app()->request->getParam('nid');   
                $model->companyname = $_POST['Monitorlysubscription']['companyname'];
                $model->email = $_POST['Monitorlysubscription']['email'];
                $model->phonenumber = $_POST['Monitorlysubscription']['phonenumber'];
                $model->nid = $_POST['Monitorlysubscription']['nid'];
                $model->createddate = date("Y-m-d H:i:s");
                //print_r($model->attributes);
                //print_r($_POST);
                //if($model->validate())
                echo $model->save(FALSE);
                $id = Yii::app()->user->id;
                $email = Yii::app()->user->emailid;
                $invite = new Monitorlynotification();
                $invite->attributes = array('typeid' => 1, 'createddate' => date("Y-m-d H:i:s"), 'createdby' => $id, 'emailtypeid' => 2);
                $invite->save();
                $mail = new EatadsMailer('accepted-invite', $email, array('resetLink' => ""), array('shruti@eatads.com'));
                $mail->eatadsSend();
//                echo "id=".$model->id ;
                //echo '<pre>';
                //              print_r($model->attributes);
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
