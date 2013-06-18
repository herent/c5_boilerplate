<?php
defined('C5_EXECUTE') or die("Access Denied.");
$this->inc('elements/head_elements.php');
$this->inc('elements/header_main.php');
?>
<div class="content-wrap">
     <div id="main" class="column">
          <?php
          $ih = Loader::helper("image");
          $nh = Loader::helper('navigation');
          $textHelper = Loader::helper('text');
          if (count($pages) > 0) {
               foreach ($pages as $cobj) {
                    $title = $textHelper->entities($cobj->getAttribute('hutman_news_headline'));
                    $thumb = $cobj->getCollectionAttributeValue('hutman_news_thumbnail');
                    $byline = $cobj->getAttribute('hutman_news_byline');
                    ?>
                    <div class="news-item">

                              <?php if ($thumb) { ?>
                              <div style="width: 120px; float: left;">
                              <?php $ih->outputThumbnail($thumb, 120, 200, $title); ?>
                              </div>
                         <?php } ?>

                              <?php if ($thumb) { ?>
                              <div style="margin-left: 130px">
                              <?php } ?>
                              <?php
                              $co = new Config;
                              $pkg = Package::getByHandle('hutman_news');
                              $co->setPackageObject($pkg);
                              $dateFormat = $co->get("HUTMAN_NEWS_DATE_FORMAT");
                              ?>
                              <h3 class="article-title"><?php echo $title ?></h3>
                              <?php if (strlen($byline) > 0) { ?>
                                   <h4 class="article-byline"><?php echo $byline ?> &ndash; <?php echo date($dateFormat, strtotime($cobj->getAttribute('hutman_news_date'))); ?></h4>
                              <?php } else { ?>
                                   <h4 class="article-byline"><?php echo date($dateFormat, strtotime($cobj->getAttribute('hutman_news_date'))); ?></h4>
                                 <?php } ?>
                                 <?php echo $cobj->getAttribute('hutman_news_short_story'); ?>
                              <a class="more-link" <?php if ($target != '') {
                                      ?> target="<?php echo $target ?>" <?php } ?> href="<?php echo $nh->getLinkToCollection($cobj) ?>">&gt;&nbsp;Read More</a>
          <?php if ($thumb) {
               ?>
                              </div>
                              <div class="clearfix"></div>
                    <?php } ?>
                    </div>
                    <?php
               }
               $pl->displayPaging();
          } else {
               ?>
               <h2><?php echo t('No Articles Found'); ?></h2>
          <?php } ?>
     </div>
     <div id="sidebar" class="column">
          <?php
          if (count($archivePages) > 0) {
               $bDate = array();
               $years = array();
               for ($i = 0; $i < count($archivePages); $i++) {
                    $cobj = $archivePages[$i];
                    $bDate[] = $cobj->getAttribute('hutman_news_date');
               }
               $dMax = date('Y', strtotime(max($bDate)));
               $dMin = date('Y', strtotime(min($bDate)));
               $dDiff = $dMax - $dMin;

               for ($i = 0; $i <= $dDiff; $dMin = $dMin + 1) {
                    $years[] = $dMin;
                    $i++;
               }
               $outYears = array();
               foreach ($years as $year) {
                    $haveYear = 0;
                    $yearDate = $year;
                    for ($s = 0; $s < count($bDate); $s++) {
                         $bDateFormatted = date('Y', strtotime($bDate[$s]));
                         if ($bDateFormatted == $yearDate) {
                              if (!$haveYear) {
                                   $haveYear = 1;
                                   $outYears[] = $bDateFormatted;
                              }
                         }
                    }
               }
               if (!strlen($currentYear)>0){
                    $currentYear = date("Y");
               }
               $months = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
               $nh = Loader::helper('navigation');
               $cURL = $nh->getLinkToCollection($c);
               ?>
               <div class="sidebar-block">
                    <div class="inner archive-links">
                         <h3><?php echo t("Sort by Date");?></h3>
                         <?php
                         foreach ($outYears as $year) {
                              echo '<ul class="archive-year">';
                              echo '<li id="year_' . $year . '_wrap"';
                              if ($year == $dMax) {
                                   echo 'class="open"';
                              };
                              echo ">";
                              echo '<a class="year-toggle" onclick="javascript: $(\'.year-month-wrap\').hide(\'fast\'); $(\'#year_' . $year . '\').show(\'fast\');';
                              echo '$(\'#year_' . $year . '_wrap\').toggleClass(\'open\');';
                              echo '" style="cursor: pointer;">&gt;&ensp;' . $year . '</a>';
                              echo '<div id="year_' . $year . '" class="year-month-wrap" style="';
                              if ($year == $currentYear) {
                                   echo 'display: block;">';
                              } else {
                                   echo 'display: none;">';
                              }

                              echo "<ul>";
                              $outMonths = array();
                              foreach ($months as $month) {
                                   $haveMonth = 0;
                                   $monthDate = $year . '-' . $month;
                                   for ($s = 0; $s < count($archivePages); $s++) {
                                        $cobj = $archivePages[$s];
                                        $cDate = date('Y-m', strtotime($cobj->getAttribute('hutman_news_date')));
                                        if ($cDate == $monthDate) {
                                             if (!$haveMonth) {
                                                  $haveMonth = 1;
                                                  $outMonths[$month] = array();
                                                  $outMonths[$month]["month"] = $cDate;
                                             }
                                        }
                                   }
                              }
                              foreach ($outMonths as $month) {
                                   echo '<li id="month_' . $month['month'] . '_wrap"';
                                   if (date('Y-m') == $month['month']) {
                                        echo 'class="open">';
                                   } else {
                                        echo '>';
                                   }
                                   if ($month['month'] == $currentMonth){
                                   echo '<span>&gt;&ensp;' . date('F', strtotime($month['month'])) . '</span>';
                                   } else {
                                        $link = $this->url($cURL, 'filter_by_month', $month['month']);
                                        echo '<a href="' . $link . '">&gt;&ensp;' . date('F', strtotime($month['month'])) . '</a>';     
                                   }
                                   echo '</li>';
                              }
                              echo '</ul></li></ul>';
                         }
                         echo '</li></ul>';
                         ?>     
                    </div>
                    <input type="hidden" id="monVal" value="1"/>
               </div>
                    <?php } ?>
          <div class="sidebar-block">
               <div class="inner filter-links">
                    <h3><?php echo t("Sort By Topic"); ?></h3>
                    <?php
                    foreach ($options as $option) {
                         if ($currentTag == $option) {
                              ?>
                              <span class="selected-filter">&gt;&nbsp;<?php echo($option); ?></span>
                         <?php } else { ?>
                              <a href="<?php echo $this->action('filter_tags', urlencode($option)) ?>">&gt;&nbsp;<?php echo($option); ?></a>
                                   <?php
                              }
                         }
                         ?>
               </div>
          </div>
     </div>
     <div class="clearfix"></div>
</div>
<?php
$this->inc('elements/footer.php');
?>