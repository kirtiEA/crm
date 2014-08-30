<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	'Users'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List User', 'url'=>array('index')),
	//array('label'=>'Create User', 'url'=>array('create')),
	//array('label'=>'Update User', 'url'=>array('update', 'id'=>$model->id)),
	//array('label'=>'Delete User', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	//array('label'=>'Manage User', 'url'=>array('admin')),
);
?>

<h1>View User #<?php echo $model->id; ?></h1>
<?php 
/*$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
));*/

    echo '<pre>';
    //print_r($model); die();
    echo  'UserID: ' .$model->id .'<br />';
    echo  'Fname: '.$model->fname.'<br />';
    echo  'Lname: '.$model->lname.'<br />';
    echo  'Role: '.$selected.'<br />';
    echo  'Email: '.$model->email.'<br />';
    echo  'User name: '.$model->username.'<br />';
    echo  'Active: '.$model->active.'<br />';
    echo  'Status: '.$model->status.'<br />';
    echo  'Subscribe: '.$model->subscribe.'<br />';
    echo  'Last Login: '.$model->lastlogin.'<br />';
    echo  'Date Created: '.$model->datecreated.'<br />';
    echo  'Date Modified: '.$model->datemodified.'<br />';
    echo  'Date Activated: '.$model->dateactivated.'<br />';

    

?>
