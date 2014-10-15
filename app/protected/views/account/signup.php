<?php $this->widget('LoginModal'); ?>
<!-- sign up content -->
<div class="row signup-content">
    <div class="col-md-12">
        <div class="signup-content-headings">
            <h1>Sign-up for Monitorly</h1>
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-6 col-md-offset-3">
                <!--                <form class="form" role="form">
                                    <input type="email" class="form-control" placeholder="Email">
                                    <input type="text" class="form-control" placeholder="Company Name">
                                    <input type="text" class="form-control" placeholder="+91">
                                    <input type="text" class="form-control" placeholder="Mobile">
                                    <br><br>
                                    <button class="btn btn-primary btn-primary-lg">Sign Up for Free</button>
                                    <br>
                                    <h5>No Credit Card required</h5>
                                    <h5>By signing-up you agree to <a href="<?php echo Yii::app()->urlManager->createUrl('account/terms'); ?>" target="_blank">Terms &amp; Conditions</a></h5>
                                </form>-->
                <?php if (Yii::app()->user->hasFlash('success')) { ?>
                    <div id="flash-messages" class="alert alert-success alert-dismissible">
                        <?php echo Yii::app()->user->getFlash('success'); ?>
                    </div>
                <?php } ?>
                <?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'vendor_subscription1',
                    'action' => 'createvendoraccount',
                    //'enableClientValidation' => true,
                    //    'clientOptions' => array(
                    //        'validateOnSubmit' => true,
                    //    ),
                    'htmlOptions' => array(
                        'class' => 'form',
                    ),
                ));
                ?>
<!--                        <input type="email" class="form-control" placeholder="Email">-->
                <div class="signup-form-wrap">
                    <div class="signup-form">
                        <?php
                        if (!empty($modelSub->email)) {
                            echo $form->emailField($modelSub, 'email', array('id' => 'emailid', 'class' => 'form-control', 'placeholder' => 'Email', 'type' => 'email', 'disabled' => 'true', 'autocomplete' => 'off', 'value' => $modelSub->email));
                            echo $form->error($modelSub, 'email');
                        } else {
                            echo $form->emailField($modelSub, 'email', array('id' => 'emailid', 'class' => 'form-control', 'placeholder' => 'Email', 'type' => 'email'));
                            echo $form->error($modelSub, 'email');
                        }
                        ?>

<!--                        <input type="text" class="form-control" placeholder="Company Name">-->
                        <?php echo $form->textField($modelSub, 'companyname', array('id' => 'companynameid', 'class' => 'form-control companyname', 'placeholder' => 'Company Name', 'autocomplete' => 'off')); ?> 
                        <?php echo $form->error($modelSub, 'companyname'); ?>

                        <?php echo $form->hiddenField($modelSub, 'companyid', array('id' => 'vendor-ac-id')); ?>

                        <?php echo $form->passwordField($modelSub, 'password', array('id' => 'password', 'class' => 'form-control', 'placeholder' => 'Password', 'style' => 'width:300px;')); ?>
                        <?php echo $form->error($modelSub, 'password'); ?>
                        <input type="password" class="form-control" placeholder="Confirm Password" style="width: 300px;" id="cnf_password">


<!--                        <input type="text" class="form-control" placeholder="+91">-->
<!--                        <input  class="form-control intl-tel-input" placeholder="Mobile" id="mobile-number" type="tel">-->
                        <?php echo $form->textField($modelSub, 'phonenumber', array('class' => 'form-control intl-tel-input', 'placeholder' => 'Mobile', 'type' => 'tel', 'id' => 'mobile-number', 'autocomplete' => 'off')); ?>                            
                        <?php echo $form->error($modelSub, 'phonenumber'); ?>
                        <br><br>
                        <?php echo $form->hiddenField($modelSub, 'nid', array('value' => $nid, 'id' => 'nid')); ?>
                        <?php echo $form->hiddenField($modelSub, 'type', array('value' => $type, 'id' => 'type')); ?>
                        <?php
                        if (!empty($modelSub->email)) {
                            echo $form->hiddenField($modelSub, 'email', array('class' => 'form-control'));
                        }
                        ?>
                        <?php // echo CHtml::submitButton('Sign Up for Free', array('class' => 'save btn btn-primary btn-primary-lg')); ?>
                        <button class="btn btn-primary btn-primary-lg" id="signup">Create Free Account</button>
                        <br>
                        <h5>No Credit Card required</h5>

                        <h5>By signing-up you agree to <a href="<?php echo Yii::app()->urlManager->createUrl('account/terms'); ?>" target="_blank">Terms &amp; Conditions</a></h5>
                    </div>
                </div>
                <?php $this->endWidget(); ?>

            </div>


        </div>
    </div>
