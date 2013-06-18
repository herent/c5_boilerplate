<?php defined('C5_EXECUTE') or die("Access Denied.");

$subject = SITE . " " . t("Contact Form Submission");
$body = t("

A site visitor has filled out the contact form on your website.

Name: %s
Phone: %s
Email: %s
Comment: %s
", $fullname, $phone, $email, $comment);
