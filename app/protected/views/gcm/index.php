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
                        <label class="control-label">Version</label>
                        <?php //echo $form->textField($model,'version', array('id' => 'sdate', 'class'=>'datepicker')); ?>
                        <input type="text" class="datepicker" name="version" id="version" />
                    </div>
                    <button class="btn btn-primary" >Submit</button>
                </div>
<!--            </form>-->
<?php $this->endWidget(); ?>
          </div>
      </div>
  </div>
   <!-- end of filters sub-header --> 
<div class="container-fluid content-wrapper">
    <div class="row">
      <div class="col-md-12">
          <h1 class="list-heading pull-left">Versions </h1>
<!--        <button class="btn btn-primary pull-right table-control">Save Changes</button>-->
        <table class="table table-hover" id="tasks">
         <tr> 
            <th>Version</th>
            <th>Start Date</th>
            <th>End Date</th>
        </tr>
            <?php
             foreach ($versions as $version) {
                 $row = '<tr><td>' . $version['version'] . '</td>';
                 $row = $row . '<td>' . $version['startdate'] . '</td>';
                 $row = $row . '<td>' . $version['enddate'] . '</td></tr>';
                 echo $row;
             }
            ?>
        </table>
      </div>
    </div>
</div>    