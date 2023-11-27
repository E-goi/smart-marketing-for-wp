<?php
// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die();
}


/**
 * Transactional Email helper class
 */
class TransactionalEmailHelper {

	/**
	 *
	 * @var array List of plugins WP Mail SMTP may be conflicting with.
	 */
	public static $plugins = array(
		'swpsmtp_init_smtp'     => array(
			'name' => 'Easy WP SMTP',
		),
		'postman_start'         => array(
			'name' => 'Postman SMTP',
		),
		'post_start'            => array(
			'name' => 'Post SMTP Mailer/Email Log',
		),
		'mail_bank'             => array(
			'name' => 'WP Mail Bank',
		),
		'SMTP_MAILER'           => array(
			'name'  => 'SMTP Mailer',
			'class' => true,
		),
		'GMAIL_SMTP'            => array(
			'name'  => 'Gmail SMTP',
			'class' => true,
		),
		'WP_Email_Smtp'         => array(
			'name'  => 'WP Email SMTP',
			'class' => true,
		),
		'smtpmail_include'      => array(
			'name' => 'SMTP Mail',
		),
		'bwssmtp_init'          => array(
			'name' => 'SMTP by BestWebSoft',
		),
		'WPSendGrid_SMTP'       => array(
			'name'  => 'WP SendGrid SMTP',
			'class' => true,
		),
		'sar_friendly_smtp'     => array(
			'name' => 'SAR Friendly SMTP',
		),
		'WPGmail_SMTP'          => array(
			'name'  => 'WP Gmail SMTP',
			'class' => true,
		),
		'st_smtp_check_config'  => array(
			'name' => 'Cimy Swift SMTP',
		),
		'WP_Easy_SMTP'          => array(
			'name'  => 'WP Easy SMTP',
			'class' => true,
		),
		'WPMailgun_SMTP'        => array(
			'name'  => 'WP Mailgun SMTP',
			'class' => true,
		),
		'my_smtp_wp'            => array(
			'name' => 'MY SMTP WP',
		),
		'mail_booster'          => array(
			'name' => 'WP Mail Booster',
		),
		'Sendgrid_Settings'     => array(
			'name'  => 'SendGrid',
			'class' => true,
		),
		'WPMS_php_mailer'       => array(
			'name' => 'WP Mail Smtp Mailer',
		),
		'WPAmazonSES_SMTP'      => array(
			'name'  => 'WP Amazon SES SMTP',
			'class' => true,
		),
		'Postmark_Mail'         => array(
			'name'  => 'Postmark for WordPress',
			'class' => true,
		),
		'Mailgun'               => array(
			'name'  => 'Mailgun',
			'class' => true,
		),
		'WPSparkPost\SparkPost' => array(
			'name'  => 'SparkPost',
			'class' => true,
		),
		'WPYahoo_SMTP'          => array(
			'name'  => 'WP Yahoo SMTP',
			'class' => true,
		),
		'wpses_init'            => array(
			'name'  => 'WP SES',
			'class' => true,
		),
		'TSPHPMailer'           => array(
			'name' => 'turboSMTP',
		),
		'WP_SMTP'               => array(
			'name'  => 'WP SMTP',
			'class' => true,
		),
		'wp_mail_smtp'          => array(
			'name' => 'WP Mail SMTP',
		),
	);

	protected $conflict = array();

	public function __construct(){}

	/**
	 * Make a call to APIv3 to return the email senders list
	 */
	public function get_email_senders() {

		$apikey = $this->getApikey();

		$api = new EgoiApiV3( $apikey );
		return json_decode( $api->getSenders() );
	}

