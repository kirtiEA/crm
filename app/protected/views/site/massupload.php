<?php
/* @var $this SiteController */

$this->breadcrumbs=array(
	'Site'=>array('/site'),
	'Massupload',
);
?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>

<p>
	You may change the content of this page by modifying
	the file <tt><?php echo __FILE__; ?></tt>.
</p>
<script type="text/javascript">
    $(function(){
        $('.mon_menu').each(function(){
            $(this).removeClass('active');
        });
        $('.menu_site_mass').addClass('active');
    });
</script>