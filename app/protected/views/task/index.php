<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/dust/dust-full-2.2.0.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/dust/dust-helpers-1.1.1.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/template/js/tasks.js"></script>
<script type="text/javascript">
$(document).ready(function () {

var menu = $('#submenu');
var origOffsetY = menu.offset().top;

function scroll() {
//    console.log("yes"+origOffsetY);

    if ($(window).scrollTop() >= origOffsetY) {
        $('#submenu').css("margin-top","0px");
        $('#submenu').addClass('navbar-fixed-top');
       // $('.content').addClass('menu-padding');
    } else {
        $('#submenu').css("margin-top","-20px");
        $('#submenu').removeClass('navbar-fixed-top');
        //$('.content').removeClass('menu-padding');
    }
    
    if ($(window).scrollTop() == ($(document).height() - $(window).height())) {
        fetchNextTasks(1);
    }

   }

  document.onscroll = scroll;
        $('.mon_menu').each(function(){
            $(this).removeClass('active');
        });
        $('.menu_task').addClass('active');

    });
        
       function filter() {
            $('#campaignids').val(JSON.stringify($('#multiselect-campaigns').val()));
           $('#userids').val(JSON.stringify($('#multiselect-users').val()));
           //$('#filter-form').submit();
           fetchNextTasks(2);
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
    var start = <?php echo count($tasks);?>;
    var limit = 10;
    function fetchNextTasks(id) {
        if (id == 2) {
            start = 0;
        }
        $.ajax({
                   type: 'POST',
                   url: '<?php echo Yii::app()->urlManager->createUrl('ajax/filterTasks'); ?>',
                   data: {
                       'campaignids': JSON.stringify($('#multiselect-campaigns').val()),
                       'userids' : JSON.stringify($('#multiselect-users').val()),
                       'sdate' : $('#sdate').val(),
                       'edate' :$('#edate').val(),
                       'start' :start
                   },
                success: function (data) {
                    start += limit;
                    
                    var template = $('#task_row').html();
                    Mustache.parse(template);   // optional, speeds up future uses
                    var rendered = Mustache.render(template, JSON.parse(data));
                    if (id == 1) {
                        $('#tbody_task').append(rendered);
                    } else if (id == 2) {
                        $('#tbody_task').html(rendered);
                    }    
                    
                    
                    $('#task_cnt').html(start);
                   },
                   error: function(data) { // if error occured
                         alert("Error occured.please try again");
                         alert(data);
                    }
                  });
    }
    
    $('.infinite-scroll').jscroll({
    loadingHtml: '<img src="loading.gif" alt="Loading" /> Loading...', // The HTML to show at the bottom of the content while loading the next set.
    padding: 2, // The distance from the bottom of the scrollable content at which to trigger the loading of the next set of content. 
    nextSelector: 'a.jscroll-next:last',
    autoTrigger: true
});


function renderDropDown(id) {
//    console.log(id + "sdfsf");
    var template = $('#dropdown_row').html();
    Mustache.parse(template);   // optional, speeds up future uses
    //var json = parseJSON();
    var json = [];
    json['taskid'] = id;
    json['users'] = <?php echo json_encode($users);?>;
    var rendered = Mustache.render(template, <?php echo json_encode($users);?>);
    $('#ul_'+id).html(rendered);
    
}    
 
</script>

<div id="submenu" class="container-fluid sub-header">
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
                    &nbsp;
                    <a href="javascript:location.reload();" ><b>Clear Filter</b></a>
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
          <h1 class="list-heading pull-left">Tasks List (<span id="task_cnt"><?php echo count($tasks)?></span>)</h1>
<!--        <button class="btn btn-primary pull-right table-control">Save Changes</button>-->

    <table class="table table-condensed" style="table-layout:fixed">
        <thead>
            <tr>
            <th>Campaign</th>
            <th>Site</th>
            <th>Media Type</th>
            <th>Assigned To</th>
            <th>Due Date</th>
            </tr>
        </thead>
    </table>

<div id="rcontent" class="div-table-content scroll" data-ui="jscroll-default">
    <div class="jscroll-inner">
            <table class="table table-hover" style="table-layout:fixed">
                <tbody id="tbody_task" class="scroll">
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

                </tbody>

            </table>
        <div class="next jscroll-next-parent" ><a href="javascript:fetchNextTasks(1);">next</a></div>
    </div>
                </div>
      </div>
    </div>
  </div>
   
   <script id="task_row">
       {{#.}}
       <tr>
                <td>{{campaignname}}</td>
                <td>{{name}}, {{locality}}</td>
                <td>{{mediatype}}</td>
                <td>
                    <div class="dropdown">
                      <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" onclick="renderDropDown('{{id}}');" id="user_assigned_{{id}}">{{assignedusername}}<span class="caret"></span>
                      </button>
              <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1" id="ul_{{id}}">
              </ul>
                    </div>
                </td>
                <td>{{dueDate}}</td>
</tr>
{{/.}}
   </script>
   <script id="dropdown_row">
        {{#.}}
            <li role="presentation" onclick="assignTaskToUser({{id}},{{taskid}});">
                        <a role="menuitem" tabindex="-1" href="#!">{{name}}</a>
             </li>
        {{/.}}
   </script>