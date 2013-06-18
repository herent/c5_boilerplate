<?php  defined('C5_EXECUTE') or die("Access Denied.");

class BoilerplatePageTypeController extends Controller {	
	
	public function on_page_add($cobj){
		/*
		 * the new page object will be passed in here. I'm not sure, but I think
		 * that you can't do things like set attributes and a few other things 
		 * because it's not fully instantiated. 
		 */
		$db = Loader::db();
		$vals = array(
		    $cobj->getCollectionParentID(),
		    $cobj->getCollectionID(),
		    $cobj->getCollectionDatePublic(),
		    $cobj->getCollectionName(),
		    '1'
		);
		$q = "INSERT INTO BoilerplateSample 
			(cParentID, cID, date_col, text_col, boolean_col)
			VALUES 
			(?, ?, ?, ?, ?)";
		$db->query($q, $vals);
	}
}
?>