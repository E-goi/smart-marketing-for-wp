<?php
// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

require_once plugin_dir_path( __FILE__ ) . '../includes/class-egoi-for-wp-apiv3.php';

/**
 * Class responsible to handle public interactions with plugin
 */
class Egoi_For_Wp_Public {

	protected $egoiWpApiV3;
	
	/**
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name                    = 'Smart Marketing for WordPress';
	const DISABLE_SUBSCRIBER_BAR_PERMISSION = 'egoi_disable_sub_bar';
    const WP_KSES_OPTION_SIMPLE_FORM = [
        'form'      => [ 'id' => [], 'class'=>[], 'method' => [], 'action' => [] ],
        'div'       => [ 'id'=> [], 'class'=>[], 'style'=> [] ],
        'script'    => [ 'type' => [] ],
        'p'         => [],
        'button'    => [ 'type' => [], 'style'=>[], 'class' => [], 'id' => [] ],
        'label'     => [ 'for' => [],'style'=>[] ],
        'input'     => [ 'style' => [] ,'type' => [], 'name' => [],'id' => [], 'class' => [], 'value'=>[] ],
		'select' 	=> [ 'id'=> [], 'class'=>[], 'name'=> [] ],
    ];
	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string $plugin_name       The name of the plugin.
	 * @param    string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name = '', $version = '' ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		$apikey = $this->getApiKey();

		if ( ! empty( $apikey ) ) {
			$this->egoiWpApiV3 = new EgoiApiV3( $apikey );
		}

		
		add_filter( 'shortcode_instance', array( 'Egoi_For_Wp_Public', 'get_shortcode' ) );
	}

	public function get_shortcode() {

		return $this;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		$bar_post = get_option( Egoi_For_Wp_Admin::BAR_OPTION_NAME );
		if ( ! wp_style_is( 'dashicons', 'enqueued' ) ) {
			wp_enqueue_style( 'dashicons' );
		}

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/egoi-for-wp-public.css', array(), $this->version, false );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		$bar_post = get_option( Egoi_For_Wp_Admin::BAR_OPTION_NAME );

		if ( isset( $bar_post['enabled'] ) && ( $bar_post['enabled'] ) ) {
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/e-goi.min.js', array( 'jquery' ), $this->version, false );
			wp_localize_script( $this->plugin_name, 'url_egoi_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		}
	}

	/**
	 * Get the api key
	 * 
	 * @return string
	 */
	public function getApiKey()
	{
		$apikey = get_option( 'egoi_api_key' );
		if ( ! empty( $apikey['api_key'] ) ) {
			return $apikey['api_key'];
		}
		return false;
	}

