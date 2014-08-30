<?php

Yii::import('application.models.base.BaseUser');
class User extends BaseUser
{
//    public $confirmPassword;
//    public $id;
//    public $email;
        
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
        
        /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
            return array(
                
                array('username,password,phonenumber', 'required', 'on' => 'createScenario','message' => 'User Name is Required' ),
 
                                );
    }
    
    /*
         * returns the model selected of user table.
         * the model has sql run over it
         * sql query-> select fname,lname,email,phonenumber from user where active=1 AND status=1
         */
        public static function fetchUserDetails() {
            $criteria = new CDbCriteria();
            //$criteria->concat = 'fname, lname as name';
            $criteria->select = 'id, fname, lname, email, phonenumber';
            $criteria->condition = 'active=:active AND status=:status';
            $criteria->params = array(':active'=>1, ':status'=>1);
            $model = User::model()->findAll($criteria);
            return $model;
        }
        
//        public function insertUser($model) {
//            $cmd = Yii::app()->db->createCommand();
//            $cmd = $cmd->insert('user', array(
//                        'fname'=>$model->fname,
//                        'lname'=>$model->fname,
//                        'phonenumber'=>$model->phonenumber,
//                        'password'=>$model->password,
//                        'datecreated'=>$model->datecreated,
//                        'datemodified'=>$model->datemodified,
//            ));
//        }
        
        public static function changePassword($id,$pwd) {
            
            $cmd = Yii::app()->db->createCommand("Select ");
            $cmd = $cmd->update('user', array(
                       'password'=> $pwd,),
                       'id=:id',
                        array(':id'=>$id));
            
        }
}