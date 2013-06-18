<?php defined('C5_EXECUTE') or die("Access Denied.");

/*
 * Customize this form for whatever you need it to do.
 * These are typically for one
 */

$form = Loader::helper('form');
?>

<form enctype="multipart/form-data"  
	 class="contact-form" 
	 method="post" 
	 action="<?php echo $this->action('do_contact') ?>">
	<h3 class="gray"><?= t("Send us a message:");?></h3>
	<div class="fields">
		<div class="field field-text">
			<label for="yourname"><?= t("Your name");?></label>
			<?php echo $form->text('fullname');?>
		</div>
		<div class="field field-text">
			<label for="phone"><?= t("Your phone number");?></label>
			<?php echo $form->text('phone');?>
		</div>
		<div class="field field-text">
			<label for="email"><?= t("Your email address");?></label>
			<?php echo $form->text('email');?>
		</div>
		<div class="field field-textarea">
			<label  for="comment"><?= t("Your comment");?></label>
			<?php echo $form->textarea('comment');?>
		</div>
		<div class="field field-submit">
			<span class="required"><?= t("All fields are required.");?></span>
               <br /><br />
               <input type="submit" name="Submit" class="submit" value="<?= t("Send");?>" />
		</div>
		<div class="field field-message">
<?php
if (isset($error) && $error != '') {
	if ($error instanceof Exception) {
		$_error[] = $error->getMessage();
	} else if ($error instanceof ValidationErrorHelper) {
		$_error = $error->getList();
	} else if (is_array($error)) {
		$_error = $error;
	} else if (is_string($error)) {
		$_error[] = $error;
	}
	?>
<ul class="red">
<?php foreach($_error as $e) echo('<li>'.$e.'</li>'."\n"); ?>
</ul>
<?php
}
if (isset($response)) echo('<span class="red">'.$response.'</span>'); ?>
		</div>
	</div>
</form>