	/*
	*	Generate E-goi bar
	*/
	public function generate_bar( $regenerate = null ) {
		if ( current_user_can( self::DISABLE_SUBSCRIBER_BAR_PERMISSION ) ) {
			return false;}
		$bar_post = get_option( Egoi_For_Wp_Admin::BAR_OPTION_NAME );


		// add new tag to E-goi
		if ( isset($bar_post['tag-egoi']) && $bar_post['tag-egoi'] != '' ) {
			$tag  = $bar_post['tag-egoi'];
		} else {
			$tag = 0;
		}

		// if defined some redirection
		if (isset($bar_post['redirect']) ) {
			if ( isset($_POST['egoi_action_sub']) ) {
				$this->subscribe();
			}
		}

		if ( isset($bar_post['open']) ) {

			$enable = '0';
			$hidden = '';
			setcookie( 'hide_bar', $enable, 1 );
			$_COOKIE['hide_bar'] = $enable;

		} else {

			$enable = '';
			$hidden = '';

			if ( isset( $_COOKIE['hide_bar'] ) && ( $_COOKIE['hide_bar'] == 1 ) ) {
				$hidden = 'display:none;';
			}
		}

		if ( isset($bar_post['sticky']) ) {
			$id_tab = 'tab_egoi_footer_fixed';
		} else {
			$id_tab = 'tab_egoi_footer';
		}

		$bar_post['color_bar'] = ! empty( $bar_post['color_bar_transparent'] ) ? $bar_post['color_bar'] : 'transparent';

		$bar_post['lang'] = isset($bar_post['lang'] ) ? $bar_post['lang'] : 'pt' ;

		$bar_post['double_optin'] = isset($bar_post['double_optin'] ) ? $bar_post['double_optin'] : 0 ;

        ?>
        <!-- Smart Marketing Bar -->
        <?php

        ?>
        <div id="smart-marketing-egoi">
        <?php

		if ( isset($bar_post['position']) && $bar_post['position'] == 'top' ) {
            ?>
			<span style="display:none;" id="e-goi-bar-session"><?php echo esc_attr($enable) ?></span>
			<div class="egoi-bar" id="egoi-bar" style="<?php echo esc_attr($hidden) ?>">
				<input type="hidden" name="list" value="<?php echo esc_attr($bar_post['list']) ?>">
				<input type="hidden" name="tag" value="<?php echo esc_attr($tag) ?>">
				<input type="hidden" name="double_optin" value="<?php echo esc_attr($bar_post['double_optin']) ?>">
				<label class="egoi-label" style="display:inline-block;"><?php echo esc_attr($bar_post['text_bar']) ?></label>
				<input type="email" name="email" placeholder="<?php echo esc_attr($bar_post['text_email_placeholder']) ?>" class="egoi-email" required>
				<input type="button" class="egoi_sub_btn" value="<?php echo esc_attr($bar_post['text_button']) ?>" />
				<span id="process_data_egoi" class="loader_btn_egoi" style="display:none;"></span>
			</div>
			<span class="egoi-action" id="tab_egoi" style="background:<?php echo esc_attr($bar_post['color_bar']) ?>;"></span>
        <?php
		} else {
        ?>
			<span style="display:none;" id="e-goi-bar-session"><?php echo esc_attr($enable) ?></span>
				<span class="egoi-bottom-action" id="<?php echo esc_attr($id_tab) ?>" style="background:<?php echo esc_attr($bar_post['color_bar']) ?>;"></span>
				<div class="egoi-bar" id="egoi-bar" style="<?php echo esc_attr($hidden) ?>">
					<input type="hidden" name="list" value="<?php echo esc_attr($bar_post['list']) ?>">
					<input type="hidden" name="lang" value="<?php echo esc_attr($bar_post['lang']) ?>">
					<input type="hidden" name="tag" value="<?php echo esc_attr($tag) ?>">
				    <input type="hidden" name="double_optin" value="<?php echo esc_attr($bar_post['double_optin']) ?>">
					<label class="egoi-label"><?php echo esc_attr($bar_post['text_bar']) ?></label>
					<input type="email" name="email" placeholder="<?php echo esc_attr($bar_post['text_email_placeholder']) ?>" class="egoi-email" required style="display:inline-block;width:20%;">
					<input type="button" class="egoi_sub_btn" disabled value="<?php echo esc_attr($bar_post['text_button']) ?>" />
					<span id="process_data_egoi" class="loader_btn_egoi" style="display:none;"></span>
				</div>
        <?php
		}
        ?>
        </div>
        <!-- / Smart Marketing Bar -->
        <?php

		$this->set_custom_css( $bar_post );
		$this->set_custom_script();

		if ( $regenerate ) {
			exit;
		}
	}

	private function set_custom_script() {
		?>
		<script>
			jQuery(document).ready(function() {
				(function ($) {
					let button = $("#egoi-bar>.egoi_sub_btn")
					let email = $("#egoi-bar>.egoi-email");
					email.on('keyup', function(){
						if(email.val() == "" || email.val() == undefined){
							button.attr("disabled", true);
						}else{
							button.attr("disabled", false);
						}

					})
				})(jQuery);
			});
		</script>
		<?php
	}
	private function set_custom_css( $css ) {

		$position = 'absolute';
		if ( isset($css['sticky']) ) {
			$position = 'fixed';
		}

		if ( isset($css['position']) && $css['position'] == 'top' ) {
			$top    = 'top: 0;';
			$border = 'border-bottom';
		} else {
			$top      = 'bottom: 0;';
			$border   = 'border-top';
			$position = 'relative';
			if ( isset($css['sticky']) ) {
				$position = 'fixed';
				$tab_bar  = 'position:fixed !important; bottom:50px;';
			}
		}
        ?>
		<style type="text/css">

			.egoi-bar {
                left: 0px;
                background:<?php echo esc_attr($css['color_bar'])?> !important;
                <?php echo esc_attr($border)?>:<?php echo esc_attr($css['border_px'])?> solid <?php echo esc_attr($css['border_color'])?> !important;
                <?php echo esc_attr($top)?>
                position:<?php echo esc_attr($position)?>;
            }
			.egoi-label {
                color: <?php echo esc_attr($css['bar_text_color']) ?> !important;
            }

			.egoi-close {
                <?php if(isset($tab_bar)){ echo esc_attr($tab_bar); } ?>
                background-color:<?php echo esc_attr($css['color_bar']); ?>;
                border-left:<?php echo esc_attr($css['border_px']); ?> solid <?php echo esc_attr($css['border_color']); ?> !important;
            }

			.egoi_sub_btn {
                color: <?php echo esc_attr($css['color_button_text']); ?> !important;
                background-color: <?php echo esc_attr($css['color_button']); ?> !important;
                padding: 2px 5px !important;
                height: 30px !important;
            }

        </style>
	    <?php
    }

