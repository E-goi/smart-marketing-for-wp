<?php
// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

require_once plugin_dir_path( __FILE__ ) . '../class-egoi-for-wp-apiv3.php';

/**
 * Campaign widget Class
 */
class CampaignWidget {

	const CREATE_CAMPAIGN_EMAIL   = 'https://api.egoiapp.com/campaigns/email';
	const CREATE_CAMPAIGN_WEBPUSH = 'https://api.egoiapp.com/campaigns/web-push';
	const SEND_CAMPAIGN_EMAIL     = 'https://api.egoiapp.com/campaigns/email/';
	const SEND_CAMPAIGN_WEBPUSH   = 'https://api.egoiapp.com/campaigns/web-push/';
	const GET_WEBPUSH_SITES       = 'https://api.egoiapp.com/webpush/sites';

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
	  * Obtain the api_key
	  */
	private function getApikey() {
		$apikey = get_option( 'egoi_api_key' );
		if ( ! empty( $apikey['api_key'] ) ) {
			return $apikey['api_key'];
		}
		return false;
	}

	/**
	 * Add the meta box data to post pages
	 */
	public function email_campaign_widget_meta_box() {

		add_meta_box(
			'post_widget_custom',
			__( 'E-goi Campaign', 'egoi-for-wp' ),
			array( $this, 'email_campaign_widget_display_meta_box' ),
			'post',
			'side',
			'high'
		);

		// Then add our meta box for all other post types that are public but not built in to WordPress
		$args       = array(
			'public'   => true,
			'_builtin' => false,
		);
		$output     = 'names';
		$operator   = 'and';
		$post_types = get_post_types( $args, $output, $operator );
		foreach ( $post_types as $post_type ) {
			add_meta_box(
				'post_widget_custom',
				__( 'E-goi Email Campaign', 'egoi-for-wp' ),
				array( $this, 'email_campaign_widget_display_meta_box' ),
				$post_type,
				'side',
				'high'
			);
		}

	}

