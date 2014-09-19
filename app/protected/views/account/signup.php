<!-- sign up content -->
<div class="row signup-content">
    <div class="col-md-12">
        <div class="signup-content-headings">
            <h1>Complete OOH Monitoring &amp; Proof Image Solution</h1>
            <h3>Start Using It With <span class="emphasis-text">100 Free Photos!</span> Sign-up in Seconds.</h3>
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-6">
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
                    'action' => 'createvendor',
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
                <?php echo $form->emailField($modelSub, 'email', array('class' => 'form-control', 'placeholder' => 'Email', 'type' => 'email')); ?>                            
                <?php echo $form->error($modelSub, 'email'); ?>
<!--                        <input type="text" class="form-control" placeholder="Company Name">-->
                <?php echo $form->textField($modelSub, 'companyname', array('class' => 'form-control ', 'placeholder' => 'Company Name')); ?> 
                <input type="hidden" value="" id="vendor-ac-id">
                <?php echo $form->error($modelSub, 'companyname'); ?>
<!--                        <input type="text" class="form-control" placeholder="+91">-->
<!--                        <input  class="form-control intl-tel-input" placeholder="Mobile" id="mobile-number" type="tel">-->
                <?php echo $form->textField($modelSub, 'phonenumber', array('max-length' => '10', 'class' => 'form-control intl-tel-input', 'placeholder' => 'Mobile', 'type' => 'tel', 'id' => 'mobile-number')); ?>                            
                <?php echo $form->error($modelSub, 'phonenumber'); ?>
                <br><br>
                <?php echo $form->hiddenField($modelSub, 'nid', array('value' => $nid, 'id' => 'nid')); ?>
                <?php echo $form->hiddenField($modelSub, 'type', array('value' => $type, 'id' => 'type')); ?>
                <?php // echo CHtml::submitButton('Sign Up for Free', array('class' => 'save btn btn-primary btn-primary-lg')); ?>
                <button class="btn btn-primary btn-primary-lg">Sign Up for Free</button>
                <br>
                <h5>No Credit Card required</h5>

                <h5>By signing-up you agree to <a href="<?php echo Yii::app()->urlManager->createUrl('account/terms'); ?>" target="_blank">Terms &amp; Conditions</a></h5>
                <?php $this->endWidget(); ?>

            </div>
            <div class="col-md-6 col-sm-6">
                <div class="value-propositions">
                    <h3>Certified Images</h3>
                    <p>Never be accused of false images again</p>

                    <h3>Easy, Automatic Reporting</h3>
                    <p>All reports are generated instantly</p>

                    <h3>Flexible, Anyone can use</h3>
                    <p>Vendor, agencies and third-parties can collect certified images</p>

                    <h3>One-stop Solution</h3>
                    <p>All your campaigns in one place (peace of mind)</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end of sign up content -->
<script>
    $(function () {
        $('li.phone1').css({
            "margin-top": "15px",
            "font-size": "16px",
            "font-weight": "600",
            "margin-right": "10px"
        });
        $('#header_nav').removeClass('navbar-dark');
    });
</script>