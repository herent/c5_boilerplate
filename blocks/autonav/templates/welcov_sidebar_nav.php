<?php

defined('C5_EXECUTE') or die("Access Denied.");
$aBlocks = $controller->generateNav();
$c = Page::getCurrentPage();
$containsPages = false;

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
          $childrenHidden = 1;
     } else { 
          $childrenHidden = 0;
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
          if ($thisLevel > $lastLevel) {
               echo("<ul>");
          } else if ($thisLevel < $lastLevel) {
               if (!$lastWasHome) {
                    for ($j = $thisLevel; $j < $lastLevel; $j++) {
                         if ($lastLevel - $j > 1) {
                              echo("</li></ul>");
                         } else {
                              echo("</li></ul></li>");
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
          $spanText = "";
          for ($i = 1; $i <= $thisLevel; $i++) {
              $spanText .= "-"; 
          }
          $spanText = "<span>" . $spanText ." </span>";
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
               $spanText = "";
               echo '<li class="' . $isFirstClass . ' ' . $navSelected .'">';

               if ($c->getCollectionID() == $_c->getCollectionID()) {
                    echo('<a class="selected" href="' . $pageLink . '"  ' . $target . '>' . $spanText .  $ni->getName() . '</a>');
               } elseif (in_array($_c->getCollectionID(), $selectedPathCIDs) && ($_c->getCollectionID() != HOME_CID)) {
                    if ($childrenHidden){
                         echo('<a class="selected" href="' . $pageLink . '"  ' . $target . '>' . $spanText .  $ni->getName() . '</a>');
                    } else {
                         echo('<a class="nav-selected" href="' . $pageLink . '"  ' . $target . '>' . $spanText .  $ni->getName() . '</a>');
                    }
               } else {
                    echo('<a href="' . $pageLink . '"  ' . $target . '>' . $spanText .  $ni->getName() . '</a>');
               }

               $lastLevel = $thisLevel;
               $i++;
               $isFirst = false;
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