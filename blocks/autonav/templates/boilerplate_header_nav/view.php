<?php

defined('C5_EXECUTE') or die("Access Denied.");
$aBlocks = $controller->generateNav();
$c = Page::getCurrentPage();
$containsPages = false;

$ih = Loader::helper('image');
$nh = Loader::helper('navigation');

$isFirst = true;
//this will create an array of parent cIDs 
$inspectC = $c;
$excludedCIDs = array();
$selectedPathCIDs = array($inspectC->getCollectionID());
$parentCIDnotZero = true;
$lastWasHome = 0;
while ($parentCIDnotZero) {
     $cParentID = $inspectC->cParentID;
     if (!intval($cParentID)) {
          $parentCIDnotZero = false;
     } else {
          $selectedPathCIDs[] = $cParentID;
          $inspectC = Page::getById($cParentID);
     }
}
$firstSubLink = 1;
foreach ($aBlocks as $ni) {
     $_c = $ni->getCollectionObject();
     $childrenArray = $_c->getCollectionChildrenArray();
     if ($_c->getCollectionAttributeValue('exclude_nav')) {
          // Get an array of children cIDs to block from subsequent loops
          $excludedCIDs = array_merge($childrenArray, $excludedCIDs);
          $excludedCIDs[] = $_c->getCollectionID(); // Add current cID to excluded array
          continue; // Move to next page
     }
     // this only works if you have the exclude_children attribute defined
     if ($_c->getCollectionAttributeValue('exclude_children')) {
          // Get an array of children cIDs
          $excludedCIDs = array_merge($childrenArray, $excludedCIDs);
     }
     // make sure our page isn't excluded.  We're using an array
     // to keep from having 'orphaned' pages show up when pages are excluded
     // from nav but have children pages in the nav array
     if (!in_array($_c->getCollectionID(), $excludedCIDs)) {

          $target = $ni->getTarget();
          if ($target != '') {
               $target = 'target="' . $target . '"';
          }

          if (!$containsPages) {
               // this is the first time we've entered the loop so we print out the UL tag
               echo("<ul>");
          }

          $containsPages = true;

          if ($ni->isActive($c) || strpos($c->getCollectionPath(), $_c->getCollectionPath()) === 0) {
               $navSelected = 'nav-selected';
          } else {
               $navSelected = '';
          }

          $thisLevel = $ni->getLevel();
          $subNav = "";
          if ($thisLevel === 0){
               $firstSubLink = 1;
          }
          if ($thisLevel > $lastLevel) {
               if ($thisLevel > 1){
//               echo("<ul>");
                    $subNav = "";
                    continue;
               } else {
                    if ($thisLevel === 1 && $firstSubLink){
                          
                         $subNav = "sub-nav-link";
                         $_cParent = Page::getByID($_c->getCollectionParentID());
                         $thumbFO = $_cParent->getAttribute('dropdown_thumbnail');
                         $desc = $_cParent->getAttribute('dropdown_text');
                         $excludeChildren = $_cParent->getCollectionAttributeValue('exclude_children');
                         if (!$excludeChildren) {
                              echo "<div class='top-menu-sub-nav hidden'>";
                              echo "<div class='green-bar'></div><div class='inner'>";
                              echo "<div class='intro'><div class='thumb-wrap'>" . $ih->outputThumbnail($thumbFO, 195, 124, null, true, true) . "</div>";
                              echo "<div class='description'>" . $desc . "</div><div class='clear'></div></div>";
                              echo "<div class='sub-nav-links-wrap'><ul class='sub-nav-links-list'>";
                         } 
                         $firstSubLink = 0;
//                         foreach($globalServices as $serviceID => $service){
//                              $service = Service::getByID($serviceID);
//                              $title = $service->getTitle();
//                              $handle = $service->getHandle();
//                              echo "<li><a class='sub-nav-link' href='" . $pageLink . $handle . "'>" . $title . "</a></li>";
//                         }
//                         echo "</ul><div class='clear'></div></div>";
//                         echo "</div><div class='clear'></div><div class='services-green-bar'></div>";
//                         echo "</div>";
                    } else {
                         $subNav = "";
                         $_cParent = null;
                         $thumbFO = null;
                         $desc = null;
                         
                    }
               }
          } else if ($thisLevel < $lastLevel) {
               if (!$lastWasHome) {
                    for ($j = $thisLevel; $j < $lastLevel; $j++) {
                         if ($lastLevel - $j > 1) {
                              echo("</li></ul>");
                         } else {
                              $firstSubLink = 1; 
                              $_cParent = Page::getByID($_c->getCollectionParentID());
                              if (!$_cParent->getCollectionAttributeValue('exclude_children')) {
                                   echo("</li></ul><div class='clear'></div></div>");
                                   echo "</div><div class='clear'></div><div class='green-bar'></div>";
                                   echo "</div>";
                              } else {
                                   $firstSubLink = 1;
                                   echo("</li></ul></li>");
                              }
                         }
                    }
               }
          } else if ($i > 0) {
               if (!$lastWasHome) {
                    echo("</li>");
               }
          }
          if ($lastWasHome) {
               $lastWasHome = 0;
          }
          $pageLink = false;

          if ($_c->getCollectionAttributeValue('replace_link_with_first_in_nav')) {
               $subPage = $_c->getFirstChild();
               if ($subPage instanceof Page) {
                    $pageLink = $nh->getLinkToCollection($subPage);
               }
          }

          if (!$pageLink) {
               $pageLink = $ni->getURL();
          }



          if ($isFirst){
               $isFirstClass = 'first';
          } else {
               $isFirstClass = '';
          }

          if ($_c->getCollectionID() == HOME_CID) {
               $lastWasHome = 1;
          } else {
               if (!$isFirst && !($thisLevel === 1)){
                    echo "<li class='divider'>|</li>";
               }
               $cTypeHandle = $_c->getCollectionTypeHandle();
               if ($thisLevel === 0){
                    echo '<li class="top-level ' . $isFirstClass . ' ' . $navSelected . ' ' . $cTypeHandle .'">';
               } else {
                    echo '<li class="' . $isFirstClass . ' ' . $navSelected . ' ' . $cTypeHandle .'">';
               }
               if ($c->getCollectionID() == $_c->getCollectionID()) {
                    echo('<a class="current' . $subNav . '" href="' . $pageLink . '"  ' . $target . '>' . $ni->getName() . '</a>');
               } elseif (in_array($_c->getCollectionID(), $selectedPathCIDs) && ($_c->getCollectionID() != HOME_CID)) {
                    echo('<a class="current path-selected ' . $subNav . '" href="' . $pageLink . '"  ' . $target . '>' . $ni->getName() . '</a>');
               } else {
                    echo('<a class="' . $subNav . '" href="' . $pageLink . '"  ' . $target . '>' . $ni->getName() .'</a>');
               }

               $lastLevel = $thisLevel;
               $i++;
               $isFirst = false;
          }
          if ($cTypeHandle == "services_list"){
               global $autonavSelectedService;
               if (is_a($autonavSelectedService, "Service")){
                    $gs = $GLOBALS['selectedService'];
                    $selectedServiceID = intval($autonavSelectedService->getServiceID());
                    $highlightSelected = 1;
               } else {
                    $highlightSelected = 0;
               }
               $firstSubLink = 1; 
               $thumbFO = $_c->getAttribute('dropdown_thumbnail');
               $desc = $_c->getAttribute('dropdown_text');
               Loader::model('service', 'community_management');
               $globalServices = Service::getGlobalServicesArray();
               
               echo "<div class='top-menu-sub-nav hidden'>";
               echo "<div class='green-bar'></div><div class='inner'>";
               echo "<div class='intro'><div class='thumb-wrap'>" . $ih->outputThumbnail($thumbFO, 195, 124, null, true, true) . "</div>";
               echo "<div class='description'>" . $desc . "</div><div class='clear'></div></div>";
               echo "<div class='sub-nav-links-wrap'><ul class='sub-nav-links-list'>";
               foreach($globalServices as $serviceID => $service){
                    $service = Service::getByID($serviceID);
                    $title = $service->getTitle();
                    $handle = $service->getHandle();
                    if ($highlightSelected && $serviceID == $selectedServiceID){
                         echo "<li class='nav-selected'><a class='current' href='" . $pageLink . $handle . "'>" . $title . "</a></li>";
                    } else {
                        echo "<li><a href='" . $pageLink . $handle . "'>" . $title . "</a></li>"; 
                    }
               }
               echo "</ul><div class='clear'></div></div>";
               echo "</div><div class='clear'></div><div class='green-bar'></div>";
               echo "</div>";
               
          }
     }
}

$thisLevel = 0;
if ($containsPages) {
     for ($i = $thisLevel; $i <= $lastLevel; $i++) {
          echo("</li></ul>");
     }
}
?>