	/**
	 * Set the meta box display options
	 */
	public function email_campaign_widget_display_meta_box( $post ) {

		$apikey = $this->getApikey();
		$api = new EgoiApiV3( $apikey );

		// get contacts list
		$lists = $api->getLists();

		// get senders
		$senders = $this->get_email_senders();

		// Initialize variables to show and save on post_meta_data - FOR EMAIL
		$email_campaign_widget_checked                 = ( get_post_meta( $post->ID, 'email_campaign_widget', true ) === '1' );
		$email_campaign_widget_custom_contents_checked = ( get_post_meta( $post->ID, 'email_campaign_widget_modify_content', true ) === '1' );
		$email_campaign_widget_custom_content          = get_post_meta( $post->ID, 'email_campaign_widget_custom_content', true );
		$email_campaign_widget_custom_heading          = get_post_meta( $post->ID, 'email_campaign_widget_custom_heading', true );
		$email_campaign_widget_sender                  = get_post_meta( $post->ID, 'email_campaign_widget_sender', true );
		$email_campaign_widget_list_contacts           = get_post_meta( $post->ID, 'email_campaign_widget_list_contacts', true );

		// Initialize variables to show and save on post_meta_data - FOR WEBPUSH
		$webpush_campaign_widget_checked                 = ( get_post_meta( $post->ID, 'webpush_campaign_widget', true ) === '1' );
		$webpush_campaign_widget_custom_contents_checked = ( get_post_meta( $post->ID, 'webpush_campaign_widget_modify_content', true ) === '1' );
		$webpush_campaign_widget_custom_content          = get_post_meta( $post->ID, 'webpush_campaign_widget_custom_content', true );
		$webpush_campaign_widget_custom_heading          = get_post_meta( $post->ID, 'webpush_campaign_widget_custom_heading', true );

		$webpushsite  = get_option( 'egoi_webpush_code' );
        $options = get_option( 'egoi_sync' );
		$webpush_info = array();
		if ( isset( $webpushsite ) ) {
			$webpush_info = $this->get_webpush_info( $webpushsite, $lists );
		}
        if(!empty($options['domain'])){

            $webpush_info = $this->get_webpush_info_from_cs( $options['domain'], $options['list'] );
            if(empty($webpush_info)){
                unset($webpush_info);
            }
        }

		?>
			<!-- Email Campaign -->  
			<div style="padding-bottom:10px;">
				<label style="font-size: 14px;font-weight: bold;"><?php _e( 'Email Campaign', 'egoi-for-wp' ); ?></label>
			</div>
			<div id="div_email_campaign_widget">
				<label>
					<input type="checkbox" id="email_campaign_widget" name="email_campaign_widget" value="true" 
					<?php
					if ( $email_campaign_widget_checked ) {
						echo 'checked';
					}
					?>
					></input>

				<?php
				if ( $post->post_status === 'publish' ) {
					_e( 'Send Email Campaign on update', 'egoi-for-wp' );
				} else {
					_e( 'Send Email Campaign on publish', 'egoi-for-wp' );
				}
				?>

				</label>

			</div>
			
			<div id="email_campaign_widget_preferences" style="padding-bottom:10px;">
				<input type="checkbox" id="email_campaign_widget_modify_content" value="true" name="email_campaign_widget_modify_content" 
				<?php
				if ( $email_campaign_widget_custom_contents_checked ) {
						echo 'checked';
				}
				?>
					></input> <?php _e( 'Customize Email Campaign content', 'egoi-for-wp' ); ?></label>
						
				<div id="email_campaign_widget_custom_contents" style="display:none;padding-top:10px;">
					<div >
						<label><?php _e( 'Campaign Title', 'egoi-for-wp' ); ?><br/>
							<input type="text" size="16"  name="email_campaign_widget_custom_heading" value="
							<?php
								echo esc_attr( $email_campaign_widget_custom_heading );
							?>
							" id="email_campaign_widget_custom_heading" placeholder="<?php _e( 'Campaign title', 'egoi-for-wp' ); ?>"></input>
						</label>
					</div>
					<div>
						<label><?php _e( 'Campaign Subject', 'egoi-for-wp' ); ?><br/>
							<input type="text" size="16"  name="email_campaign_widget_custom_content" value="
							<?php
								echo esc_attr( $email_campaign_widget_custom_content );
							?>
							" id="email_campaign_widget_custom_content" placeholder="<?php _e( 'The Post\'s Current Title', 'egoi-for-wp' ); ?>"></input>
						</label>
					</div>
				</div>
			</div> 

			<div id="email_campaign_widget_configuration">
				<div>
					<label><?php echo _e( 'Contacts:', 'egoi-for-wp' ); ?><br/>
					<select id="email_campaign_widget_list_contacts" name="email_campaign_widget_list_contacts">
					<?php
					foreach ( $lists as $list ) {
						if ( isset($list['public_name']) ) {
							?>
							<option value="<?php echo esc_textarea( $list['list_id'] ); ?>" <?php selected( $email_campaign_widget_list_contacts, $list['list_id'] ); ?>>
								<?php echo esc_textarea( $list['public_name'] ); ?>
							</option>
							<?php
						}
					}
					?>
					</select>
					</label>
				</div>
							
				<div>
					<label><?php echo esc_attr_e( 'Sender:', 'egoi-for-wp' ); ?><br/>
							
					<select id="email_campaign_widget_sender" name="email_campaign_widget_sender">
					<?php
					foreach ( $senders as $sender ) {
						if ( $sender->email ) {
							?>
							<option value="<?php echo esc_attr( $sender->sender_id ); ?>" <?php selected( $email_campaign_widget_sender, $sender->sender_id ); ?>>
								<?php echo esc_textarea( $sender->email ); ?>
							</option>
							<?php
						}
					}
					?>
					</select>
					</label>
				</div>         
			</div>          

		<?php
	}

	 /**
	  * Save the meta when the post is saved.
	  */
	public function on_save_post( $post_id, $post, $updated ) {
		if ( $post->post_type === 'wdslp-wds-log' ) {
			// Prevent recursive post logging
			return;
		}

		/*
		* If this is an autosave, our form has not been submitted,
		* so we don't want to do anything.
		*/
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		$this->save_webpush_content( $post_id );
		$this->save_email_content( $post_id );

	}

