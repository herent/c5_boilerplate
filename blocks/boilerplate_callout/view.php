<?php defined('C5_EXECUTE') or die("Access Denied."); ?>
<article class="callout<?= $hasImage;?> image-top">
     <?php
     if (intval($fID) > 0):
          $image = $controller->get_image_object($fID, 168, 108, true);
          ?>
          <div class="callout-image-wrap">
               <?php if ($includeLink) { ?>
               <a href="<?= $url; ?>">
               <img 
                    src="<?php echo $image->src; ?>" 
                    width="<?php echo $image->width; ?>" 
                    alt="<?php echo htmlentities($title, ENT_QUOTES, APP_CHARSET); ?>" 
                    class="callout-image"/>
               </a>
               <?php } else { ?>
               <img 
                    src="<?php echo $image->src; ?>" 
                    width="<?php echo $image->width; ?>" 
                    alt="<?php echo htmlentities($title, ENT_QUOTES, APP_CHARSET); ?>" 
                    class="callout-image"/>
               <?php } ?>
          </div>
          <?php endif; ?>
          <div class="callout-details">
               <?php if ($includeLink) { ?>
               <h3 class="callout-title">
                    <a href="<?= $url; ?>">
                    <?php echo htmlentities($title, ENT_QUOTES, APP_CHARSET); ?>
                    </a>
               </h3>
               <?php } else { ?>
               <h3 class="callout-title">
				<?php echo htmlentities($title, ENT_QUOTES, APP_CHARSET); ?>
			</h3>
               <?php } ?>
			<div class="callout-content-wrap">
			<?php echo $content; ?>
			</div>
		</div>
     <div class="clearfix"></div>
</article>
<div class="clearfix"></div>