</div>
<!-- end of sign up content -->

<script>
    $('#signup').on('click', function (event) {
        event.preventDefault();

        if ($('#emailid').val()) {
            var email = $('#emailid').val();
            //console.log(email);
            console.log('1');
//            $.ajax({
//                //console.log('1');
//                type: 'POST',
//                url: $('#completePath').text() + '/ajax/CheckEmail',
//                data: {
//                    email: email
//                },
//                success: function (data) {
//                    if (data == 1) {
//                        $('#emailid').focus();
//                        $('#emailid').attr('placeholder', 'Email already in use.').attr('style', 'background-color:rgb(218, 172, 172)');
//                    } else if (data == 2) {
//
//                    }
//                }
//            });
            if ($('#companynameid').val()) {
                console.log('2 ds ' + $('#password').val());

                if ($("input[name='SubscriptionForm[password]']").val()) {
                    console.log('3');

                    if ($('#cnf_password').val()) {
                        if ($('#cnf_password').val() == $("input[name='SubscriptionForm[password]']").val()) {
                            if ($('#mobile-number').val()) {
                                console.log('4');
                                $('#vendor_subscription1').submit();
                                //return true;
                            } else {
                                $('#mobile-number').focus();
                                $('#mobile-number').attr('placeholder', 'Phone Number is required').attr('style', 'background-color:rgb(218, 172, 172)');
                                //alert('Phone Number is required');
                            }
                        } else {
                            $('#cnf_password').focus();
                            $('#cnf_password').val('');
                            $('#cnf_password').attr('placeholder', 'Confirm Password does not match your password').attr('style', 'background-color:rgb(218, 172, 172)');
                        }

                    } else {
                        $('#cnf_password').focus();
                        $('#cnf_password').attr('placeholder', 'Confirm your password').attr('style', 'background-color:rgb(218, 172, 172)');

                    }

                } else {

                    $("input[name='SubscriptionForm[password]']").focus();
                    $("input[name='SubscriptionForm[password]']").attr('placeholder', 'Password is required').attr('style', 'background-color:rgb(218, 172, 172)');

                    //alert('Password is required');
                }

            } else {
                $('#companynameid').focus();
                $('#companynameid').attr('placeholder', 'Company Name is Required').attr('style', 'background-color:rgb(218, 172, 172)');
                //alert('Company Name is Required');
            }
        } else {
            $('#emailid').focus();
            $('#emailid').attr('placeholder', 'Email is required').attr('style', 'background-color:rgb(218, 172, 172)');
            alert('Email is required');
        }
    });

    $(function () {
        $('#static_logo').removeClass('logo-on-darkbcg');
        $('#static_logo').addClass('logo-on-lightbcg');


        $('#password').val('');
        $('#mobile-number').val('');
        $('li.phone1').css({
            "margin-top": "15px",
            "font-size": "16px",
            "font-weight": "600",
            "margin-right": "10px"
        });
        $('#header_nav').removeClass('navbar-dark');
    });
    
    $(document).ready(function() {
        window.phnum=null;
        window.phcheck=false;

        $('#mobile-number').keydown(function(e){
            console.log(e.which);
            if (window.phcheck==true && e.which!= 8 && e.which!= 46 && e.which!= 13){
             console.log("got in");
                e.preventDefault();
            }
           var title = $('#mobile-number').val();
           //console.log(title.length);

           if (title.length >= 16 && e.which!= 8 && e.which!= 46 && e.which!= 13){
             window.phcheck=true;
            e.preventDefault();
             console.log("disabled");
           } 
            
            if(e.which!= 8 && e.which!= 46){
                window.phcheck=false;
            }           

        });

//        $('#mobile-number').keydown(function(e){
//            if (window.phcheck==true){
//                e.preventDefault();
//            }
//           var title = $('#mobile-number').val();
//           console.log(title.length);
//
//           if (title.length >= 16 ){
//             window.phcheck=true;
//            e.preventDefault();
//
//           } 
//
//        });

    });
    
    $(function () {

//autocomplete for company name in vendor subscription form
        var allVendorJson = JSON.parse('<?php echo $vendorList; ?>');
//console.log(allVendorJson);
        $('.companyname').autocomplete({
            source: allVendorJson,
            select: function (event, ui) {
                console.log(ui.item.value + ', ' + ui.item.id);
                $("#vendor-ac-id").val(ui.item.id);
            },
            change: function (event, ui) {
                if (ui.item == null) {
                    //$(".companyname").val('');
                    $("#vendor-ac-id").val('');
                    $(".companyname").focus();
                }
            },
            messages: {
                noResults: '',
                results: function () {
                }
            },
        })
    });
</script>