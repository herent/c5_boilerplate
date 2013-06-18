<?php defined("C5_EXECUTE") or die("Access Denied."); ?>
<header>
	<?php $blocks = $c->getBlocks('Header Intro Content');
	if (count($blocks) > 0 || $c->isEditMode()) {?>
	<div id="header-intro-content">
	<?php } else { ?>
	<div id="header-intro-content" class="no-blocks">    
	<?php } ?>
		<?php
		$a = new GlobalArea("Header Intro Content");
		// in order to keep general users from editing stuff in global areas
		// this check is added. It could also check if the user is in a 
		// particular group.
		$u = new User();
		if (!$u->isSuperUser()){
			$a->disableControls();
		}
		// global areas do not need $c passed in
		$a->display();
		?>
	</div>
	<h1 id="page-title">
		<?php
		$a = new Area("Page Title - Plain Text HTML Blocks Only");
		$a->display($c);
		?>
	</h1>
	<div id="header-secondary-content">
		<?php
		$a = new Area("Header Secondary Content");
		$a->display($c);
		?>
	</div>
	<div class="clearfix"></div>
</header>