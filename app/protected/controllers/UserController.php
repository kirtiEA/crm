<?php

class UserController extends Controller {

    protected $userroleid;

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    /* public function filters()
      {
      return array(
      'accessControl', // perform access control for CRUD operations
      'postOnly + delete', // we only allow deletion via POST request
      );
      } */

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
                'users' => array('admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function init() {
        if (Yii::app()->user->isGuest) {
            $this->redirect(Yii::app()->createUrl('account'));
        }
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        //echo 'dfdfsfsd';die();
        $model = new User();
        $model->setscenario('create'); //create scenario for rules validation
        //print_r($today);die();
        //$userRole = Userrole::model()->insertRoles();
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            //print_r($_POST['User']['username']);die();
            $role = Role::model()->findByPk(5);

            $check = User::checkUniqueUsername(Yii::app()->user->id, strtolower($_POST['User']['username']));
            //echo '<pre>'; print_r(strcasecmp($check['cnt'], '0')); die();
            if (strcasecmp($check['cnt'], '0') == 0) {
                //echo 'hi';
                $model->username = strtolower($_POST['User']['username']);
                //echo $model->username;            die();
                $model->email = 'dummy' . $model->username . '@eatads.com';
                $model->phonenumber = $_POST['User']['phonenumber'];
                $model->datecreated = date("Y-m-d H:i:s");
                $model->datemodified = date("Y-m-d H:i:s");
                $model->active = 1;
                $model->fname = $_POST['User']['username'];
                $model->companyid = Yii::app()->user->cid;

                $pwd = $_POST['User']['password'];
                $ph = new PasswordHash(Yii::app()->params['phpass']['iteration_count_log2'], Yii::app()->params['phpass']['portable_hashes']);
                $password = $ph->HashPassword($pwd);
                //User::model()->insertUser($model);
                $result = $ph->CheckPassword($pwd, $model->password);
                //echo $result;
                if ($result) {
                    // Authorized
                } else {
                    // Error: Unauthorized
                }
                $model->password = $password;
                //echo '<pre>'; print_r($model); die();
                if ($model->validate()) {
                    // print_r($model->attributes);
                    $model->save();
                    UserRole::model()->insertRoles($model->id, $role->id);
                    $this->redirect(Yii::app()->getBaseUrl() . '/user');
                }
            } else {
                /*
                 * flash a message if the username already exists
                 */
                echo 'User already exists. Choose a diiferent username';
                $this->redirect(Yii::app()->getBaseUrl() . '/user');
            }
        }

//		$this->render('create',array(
//			'model'=>$model,
//                        'role'=>$role,
//		));
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $row = Userrole::model()->findByPk($id);
        $role = Role::model()->findByPk($row->roleid);
        //echo '<pre>';                print_r($row); die();
        $this->render('view', array(
            'model' => $this->loadModel($id),
            'selected' => $role->name,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all user model where ative=1 and status=1
     */
    public function actionIndex() {
        $users = User::fetchCompanyUsersModel(Yii::app()->user->cid);
        $model = new User();
        //echo '<pre>';print_r($model); die();
        $this->render('index', array(
            'users' => $users,
            'model' => $model
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new User('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['User']))
            $model->attributes = $_GET['User'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return User the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = User::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param User $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
