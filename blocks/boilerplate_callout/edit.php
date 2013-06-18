<?php
defined('C5_EXECUTE') or die("Access Denied.");
$al = Loader::helper('concrete/asset_library');
$pageSelector = Loader::helper('form/page_selector');
Loader::element('editor_config');
?>

<style type="text/css" media="screen">
     .link-wrap {
          padding-bottom: 20px;
     }
     <?php if ($includeLink) {
          ?>
          #link-elements {
               display: block;
          }
          <?php if ($showManual) {
               ?>
               #link-url-wrap {
                    display: block;
                    padding-top: 5px;
               }
          <?php } else { ?>
               #link-url-wrap {
                    display: none;
                    padding-top: 5px;
               }
          <?php } ?>
          <?php if ($showSiteMap) { ?>
               #link-cid-wrap {
                    display: block;
                    padding-top: 5px;
               }
          <?php } else { ?>
               #link-cid-wrap {
                    display: none;
                    padding-top: 5px;
               }
          <?php } ?>
          #link-elements p {
               margin-bottom: 5px;
          }
          .small {
               font-size: 90%;
               padding-top: 5px;
               display: inline-block;
          }
          div.ccm-pane-controls .red { color: #FF0000;}
     <?php } else { ?>
          #link-elements {
               display: none;
          }
          #link-url-wrap {
               display: none;
               padding-top: 5px;
          }
          #link-cid-wrap {
               display: none;
               padding-top: 5px;
          }

          #link-elements p {
               margin-bottom: 5px;
          }
          .small {
               font-size: 90%;
               padding-top: 5px;
               display: inline-block;
          }
          div.ccm-pane-controls .red { color: #FF0000;}
          .inline {
               display: block;
               overflow: hidden;
          }
          .inline label,
          .inline input{
               display: inline-block;
               float: left;
               margin-right: 5px;
          }
          .inline label {
               line-height: 22px;
          }
     <?php } ?>
</style>
<div class="ccm-ui">
     
<div class="control-group">
     <label class="control-label">Image</label>
     <div class="controls">
          <?php echo $al->image('fID', 'fID', 'Choose Image', $fID); ?>
     </div>
</div>

<div class="control-group">
     <label class="control-label">Title</label>
     <div class="controls">
          <?php echo $form->text('title', $title, array("class" => "input-xxlarge")); ?>
     </div>
</div>

<div class="control-group">
     <label class="control-label">Content</label>
     <div class="controls">
          <?php Loader::element('editor_controls'); ?>
          <textarea id="content" name="content" class="ccm-advanced-editor"><?php echo $content; ?></textarea>
     </div>
</div>

<div class="control-group link-wrap">
     <label class="control-label checkbox" for="includeLink">
          <input type="checkbox" 
                 id="includeLink" 
                 name="includeLink" 
                 value="1" 
                 onclick="BoilerplateCallout.toggleLinkElements()"
                 
                 <?php if ($includeLink) {
                    echo " checked='checked '";
                    } ?>/>
                 <?php echo t('Link to a page?') ?>
     </label>
     <div id="link-elements" class="controls">
          <div class="ccm-input-wrap" style="display: none;">
               <label for="linkText"><?php echo t("Link Text (if applicable)"); ?></label>
<?php echo $form->text('linkText', $linkText, array('style' => 'width: 560px')); ?>
          </div>
          <div class="ccm-input-wrap">
               <label for="linkType-manual" class="radio inline">
               <input type="radio" id="linkType-manual" name="linkType" value="manual" onclick="BoilerplateCallout.toggleUrlType('manual')"<?php if ($showManual) {
                    echo " checked ";
               } ?>><?php echo(t('Manually Enter URL')) ?>
               </label>
               <label for="linkType-sitemap" class="radio inline">
               <input type="radio" id="linkType-sitemap" name="linkType" value="sitemap" onclick="BoilerplateCallout.toggleUrlType('sitemap')"<?php if ($showSiteMap) {
                    echo " checked ";
               } ?>><?php echo(t('Choose From Site Map')) ?>
               </label>
          </div>
          <div class="ccm-input-wrap">
               <div id="link-url-wrap" class="ccm-input-wrap">
<?php echo $form->text('linkURL', $linkURL, array('style' => 'width: 560px')); ?>
                    <span class="small"><?php echo(t('Should be a full url including protocol (usually http://)')) ?></span>
               </div>
               <div id="link-cid-wrap" class="ccm-input-wrap">
<?php echo $pageSelector->selectPage('linkCID', $linkCID, 'ccm_selectSitemapNode'); ?>
               </div>
          </div>
     </div>
</div>

</div>

