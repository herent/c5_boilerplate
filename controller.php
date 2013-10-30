<?php

defined('C5_EXECUTE') or die(_("Access Denied."));

class C5BoilerplatePackage extends Package {

	// this must match the handle of your directory
	protected $pkgHandle = 'c5_boilerplate';
	// make sure that you are not using functions unavailable in old versions
	// list of what you can do here:
	// http://www.concrete5.org/documentation/how-tos/developers/choose-what-c5-version-to-develop-for/
	//
	// note that many of the functions in this template might need Loader::('whatever') 
	// calls for older versions. 5.6 has an autoloader that makes sure most of these
	// classes are intiated on all requests. see /concrete/startup/autoload.php

	protected $appVersionRequired = '5.6.1.2';
	// by incrementing this when you add new functionality, deployment becomes
	// much much easier
	protected $pkgVersion = '0.0.4';

	// this will show on the installation screen in the dashboard
	public function getPackageDescription() {
		return t('Defaults and themes for site ___________.');
	}

	// This can be anything you want, it doesn't have to line up with
	// the package handle. Note that it will be added to the database the
	// first time the package is read, and doesn't update after that.
	public function getPackageName() {
		return t('Boilerplate Package');
	}

	public function on_start() {
//		stuff here will run on every page request before anything else.
//		if full page cache is turned on, this will not run on cached pages
//		
//		events are the most common use case
//		for more details on events, check out the documentation here:
//		http://www.concrete5.org/documentation/developers/system/events
//		
		Events::extendPageType('boilerplate', 'on_page_add');
		$html = Loader::helper("html");
		$v = View::getInstance();
		$v->addHeaderItem($html->css("boilerplate.css", "c5_boilerplate"));
		$v->addHeaderItem($html->javascript("boilerplate.js", "c5_boilerplate"));
	}

	public function install($post = array()) {
//		
//		the post object is passed in if you are using the dashbord/install.php
//		package element. any form fields that you use in that element will
//		be elements of this array.  you do not need a form tag. check the 
//		element file for examples of syntax
//		
//		it's beneficial for installation to split things out into
//		separate functions for organization and ease of reading
//		

		$pkg = parent::install();
		$this->installBlocks($pkg);
		$this->installSinglePages($pkg);
		$this->installPageAttributes($pkg);
		$this->installPageTypes($pkg);
		$this->installPages($pkg);
		$this->installThemes($pkg);
		$this->installJobs($pkg);
		$this->installGroups();
		$this->setPermissions();
	}

	public function upgrade() {

//		If you have an attribute type in your package that needs to
//		update it's database table, you will need to run this:
//		
//		$est = AttributeType::getByHandle('attribute_handle');
//		$path = $est->getAttributeTypeFilePath(FILENAME_ATTRIBUTE_DB);
//		Package::installDB($path);

		parent::upgrade();
		$pkg = Package::getByHandle($this->pkgHandle);
		$this->installAdditionalPageAttributes($pkg);
		$this->installJobs($pkg);
	}

	public function upgradeCoreData() {
//		
//		If you need to make sure a package is installed when you upgrade your
//		package, this is how to do it. You can also run this in the install
//		controller. It's a bit ugly, just dumping out the error and dying, but
//		that's really the only way to do it with the 5.6.1.2 and below versions.
//		Not really recommended for marketplace add ons
//		
//		$havePkg = 0;
//		$packages = Package::getInstalledList();
//
//		foreach ($packages as $package) {
//			$handle = $package->getPackageHandle();
//			if ($handle == "needed_handle") {
//				$havePkg = 1;
//			}
//		}
//		if ($havePkg) {
//			parent::upgradeCoreData();
//		} else {
//			$message = t("Requirements not met, you must have the 'Needed Package' installed.");
//			throw new Exception($message);
//			exit;
//		}
	}
	
	private function installBlocks($pkg) {
          $bt = BlockType::getByHandle('boilerplate_callout');
          if (!$bt || !is_object($bt)){
               BlockType::installBlockTypeFromPackage('boilerplate_callout', $pkg);
          } else {
			// the block already exists, so we want
			// to update it to use the block from our package
			// this might not be OK for marketplace stuff if
			// you are modifying other packages or the core
			Loader::db()->execute('update Pages set pkgID = ? where btID = ?', array($pkg->pkgID, $bt->getBlockTypeID()));
			
		}
	}

	private function installGroups() {
		/*
		 * It looks like you can't associate groups with a package, so whatever
		 * you install here will remain after you uninstall
		 */
		$bpAdmins = Group::getByName("Boilerplate Admins");
		if (!is_object($bpAdmins)) {
			$bpAdmins = Group::add("Boilerplate Admins", "Sample group for the boilerplate package.");
		}
	}

