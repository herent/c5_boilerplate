<?php defined('C5_EXECUTE') or die("Access Denied.");

$form = Loader::helper('form');
?>

<form enctype="multipart/form-data"  class="contact-form" method="post" action="<?php echo $this->action('do_contact') ?>">
	<h3 class="gray">Send us a message:</h3>
	<div class="fields">
		<div class="field field-text">
			<label for="yourname">Your name</label>
			<?php echo $form->text('fullname');?>
		</div>
		<div class="field field-text">
			<label for="phone">Your phone number</label>
			<?php echo $form->text('phone');?>
		</div>
		<div class="field field-text">
			<label for="email">Your email address</label>
			<?php echo $form->text('email');?>
		</div>
		<div class="field field-textarea">
			<label  for="comment">Your comment</label>
			<?php echo $form->textarea('comment');?>
		</div>
<!--
		<div class="field field-checkbox">
			<span class="checkbox-wrap"><?php echo $form->checkbox('signUp', "Yes, please sign me up");?></span>
			Sign up to receive news, event announcements, and special promotions
		</div>
-->
		<div class="field field-submit">
			<span class="required">All fields are required.</span>
               <br /><br />
               <input type="submit" name="Submit" class="submit" value="Send" />
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
