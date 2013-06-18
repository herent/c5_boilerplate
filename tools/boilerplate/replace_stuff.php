<?php

defined("C5_EXECUTE") or die(_("Access Denied."));

$cnt = Loader::controller("/dashboard/boilerplate/output_stuff");
if (is_object($cnt)){
	$stuff = $cnt->getMyStuff(t("Item One ") . uniqid(), t("Item Two ") . uniqid());
	Loader::element('boilerplate/output_stuff', array('stuff' => $stuff), 'c5_boilerplate');
}