	public function get_bar( $element_id = 'egoi_bar_sync', $submitted = '' ) {
		global $_egoiFiltersbar;

		if ( ! isset( $_egoiFiltersbar ) ) {
			$_egoiFiltersbar = true;
		}

		global $error;
		if ( $_egoiFiltersbar == true ) {
			$_egoiFiltersbar = false;
			$this->generate_bar( $submitted );
		}
	}

	public function subscribe() {

		$bar = get_option( Egoi_For_Wp_Admin::BAR_OPTION_NAME );

		$action = sanitize_text_field( $_POST['action'] );
		$email  = sanitize_email( $_POST['email'] );

		if ( empty( $email ) ) {
            ?>
			<div class="egoi-bar" id="egoi-bar" style="background:<?php echo esc_attr($bar['error_bgcolor']) ?> !important;border:none!important;"><div class="egoi-bar-error"><?php _e( 'Email can not be empty', 'egoi-for-wp' ) ?></div>
                <span class="egoi-action-error <?php echo $bar['position'] == 'top' ?'top':'bottom' ?>" id="tab_egoi_submit_close"></span>
            </div>
			<?php
			exit;
		}

		$fname = explode( '@', $email );
		$name  = $fname[0];


		if ( isset( $bar['tag-egoi'] ) && $bar['tag-egoi'] != '' ) {
			$tag = $bar['tag-egoi'];
		}

		if ( $action ) {

			$error = '';
			if ( empty( $email ) ) {
				$error = isset($bar['text_email_placeholder']) ? $bar['text_email_placeholder'] : __( 'Email can not be empty', 'egoi-for-wp' );
			}

			if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
				$error = isset($bar['text_email_placeholder']) ? $bar['text_email_placeholder'] : __( 'Invalid email', 'egoi-for-wp' );
			}

			$get = $this->egoiWpApiV3->searchContact($bar['list'], $email);

			if ( ! empty( $get) ) {
				$error = isset($bar['text_already_subscribed']) ? $bar['text_already_subscribed'] : __( 'Already subscribed', 'egoi-for-wp' );

			} else {

				$status = ! isset( $bar['double_optin'] ) || $bar['double_optin'] == 0 ? 'active' : 'unconfirmed';
	
				$add = $this->egoiWpApiV3->addContact($bar['list'], $email, $name, '', array(), 0, array(), $status, array(intval( $tag )));

				if ( isset($add) && !isset($add['error'])) {
					$client = new Egoi_For_Wp();
					$client->smsnf_save_form_subscriber( 1, 'bar', $add, $bar['list'], $email );

					$success = isset($bar['text_subscribed']) ? $bar['text_subscribed'] : __( 'Subscribed', 'egoi-for-wp' );

					?>
					<div class="egoi-bar" id="egoi-bar" style="background:<?php echo esc_attr($bar['success_bgcolor']); ?> !important;border:none!important;"><div class="egoi-bar-success"><?php echo esc_textarea($success); ?></div>
						<span class="egoi-action-success" id="tab_egoi_submit_close"></span>
					</div>
					<?php
				}
			}

			if ( isset($error) && !empty($error)) {
                ?>
				<div class="egoi-bar" id="egoi-bar" style="background:<?php echo esc_attr($bar['error_bgcolor']); ?> !important;border:none!important;"><div class="egoi-bar-error"><?php echo esc_textarea($error); ?></div>
                    <span class="egoi-action-error <?php echo $bar['position'] == 'top' ?'top':'bottom' ?>" id="tab_egoi_submit_close"></span>
                </div>
				<?php
			}
			
		}
		exit;
	}

	// Simple form shortcode output
	public function subscribe_egoi_simple_form( $atts, $qt = 1 ) {
		global $wpdb;

		if ( empty( $atts['id'] ) || wp_is_json_request()) {
			return;
		}

		$id                 = sanitize_key( $atts['id'] );
		$simple_form        = 'egoi_simple_form_' . $id . '_' . $qt;
		$simple_form_result = $simple_form . '_result';
		$options            = get_option( 'egoi_simple_form_' . $id );
		$data               = json_decode( $options );

		$table     = $wpdb->prefix . 'posts';
		$html_code = $wpdb->get_row( " SELECT post_content, post_title FROM $table WHERE ID = '$id' " );
        if(empty($html_code)){
            return;
        }
		$tags      = array( 'name', 'email', 'mobile', 'submit' );
		foreach ( $tags as $tag ) {
			$html_code->post_content = str_replace( '[e_' . $tag . ']', '', $html_code->post_content );
			$html_code->post_content = str_replace( '[/e_' . $tag . ']', '', $html_code->post_content );
		}

        $content = '<form id="'.esc_attr( $simple_form ).'" class="egoi_simple_form" method="post" action="/">
			<input type="hidden" name="egoi_simple_form" id="egoi_simple_form" value="' . esc_attr( $id ) . '">
			<input type="hidden" name="egoi_list" id="egoi_list" value="'.esc_attr( $data->list ).'">
			<input type="hidden" name="egoi_tag" id="egoi_tag" value="'.esc_attr( $data->tag ).'">
			<input type="hidden" name="egoi_double_optin" id="egoi_double_optin" value="'.esc_attr( $data->double_optin ).'">
			<input type="hidden" name="validation_status" id="validation_status" value="valid">
			'. wp_kses($html_code->post_content, self::WP_KSES_OPTION_SIMPLE_FORM) . '
			<div id="'.esc_attr( $simple_form_result ).'" class="egoi_simple_form_success_wrapper" style="margin:10px 0px; padding:12px; display:none;"></div>
		</form>';
?>

		<script type="text/javascript" >
			 jQuery(document).ready(function() {
			 	jQuery("#<?php echo esc_attr( $simple_form ); ?> select[name=egoi_country_code]").empty();
			 <?php

            $countryStore = '';
            if ( class_exists( 'woocommerce' ) ) {
                $countryStore = wc_get_base_location()['country'];
            }
			foreach ( unserialize( EFWP_COUNTRY_CODES ) as $key => $value ) {
				$string = ucwords( strtolower( $value['country_pt'] ) ) . ' (+' . $value['prefix'] . ')';
				if ( $countryStore == $key ) {// selects store country code by default
					?>
				jQuery("#<?php echo esc_attr( $simple_form ); ?> select[name=egoi_country_code]").append("<option selected value=<?php echo esc_attr( $value['prefix'] ); ?>><?php echo esc_textarea( $string ); ?></option>");
					<?php
				} else {
					?>
				 jQuery("#<?php echo esc_attr( $simple_form ); ?> select[name=egoi_country_code]").append("<option value=<?php echo esc_attr( $value['prefix'] ); ?>><?php echo esc_textarea( $string ); ?></option>");
					<?php
				}
			}
			?>


		jQuery("#<?php echo esc_attr( $simple_form ); ?>").submit(function(event) {
                var simple_form = jQuery(this);
                var isValid = true;
                var validationStatus = simple_form.find('input[name="validation_status"]');

                // Check required fields
                simple_form.find('input, select, textarea').each(function() {
                    var field = jQuery(this);
                    var label = simple_form.find('label[for="' + field.attr('id') + '"]');
                    if (label.length && label.text().includes('*')) {
                        if (field.val().trim() === '') {
                            isValid = false;
                            label.css('color', 'red'); // Highlight label in red if the field is empty
                        } else {
                            label.css('color', ''); // Remove highlight if filled correctly
                        }
                    }
                });

                if (!isValid) {
                    event.preventDefault(); // Prevent form submission
                    alert('<?php _e('Please fill out all required fields(*).', 'egoi-for-wp'); ?>');
                    validationStatus.val('invalid');
                } else {
                    validationStatus.val('valid');

					var simple_form = jQuery(this);
					event.preventDefault(); // Stop form from submitting normally

					var button_obj = jQuery("button[type=submit]", "#<?php echo esc_attr( $simple_form ); ?>");
					var button_original_style = button_obj.attr("style");
					var button_style = button_obj.css(["width", "height"]);
					var button_text = button_obj.text();

					var max = 3;
					var i = 2;
					button_obj.text(".").prop("disabled",true).css(button_style);
					var button_effect = setInterval(function () {
						if (i <= max) {
							button_obj.text(".".repeat(i)).prop("disabled",true).css(button_style);
							i++;
						} else {
							button_obj.text(".").prop("disabled",true).css(button_style);
							i=2;
						}
					}, 400);


					jQuery( "#<?php echo esc_attr( $simple_form_result ); ?>" ).hide();

					var ajaxurl = "<?php echo admin_url( 'admin-ajax.php' ); ?>";
					var egoi_simple_form = jQuery("#<?php echo esc_attr( $simple_form ); ?> input[name=egoi_simple_form]").val();
					var egoi_name = jQuery("#<?php echo esc_attr( $simple_form ); ?> input[name=egoi_name]").val();
					var egoi_email = jQuery("#<?php echo esc_attr( $simple_form ); ?> input[name=egoi_email]").val();
					var egoi_country_code	= jQuery("#<?php echo esc_attr( $simple_form ); ?> select[name=egoi_country_code]").val();
					var egoi_mobile	= jQuery("#<?php echo esc_attr( $simple_form ); ?> input[name=egoi_mobile]").val();
					var egoi_list = jQuery("#<?php echo esc_attr( $simple_form ); ?> input[name=egoi_list]").val();
					var egoi_tag = jQuery("#<?php echo esc_attr( $simple_form ); ?> input[name=egoi_tag]").val();
					var egoi_double_optin = jQuery("#<?php echo esc_attr( $simple_form ); ?> input[name=egoi_double_optin]").val();

					var data = {
						"action": "egoi_simple_form_submit",
						"egoi_simple_form": egoi_simple_form,
						"egoi_name": egoi_name,
						"egoi_email": egoi_email,
						"egoi_country_code": egoi_country_code,
						"egoi_mobile": egoi_mobile,
						"egoi_list": egoi_list,
						"egoi_tag": egoi_tag,
						"egoi_double_optin" : egoi_double_optin
					};

						var posting = jQuery.post(ajaxurl, data);

						posting.done(function( data ) {
							if (data.substring(0, 5) != "ERROR" && data.substring(0, 4) != "ERRO") {

								var event = new Event("<?php echo esc_attr( $simple_form ); ?>");
								var elem = document.getElementsByTagName("html");
								elem[0].dispatchEvent(event);

								jQuery( "#<?php echo esc_attr( $simple_form_result ); ?>" ).css({
									"color": "#4F8A10",
									"background-color": "#DFF2BF"
								});

								jQuery( "#<?php echo esc_attr( $simple_form ); ?>" )[0].reset();

							} else {
								jQuery( "#<?php echo esc_attr( $simple_form_result ); ?>" ).css({
									"color": "#9F6000",
									"background-color": "#FFD2D2"
								});
							}

							jQuery( "#<?php echo esc_attr( $simple_form_result ); ?>" ).empty().append( data ).slideDown( "slow" );
							clearInterval(button_effect);
							if (button_original_style) {
								button_obj.prop("disabled",false).attr("style", button_original_style).html(button_text);
							} else {
								button_obj.prop("disabled",false).removeAttr("style").html(button_text);
							}
						});
                }
            });
        });
		</script>
		<?php
        return $content;
	}

	/**
	 * WPBakery Page Builder Shortcode output
	 */
	public function egoi_vc_shortcode_output( $atts, $content = null ) {

		if ( function_exists( 'vc_map_get_attributes' ) ) {
			// Extract shortcode attributes (based on the vc_lean_map function - see next function)
			extract( vc_map_get_attributes( 'egoi_vc_shortcode', $atts ) );

			echo wp_kses($this->subscribe_egoi_simple_form( array( 'id' => $shortcode_id ) ), self::WP_KSES_OPTION_SIMPLE_FORM);
		} else {
			return false;
		}

	}


	public function add_egoi_rss_feeds() {
		global $wpdb;
		$table   = $wpdb->prefix . 'options';
		$options = $wpdb->get_results( ' SELECT option_name FROM ' . $table . " WHERE option_name LIKE 'egoi_rssfeed_%' " );
		foreach ( $options as $option ) {
			add_feed( $option->option_name, 'egoi_rss_feeds' );
		}
	}

	public function egoi_add_newsletter_signup_hide() {
		if ( is_user_logged_in() ) {
			echo '<script>

            jQuery(document).ready(function() {
                (function ($) {
                    var check = $("#egoi_newsletter_active");
                    if(check.length > 0 && check.is(":checked")){
                        $($($(check.parent()).parent()).parent()).hide();
                    }
                })(jQuery);
            });

            </script>';

		}

		$this->egoi_add_newsletter_signup();
	}

	public function egoi_add_newsletter_signup() {
		$options = get_option( Egoi_For_Wp_Admin::OPTION_NAME );

		$fields = Egoi_For_Wp::egoi_subscriber_signup_fields();
		global $current_user;

		foreach ( $fields as $key => $field_args ) {
			if ( $current_user ) {
				$checked = get_user_meta( $current_user->ID, $key, true );
			}
			woocommerce_form_field( $key, $field_args, ( ( empty( $checked ) ? 0 : true ) || ( ! empty( $options[ $key ] ) ) ) );
		}
	}
	public function egoi_save_account_fields_order( $order_id ) {

		if ( is_user_logged_in() ) {
			return;
		} else { // guest buyer
			$options = $this->load_options();
			$user    = $_POST;
			$sub     = $this->get_default_map( $user );
			if ( get_option( 'egoi_mapping' ) ) {
				$sub = $this->egoi_map_subscriber( $user, $sub );
			}
		}
		$tags = array();
		$tag = $this->egoiWpApiV3->getTag( Egoi_For_Wp::GUEST_BUY );

		if ( isset( $tag->tag_id ) ) {
			array_push( $tags, $tag->tag_id );
		}

		if ( ! empty( $user_meta['egoi_newsletter_active'] ) || ! empty( $_POST['egoi_newsletter_active'] ) ) {
			$tag = $this->egoiWpApiV3->getTag( Egoi_For_Wp::TAG_NEWSLETTER );

			if ( isset( $tag->tag_id ) ) {
				array_push( $tags, $tag->tag_id );
			}
		}


		$this->egoiWpApiV3->addContact(
			$options['list'],
			$sub['email'],
			$sub['first_name'],
			$sub['last_name'],
			isset($sub['extra']) ? $sub['extra'] : array(),
			isset($sub['extra']) ? 1 : 0,
			array('cell' => $sub['cellphone']),
			'active',
			$tags
		);
	}

	public function egoi_save_account_fields( $customer_id ) {
		$fields         = Egoi_For_Wp::egoi_subscriber_signup_fields();
		$sanitized_data = array();

		foreach ( $fields as $key => $field_args ) {

			$sanitize = isset( $field_args['sanitize'] ) ? $field_args['sanitize'] : 'wc_clean';
			$value    = isset( $_POST[ $key ] ) ? call_user_func( $sanitize, $_POST[ $key ] ) : '';

			update_user_meta( $customer_id, $key, $value );
		}

		if ( ! empty( $sanitized_data ) ) {
			$sanitized_data['ID'] = $customer_id;
			wp_update_user( $sanitized_data );
		}
	}

	private function get_default_map( $subscriber ) {
		if ( is_array( $subscriber ) ) {
			return array(
					'email'      => empty( $subscriber['user_email'] ) ? $subscriber['billing_email'] : $subscriber['user_email'],
					'cellphone'  => empty( $subscriber['billing_phone'] ) ? Egoi_For_Wp::smsnf_get_valid_phone( $subscriber['shipping_phone'], ( empty( $subscriber['billing_country'] ) ? $subscriber['shipping_country'] : $subscriber['billing_country'] ) ) : Egoi_For_Wp::smsnf_get_valid_phone( $subscriber['billing_phone'] ),
					'first_name' => empty( $subscriber['first_name'] ) ? $subscriber['billing_first_name'] : $subscriber['first_name'],
					'last_name'  => empty( $subscriber['last_name'] ) ? $subscriber['billing_last_name'] : $subscriber['last_name'],		
			);
		}
	}

	private function egoi_map_subscriber( $subscriber, $defaultMap ) {

		$api = new Egoi_For_Wp();

		if ( class_exists( 'WooCommerce' ) ) {
			$wc          = new WC_Admin_Profile();
			$woocommerce = array();
			foreach ( $wc->get_customer_meta_fields() as $key => $value_field ) {
				foreach ( $value_field['fields'] as $key_value => $label ) {
					$row_new_value = $api->getFieldMap( 0, $key_value );
                    if(!empty($row_new_value)) {
                        if(is_array($row_new_value) && !empty($row_new_value['egoi'])) {
                            $woocommerce[ $row_new_value['egoi'] ] = $key_value;
                        } elseif(is_object($row_new_value) && !empty($row_new_value->egoi)) {
                            $woocommerce[ $row_new_value->egoi ] = $key_value;
                        } elseif ( !is_array($row_new_value) && !is_object($row_new_value)) {
                            $woocommerce[ $row_new_value ] = $key_value;
                        }
                    }
				}
			}
		}

		foreach ( $subscriber as $key => $value ) {
			$row = $api->getFieldMap( 0, $key );
            if(!empty($row)) {
                if(is_array($row) && !empty($row['egoi'])) {
                    $row = $row['egoi'];
                } elseif(is_object($row) && !empty($row->egoi)) {
                    $row = $row->egoi;
                }
            }

            if ( !empty($row) && !is_array($row) && !is_object($row)) {
				preg_match( '/^key_[0-9]+/', $row, $output );
				if ( count( $output ) > 0 ) {
					$defaultMap['extra'][ str_replace( 'key_', 'extra_', $row ) ] = $value;
				} else {
					$defaultMap['extra'][ $row ] = $value;
				}
			}
		}

		foreach ( $woocommerce as $key => $value ) {
			if ( isset( $subscriber->$value ) ) {
				$defaultMap['extra'][ str_replace( 'key', 'extra', $key ) ] = $subscriber->$value;
			} elseif ( isset( $subscriber[ $value ] ) && ! is_array( $subscriber[ $value ] ) ) {
				$defaultMap['extra'][ str_replace( 'key', 'extra', $key ) ] = $subscriber[ $value ];
			} elseif ( isset( $subscriber[ $value ][0] ) ) {
				$defaultMap['extra'][ str_replace( 'key', 'extra', $key ) ] = $subscriber[ $value ][0];
			}
		}

		return $defaultMap;
	}

	public function efwp_process_simple_form_add() {
		$api = new Egoi_For_Wp();

		// double opt-in
		$status    = sanitize_key( $_POST['egoi_double_optin'] ) == '1' ? 1 : 0;
		$form_data = array();

		if ( empty( $_POST['elementorEgoiForm'] ) ) {// old simple forms

			$form_data = array(
				'email'      => sanitize_email( $_POST['egoi_email'] ),
				'cellphone'  => empty($_POST['egoi_mobile']) ? '' : sanitize_text_field( $_POST['egoi_country_code'] . '-' . $_POST['egoi_mobile'] ),
				'first_name' => sanitize_text_field( stripslashes( $_POST['egoi_name'] ) ),
				'tags'       => array( sanitize_key( $_POST['egoi_tag'] ) ),
				'status'     => $status,
			);

			$refFields = array();

			if(isset($form_data['cellphone']) && ($form_data['cellphone'] != '-' || !empty($form_data['cellphone']))){
				$refFields['cell'] = $form_data['cellphone'];
			}

			$add = $this->egoiWpApiV3->addContact(
				sanitize_key( $_POST['egoi_list'] ),
				$form_data['email'],
				$form_data['first_name'],
				'',
				array(),
				$status,
				$refFields,
				$status == 0 ? 'active' : 'unconfirmed',
				$form_data['tags']
			);
		} else {
			$form_data = array(
				'email'      => sanitize_email( $_POST['email'] ),
				'cellphone'  => isset($_POST['cellphone']) ? sanitize_text_field( $_POST['cellphone'] ) : '',
				'first_name' => isset($_POST['first_name']) ? sanitize_text_field( stripslashes( $_POST['first_name'] ) ) : '',
				'last_name'  => isset($_POST['last_name']) ? sanitize_text_field( stripslashes( $_POST['last_name'] ) ) : '',
				'tags'       => isset($_POST['egoi_tag']) ? array( sanitize_key( $_POST['egoi_tag'] )) : array(),
				'birth_date' => isset($_POST['birth_date']) ? sanitize_text_field( stripslashes( $_POST['birth_date'] ) ) : '',
				'lang'		 => isset($_POST['lang']) ? sanitize_text_field( stripslashes( $_POST['lang'] ) ) : '',
				'status'     => $status,
			);

			$add = $this->egoiWpApiV3->addContact(
				sanitize_key( $_POST['egoi_list'] ),
				$form_data['email'],
				$form_data['first_name'],
				$form_data['last_name'],
				array_filter($_POST, function($val, $key){ if(preg_match('/^extra_/', $key)){return [$key => $val];}}, ARRAY_FILTER_USE_BOTH),
				1,
				array('cell' => $form_data['cellphone'], 'bd' => $form_data['birth_date'], 'lang' => $form_data['lang']), 
				$status == 0 ? 'active' : 'unconfirmed',
				$form_data['tags']
			);
		}

		if ( isset($add) && !isset($add['errors'])) {
			if ( empty( $_POST['elementorEgoiForm'] ) ) {
				$form_id = sanitize_key( $_POST['egoi_simple_form'] );
				$api->smsnf_save_form_subscriber( $form_id, 'simple-form', $add, $_POST['egoi_list'], $form_data['email'] );
			}

			_e( 'Contact was successfully registered!', 'egoi-for-wp' );

		} else {
			_e( 'ERROR: invalid data submitted', 'egoi-for-wp' );
		}

		wp_die(); // this is required to terminate immediately and return a proper response
	}

	public function hookEcommerce() {

		if ( is_admin() ) {
			return false;
		}

		if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
			return false;
		}

		$options = $this->load_options();

		$client_info     = get_option( 'egoi_client' );
		$client_id       = isset($client_info->CLIENTE_ID) ? $client_info->CLIENTE_ID : '';
		$track_social_id = ! empty( $options['social_track'] ) && ! empty( $options['social_track_id'] ) ? $options['social_track_id'] : null;

		if(empty($client_id)){
			return false;
		}
		
		require_once plugin_dir_path( __FILE__ ) . 'includes/TrackingEngageSDK.php';
		$track = new TrackingEngageSDK( $client_id, $options['list'], false, $track_social_id );
		if ( ! empty( $options['list'] ) && ! empty( $options['track'] ) && empty( $options['domain'] ) ) {
			$track->getStartUp();
		}
		if ( ! empty( $options['list'] ) && ! empty( $options['track'] ) && ! empty( $options['domain'] ) ) {
			$track->getStartUpCS( $options['domain'] );
		}
		if ( isset( $options['social_track'] ) && $options['social_track'] ) {
			$track->getStartUpSocial();
		}
		if ( class_exists( 'WooCommerce' ) && is_product() && isset( $options['social_track_json'] ) ) {
			$track->getProductLdJSON();
		}
	}

	public function hookEcommerceSetOrder( $order_id = false ) {

		$options = $this->load_options();

		if ( empty( $options['list'] ) || empty( $options['track'] ) ) {
			return false;} //list && tracking&engage setup check
		$client_info = get_option( 'egoi_client' );
		$client_id   = $client_info->CLIENTE_ID;

		require_once plugin_dir_path( __FILE__ ) . 'includes/TrackingEngageSDK.php';
		$track = new TrackingEngageSDK( $client_id, $options['list'], $order_id );
		$track->setOrder();
	}

	public function hookCartBackend() {
		$variations = $this->catalogsIsVariations();
		$options    = $this->load_options();
		require_once plugin_dir_path( __FILE__ ) . '../includes/class-egoi-for-wp-convert.php';
		$converter = new EgoiConverter( $options );
		$converter->convertCart( $variations );
		return true;
	}


	private function catalogsIsVariations() {
		require_once plugin_dir_path( __FILE__ ) . '../includes/class-egoi-for-wp-products-bo.php';
		$options_catalogs = EgoiProductsBo::getCatalogOptions();
		$options_catalogs = empty( $options_catalogs ) ? array() : $options_catalogs;
		$variation        = false;
		foreach ( $options_catalogs as $options_catalog ) {
			$variation = $variation || ( empty( $options_catalog['variations'] ) ? false : true );
		}
		return $variation;
	}

	public function hookEcommerceGetOrder( $order = false ) {
		$options = $this->load_options();

		if ( version_compare( get_option( 'woocommerce_version' ), '3.0.0', '>=' ) ) {
			if ( is_numeric( $order ) ) {
				$order_id = $order;
			} else {
				$order_id = $order->get_id();
			}
		} else {
			if ( is_numeric( $order ) ) {
				$order_id = $order;
			} else {
				$order_id = $order->id;
			}
		}

		if ( empty( $options['list'] ) || empty( $options['track'] ) ) {
			return false;
		} //list && tracking&engage setup check

		$client_info = get_option( 'egoi_client' );
		$client_id   = $client_info->CLIENTE_ID;
		require_once plugin_dir_path( __FILE__ ) . 'includes/TrackingEngageSDK.php';
		$track = new TrackingEngageSDK( $client_id, $options['list'], $order_id );
		$track->getOrder();
	}

	public function loadPopups() {

		require_once plugin_dir_path( __FILE__ ) . '../includes/class-egoi-for-wp-popup.php';

		$popups = EgoiPopUp::getSavedPopUps();

		foreach ( $popups as $popup_id ) {
			$popup = new EgoiPopUp( $popup_id );
			$popup->printPopup();
		}

	}

	private function load_options() {

		static $defaults = array(
			'list'                   => '',
			'enabled'                => 0,
			'egoi_newsletter_active' => 0,
			'track'                  => 1,
			'social-track'           => 0,
			'role'                   => 'All',
		);

		if ( ! get_option( Egoi_For_Wp_Admin::OPTION_NAME, array() ) ) {
			add_option( Egoi_For_Wp_Admin::OPTION_NAME, array( $defaults ) );
		} else {
			$options = (array) get_option( Egoi_For_Wp_Admin::OPTION_NAME, array() );

			$options = array_merge( $defaults, $options );
			return (array) apply_filters( 'egoi_sync_options', $options );
		}
	}

}
