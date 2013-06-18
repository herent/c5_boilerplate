<?php  defined('C5_EXECUTE') or die("Access Denied.");

class BoilerplatePageTypeController extends Controller {	
	
	public function on_start() {
//          $html = Loader::helper('html');
//          $this->addHeaderItem('<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>');
//          $this->addHeaderItem('<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>');
//
//          $this->addHeaderItem($html->javascript('jquery.js'));
//          $this->addFooterItem($html->javascript('jquery.ui.js'));
//          $this->addFooterItem($html->javascript('jquery.colorbox-min.js'));
//          $this->addHeaderItem($html->css('jquery.ui.css'));
//          $this->addHeaderItem($html->javascript('jquery-ui-map/jquery.ui.map.js', 'community_management'));
//          $this->addHeaderItem($html->javascript('jquery-ui-map/jquery.ui.map.services.js', 'community_management'));
//          $this->addHeaderItem($html->javascript('jquery-ui-map/jquery.ui.map.extensions.js', 'community_management'));
//          $this->addHeaderItem($html->javascript('markerclustererplus-2.0.6/markerclusterer.min.js', 'community_management'));
//          
//          Loader::helper('concrete/file');
//		Loader::model('file_attributes');
//		Loader::library('file/types');
//		Loader::model('file_list');
//		Loader::model('file_set');
//		
//
//		$fs = FileSet::getByName("Community Slideshow");
//		$fileList = new FileList();		
//		$fileList->filterBySet($fs);
//		$fileList->filterByType(FileType::T_IMAGE);	
//		$fileList->sortByFileSetDisplayOrder();
//		
//		$files = $fileList->get(1000,0);
//		$this->set("images", $files);
          $c = Page::getCurrentPage();
          $cParent = Page::getByID($c->getCollectionParentID());
          $link = View::url($cParent->getCollectionPath(), "view_position", $c->getCollectionID());
          $this->redirect($link);
     }
}
?>