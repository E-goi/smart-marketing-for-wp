<?php

require_once plugin_dir_path( __FILE__ ) . 'mail-catcher-interface.php';
require_once plugin_dir_path( __FILE__ ) . 'mailer.php';
require_once plugin_dir_path( __FILE__ ) . 'transactional-email-helper.php';

/**
 * Class MailCatcher replaces the \PHPMailer\PHPMailer\PHPMailer and modifies the email sending logic.
 */
class MailCatcher extends \PHPMailer\PHPMailer\PHPMailer implements MailCatcherInterface {

	/**
	 * Modify the default send() behaviour.
	 *
	 * @throws \PHPMailer\PHPMailer\Exception When sending via PhpMailer fails for some reason.
	 *
	 * @return bool
	 */
	public function send() { // phpcs:ignore

		$mail_mailer = get_option( 'egoi_transactional_email' );

		// We need this so that the PHPMailer class will correctly prepare all the headers.
		$this->Mailer = 'mail'; // phpcs:ignore

		// Prepare everything (including the message) for sending.
		if ( ! $this->preSend() ) {
			return false;
		}

		// Use the E-goi Mailer instead of default PHPMailer
		$mailer = new Mailer( $this );

		if ( ! $mailer ) {
			return false;
		}

		/*
		 * Send the actual email.
		 * We reuse everything, that was preprocessed for usage in PHPMailer.
		 */
		$mailer->send();

		$res = $mailer->get_response();

		if ( wp_remote_retrieve_response_code( $res ) === 200 ) {
			$is_sent = true;

			$option = get_option( 'transactional_email_option' );

			$option['sent'] = $option['sent'] + 1;
			update_option( 'transactional_email_option', $option );

		} else {
			$helper = new TransactionalEmailHelper();
			$helper->handle_error( $res );
			$is_sent = false;
		}

		// Allow to perform any actions with the data.
		do_action( 'egoi_transactional_email_mailcatcher_send_after', $mailer, $this );

		return $is_sent;
	}

	/**
	 * Get the PHPMailer line ending.
	 *
	 * @return string
	 */
	public function get_line_ending() {

		return static::$LE; // phpcs:ignore
	}

	/**
	 * Create a unique ID to use for multipart email boundaries.
	 *
	 * @return string
	 */
	public function generate_id() {

		return $this->generateId();
	}
}
