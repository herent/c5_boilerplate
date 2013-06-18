<?php
defined('C5_EXECUTE') or die("Access Denied.");
$co = new Config;
$pkg = Package::getByHandle('hutman_news');
$co->setPackageObject($pkg);
$dateFormat = $co->get("HUTMAN_NEWS_DATE_FORMAT");
$this->inc('elements/head_elements.php');
$this->inc('elements/header_news_item.php');
$co = new Config;
$pkg = Package::getByHandle('hutman_news');
$co->setPackageObject($pkg);
$dateFormat = $co->get("HUTMAN_NEWS_DATE_FORMAT");
?>
<div class="content-wrap">
     <div id="main" class="column">
          <h2 class="article-title"><?php echo $c->getAttribute('hutman_news_headline') ?></h2>
          <?php
          $byline = $c->getAttribute('hutman_news_byline');
          if (strlen($byline) > 0) {
               ?>
               <h3 class="article-byline"><?php echo $byline ?> &ndash; <?php echo date($dateFormat, strtotime($c->getAttribute('hutman_news_date'))); ?></h3>
          <?php } else { ?>
               <h3 class="article-byline"><?php echo date($dateFormat, strtotime($c->getAttribute('hutman_news_date'))); ?></h3>
          <?php } ?>
          <?php
          $a = new Area('Main');
          $a->display($c);
          ?>
               <h4 class="sharing-title"><?php echo t("Share This Blog...");?></h4>
               <div class="post-sharing-buttons">
                    <?php $nh = Loader::helper("navigation");
                    $link = $nh->getLinkToCollection($c, true);?>
                    <a href="https://twitter.com/share" class="twitter-share-button" data-url="<?= $link;?>" data-via="empowered">Tweet</a>
                    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                    <script src="//platform.linkedin.com/in.js" type="text/javascript"></script>
                    <script type="IN/Share" data-url="<?= $link;?>" data-counter="right"></script>
               </div>
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
               $cURL = $nh->getLinkToCollection($cParent);
               ?>
               <div class="sidebar-block">
                    <div class="inner archive-links">
                         <h3><?php echo t("Sort by Date"); ?></h3>
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
                                   if ($month['month'] == $currentMonth) {
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
                    $basePath = $cParent->getCollectionPath();
                    foreach ($options as $option) {
                         ?>
                         <a href="<?php echo View::url($basePath, 'filter_tags', urlencode($option)) ?>">&gt;&nbsp;<?php echo($option); ?></a>
                         <?php
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