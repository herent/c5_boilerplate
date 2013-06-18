<?php

defined("C5_EXECUTE") or die(_("Access Denied."));

class DashboardBoilerplateBoilerplateSubController extends Controller {
	
	public function view() {
		$stuff = $this->getMyStuff(t("Item One"), t("Item Two"));
		$this->set("stuff", $stuff);
	}
	
	/*
	 * Returns stuff in an array
	 * 
	 * @param string $itemOne First element for the array
	 * @param string $itemTwo Second element for the array
	 * 
	 * @return array
	 */
	public function getMyStuff($itemOne = "", $itemTwo = "") {
		$stuff = array(
		    'itemOne' => $itemOne,
		    'itemTwo' => $itemTwo
		);
		return $stuff;
	}
	
}