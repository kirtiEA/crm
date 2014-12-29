<!--HEADER-->
    <?php include_once "header.php"; ?>
<!--HEADER-->

<!--main content start-->
    <div class="container-fluid content-wrapper">
        <!--CONTENT-->
            <?php echo $content; ?>
        <!--CONTENT_END-->
        
        <!--FOOTER-->
            <?php 
			if(Yii::app()->controller->id == 'account' && Yii::app()->controller->action->id == 'signup') {
				// do nothing
			} else {
				//include_once "footer.php";
			}
			?>
        <!--FOOTER_END-->
        
    </div>
<!--main content end-->

