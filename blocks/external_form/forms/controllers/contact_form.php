<?php

defined('C5_EXECUTE') or die("Access Denied.");

class ContactFormExternalFormBlockController extends BlockController {
	/*
	 * Note the name of the function - action_do_contact
	 * In the view, it's $this->action('do_contact')
	 */

	public function action_do_contact() {

		$e = Loader::helper('validation/error');
		// this 
		$str = Loader::helper('validation/strings');


		if ($str->notempty($_POST['fullname']) > 0) {
			$fullname = $_POST['fullname'];
		} else {
			$e->add(t("Please include your name."));
		}

		if ($str->notempty($_POST['phone']) > 0) {
			$phone = $_POST['phone'];
		} else {
			$e->add(t("Please include your phone number."));
		}

		// the second paramater is to check if the MX record exists
		if ($str->email($_POST['fb_email'], true)) {
			$email = $_POST['email'];
		} else {
			$e->add(t("Please include a valid email address."));
		}

		if ($str->notempty($_POST['comment']) > 0) {
			$comment = $_POST['comment'];
		} else {
			$e->add(t("Please include a comment."));
		}

		if (!$e->has()) {
			$this->set('response', t('Thanks!'));

			$mh = Loader::helper('mail');
			if (defined('EMAIL_DEFAULT_FROM_ADDRESS')) {
				$mh->from(EMAIL_DEFAULT_FROM_ADDRESS, t('Website Contact Form Robot'));
			} else {
				$adminUser = UserInfo::getByID(USER_SUPER_ID);
				if (is_object($adminUser)) {
					$mh->from($adminUser->getUserEmail(), t('Website Contact Form Robot'));
				}
			}
			$mh->addParameter('fullname', $fullname);
			$mh->addParameter('phone', $phone);
			$mh->addParameter('email', $email);
			$mh->addParameter('comment', $comment);
			// this is not neccessary, but helps make things easier for 
			// the site owner
			$mh->replyto($email, $fullname);
			$mh->to("email@somesite.com");
			// leave out the second argument if you are loading from the
			// core or the outer directory
			$mh->load('contact_form_submission', 'c5_boilerplate');
			$mh->sendMail();

			// another way to do this is with bodyHTML
			// note that the other parameters for to, from, etc
			// are preserved.

			$emailText = "<h1>" . t("Thanks!") . "</h1>";
			$mh->setBodyHTML($emailText);
			$mh->sendMail();

			// redirect to another page
			// header('Location: ' . View::url('/contact-us/thank-you'));
			// or set message for the view
			$this->set("message", t("Thank you for contacting us!"));
		} else {
			$this->set('error', $e);
		}
	}

}
