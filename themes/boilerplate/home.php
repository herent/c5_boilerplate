<?php
defined('C5_EXECUTE') or die("Access Denied.");
$this->inc('elements/head_elements.php');
$this->inc('elements/header_blank.php');?>
<div class="content-wrap home">
	<?php $a = new Area('Main');
	$a->display($c);?>
</div>
<?php 
$this->inc('elements/footer.php');
?>
