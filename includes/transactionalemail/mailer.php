<?php


/**
 * Class Mailer.
 */
class Mailer {

	/**
	 *
	 * phpmailer reference
	 *
	 * @var MailCatcherInterface
	 */
	protected $phpmailer;

	/**
	 *
	 * Response after api request
	 *
	 * @var mixed
	 */
	protected $response = array();

	/**
	 * The error message recorded when email sending failed
	 *
	 * @var string
	 */
	protected $error_message = '';

	/**
	 * Request body
	 *
	 * @var array
	 */
	protected $body = array();

	/**
	 * Set the mailer (default or egoi)
	 *
	 * @var string
	 */
	protected $mailer = 'egoi';

	/**
	 * Successful response code
	 *
	 * @var int
	 */
	protected $email_sent_code = 201;

	/**
	 * URL to make an API request to.
	 * Transactional API V2
	 *
	 * @var string
	 */
	protected $url = 'https://slingshot.egoiapp.com/api/v2/email/messages/action/send';

	/**
	 * Mailer constructor.
	 *
	 * @param MailCatcherInterface $phpmailer The MailCatcher object.
	 */
	public function __construct( MailCatcherInterface $phpmailer ) {

		$this->process_phpmailer( $phpmailer );
	}


	/**
	 * Re-use the MailCatcher class methods and properties.
	 * Construct the request body
	 *
	 * @param MailCatcherInterface $phpmailer The MailCatcher object.
	 */
	public function process_phpmailer( $phpmailer ) {

		$this->phpmailer          = $phpmailer;
		$transactionalEmailOption = get_option( 'egoi_transactional_email' );

		$this->set_recipients(
			array(
				'to'  => $this->phpmailer->getToAddresses(),
				'cc'  => $this->phpmailer->getCcAddresses(),
				'bcc' => $this->phpmailer->getBccAddresses(),
			)
		);
		$this->set_from( $transactionalEmailOption['fromId'], $transactionalEmailOption['fromname'] );
		$this->set_subject( $this->phpmailer->Subject );
		$this->set_content( $this->phpmailer->Body, $this->phpmailer->ContentType );
		$this->set_attachments( $this->phpmailer->getAttachments() );

	}

	/**
	 * Redefine the way email body is returned.
	 * By default we are sending an array of data.
	 * E-goi requires a JSON, so we encode the body.
	 */
	public function get_body() {

		$body = apply_filters( 'egoi_transactional_email_mailer_get_body', $this->body, $this->mailer );

		return wp_json_encode( array( $body ) );
	}

	/**
	 * Set sender id and sender name
	 */
	public function set_from( $id, $name = '' ) {

		// verify if id not emepty
		if ( empty( $id ) ) {
			return;
		}

		if ( ! empty( $name ) ) {

			$this->set_body_param(
				array(
					'senderId'   => $id,
					'senderName' => $name,
				)
			);

		} else {
			$this->set_body_param(
				array(
					'senderId' => $id,
				)
			);
		}
	}

	/**
	 * set the attributes to, cc and bcc
	 */
	public function set_recipients( $recipients ) {

		if ( empty( $recipients ) ) {
			return;
		}

		// Allow for now only these recipient types.
		$default = array( 'to', 'cc', 'bcc' );
		$data    = array();

		foreach ( $recipients as $type => $emails ) {
			if (
				! in_array( $type, $default, true ) ||
				empty( $emails ) ||
				! is_array( $emails )
			) {
				continue;
			}

			$data[ $type ] = array();

			// Iterate over all emails for each type.
			// There might be multiple cc/to/bcc emails.
			foreach ( $emails as $email ) {
				$addr = isset( $email[0] ) ? $email[0] : false;

				if ( ! filter_var( $addr, FILTER_VALIDATE_EMAIL ) ) {
					continue;
				}

				array_push( $data[ $type ], $addr );
			}

			$this->set_body_param(
				array(
					$type => $data[ $type ],
				)
			);
		}
	}

	/**
	 * Set email content
	 * textBody or htmlBody
	 */
	public function set_content( $content, $type ) {

		if ( empty( $content ) ) {
			return;
		}

		if ( $type === 'text/plain' ) {
			$this->set_body_param(
				array(
					'textBody' => $content,
				)
			);
		} else {
			$this->set_body_param(
				array(
					'htmlBody' => $content,
				)
			);
		}

	}

