<script type="text/javascript">
    $(function () {
        $('.mon_menu').each(function () {
            $(this).removeClass('active');
        });
        $('.menu_user').addClass('active');
    });
    //save user password


</script>

<!-- add new user subheader -->    

<div class="container-fluid sub-header">
    <div class="row">
        <div class="col-md-12">
            <?php
            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'create_user',
                'action' => 'user/create',
                'enableClientValidation' => true,
                'clientOptions' => array(
                    'validateOnSubmit' => true,
                ),
                'htmlOptions' => array(
                    'class' => 'form-horizontal',
                )
            ));
            ?>
            <div class="form-group">
                <h3 class="subheader-heading" style="margin-left: 16px;">Add New User</h3>
                <div class="control">
                    <label class="control-label">Username</label>
                    <?php echo $form->textField($model, 'username', array('autocomplete' => "off", 'maxlength'=>'10','id' => 'username')); ?>    
                    <?php echo $form->error($model, 'username'); ?>
                </div>
                <div class="control">
                    <label class="control-label">Password</label>
                    <?php echo $form->passwordField($model, 'password', array('autocomplete' => "off", 'id' => 'password')); ?>                            
                    <?php echo $form->error($model, 'password'); ?>
                </div>
                <div class="control">
                    <label class="control-label">Mobile No.</label>
<!--                        <select>
                        <option>+91</option>
                        <option>+01</option>
                    </select>-->
                    <?php echo $form->textField($model, 'phonenumber', array('maxlength' => '10', 'autocomplete' => "off", 'id' => 'phonenumber')); ?>                            

                    <?php echo $form->error($model, 'phonenumber'); ?>
                </div>
                <?php //echo CHtml::submitButton('Add', array('class'=>'add btn-primary', 'id'=>'_submit'));  ?>
                <button class="btn btn-primary" id="add">Add</button>
            </div>
            <?php $this->endWidget(); ?>
        </div>
    </div>
</div>
<!-- end of add new user subheader --> 

<!-- user list --> 
<div class="container-fluid content-wrapper">
    <div class="row">
        <div class="col-md-12">
            <h1 class="list-heading">Users List (<?php
                $no = count($users);
                echo $no;
                ?>)
            </h1>
            <ul class="list">
                <?php foreach ($users as $value): ?>

                    <li class="list-item">
                        <div class="pull-left">
                            <h2 class="list-item-heading">
                            <?php echo $value->fname . ' ' . $value->lname; ?></h2>
                            <h4><?php echo $value->phonenumber; ?></h4>
                        </div>
                        <div class="pull-right" id="<?php echo $value->id; ?>">

                            <!--                        <button class="btn btn-secondary">Send Message</button>&nbsp;-->
                            <button class="change-pwd btn btn-secondary">Change Password</button>

                        </div>
                        <div class="clearfix"></div>
                    </li>
                <?php endforeach; ?>
                <span id="hidden-change-pwd" style="display:none;" >                    
                    <input class="password" type="password" value="" placeholder="New Password">
                    <button class="save btn-primary" >Save</button>
                    <button class="cancel btn-primary" >Cancel</button>

                </span>
                <input id="base-url" type="hidden" value="<?php Yii::app()->getBaseUrl(); ?>" style="display:none;"/>
            </ul>
        </div>
    </div>
</div>
<!-- end of user list --> 
<script>
    $('#add').on('click', function (event) {
        event.preventDefault();
        //console.log('Add ');
        if ($('#username').val()) {
            //console.log('1');
            if ($('#password').val()) {
                //              console.log('2');
                if ($('#phonenumber').val().length==10) {
                    //console.log('4');
                    $('#create_user').submit();
                    //return true;
                } else {
                    $('#phonenumber').focus();
                    $('#phonenumber').attr('placeholder', '10 Digit phone number is required').attr('style', 'background-color:rgb(218, 172, 172)');
                    //alert('Phone Number is required');
                }
            } else {
                $('#password').focus();
                $('#password').attr('placeholder', 'Password is Required').attr('style', 'background-color:rgb(218, 172, 172)');
                //alert('Company Name is Required');
            }
        } else {
            //console.log('5');
            $('#username').focus();
            $('#username').attr('placeholder', 'Username is required').attr('style', 'background-color:rgb(218, 172, 172)');
            //alert('Email is required');
        }
    });
</script>