	public function save_email_content( $post_id ) {
		if ( array_key_exists( 'email_campaign_widget', $_POST ) ) {
			update_post_meta( $post_id, 'email_campaign_widget', true );
			update_post_meta( $post_id, 'email_campaign_widget_sender', sanitize_key( $_POST['email_campaign_widget_sender'] ) );
			update_post_meta( $post_id, 'email_campaign_widget_list_contacts', sanitize_key( $_POST['email_campaign_widget_list_contacts'] ) );
		} else {
			update_post_meta( $post_id, 'email_campaign_widget', false );
		}

		if ( array_key_exists( 'email_campaign_widget_modify_content', $_POST ) ) {
			update_post_meta( $post_id, 'email_campaign_widget_modify_content', true );
			update_post_meta( $post_id, 'email_campaign_widget_custom_heading', sanitize_text_field( $_POST['email_campaign_widget_custom_heading'] ) );
			update_post_meta( $post_id, 'email_campaign_widget_custom_content', sanitize_textarea_field( $_POST['email_campaign_widget_custom_content'] ) );
		} else {
			update_post_meta( $post_id, 'email_campaign_widget_modify_content', false );
			update_post_meta( $post_id, 'email_campaign_widget_custom_heading', null );
			update_post_meta( $post_id, 'email_campaign_widget_custom_content', null );
		}
	}

	public function save_webpush_content( $post_id ) {
		if ( array_key_exists( 'webpush_campaign_widget', $_POST ) ) {
			update_post_meta( $post_id, 'webpush_campaign_widget', true );
			update_post_meta( $post_id, 'webpush_campaign_widget_site_info', sanitize_key( $_POST['webpush_campaign_widget_site_info'] ) );
		} else {
			update_post_meta( $post_id, 'webpush_campaign_widget', false );
		}

		if ( array_key_exists( 'webpush_campaign_widget_modify_content', $_POST ) ) {
			update_post_meta( $post_id, 'webpush_campaign_widget_modify_content', true );
			update_post_meta( $post_id, 'webpush_campaign_widget_custom_heading', sanitize_text_field( $_POST['webpush_campaign_widget_custom_heading'] ) );
			update_post_meta( $post_id, 'webpush_campaign_widget_custom_content', sanitize_textarea_field( $_POST['webpush_campaign_widget_custom_content'] ) );
		} else {
			update_post_meta( $post_id, 'webpush_campaign_widget_modify_content', false );
			update_post_meta( $post_id, 'webpush_campaign_widget_custom_heading', null );
			update_post_meta( $post_id, 'webpush_campaign_widget_custom_content', null );
		}
	}

	/**
	 * Creates email and webpush campaign and sends them
	 */
	public function create_campaign( $post ) {
		$was_posted = ! empty( $_POST );

		$email_campaign_widget_checked   = $was_posted && array_key_exists( 'email_campaign_widget', $_POST ) && $_POST['email_campaign_widget'] === 'true';
		$webpush_campaign_widget_checked = $was_posted && array_key_exists( 'webpush_campaign_widget', $_POST ) && $_POST['webpush_campaign_widget'] === 'true';

		if ( $email_campaign_widget_checked ) {
			$this->create_email_campaign( $post );
		}

		if ( $webpush_campaign_widget_checked ) {
			$this->create_webpush_campaign( $post );
		}
	}

	/**
	 * Creates and send email campaign
	 */
	public function create_email_campaign( $post ) {
		/* Returns true if there is POST data */
		$was_posted = ! empty( $_POST );

		if ( ! empty( get_post_meta( $post->ID, 'email_campaign_widget_sender', true ) ) ) {
			$email_campaign_widget_sender        = get_post_meta( $post->ID, 'email_campaign_widget_sender', true );
			$email_campaign_widget_list_contacts = get_post_meta( $post->ID, 'email_campaign_widget_list_contacts', true );
		} else {
			$email_campaign_widget_sender        = sanitize_text_field( $_POST['email_campaign_widget_sender'] );
			$email_campaign_widget_list_contacts = sanitize_key( $_POST['email_campaign_widget_list_contacts'] );
		}

		/* Check if the checkbox "Customize notification content" is selected */
		$email_campaign_widget_modify_content_checked = $was_posted && array_key_exists( 'email_campaign_widget_modify_content', $_POST ) && $_POST['email_campaign_widget_modify_content'] == 'true';

		// If this post is newly being created and if the user has chosen to customize the content
		$email_campaign_widget_modify_content = $email_campaign_widget_modify_content_checked || ( get_post_meta( $post->ID, 'email_campaign_widget_modify_content', true ) === '1' );

		if ( $was_posted && $email_campaign_widget_modify_content ) {
			$email_campaign_widget_custom_heading = sanitize_text_field( $_POST['email_campaign_widget_custom_heading'] );
			$email_campaign_widget_custom_content = sanitize_textarea_field( $_POST['email_campaign_widget_custom_content'] );
		} else {
			$email_campaign_widget_custom_heading = get_post_meta( $post->ID, 'email_campaign_widget_custom_heading', true );
			$email_campaign_widget_custom_content = get_post_meta( $post->ID, 'email_campaign_widget_custom_content', true );
		}

		// reset post meta data
		$this->default_meta_data( $post->ID, 'email', $was_posted );

		// Sets the title and content of campaign
		$title = $post->post_title;

		$content = get_the_content( $post );
		$content = preg_replace( '/(<figure.*?[^>]*>)(.*?)(<\/figure>)/i', '', $content );

		// If customization is checked
		if ( $email_campaign_widget_modify_content ) {
			$title   = $email_campaign_widget_custom_heading;
			$content = $email_campaign_widget_custom_content;
		}

		// get thumbnail
		$thumbnail = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );

