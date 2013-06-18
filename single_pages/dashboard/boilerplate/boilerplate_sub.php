<?php
defined("C5_EXECUTE") or die(_("Access Denied."));
$ih = Loader::helper('concrete/interface');
$dbh = Loader::helper('concrete/dashboard');
$url = Loader::helper('concrete/urls');

echo $dbh->getDashboardPaneHeaderWrapper(t('Boilerplate Sub'), false, false, false);
?>
<div class='ccm-pane-body'>
	<div id="boilerplate-results-wrap">
	<?php Loader::element('boilerplate/output_stuff', 'c5_boilerplate'); ?>
	</div>
</div>
<div class="ccm-pane-footer">
	<?php
	print $ih->button_js( 
		   t('Reload Stuff') . "&nbsp;<i class='icon-plus icon-white'></i>", 
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