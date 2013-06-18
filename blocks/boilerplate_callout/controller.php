<?php

defined('C5_EXECUTE') or die("Access Denied.");

class BoilerplateCalloutBlockController extends BlockController {

	protected $btTable = 'btBoilerplateCallout';
	protected $btInterfaceWidth = "700";
	protected $btInterfaceHeight = "450";
	protected $btCacheBlockRecord = true;
	protected $btCacheBlockOutput = true;
	// this is super dangerous. I don't know why it even exists
	// if you have this set, and are doing something like outputting
	// an 'edit mode' message, that's what will be cached and displayed
	// to site visitors.
	protected $btCacheBlockOutputOnPost = false;
	protected $btCacheBlockOutputForRegisteredUsers = false;
	protected $btCacheBlockOutputLifetime = CACHE_LIFETIME;

	/**
	 * Used for localization. If we want to localize the name/description we have to include this
	 */
	public function getBlockTypeDescription() {
		return t("Display a little block of custom content.");
	}

	public function getBlockTypeName() {
		return t("Callout");
	}

	public function getSearchableContent() {
		$content = array();
		$content[] = $this->title;
		$content[] = $this->content;
		return implode(' - ', $content);
	}

	public function on_start() {
		$includeLink = $this->includeLink;
		$this->set('includeLink', $includeLink);
		if ($includeLink == 1) {
			$linkType = $this->linkType;
			$this->set('linkType', $linkType);
			$linkText = $this->linkText;
			$this->set('linkText', $linkText);
			if ($linkType == "sitemap") {
				// we need to get a link to the collection
				$link = Page::getByID($this->linkCID);
				$nh = Loader::helper('navigation');
				$url = $nh->getCollectionURL($link);
				$this->set('url', $url);
				$this->set('linkCID', $this->linkCID);
				$this->set('linkURL', "");
				$this->set('showSiteMap', 1);
			} else if ($linkType == "manual") {
				$url = $this->linkURL;
				$this->set('url', $url);
				$this->set('linkURL', $url);
				$this->set('linkCID', 0);
				$this->set('showManual', 1);
			} else if ($linkType == "popup") {
				$this->set('linkURL', "");
				$this->set('linkCID', 0);
				$this->set('showPopup', 1);
			}
		} else {
			$this->set('linkType', "");
			$this->set('linkText', "");
			$this->set("url", "");
		}
	}

	public function view() {
		$this->set('hasImage', (empty($this->fID) ? "" : " has-image"));
		$this->set('content', $this->translateFrom($this->content));
//		This is an example of adding an element to the footer.
//		
//		$html = Loader::helper('html');
//		$this->addFooterItem($html->javascript("jquery.form.js"));
	}

	/*
	 * Makes sure that the add/edit values are correct.
	 * 
	 * @param array $args This is the $_POST array that will be passed to your save function.
	 */
	public function validate($args) {
		$error = Loader::helper('validation/error');
//		if (intval($args['fID'])==0) {
//			$error->add(t("Please select an image"));
//		}

		if (strlen($args['title']) == 0) {
			$error->add(t("Please add a title."));
		}

		if (strlen($args['content']) == 0) {
			$error->add(t("Please include some content."));
		}

		if (intval($args['includeLink']) > 0) {
			if (strlen($args['linkType']) > 0) {
				if ($args['linkType'] == "manual") {
					if (strlen($args['linkURL']) === 0) {
						$error->add(t("Please enter the URL you would like to link to."));
					}
				}
				if ($args['linkType'] == "sitemap") {
					if (!intval($args['linkCID']) > 0) {
						$error->add(t("Please choose a page from the site map."));
					}
				}
			} else {
				$error->add(t("Please choose a link type."));
			}
		}
		if ($error->has()) {
			// the results will be shown in a dialog as a red / pink list
			return $error;
		}
	}

	public function edit() {
		$this->set('fID', (empty($this->fID) ? null : File::getByID($this->fID)));
		$this->set('content', $this->translateFromEditMode($this->content));
	}

	public function save($args) {
		$args['fID'] = empty($args['fID']) ? 0 : $args['fID'];
		$args['content'] = $this->translateTo($args['content']);
		$args['includeLink'] = (intval($args['includeLink']) > 0) ? 1 : 0;
		if ($args['includeLink'] == 1) {
			if ($args['linkType'] == "sitemap") {
				$args['linkType'] = "sitemap";
				$args['linkCID'] = intval($args['linkCID']);
				$args['linkURL'] = "";
				$args['linkText'] = $args['linkText'];
			}
			if ($args['linkType'] == "manual") {
				$args['linkType'] = "manual";
				$args['linkCID'] = 0;
				$args['linkURL'] = $args['linkURL'];
				$args['linkText'] = $args['linkText'];
			}
			if ($args['linkType'] == "popup") {
				$args['linkType'] = "popup";
				$args['linkCID'] = 0;
				$args['linkURL'] = "";
				$args['linkText'] = $args['linkText'];
			}
		} else {
			$args['linkType'] = "";
			$args['linkText'] = "";
			$args['linkCID'] = 0;
			$args['linkURL'] = "";
		}
		parent::save($args);
	}

