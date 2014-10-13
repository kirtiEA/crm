<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/dust/dust-full-2.2.0.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/dust/dust-helpers-1.1.1.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/template/js/tasks.js"></script>
<script type="text/javascript">
    $(function(){
        $('.mon_menu').each(function(){
            $(this).removeClass('active');
        });
        $('.menu_task').addClass('active');

    });
//            $('#filter-form').submit(function() {
//        console.log('sdfsdf'); 
//        });
//        $(document).ready(function() {
////            dust.render("tasks", <?php //echo $tasks;?>, function(err, out) {
////                $('#tasks').html(out);
////            });
//        });
        
       function filter() {
            $('#campaignids').val(JSON.stringify($('#multiselect-campaigns').val()));
           $('#userids').val(JSON.stringify($('#multiselect-users').val()));
           $('#filter-form').submit();
        }     
        
    function assignTaskToUser(uid,tid) {
        $.ajax({
                   type: 'POST',
                   url: '<?php echo Yii::app()->urlManager->createUrl('ajax/assignTaskToUser'); ?>',
                   data: {
                       'tid': tid,
                       'uid' : uid
                   },
                success:function(data){
                    $('#user_assigned_'+tid).html(JSON.parse(data)['assignedusername'] + '<span class="caret"></span>');    
//                    console.log(JSON.parse(data)['assignedusername']);  
                   },
                   error: function(data) { // if error occured
                         alert("Error occured.please try again");
                         alert(data);
                    }
                  });
    }
</script>

<div class="container-fluid sub-header">
      <div class="row">
          <div class="col-md-12">
<!--              <form class="form-horizontol" role="form" name="filter">-->
               <?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'filter-form',
    'enableAjaxValidation'=>false,
        'htmlOptions'=>array(
                               'class'=>"form-horizontol;",/* Disable normal form submit */
                             ),
)); ?>   
                <div class="form-group">
                    <div class="control">
                        <label class="control-label">Campaigns</label>
                        <select class="multiselect" id="multiselect-campaigns" multiple="multiple" >
                            <?php
                                foreach ($campaigns as $value) {
                                    echo '<option value=' . $value['id']. '>'. $value['name'] . '(' . $value['count'] . ')</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="control">
                      <label class="control-label">Assigned To</label>
                      <select class="multiselect" id="multiselect-users" multiple="multiple" >
                        <?php
                            foreach ($users as $value) {
                                echo '<option value=' . $value['id']. '>'. $value['name'] . '</option>';
                            }
                        ?>
                        </select>
                    </div>
                    <div class="control">
                        <label class="control-label">Start Date</label>
                        <?php echo $form->textField($model,'sdate', array('id' => 'sdate', 'class'=>'datepicker')); ?>
<!--                        <input type="text" class="datepicker" name="sdate" id="sdate" />-->
                    </div>
                    <div class="control">
                        <label class="control-label">End Date</label>
                        <?php echo $form->textField($model,'edate', array('id' => 'edate', 'class'=>'datepicker')); ?>
<!--                        <input type="text" class="datepicker" name="edate" id="edate" />-->
                        <?php echo $form->hiddenField($model, 'campaignids', array('id' => 'campaignids')) ?>
<!--                        <input type="hidden" name="campaigns" >-->
                        <?php echo $form->hiddenField($model, 'userids', array('id' => 'userids')) ?>
                    </div>
                    <div class="btn btn-primary" onclick="filter();">Filter</div>
                </div>
<!--            </form>-->
<?php $this->endWidget(); ?>
          </div>
      </div>
  </div>
   <!-- end of filters sub-header --> 
    
    
   <!-- tasks list --> 
  <div class="container-fluid content-wrapper">
    <div class="row">
      <div class="col-md-12">
          <h1 class="list-heading pull-left">Tasks List (<?php echo count($tasks)?>)</h1>
<!--        <button class="btn btn-primary pull-right table-control">Save Changes</button>-->
        <table class="table table-hover" id="tasks">
         <tr> 
            <th>Campaign</th>
            <th>Site</th>
            <th>Media Type</th>
            <th>Assigned To</th>
            <th>Due Date</th>
        </tr>
            <?php
            foreach ($tasks as $value) {
                 $row = '<tr>
                <td>' . $value['campaignname']. '</td>
                <td>'. $value['name']. ', '. $value['locality'].'</td>
                <td>'.$value['mediatype'].'</td>
                <td>
                    <div class="dropdown">
                      <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" id="user_assigned_'. $value['id'] .'">';
                 
                  if ($value['assigneduserid']) {
                        $row = $row . $value['assignedusername'];   
                 } else {
                     $row = $row . 'Unassigned';
                 }
                 $row = $row . 
                        '<span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">';
                 
                        foreach ($users as $user) {
                            $row = $row . '<li role="presentation" onclick="assignTaskToUser('.$user['id'].','. $value['id'] .');"><a role="menuitem" tabindex="-1" href="#!">'. $user['name'] .'</a></li>';
                            
                        }
 
                        $row = $row .
                      '</ul>
                    </div>
                </td>
                <td>'. $value['dueDate'].'</td>
</tr>'   
                ;
                        
               echo $row;         
            }
          ?>
        </table>
      </div>
    </div>
  </div>