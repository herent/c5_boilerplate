<?php
defined("C5_EXECUTE") or die("Access Denied.");

$c = Page::getCurrentPage();
$p = new Permissions($c);
if ($p->canWrite()) {
     $canWrite = " can-write";
} else {
     $canWrite = " no-write";
}
$isEditMode = $c->isEditMode();
if ($isEditMode) {
     $edit = " edit-active";
} else {
     $edit = "";
}
?>
<!doctype html>
<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js not-ie"> <!--<![endif]-->
     <head>
          <meta charset="utf-8">
          <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name = "viewport" content = "initial-scale = 1.0">
		<style type="text/css" id="stylesTest"></style>
          <link rel="stylesheet" href="<?php echo $this->getThemePath(); ?>/css/normalize.css">
          <link rel="stylesheet" href="<?php echo $this->getThemePath(); ?>/typography.css">
          <link rel="stylesheet" href="<?php echo $this->getThemePath(); ?>/css/main.css">
          
          <!--[if lt IE 9]>
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
          <![endif]-->
          <script src="<?php echo $this->getThemePath(); ?>/js/modernizr-2.6.2.min.js"></script>
          <?php Loader::element('header_required'); ?>
		<? /* Uncomment to add in shortcut icons
          <link rel="shortcut icon" href="<?php echo $this->getThemePath(); ?>/images/favicon.ico">
          <link rel="apple-touch-icon" href="<?php echo $this->getThemePath(); ?>/images/apple-touch-icon.png">
          <link rel="apple-touch-icon" sizes="57x57" href="<?php echo $this->getThemePath(); ?>/images/apple-touch-icon-57x57-precomposed.png">
          <link rel="apple-touch-icon" sizes="72x72" href="<?php echo $this->getThemePath(); ?>/images/apple-touch-icon-72x72-precomposed.png">
          <link rel="apple-touch-icon" sizes="114x114" href="<?php echo $this->getThemePath(); ?>/images/apple-touch-icon-114x114-precomposed.png">
          <link rel="apple-touch-icon" sizes="144x144" href="<?php echo $this->getThemePath(); ?>/images/apple-touch-icon-144x144-precomposed.png">
		 */?>
     </head>
     <body class="<?php echo $c->getCollectionTypeHandle(); ?><?php echo $canWrite . $edit; ?>">
		<!-- Scope all of your css rules to .page-body-wrap if possible.
			This will keep them from eating into the CMS -->
          <div class="page-body-wrap">