		// get the first image of post content if there's no thumbnail
		if ( empty( $thumbnail ) ) {
			$content_aux = $post->post_content;
			$regex       = '/src="([^"]*)"/';
			preg_match_all( $regex, $content_aux, $matches );

			if ( ! empty( $matches ) ) {
				$thumbnail = $matches[0][0];
			}
		} else {
			$thumbnail = 'src="' . $thumbnail . '"';
		}
		$custom_logo_id = get_theme_mod( 'custom_logo' );
		$logo           = wp_get_attachment_image_src( $custom_logo_id, 'full' );
		$link           = home_url() . '/?page_id=' . $post->ID;

		$blog_info = array(
			'logo'        => $logo[0],
			'title'       => get_bloginfo( 'name' ),
			'description' => get_bloginfo( 'description' ),
			'url'         => $link,
		);

		$body = array(
			'list_id'       => $email_campaign_widget_list_contacts,
			'internal_name' => $title,
			'subject'       => $title,
			'content'       => array(
				'type' => 'html',
				'body' => $this->get_email_html_template( $title, $content, $thumbnail, $blog_info ),
			),
			'sender_id'     => $email_campaign_widget_sender,
			'reply_to'      => $email_campaign_widget_sender,
		);

		$response_aux = $this->make_request( $body, self::CREATE_CAMPAIGN_EMAIL );

		$response = json_decode( $response_aux, true );

		if ( isset( $response['campaign_hash'] ) ) {
			$campaign_hash = $response['campaign_hash'];
		}

		if ( ! isset( $campaign_hash ) ) {
			set_transient(
				'egoi_campaigns_error',
				'<div class="error notice">
                                    <p><strong>E-goi error creating campaign</strong><em> Error Status: ' . $response['status'] . '.</em></p>
                                    </div>'
			);
		} else {
			$data = array(
				'list' => $email_campaign_widget_list_contacts,
				'hash' => $campaign_hash,
			);

			add_option( 'egoi_email_campaign_' . $post->ID, $data );
		}