	private function setPermissions() {
		/*
		 * This only covers permissions in 5.6+ They changed quite massively at
		 * that revision. Eventually, this package will have other branches for 
		 * earlier versions.
		 * 
		 * Not everything shown here will work with simple permissions. People 
		 * will just be set as able to view or admin, the nuanced stuff about 
		 * sub page permissions, etc will not be applied
		 * 
		 * First off, we need to set up arrays of what people are allowed to do.
		 */

		$viewOnly = array('view_page');
		$writePage = array(
		    'view_page',
		    'view_page_versions',
		    'edit_page_properties',
		    'edit_page_contents',
		    'approve_page_versions'
		);
		$adminPage = array(
		    'edit_page_speed_settings',
		    'edit_page_permissions',
		    'edit_page_theme',
		    'schedule_page_contents_guest_access',
		    'edit_page_type',
		    'delete_page',
		    'preview_page_as_user',
		    'delete_page_versions',
		    'move_or_copy_page',
		    'edit_page_type'
		);

		// Now to get the the group that we made for boilerplate
		$bpGroup = Group::getByName("Boilerplate Admins");
		// Then the current user, again, could be anyone
		$u = new User();
		$ui = UserInfo::getByID($u->getUserID());
		// and our sample page
		$bpPage = Page::getByPath('/boilerplate-sample');

		if (is_object($bpPage) && is_a($bpPage, "Page")) {
			// by passing in -1, we are saying that all permissions in the array are
			// not allowed
			// 
			// After some more digging, it seems like saying can't view doesn't
			// work properly. It will hide the page from everyone. If you simply
			// don't assign any permissions for them at all, then it works properly
			// I don't get why that is, might be a bug.
			// 
//			$bpPage->assignPermissions(Group::getByID(GUEST_GROUP_ID), $viewOnly, -1);
//			$bpPage->assignPermissions(Group::getByID(REGISTERED_GROUP_ID), $viewOnly, -1);
			$bpPage->assignPermissions(Group::getByID(ADMIN_GROUP_ID), $adminPage);
			$bpPage->assignPermissions(Group::getByID(ADMIN_GROUP_ID), $writePage);
			$bpPage->assignPermissions($bpGroup, $writePage);
			$bpPage->assignPermissions($ui, $writePage);
			// at this point, our page will let people edit, and others can't even view
			// in order to allow sub-pages to be added by our admins, we'll need to get
			// a _bit_ more complicated.
			// this could probbly be cleaned up a little, to be more efficient
			// first get the ctID of the page type we want them to be able to add
			$bpID = CollectionType::getByHandle('boilerplate')->getCollectionTypeID();
			// In order to allow the user to add sub pages, we need to do this
			$bpAdminUserPE = UserPermissionAccessEntity::getOrCreate($ui);
			$entities[] = $bpAdminUserPE;
			// lets them add external links
			$args = array();
			$args['allowExternalLinksIncluded'][$bpAdminUserPE->getAccessEntityID()] = 1;
			// I can't remember why it's "C" or what the other options are...
			$args['pageTypesIncluded'][$bpAdminUserPE->getAccessEntityID()] = 'C';
			// you can repeat this with as many different collection type IDs as you like
			$args['ctIDInclude'][$bpAdminUserPE->getAccessEntityID()][] = $bpID;

			// now to allow it for groups
			$bpAdminPE = GroupPermissionAccessEntity::getOrCreate($bpGroup);
			$entities[] = $bpAdminPE;
			$args['allowExternalLinksIncluded'][$bpAdminPE->getAccessEntityID()] = 1;
			$args['pageTypesIncluded'][$bpAdminPE->getAccessEntityID()] = 'C';
			$args['ctIDInclude'][$bpAdminPE->getAccessEntityID()][] = $bpID;

			// ordinary admins
			$adminPE = GroupPermissionAccessEntity::getOrCreate(Group::getByID(ADMIN_GROUP_ID));
			$entities[] = $adminPE;
			$args['allowExternalLinksIncluded'][$adminPE->getAccessEntityID()] = 1;
			$args['pageTypesIncluded'][$adminPE->getAccessEntityID()] = 'C';
			$args['ctIDInclude'][$adminPE->getAccessEntityID()][] = $bpID;

			// and now some crazy voodoo
			$pk = PagePermissionKey::getByHandle('add_subpage');
			$pk->setPermissionObject($bpPage);

			$pt = $pk->getPermissionAssignmentObject();
			$pa = $pk->getPermissionAccessObject();
			if (!is_object($pa)) {
				$pa = PermissionAccess::create($pk);
			}
			foreach ($entities as $pe) {
				$pa->addListItem($pe, false, PagePermissionKey::ACCESS_TYPE_INCLUDE);
			}
			$pa->save($args);
			$pt->assignPermissionAccess($pa);

			// and now we set it so that sub-pages added under this page
			// inherit the same permissions
			$pkr = new ChangeSubpageDefaultsInheritancePageWorkflowRequest();
			$pkr->setRequestedPage($bpPage);
			// if you pass in 0, they will inherit from page type default 
			// permissions in the dashboard. That's what they would do anyway,
			// if you don't do any of this stuff.
			$pkr->setPagePermissionsInheritance(1);
			$pkr->setRequesterUserID($u->getUserID());
			$pkr->trigger();
		}
	}

