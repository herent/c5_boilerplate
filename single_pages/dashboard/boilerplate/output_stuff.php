<?php
defined("C5_EXECUTE") or die(_("Access Denied."));
$ih = Loader::helper('concrete/interface');
$dbh = Loader::helper('concrete/dashboard');
$url = Loader::helper('concrete/urls');

echo $dbh->getDashboardPaneHeaderWrapper(t('Output Stuff'), false, false, false);
?>
<div class='ccm-pane-body'>
	<h1><?= t("About This Page");?></h1>
	<p>
		<?= t("This page has been created to show how the general workflow of 
			using page controllers, views, tools files, and elements together 
			is done in concrete5. Please check the source code to understand 
			what is happening.");?>
	</p>
	<div id="boilerplate-results-wrap">
	<?php Loader::element('boilerplate/output_stuff', array('stuff' => $stuff), 'c5_boilerplate'); ?>
	</div>
</div>
<div class="ccm-pane-footer">
	<?php
	print $ih->button_js( 
		   t('Load New Stuff'), 
		   'reloadStuff()', 
		   'right', 
		   'btn-success');
	?>
	<div class="clearfix" style="padding: 0 !important;"></div>
</div>
<?php
echo Loader::helper('concrete/dashboard')->getDashboardPaneFooterWrapper(false);?>
<script type="text/javascript">
	var REPLACE_STUFF_URL = '<?= $url->getToolsURL('boilerplate/replace_stuff', 'c5_boilerplate');?>';
	
	function reloadStuff() {
		$.get(REPLACE_STUFF_URL, function(res){
			$("#boilerplate-results-wrap").html(res);
			
		});
		return false;
	}
</script>