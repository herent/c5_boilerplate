<?php
defined('C5_EXECUTE') or die("Access Denied.");
$this->inc('elements/head_elements.php');
$this->inc('elements/header_main.php');
?>
<div class="content-wrap">
	<?php 
	// this is required for all themes. It will be used for single pages and 
	// for page types that do not have a corresponding template in the theme.
	print $innerContent; ?>
     <div class="clearfix"></div>
</div>
<?php 
$this->inc('elements/footer.php');
?>
