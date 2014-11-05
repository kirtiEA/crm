
<!-- login modal -->
<?php $theme = Yii::app()->theme; ?>
<div class="modal fade modal-app" id="modal-login" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h3 class="modal-title"><img src="<?php echo $theme->getBaseUrl(); ?>/images/login.png"> &nbsp;Login to Your Monitorly Account</h3>
            </div>
            <div class="modal-body">
                <div class="form-wrap">
                    <?php
                    $form = $this->beginWidget('CActiveForm', array(
                        'id' => 'login-form',
                        //'action' => Yii::app()->getBaseUrl() .  '/account/login',   
                        'enableClientValidation' => true,
                        'clientOptions' => array(
                            'validateOnSubmit' => true,
                        ),
                    ));
                    ?>  
                    <div class="form-group">
                        <?php echo $form->textField($model, 'email', array('class' => 'form-control', 'placeholder' => 'Email', 'id' => 'email')); ?>
                        <?php echo $form->error($model, 'email'); ?>
                    </div>
                    <div class="form-group">
                        <?php echo $form->passwordField($model, 'password', array('class' => 'form-control', 'placeholder' => 'Password', 'id' => 'password')); ?>
                        <?php echo $form->error($model, 'password'); ?>
                    </div>
                    <button class="btn btn-primary">Login</button>
                    <?php //echo CHtml::button('Login', array('class' => 'btn btn-primary', 'id' => '_submit')); ?>&nbsp;
                    <a href="#" data-dismiss="modal">Cancel</a>
                    <?php $this->endWidget(); ?>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" data-toggle="modal" id="forgotpassword">Forgot Password?</a> 
                <a href="<?php echo Yii::app()->urlManager->createUrl('account/signup'); ?>" class="pull-right">Don't have an account? <b>Sign Up</b></a>
            </div>
        </div>
    </div>
</div>
<!-- end of login modal -->

<!-- forgot password modal -->

<div class="modal fade modal-app" id="modal-forgotpassword" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h3 class="modal-title">Forgot Password?</h3>
            </div>
            <div class="modal-body">
                <div class="alert alert-success" id="forgot_modal_alert" style="display: none;"></div>
                <div class="form-wrap" id="forgot_modal_form">
                    <h4>Don't worry. <b>What's your email?</b></h4>
                    <br>
                    <h5>We will mail you a link to quickly reset it.</h5>
                    <br>
                    <form class="form">
                        <div class="form-group">
                            <input class="form-control" type="email" id="forgot_email" placeholder="Email">
                        </div>
                        <button type="button" class="btn btn-primary" id="forgot_submit">Okay</button>&nbsp;
                        <a href="#" data-dismiss="modal">Cancel</a>
                    </form>
                </div>
            </div>
            <div class="modal-footer">                         
                <a href="<?php echo Yii::app()->urlManager->createUrl('account/signup'); ?>" class="pull-right">Don't have an account? <b>Sign Up</b></a>
            </div>
        </div>
    </div>
</div>
<!-- end of forgot password modal -->





<script type="text/javascript">
    $(function () {
        //$('#modal-login').modal('show');
        //$('#modal-forgotpassword').modal('show');
        //

        var status = <?php echo $status; ?>;
        if (status != 200) {
            $('#modal-login').modal('show');
        }

        

        $('#forgotpassword').click(function () {
            $('#modal-login').modal('hide');
            $('#modal-forgotpassword').modal('show');
        });

        $('#forgot_submit').click(function () {
            var email = $('#forgot_email').val();
            //console.log(email);
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->urlManager->createUrl('ajax/forgotpwd'); ?>",
                data: {
                    email: email
                },
                async: false,
                success: function (data) {
                    //console.log(data);

                    if (data == 1) {
                        $('#forgot_modal_alert').html('<b>Leap to your mailbox.</b> Reset password. And be back on Monitorly!');
                        $('#forgot_modal_alert').removeClass('alert-warning');
                        $('#forgot_modal_alert').addClass('alert-success');
                        $('#forgot_modal_form').hide();
                        // remove text buttons
                    } else if (data == 2) {
                        $('#forgot_modal_alert').html("Our server couldn't send a mail to you. Sorry, it's acting quirky. Try again in some time.");
                        $('#forgot_modal_alert').removeClass('alert-success');
                        $('#forgot_modal_alert').addClass('alert-warning');
                    } else if (data == 3) {
                        $('#forgot_modal_alert').html("We don't have this email in our system. Check if your entered it correctly.");
                        $('#forgot_modal_alert').removeClass('alert-success');
                        $('#forgot_modal_alert').addClass('alert-warning');
                    }
                    $('#forgot_modal_alert').show();
                }
            });
        });
    });
</script>