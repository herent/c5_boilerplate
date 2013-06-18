<?php

defined("C5_EXECUTE") or die(_("Access Denied."));

if (is_array($args['stuff'])){
	$stuff = $args['stuff'];?>
<h3><?= t("This is the stuff:");?></h3>
<ul>
	<li><?= $stuff['itemOne'];?></li>
	<li><?= $stuff['itemTwo'];?></li>
</ul>

<?php } else {
	$e = Loader::helper('validation/error');
	$e->add(t("You must supply an array called stuff"));
	// this is new in 5.6.1.2 I think. It won't work for many older sites
	Loader::element("system_errors", array('error' => $e));
}