	/**
	 * E-goi accepts an array of files content in body, so we will include all files and send.
	 * to-do: Modificar attachment de acordo com a transactional
	 *
	 * @param array $attachments
	 */
	public function set_attachments( $attachments ) {
		if ( empty( $attachments ) ) {
			return;
		}

		$data = array();

		foreach ( $attachments as $attachment ) {
			$file = false;

			try {
				if ( is_file( $attachment[0] ) && is_readable( $attachment[0] ) ) {
					$file = file_get_contents( $attachment[0] ); // phpcs:ignore
				}
			} catch ( \Exception $e ) {
				$file = false;
			}

			if ( $file === false ) {
				continue;
			}

			$data[] = array(
				'filename'    => $attachment[2],
				'data'        => base64_encode( $file ),
				'mimeType'    => $attachment[4],
				'disposition' => 'attachment',
			);

		}

		if ( ! empty( $data ) ) {
			$this->set_body_param(
				array(
					'attachments' => $data,
				)
			);
		}
	}

	/**
	 * Set the email subject
	 */
	public function set_subject( $subject ) {

		$this->body['subject'] = $subject;
	}

	/**
	 * Get an E-goi response with a helpful error.
	 */
	public function get_response(){ // phpcs:ignore

		return $this->response;
	}

	/**
	 * Set the request params, that goes to the body of the HTTP request.
	 *
	 * @param array $param Key=>value of what should be sent to a 3rd party API.
	 *
	 * @internal param array $params
	 */
	protected function set_body_param( $param ) {

		$this->body = $this->array_merge_recursive( $this->body, $param );
	}

	/**
	 * Merge recursively, including a proper substitution of values in sub-arrays when keys are the same.
	 * It's more like array_merge() and array_merge_recursive() combined.
	 *
	 * @return array
	 */
	public static function array_merge_recursive() {

		$arrays = func_get_args();

		if ( count( $arrays ) < 2 ) {
			return isset( $arrays[0] ) ? $arrays[0] : array();
		}

		$merged = array();

		while ( $arrays ) {
			$array = array_shift( $arrays );

			if ( ! is_array( $array ) ) {
				return array();
			}

			if ( empty( $array ) ) {
				continue;
			}

			foreach ( $array as $key => $value ) {
				if ( is_string( $key ) ) {
					if (
						is_array( $value ) &&
						array_key_exists( $key, $merged ) &&
						is_array( $merged[ $key ] )
					) {
						$merged[ $key ] = call_user_func( __METHOD__, $merged[ $key ], $value );
					} else {
						$merged[ $key ] = $value;
					}
				} else {
					$merged[] = $value;
				}
			}
		}

		return $merged;
	}

	/**
	 * Sanitize the value, similar to `sanitize_text_field()`, but a bit differently.
	 * It preserves `<` and `>` for non-HTML tags.
	 *
	 * @param string $value String we want to sanitize.
	 *
	 * @return string
	 */
	public static function sanitize_value( $value ) {

		// Remove HTML tags.
		$filtered = wp_strip_all_tags( $value, false );
		// Remove multi-lines/tabs.
		$filtered = preg_replace( '/[\r\n\t ]+/', ' ', $filtered );
		// Remove whitespaces.
		$filtered = trim( $filtered );

		// Remove octets.
		$found = false;
		while ( preg_match( '/%[a-f0-9]{2}/i', $filtered, $match ) ) {
			$filtered = str_replace( $match[0], '', $filtered );
			$found    = true;
		}

		if ( $found ) {
			// Strip out the whitespace that may now exist after removing the octets.
			$filtered = trim( preg_replace( '/ +/', ' ', $filtered ) );
		}

		return $filtered;
	}

	/**
	 * Send the email.
	 *
	 * Set the headers, body and method
	 */
	public function send() {

		$params = $this->array_merge_recursive(
			$this->get_default_params(),
			array(
				'headers' => array(
					'Content-Type' => 'application/json',
					'ApiKey'       => get_option( 'egoi_api_key' )['api_key'],
				),
				'body'    => $this->get_body(),
			)
		);

		$response = wp_safe_remote_post( $this->url, $params );

		$this->process_response( $response );
	}

	/**
	 * We might need to do something after the email was sent to the API.
	 * In this method we preprocess the response from the API.
	 *
	 * @param mixed $response
	 */
	protected function process_response( $response ) {

		if ( is_wp_error( $response ) ) {
			// Save the error text.
			$errors = $response->get_error_messages();
			foreach ( $errors as $error ) {
				$this->error_message .= $error . PHP_EOL;
			}

			return;
		}

		if ( isset( $response['body'] ) ) {
			$response['body'] = \json_decode( $response['body'] );
		}

		$this->response = $response;
	}

		/**
		 * Get the default params, required for wp_safe_remote_post().
		 *
		 * @return array
		 */
	protected function get_default_params() {

		return apply_filters(
			'egoi_mail_transactional_email_providers_mailer_get_default_params',
			array(
				'timeout'     => 30,
				'httpversion' => '1.1',
				'blocking'    => true,
			),
			$this->mailer
		);
	}
}