	private function installPageTypes($pkg) {
//		
//		Often page types are needed by packages. 
//		
		$boilerplate = CollectionType::getByHandle('boilerplate');
		if (!is_object($boilerplate)) {
			/*
			 * Other arguments you can pass in:
			 * 
			 * ctIsInternal boolean - will prevent page type from showing up
			 * in the add page dialog and page_types dashboard page
			 * 
			 * ctIcon - filename for the icon in the add page dialog, not sure
			 * if this has to exist somewhere, or if it can be a full http://
			 * path. the system just has filenames
			 * 
			 * cDescription - self explanatory
			 * 
			 * akID = array - each element would be the id of an attribute that
			 * you would like to be associated with the page on the add screen
			 * 
			 * this is how to get the id of the attribute you want
			 * $ak = CollectionAttributeKey::getByHandle('your_handle');
			 * $akID = $ak->getAttributeKeyID();
			 * 
			 */
			$data = array(
			    'ctHandle' => 'boilerplate',
			    'ctName' => t('Boilerplate Page'));

			$boilerplate = CollectionType::add($data, $pkg);
		} 
		/*
		 * Now that we have the boilerplate page type, we want to grab the master
		 * collection and add a block to it
		 */
		$bpMC = $boilerplate->getMasterTemplate();
		$bt = BlockType::getByHandle('content');
		$data = array('content' => t("Sample Boilerplate Content"));
		$bpMC->addBlock($bt, 'Boilerplate Content', $data);

		/*
		 * If you want to assign all the blocks from another page type to your
		 * custom page type, this is the syntax:
		 */

//		$mc1 = CollectionType::getByHandle('left_sidebar');
//		$mc2 = CollectionType::getByHandle('page');
//		if (is_object($mc1) && is_object($bpMC)) {
//			$dm = $mc1->getMasterTemplate();
//			$blocks = $dm->getBlocks();
//
//			// alias these blocks to the new event calendar page
//			foreach ($blocks as $b) {
//				$b->alias($bpMC);
//			}
//		} else {
//			if (is_object($mc2) && is_object($bpMC)) {
//				$dm = $mc2->getMasterTemplate();
//				$blocks = $dm->getBlocks();
//				// alias these blocks to the new event calendar page
//				foreach ($blocks as $b) {
//					$b->alias($bpMC);
//				}
//			}
//		}
	}

	private function installPages($pkg) {

		// this could be any handle
		$boilerplate = CollectionType::getByHandle("boilerplate");

		// we want the user ID of the person installing. Really, you could use
		// any user here if you want.
		$u = new User();
		$uID = $u->getUserID();

		// doesn't need to be home, you could install these anywhere
		$home = Page::getByID(HOME_CID);
		$data = array(
		    'cHandle' => "boilerplate-sample",
		    'cName' => "Boilerplate Sample",
		    'pkgID' => $pkg->getPackageID(),
		    'uID' => $uID
		);
		$boilerplateSample = $home->add($boilerplate, $data);

		/*
		 * After you have added the page, you can add blocks with the same 
		 * syntax as was used in the installPageTypes function above
		 * 
		 * $boilerplateSample->addBlock($bt, 'Area Name', $data);
		 */

		/*
		 * It's also useful to assign attributes here, so that you don't have
		 * to do them from the front end later. There are a lot of different 
		 * things that you can do here, these two are just to show how and where
		 * to do it.
		 */
		$boilerplateSample->setAttribute('exclude_page_list', 1);
		$boilerplateSample->setAttribute('meta_description', t("A sample page created by the C5 Boilerplate package."));
	}

