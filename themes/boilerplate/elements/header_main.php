<?php defined("C5_EXECUTE") or die("Access Denied."); ?>
<header>
	<?php $blocks = $c->getBlocks('Header Intro Content');
	if (count($blocks) > 0 || $c->isEditMode()) {
		?>
	     <div id="header-intro-content">
		<?php } else { ?>
		<div id="header-intro-content" class="no-blocks">    
			<?php } ?>
			<?php
			$a = new Area("Header Intro Content");
			$a->display($c);
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