	//Helper function for image fields
	public function get_image_object($fID, $width = 0, $height = 0, $crop = false) {
		if (empty($fID)) {
			$image = null;
		} else if (empty($width) && empty($height)) {
			//Show image at full size (do not generate a thumbnail)
			$file = File::getByID($fID);
			$image = new stdClass;
			$image->src = $file->getRelativePath();
			$image->width = $file->getAttribute('width');
			$image->height = $file->getAttribute('height');
		} else {
			//Generate a thumbnail
			$width = empty($width) ? 9999 : $width;
			$height = empty($height) ? 9999 : $height;
			$file = File::getByID($fID);
			$ih = Loader::helper('image');
			$image = $ih->getThumbnail($file, $width, $height, $crop);
		}

		return $image;
	}

//WYSIWYG HELPER FUNCTIONS (COPIED FROM "CONTENT" BLOCK):
	function translateFromEditMode($text) {
		// now we add in support for the links

		$text = preg_replace(
			   '/{CCM:CID_([0-9]+)}/i', BASE_URL . DIR_REL . '/' . DISPATCHER_FILENAME . '?cID=\\1', $text);

		// now we add in support for the files

		$text = preg_replace_callback(
			   '/{CCM:FID_([0-9]+)}/i', array('BoilerplateCalloutBlockController', 'replaceFileIDInEditMode'), $text);


		$text = preg_replace_callback(
			   '/{CCM:FID_DL_([0-9]+)}/i', array('BoilerplateCalloutBlockController', 'replaceDownloadFileIDInEditMode'), $text);


		return $text;
	}

	function translateFrom($text) {
		// old stuff. Can remove in a later version.
		$text = str_replace('href="{[CCM:BASE_URL]}', 'href="' . BASE_URL . DIR_REL, $text);
		$text = str_replace('src="{[CCM:REL_DIR_FILES_UPLOADED]}', 'src="' . BASE_URL . REL_DIR_FILES_UPLOADED, $text);

		// we have the second one below with the backslash due to a screwup in the
		// 5.1 release. Can remove in a later version.

		$text = preg_replace(
			   array(
		    '/{\[CCM:BASE_URL\]}/i',
		    '/{CCM:BASE_URL}/i'), array(
		    BASE_URL . DIR_REL,
		    BASE_URL . DIR_REL)
			   , $text);

		// now we add in support for the links

		$text = preg_replace_callback(
			   '/{CCM:CID_([0-9]+)}/i', array('BoilerplateCalloutBlockController', 'replaceCollectionID'), $text);

		$text = preg_replace_callback(
			   '/<img [^>]*src\s*=\s*"{CCM:FID_([0-9]+)}"[^>]*>/i', array('BoilerplateCalloutBlockController', 'replaceImageID'), $text);

		// now we add in support for the files that we view inline
		$text = preg_replace_callback(
			   '/{CCM:FID_([0-9]+)}/i', array('BoilerplateCalloutBlockController', 'replaceFileID'), $text);

		// now files we download

		$text = preg_replace_callback(
			   '/{CCM:FID_DL_([0-9]+)}/i', array('BoilerplateCalloutBlockController', 'replaceDownloadFileID'), $text);

		return $text;
	}

	private function replaceFileID($match) {
		$fID = $match[1];
		if ($fID > 0) {
			$path = File::getRelativePathFromID($fID);
			return $path;
		}
	}

	private function replaceImageID($match) {
		$fID = $match[1];
		if ($fID > 0) {
			preg_match('/width\s*="([0-9]+)"/', $match[0], $matchWidth);
			preg_match('/height\s*="([0-9]+)"/', $match[0], $matchHeight);
			$file = File::getByID($fID);
			if (is_object($file) && (!$file->isError())) {
				$imgHelper = Loader::helper('image');
				$maxWidth = ($matchWidth[1]) ? $matchWidth[1] : $file->getAttribute('width');
				$maxHeight = ($matchHeight[1]) ? $matchHeight[1] : $file->getAttribute('height');
				if ($file->getAttribute('width') > $maxWidth || $file->getAttribute('height') > $maxHeight) {
					$thumb = $imgHelper->getThumbnail($file, $maxWidth, $maxHeight);
					return preg_replace('/{CCM:FID_([0-9]+)}/i', $thumb->src, $match[0]);
				}
			}
			return $match[0];
		}
	}

	private function replaceDownloadFileID($match) {
		$fID = $match[1];
		if ($fID > 0) {
			$c = Page::getCurrentPage();
			if (is_object($c)) {
				return View::url('/download_file', 'view', $fID, $c->getCollectionID());
			} else {
				return View::url('/download_file', 'view', $fID);
			}
		}
	}

	private function replaceDownloadFileIDInEditMode($match) {
		$fID = $match[1];
		if ($fID > 0) {
			return View::url('/download_file', 'view', $fID);
		}
	}

	private function replaceFileIDInEditMode($match) {
		$fID = $match[1];
		return View::url('/download_file', 'view_inline', $fID);
	}

	private function replaceCollectionID($match) {
		$cID = $match[1];
		if ($cID > 0) {
			$c = Page::getByID($cID, 'APPROVED');
			return Loader::helper("navigation")->getLinkToCollection($c);
		}
	}

	function translateTo($text) {
		// keep links valid
		$url1 = str_replace('/', '\/', BASE_URL . DIR_REL . '/' . DISPATCHER_FILENAME);
		$url2 = str_replace('/', '\/', BASE_URL . DIR_REL);
		$url3 = View::url('/download_file', 'view_inline');
		$url3 = str_replace('/', '\/', $url3);
		$url3 = str_replace('-', '\-', $url3);
		$url4 = View::url('/download_file', 'view');
		$url4 = str_replace('/', '\/', $url4);
		$url4 = str_replace('-', '\-', $url4);
		$text = preg_replace(
			   array(
		    '/' . $url1 . '\?cID=([0-9]+)/i',
		    '/' . $url3 . '([0-9]+)\//i',
		    '/' . $url4 . '([0-9]+)\//i',
		    '/' . $url2 . '/i'), array(
		    '{CCM:CID_\\1}',
		    '{CCM:FID_\\1}',
		    '{CCM:FID_DL_\\1}',
		    '{CCM:BASE_URL}')
			   , $text);
		return $text;
	}

}
