<?php

defined("C5_EXECUTE") or die(_("Access Denied."));

/* 
 * The contents of this file will be used by view.php in themes that do not have
 * a boilerplate.php template.
 * 
 * I like to make sure that $c is the current page, someone could overrite it 
 * elsewhere if you are just relying on the global
 */
$c = Page::getCurrentPage();
?>
<h1><?= $c->getCollectionName();?></h1>
<?php
$a = new Area("Boilerplate Content");
$a->display($c);
?>