	private function installSinglePages($pkg) {

		//this array will hold all the custom dashboard page paths and their icons. 
		//see the setupDashboardIcons method for more info
		$dashboardIcons = array();

		$path = '/dashboard/boilerplate';

		$cID = Page::getByPath($path)->getCollectionID();
		if (intval($cID) > 0 && $cID !== 1) {
			// the single page already exists, so we want
			// to update it to use our package elements
			// this might not be OK for marketplace stuff if
			// you are modifying the core single pages
			Loader::db()->execute('update Pages set pkgID = ? where cID = ?', array($pkg->pkgID, $cID));
		} else {
			// it doesn't exist, so now we add it
			$p = SinglePage::add($path, $pkg);
			if (is_object($p) && $p->isError() !== false) {
				$p->update(array('cName' => t('Boilerplate')));
			}
		}

		$path = '/dashboard/boilerplate/output_stuff';
		$cID = Page::getByPath($path)->getCollectionID();
		if (intval($cID) > 0 && $cID !== 1) {
			Loader::db()->execute('update Pages set pkgID = ? where cID = ?', array($pkg->pkgID, $cID));
		} else {
			$p = SinglePage::add($path, $pkg);
			if (is_object($p) && $p->isError() !== false) {
				$p->update(array('cName' => t('Output Stuff')));
			}
		}
		// Set the icon for the /dashboard/boilerplate/output_stuff page.
		// See the icons section of the twitter bootstrap docs for available icons.
		$dashboardIcons[$path] = 'icon-bullhorn';

		//setup the icons set for custom dashboard single pages
		$this->setupDashboardIcons($dashboardIcons);
	}

	private function installAdditionalPageAttributes($pkg) {
		$bpa = AttributeSet::getByHandle('boilerplate_page_attributes');

		// see install page attributes section for syntax
	}

	private function installThemes($pkg) {
		PageTheme::add('boilerplate', $pkg);
	}

	private function installJobs($pkg){
		Loader::model('job');

		//Make sure the job isn't already installed
		$dumpSample = Job::getByHandle('dump_sample_table');
		if(!is_object($dumpSample)){
			Job::installByPackage('dump_sample_table', $pkg);
		}
	}

