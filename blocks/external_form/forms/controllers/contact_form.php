<?php defined('C5_EXECUTE') or die("Access Denied.");

class ContactFormExternalFormBlockController extends BlockController {

	public function action_do_contact() {

		$e = Loader::helper('validation/error');

		if (strlen($_POST['fullname']) > 0) {
			$fullname = $_POST['fullname'];
		} else {
			$e->add("Please include your name.");
		}

		if (strlen($_POST['phone']) > 0) {
			$phone = $_POST['phone'];
		} else {
			$e->add("Please include your phone number.");
		}

		if (eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $_POST['email'])) {
		  $email = $_POST['email'];
		} else {
		  $e->add("Please include a valid email address.");
		}

		if (strlen($_POST['comment']) > 0) {
			$comment = $_POST['comment'];
		} else {
			$e->add("Please include a comment.");
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
			$mh->addParameter('phone'   , $phone);
			$mh->addParameter('email'   , $email);
			$mh->addParameter('comment' , $comment);
			$mh->addParameter('signUp'  , $signUp);
			$mh->to("developers@hutman.net");
			$mh->load('contact_form_submission');
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