	/**
	 * Update egoi_transactional_email option
	 */
	public function update_egoi_transactional_email_option( $post, $senders ) {

		$transactionalEmailOptions = get_option( 'egoi_transactional_email' );
		$response                  = '';

		// Re-define transactional email options
		if ( $post['check_transactional_email'] == 1 ) {
			// If e-goi transactional email enable

			$transactionalEmailOptions['from']                      = sanitize_text_field( $post['from'] );
			$transactionalEmailOptions['mailer']                    = 'egoi';
			$transactionalEmailOptions['check_transactional_email'] = 1;

			// Define transactional email option - id and name
			foreach ( $senders as $sender ) {
				if ( $sender->email == $transactionalEmailOptions['from'] ) {
					$transactionalEmailOptions['fromId'] = $sender->sender_id;

					if ( $sender->name != null ) {
						$transactionalEmailOptions['fromname'] = $sender->name;
					} else {
						$transactionalEmailOptions['fromname'] = sanitize_text_field( $post['from'] );
					}
				}
			}

			$response = __( 'E-goi Transactional Email Configuration.', 'egoi-for-wp' );
		} else {
			// If e-goi disable

			$transactionalEmailOptions['from']                      = '';
			$transactionalEmailOptions['fromname']                  = '';
			$transactionalEmailOptions['mailer']                    = 'default';
			$transactionalEmailOptions['check_transactional_email'] = 0;

			$response = __( 'Default Email Configuration.', 'egoi-for-wp' );
		}

		update_option( 'egoi_transactional_email', $transactionalEmailOptions );

		echo get_notification( __( 'Success!', 'egoi-for-wp' ), __( $response, 'egoi-for-wp' ) );

	}

	/**
	 * send a test email
	 */
	public function send_test_email( $post ) {

		if ( ! filter_var( $post['to'], FILTER_VALIDATE_EMAIL ) ) {
			echo get_notification( __( 'Error!', 'egoi-for-wp' ), __( 'Invalid email.', 'egoi-for-wp' ), 'error' );
			return;
		}

		$mailResult = true;
		$mailResult = wp_mail( $post['to'], $post['subject'], $post['message'] );

		if ( $mailResult ) {
			echo get_notification( __( 'Success!', 'egoi-for-wp' ), __( 'Email sent successfully', 'egoi-for-wp' ) );
		} else {
			echo get_notification( __( 'Error!', 'egoi-for-wp' ), __( 'Error on sending email.', 'egoi-for-wp' ), 'error' );
		}

	}

	/**
	 * Obtain the api_key
	 */
	private function getApikey() {
		$apikey = get_option( 'egoi_api_key' );
		if ( ! empty( $apikey['api_key'] ) ) {
			return $apikey['api_key'];
		}
		return false;
	}

	// PLUGIN CONFLICT LOGIC ###

	/**
	 * Check if there are any conflits with other smtp email plugins
	 */
	public function is_conflict_detected() {
		foreach ( self::$plugins as $callback => $plugin ) {
			if ( ! empty( $plugin['class'] ) ) {
				$detected = class_exists( $callback, false );
			} else {
				$detected = function_exists( $callback );
			}

			if ( $detected ) {
				$this->conflict = $plugin;
				break;
			}
		}

		return ! empty( $this->conflict );
	}

	/**
	 * Get the conflicting plugin name
	 *
	 * @return null|string
	 */
	public function get_conflict_name() {

		$name = null;

		if ( ! empty( $this->conflict['name'] ) ) {
			$name = $this->conflict['name'];
		}

		return $name;
	}

	public function notify_conflict() {
		if ( empty( $this->conflict ) ) {
			return;
		}

		?>
			<div class="error notice">
				<p>
				<?php
				echo sprintf(
					esc_html__( 'E-goi Smart Marketing has detected %1$s is activated. Please deactivate %2$s to prevent conflicts with transactional email feature.', 'egoi-for-wp' ),
					$this->get_conflict_name(),
					$this->get_conflict_name()
				);
				?>
				 </p>
			</div>
		<?php

	}

	// Deal with transactional email error ###

	public function handle_error( $response ) {

		$body = $response['body'];

		if ( isset( $body->detail ) ) {
			$option = get_option( 'transactional_email_error_option' );
			if ( ! $option['active'] ) {
				$option['active'] = 1;
				$option['detail'] = $body->detail;
				update_option( 'transactional_email_error_option', $option );
			}
		} else {
			if ( ! $option['active'] ) {
				$option['active'] = 1;
				$option['detail'] = $body->error;
				update_option( 'transactional_email_error_option', $option );
			}
		}

	}
}