	private function installPageAttributes($pkg) {

		$cakc = AttributeKeyCategory::getByHandle('collection');
		// Multiple means an attribute can be in more than one set, but you 
		// can't choose what set they show up in for the gui
//		$cakc->setAllowAttributeSets(AttributeKeyCategory::ASET_ALLOW_MULTIPLE);
//		$cakc->setAllowAttributeSets(AttributeKeyCategory::ASET_ALLOW_NONE);
		$cakc->setAllowAttributeSets(AttributeKeyCategory::ASET_ALLOW_SINGLE);
		$bpa = $cakc->addSet('boilerplate_page_attributes', t('Boilerplate Page Attributes'), $pkg);

//		$bp_select_handle = CollectionAttributeKey::getByHandle('bp_select_handle');
//		if (!$bp_select_handle instanceof CollectionAttributeKey) {
//			$bp_select_handle = CollectionAttributeKey::add('select', array(
//					  'akHandle' => 'bp_category',
//					  'akName' => t('Select Name'),
//					  'akIsSearchable' => true,
//					  'akIsSearchableIndexed' => 1,
//					  'akSelectAllowMultipleValues' => false,
//					  'akSelectAllowOtherValues' => true,
//					  'akSelectOptionDisplayOrder' => 'alpha_asc'), $pkg)->setAttributeSet($bpa);
//			$ak = CollectionAttributeKey::getByHandle('bp_select_handle');
//			SelectAttributeTypeOption::add($ak, "Option Value");
//		} else { 
//			$bp_select_handle->delete();
//			$bp_select_handle = CollectionAttributeKey::add('select', array(
//					  'akHandle' => 'bp_category',
//					  'akName' => t('Select Name'),
//					  'akIsSearchable' => true,
//					  'akIsSearchableIndexed' => 1,
//					  'akSelectAllowMultipleValues' => false,
//					  'akSelectAllowOtherValues' => true,
//					  'akSelectOptionDisplayOrder' => 'alpha_asc'), $pkg)->setAttributeSet($bpa);
//			$ak = CollectionAttributeKey::getByHandle('bp_select_handle');
//			SelectAttributeTypeOption::add($ak, "Option Value");
//		}
//		Handy way to populate a states array
//		
//		$bp_state = CollectionAttributeKey::getByHandle('bp_state');
//		if (!$bp_state instanceof CollectionAttributeKey) {
//			$bp_state = CollectionAttributeKey::add('select', array(
//					  'akHandle' => 'bp_state',
//					  'akName' => t('State'),
//					  'akIsSearchable' => true,
//					  'akIsSearchableIndexed' => 1,
//					  'akSelectAllowMultipleValues' => false,
//					  'akSelectAllowOtherValues' => false,
//					  'akSelectOptionDisplayOrder' => 'alpha_asc'), $pkg)->setAttributeSet($bpa);
//			$ak = CollectionAttributeKey::getByHandle('bp_state');
//			$list = Loader::helper("lists/states_provinces");
//			$states = $list->getStates();
//			foreach ($states as $stateAbr => $stateName) {
//				SelectAttributeTypeOption::add($ak, $stateName);
//			}
//		}
//		$bp_text = CollectionAttributeKey::getByHandle('bp_text');
//		if (!$bp_text instanceof CollectionAttributeKey) {
//			$bp_text = CollectionAttributeKey::add('text', array(
//					  'akHandle' => 'bp_text',
//					  'akName' => t('Text Name'),
//					  'akIsSearchable' => true,
//					  'akIsSearchableIndexed' => true), $pkg)->setAttributeSet($bpa);
//		}
//		Options for akTextareaDisplayMode:
//		
//		'text' => t('Plain Text')
//		'rich_text' => t('Rich Text - Simple (Default Setting)')
//		'rich_text_basic' => t('Rich Text - Basic Controls')
//		'rich_text_advanced' => t('Rich Text - Advanced')
//		'rich_text_office' => t('Rich Text - Office')
//		'rich_text_custom' => t('Rich Text - Custom')
//		
//		$bp_textarea = CollectionAttributeKey::getByHandle('bp_textarea');
//		if (!$bp_textarea instanceof CollectionAttributeKey) {
//			$bp_textarea = CollectionAttributeKey::add("textarea", array(
//					  'akHandle' => 'bp_textarea',
//					  'akName' => t('Textarea Name'),
//					  'akIsSearchable' => true,
//					  'akIsSearchableIndexed' => true,
//					  'akTextareaDisplayMode' => 'text'), $pkg)->setAttributeSet($bpa);
//		}
//		akDateDisplayMode options:
//		
//		'date_time' => t('Both Date and Time')
//		'date' => t('Date Only')
//		'text' => t('Text Input Field')
//		
//		$bp_time = CollectionAttributeKey::getByHandle('bp_time');
//		if (!$bp_time instanceof CollectionAttributeKey) {
//			$bp_time = CollectionAttributeKey::add(
//						'date_time', array(
//					  'akHandle' => 'bp_time',
//					  'akName' => t('Time Name'),
//					  'akIsSearchable' => true,
//					  'akIsSearchableIndexed' => true,
//					  'akDateDisplayMode' => 'date'), $pkg)->setAttributeSet($bpa);
//		}
//		$bp_number = CollectionAttributeKey::getByHandle('bp_number');
//		if (!$bp_number instanceof CollectionAttributeKey) {
//			$bp_number = CollectionAttributeKey::add('number', array(
//					  'akHandle' => 'bp_number',
//					  'akName' => t('Number Name'),
//					  'akIsSearchable' => true,
//					  'akIsSearchableIndexed' => true), $pkg)->setAttributeSet($bpa);
//		}
//		$bp_boolean = CollectionAttributeKey::getByHandle('bp_boolean');
//		if (!$bp_boolean instanceof CollectionAttributeKey) {
//			$bp_boolean = CollectionAttributeKey::add("boolean", array(
//					  'akHandle' => 'bp_boolean',
//					  'akName' => t('Boolean Name'),
//					  'akIsSearchable' => true,
//					  'akIsSearchableIndexed' => true), $pkg)->setAttributeSet($bpa);
//		}
//		
//		$bp_file = CollectionAttributeKey::getByHandle('bp_file');
//		if (!$bp_file instanceof CollectionAttributeKey) {
//			$bp_file = CollectionAttributeKey::add("image_file", array(
//					  'akHandle' => 'bp_file',
//					  'akName' => t('File Name'),
//					  'akIsSearchable' => false), $pkg)->setAttributeSet($bpa);
//		}
	}

	//Takes an associative array of pages to set icons for. This is only for dashboard single pages
	//Key 	= page path
	//Value = bootstrap icon class
	private function setupDashboardIcons($iconArray) {
		$cak = CollectionAttributeKey::getByHandle('icon_dashboard');
		if (is_object($cak)) {
			foreach($iconArray as $path => $icon) {
				$sp = Page::getByPath($path);
				if (is_object($sp) && (!$sp->isError())) {
					$sp->setAttribute('icon_dashboard', $icon);
				}
			}
		}
	}
}
