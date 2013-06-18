<?php

defined("C5_EXECUTE") or die(_("Access Denied."));

class DashboardBoilerplateController extends Controller {

	// this is to make the display on the main dashboard page look nicer
	// otherwise, it would show boilerplate as the title, then "home" as the
	// link underneath that. redirecting gives more control
	public function view() {
		$this->redirect('/dashboard/boilerplate/boilerplate_sub');
	}

}