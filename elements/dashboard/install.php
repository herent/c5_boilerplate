<?php
defined("C5_EXECUTE") or die(_("Access Denied."));
$form = Loader::helper('form');
?>
<div class="horizontal">
     <h2><?php echo t("Boilerplate Installation"); ?></h2>
     <p>
		<?php echo t("You can add notices to your users here about the package you 
			are creating. You may also add in form fields. No actual form tag is 
			required, but whatever fields are added here will be submitted to the
			package controller's install function."); ?><br /><br />
		<?php echo t("To customize this screen, edit the file /elements/dashboard/install.php");?>
     </p>
     <div class="control-group">
		<label for="boilerpateOption" class="control-label">
			<?php echo t("Boilerplate Option"); ?>
			<a id="boilerplate-help" 
			   href="javascript:void(0)"
			   data-toggle="popover"
			   data-placement="right"
			   data-original-title="<?php echo t("Boilerplate Option"); ?>"
			   data-content="<?php
			   echo
			   t("This is the help popup text. It could have anything you want.");
			   ?>"
			   class="boilerplate-options-help">
				<i class="icon-question-sign"></i>
			</a>
		</label>
          <div class="controls">
<?php echo $form->text("boilerpateOption", "", array("class" => "span4")); ?>
          </div>
     </div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$(".boilerplate-options-help")
			   .popover({html: true})
			   .click(function(e) {
			e.stopPropagation();
		});
	});
</script>