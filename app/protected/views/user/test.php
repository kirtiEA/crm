<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    $this->menu=array(
        array('label'=>'List User', 'url'=>array('index')),
        array('label'=>'Create User', 'url'=>array('create')),
	array('label'=>'Manage User', 'url'=>array('admin')),
);
?>
<h1>Inactive Users</h1>

<?php 
/*$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
));*/

    echo '<pre>';
    foreach ($model as $value) {
   
        //echo  'User ID: ' .$value->id .'  ';
        echo  'Name: ' .$value->fname ;
        echo  ' ' .$value->lname . '  ';
        //array('label'=>$value->email, 'url'=>array('update'));
        //$url = yii::app()->getBaseUrl().'/'.'update'.'/'.$value->id;
        //echo $baseUrl;
        $url= Yii::app()->urlManager->createUrl('user/update/'.$value->id);
        echo 'Email: '.'<a href='.$url. '>'.$value->email.'  '.'</a>';
        echo  'Ph no: ' .$value->phonenumber . '<br />';
}
    

?>
