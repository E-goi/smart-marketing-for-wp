<?php


/**
 * Interface MailCatcherInterface.
 */
interface MailCatcherInterface {

	/**
	 * Modify the default send() behaviour.
	 *
	 * @throws \phpmailerException|\PHPMailer\PHPMailer\Exception When sending via PhpMailer fails for some reason.
	 *
	 * @return bool
	 */
	public function send();

	/**
	 * Get the PHPMailer line ending.
	 *
	 * @return string
	 */
	public function get_line_ending();

	/**
	 * Create a unique ID to use for multipart email boundaries.
	 *
	 * @return string
	 */
	public function generate_id();
}
