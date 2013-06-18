<?php
defined('C5_EXECUTE') or die("Access Denied.");
$this->inc('elements/head_elements.php');
$this->inc('elements/header_main.php');
?>
<div class="content-wrap">
	<h1><?= $c->getCollectionName();?></h1>
	<h3><?= t("This is custom content because we're using a page type in the 
		boilerplate theme, instead of view.php.");?></h3>
	<?php $a = new Area('Boilerplate Content');
	$a->display($c);?>
     <div class="clearfix"></div>
</div>
<?php 
$this->inc('elements/footer.php');
?>
