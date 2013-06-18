<?php

defined('C5_EXECUTE') or die(_("Access Denied."));

class C5BoilerplatePackage extends Package {

	// this must match the handle of your directory
	protected $pkgHandle = 'c5_boilerplate';
	// make sure that you are not using functions unavailable in old versions
	// list of what you can do here:
	// http://www.concrete5.org/documentation/how-tos/developers/choose-what-c5-version-to-develop-for/
	protected $appVersionRequired = '5.6.1.2';
	// by incrementing this when you add new functionality, deployment becomes
	// much much easier
	protected $pkgVersion = '0.0.1';

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
//		Events::extendPageType('ct_handle', 'on_page_add');
//		$html = Loader::helper("html");
//		View::addHeaderItem($html->css("boilerplate.css", "boilerplate"));
//		View::addHeaderItem($html->javascript("boilerplate.js", "boilerplate"));
	}

	public function upgrade() {
		
//		If you have an attribute type in your package that needs to
//		update it's database table, you will need to run this:
//		
//		$est = AttributeType::getByHandle('attribute_handle');
//		$path = $est->getAttributeTypeFilePath(FILENAME_ATTRIBUTE_DB);
//		Package::installDB($path);
//		
		
		parent::upgrade();
		$pkg = Package::getByHandle($this->pkgHandle);
		$this->installAdditionalPageAttributes($pkg);
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
		$this->installSinglePages($pkg);
		$this->installPageAttributes($pkg);
		$this->installPageTypes($pkg);
		$this->installThemes($pkg);
	}

	private function installPageTypes($pkg) {
//		
//		Often page types are needed by packages. 
//		
//		$ct_handle = CollectionType::getByHandle('ct_handle');
//		if (!is_object($ct_handle)) {
//			$data = array('ctHandle' => 'ct_handle', 'ctName' => t('Handle'));
//			$ct_handle = CollectionType::add($data, $pkg);
//		}
	}

	private function installSinglePages($pkg) {
		Loader::model('single_page');
//		$p = SinglePage::add('/dashboard/handle');
//		if (is_object($p) && $p->isError() !== false) {
//			$p->update(array('cName' => t('Name')));
//		}
	}

	private function installAdditionalPageAttributes($pkg) {
		Loader::model('attribute/categories/collection');
		Loader::model('attributes/set');
		$bpa = AttributeSet::getByHandle('boilerplate_page_attributes');
		
		// see install page attributes section for syntax
	}

	private function installThemes($pkg) {
		PageTheme::add('boilerplate', $pkg);
	}

	private function installPageAttributes($pkg) {
		Loader::model('attribute/categories/collection');

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

}