		return;
	}

	/**
	 * Creates and send webpush campaign
	 */
	public function create_webpush_campaign( $post ) {

		/* Returns true if there is POST data */
		$was_posted = ! empty( $_POST );

		$webpush_campaign_widget_site_info = sanitize_text_field( $_POST['webpush_campaign_widget_site_info'] );

		/* Check if the checkbox "Customize notification content" is selected */
		$webpush_campaign_widget_modify_content_checked = $was_posted && array_key_exists( 'webpush_campaign_widget_modify_content', $_POST ) && $_POST['email_campaign_widget_modify_content'] == 'true';

		// If this post is newly being created and if the user has chosen to customize the content
		$webpush_campaign_widget_modify_content = $webpush_campaign_widget_modify_content_checked || ( get_post_meta( $post->ID, 'webpush_campaign_widget_modify_content', true ) === '1' );

		if ( $was_posted && $webpush_campaign_widget_modify_content ) {
			$webpush_campaign_widget_custom_heading = sanitize_text_field( $_POST['webpush_campaign_widget_custom_heading'] );
			$webpush_campaign_widget_custom_content = sanitize_textarea_field( $_POST['webpush_campaign_widget_custom_content'] );
		} else {
			$webpush_campaign_widget_custom_heading = get_post_meta( $post->ID, 'webpush_campaign_widget_custom_heading', true );
			$webpush_campaign_widget_custom_content = get_post_meta( $post->ID, 'webpush_campaign_widget_custom_content', true );
		}

		// reset post meta data
		$this->default_meta_data( $post->ID, 'webpush', $was_posted );

		// Sets the title and content of campaign
		$title = $post->post_title;

		$content = wp_strip_all_tags( get_the_content( $post ) );

		// If customization is checked
		if ( $webpush_campaign_widget_modify_content ) {
			$title   = $webpush_campaign_widget_custom_heading;
			$content = $webpush_campaign_widget_custom_content;
		}

		$link = home_url() . '/?page_id=' . $post->ID;

		$body = array(
			'site_id'       => $webpush_campaign_widget_site_info,
			'internal_name' => $title,
			'content'       => array(
				'title'   => $title,
				'message' => $content,
				'link'    => $link,
			),
		);

		$response_aux = $this->make_request( $body, self::CREATE_CAMPAIGN_WEBPUSH );

		$response = json_decode( $response_aux, true );

		set_transient( 'egoi_ok1', $response['campaign_hash'] );

		if ( isset( $response['campaign_hash'] ) ) {
			$campaign_hash = $response['campaign_hash'];
		}

		if ( ! isset( $campaign_hash ) ) {
			set_transient(
				'egoi_campaigns_error',
				'<div class="error notice">
                <p><strong>E-goi error creating campaign</strong><em>' . $response['errors'] . '</em></p>
                </div>'
			);
		} else {
			$data = array(
				'site_id' => $webpush_campaign_widget_site_info,
				'hash'    => $campaign_hash,
			);

			add_option( 'egoi_webpush_campaign_' . $post->ID, $data );
		}

		return;
	}

	public function send_campaign( $post_id ) {
		if ( get_option( 'egoi_email_campaign_' . $post_id ) ) {
			$option = get_option( 'egoi_email_campaign_' . $post_id );
			$this->send_email_campaign( $option['list'], $option['hash'] );
			delete_option( 'egoi_email_campaign_' . $post_id );
		}

		if ( get_option( 'egoi_webpush_campaign_' . $post_id ) ) {
			$option = get_option( 'egoi_webpush_campaign_' . $post_id );
			$this->send_webpush_campaign( $option['site_id'], $option['hash'] );
			delete_option( 'egoi_webpush_campaign_' . $post_id );
		}

		return;
	}


	/**
	 * Sends the created email campaign.
	 */
	public function send_email_campaign( $list, $hash ) {

		$body = array(
			'list_id'  => $list,
			'segments' => array( 'type' => 'none' ),
		);

		$response_aux = $this->make_request( $body, self::SEND_CAMPAIGN_EMAIL . $hash . '/actions/send' );

		$response = json_decode( $response_aux, true );

		if ( ! isset( $response['result'] ) ) {
			set_transient(
				'egoi_campaigns_error',
				'<div class="error notice">
                        <p><strong>E-goi error sending campaign</strong><em> Error Status: ' . $response['status'] . '.</em></p>
                        </div>'
			);
		}

		return;
	}


	/**
	 * Sends the created webpush campaign.
	 */
	public function send_webpush_campaign( $site_id, $hash ) {

		$body = array(
			'site_id'  => $site_id,
			'segments' => array( 'type' => 'none' ),
		);

		$response_aux = $this->make_request( $body, self::SEND_CAMPAIGN_WEBPUSH . $hash . '/actions/send' );

		$response = json_decode( $response_aux, true );

		if ( ! isset( $response['result'] ) ) {
			set_transient(
				'egoi_campaigns_error',
				'<div class="error notice">
                        <p><strong>E-goi error sending campaign</strong><em> Error Status: ' . $response_body['status'] . '.</em></p>
                        </div>'
			);
		}

		return;
	}

	/**
	 * Reset post meta data settings
	 *
	 * @type : email or webpush
	 */
	public function default_meta_data( $post_id, $type, $was_posted ) {

		/*
		 Now that all settings are retrieved, and we are actually sending the notification, reset the post's metadata
		* If this post is sent through a plugin in the future, existing metadata will interfere with the send condition logic
		* If this post is re-sent through the WordPress editor, the metadata will be added back automatically
		*/
		update_post_meta( $post->ID, $type . '_campaign_widget', false );
		update_post_meta( $post->ID, $type . '_campaign_widget_list_contacts', null );
		update_post_meta( $post->ID, $type . '_campaign_widget_modify_content', false );
		update_post_meta( $post->ID, $type . '_campaign_widget_custom_heading', null );
		update_post_meta( $post->ID, $type . '_campaign_widget_custom_content', null );

		if ( $type == 'email' ) {
			update_post_meta( $post->ID, 'email_campaign_widget_sender', null );
		}

		if ( $type == 'webpush' ) {
			update_post_meta( $post->ID, 'webpush_campaign_widget_site_info', null );
		}

		/*
		 Some WordPress environments seem to be inconsistent about whether on_save_post is called before transition_post_status
		* This sets the metadata back to true, and will cause a post to be sent even if the checkbox is not checked the next time
		* We remove all related $_POST data to prevent this
		*/
		if ( $was_posted ) {
			if ( array_key_exists( $type . '_campaign_widget', $_POST ) ) {
				unset( $_POST[ $type . '_campaign_widget' ] );
			}
			if ( array_key_exists( $type . '_campaign_widget_list_contacts', $_POST ) ) {
				unset( $_POST[ $type . '_campaign_widget_list_contacts' ] );
			}
			if ( array_key_exists( $type . '_campaign_widget_modify_content', $_POST ) ) {
				unset( $_POST[ $type . '_campaign_widget_modify_content' ] );
			}
			if ( array_key_exists( $type . '_campaign_widget_custom_heading', $_POST ) ) {
				unset( $_POST[ $type . '_campaign_widget_custom_heading' ] );
			}
			if ( array_key_exists( $type . '_campaign_widget_custom_content', $_POST ) ) {
				unset( $_POST[ $type . '_campaign_widget_custom_content' ] );
			}

			if ( $type == 'email' ) {
				if ( array_key_exists( 'email_campaign_widget_sender', $_POST ) ) {
					unset( $_POST['email_campaign_widget_sender'] );
				}
			}

			if ( $type == 'webpush' ) {
				if ( array_key_exists( 'webpush_campaign_widget_site_info', $_POST ) ) {
					unset( $_POST['webpush_campaign_widget_site_info'] );
				}
			}
		}
	}

	/**
	 * Make request to APIV3
	 */
	public function make_request( $body, $url ) {

		$request = array(
			'headers' => array(
				'Content-Type' => 'application/json',
				'ApiKey'       => $this->getApikey(),
			),
			'body'    => wp_json_encode( $body ),
			'timeout' => 30,
		);

		$response = wp_remote_post( $url, $request );

		return wp_remote_retrieve_body( $response );
	}
    public function get_webpush_info_from_cs($domain, $list){
        $apikey  = $this->getApikey();
        $api     = new EgoiApiV3( $apikey );
        return $api->getWebpushSiteIdFromCS($domain, $list);
    }

	public function get_webpush_info( $webpushsite, $lists ) {

		$webpush_info = array();

		// get all websites - make request API v3
		$apikey  = $this->getApikey();
		$api     = new EgoiApiV3( $apikey );
		$w_sites = json_decode( $api->getWebPushSites() );

        if(!empty($w_sites)) {
            if (!empty($w_sites->status) && !empty($webpushsite['code'])) {
                foreach ($w_sites as $w_site) {
                    if ($w_site->app_code == $webpushsite['code']) {
                        $webpush_info['site_id'] = $w_site->site_id;
                        $webpush_info['site'] = $w_site->site;
                        $webpush_info['list_id'] = $w_site->list_id;
                    }
                }
            }

            if (!empty($webpush_info) && !empty($webpush_info['list_id'])) {
                foreach ($lists as $list) {
                    if ($list['list_id'] == $webpush_info['list_id']) {
                        $webpush_info['list'] = $list['public_name'];

                        return $webpush_info;
                    }
                }
            }
        }

		return [];
	}

	public function was_post_restored_from_trash( $old_status, $new_status ) {
		return $old_status === 'trash' && $new_status === 'publish';
	}

	/**
	 * When a post changes status to publish we create the campaign.
	 */
	public function on_transition_post_status( $new_status, $old_status, $post ) {
		if ( $post->post_type === 'wdslp-wds-log' ||
			$this->was_post_restored_from_trash( $old_status, $new_status ) ) {
			return;
		}

		if ( ! empty( $post ) &&
			$new_status === 'publish' ) {

			$this->create_campaign( $post );
			return;
		}

		if ( ! empty( $post ) &&
			$new_status === 'future' ) {
			$this->create_campaign( $post );
			return;
		}

		return;
	}

	/**
	 * Gets the html template
	 */
	public function get_email_html_template( $title, $content, $thumbnail, $blog_info ) {

		$template_file = apply_filters( 'egoi_email_widget_template', plugin_dir_path( __FILE__ ) . '../../admin/partials/emailcampaignwidget/email_campaign.php', $title, $content, $thumbnail );

		ob_start();
		include $template_file;
		$template = ob_get_contents();
		ob_end_clean();

		return $template;
	}

}
