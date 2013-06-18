<?php defined("C5_EXECUTE") or die("Access Denied."); ?>
<div class="footer">
     <div id="footer-content">
          <div class="inner">
               <h1 id="logo">
                    <a href="/"><?php echo SITE; ?></a>
               </h1>
               <nav id="footer-main-nav">
                    <?php
//                    $globalNav = Stack::getByName('Embedded Footer Nav');
//                    $globalNav->display();
                    ?>
                    <div class="clearfix"></div>
               </nav>
          </div>
		<div id="footer-column-1" class="footer-column">
			<?php
                    $a7 = new GlobalArea("Footer Message");
                    $a7->setBlockLimit(1);
                    $a7->display($c);
                    ?>
			</div>
          <div class="clearfix"></div>
     </div>
     <div class="clearfix"></div>
</div>
<div class="clearfix"></div>
</div>
<?php
Loader::element('footer_required');
//$tool_helper = Loader::helper('concrete/urls');
?>
<!--[if lt IE 7 ]>
<script src="<?php echo $this->getThemePath() ?>/js/dd_belatedpng.js"></script>
<script> DD_belatedPNG.fix('img, .png_bg'); </script>
<![endif]-->
<script src="<?php echo $this->getThemePath(); ?>/js/respond.min.js"></script>
</body>
</html>