<?php
// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die();
}
/*
*
* Class to estend Widgets in E-goi
*
*/
class Egoi4Widget extends WP_Widget {

	private $egoi_id;

	public function __construct() {
		$opt = get_option( 'egoi_widget' );

		if($opt){
			$this->widget_enabled = isset($opt['egoi_widget']['enabled']) ? $opt['egoi_widget']['enabled'] : 0;
			$this->redirect       = isset($opt['egoi_widget']['redirect']) ? $opt['egoi_widget']['redirect'] : '';
			$this->subscribed     = isset($opt['egoi_widget']['msg_subscribed']) ?  $opt['egoi_widget']['msg_subscribed'] : '';
			$this->input_width    = $opt['egoi_widget']['input_width'] ? 'width:' . $opt['egoi_widget']['input_width'] : '100%';
			$this->btn_width      = $opt['egoi_widget']['btn_width'] ? 'width:' . $opt['egoi_widget']['btn_width'] : '';
			$this->bcolor         = $opt['egoi_widget']['bcolor'] ? 'border: 1px solid ' . $opt['egoi_widget']['bcolor'] : '';
			$this->listID         = isset($opt['egoi_widget']['list']) ? $opt['egoi_widget']['list'] : 0;
			$this->lang           = isset($opt['egoi_widget']['lang']) ? $opt['egoi_widget']['lang'] : 'pt';
			$this->tag_egoi       = isset($opt['egoi_widget']['tag-egoi']) ? $opt['egoi_widget']['tag-egoi'] : '';
			$this->double_optin   = isset($opt['egoi_widget']['double_optin']) ? $opt['egoi_widget']['double_optin'] : 0;
		} else {
			$this->widget_enabled = 0;
			$this->redirect       = '';
			$this->subscribed     = '';
			$this->input_width    = '100%';
			$this->btn_width      = '';
			$this->bcolor         = '';
			$this->listID         = 0;
			$this->lang           = 'en';
			$this->tag_egoi       = '';
			$this->double_optin   = 0;
		}

		$widget_ops = array(
			'classname'   => 'Egoi4Widget',
			'description' => 'E-goi Form Widget',
		);
		parent::__construct( false, $name = 'Smart Marketing Widget', $widget_ops );
		wp_enqueue_script( 'jquery' );

	}

	/* Widget Layout for customers (frontend) */
	public function widget( $args, $instance ) {

		if ( $this->widget_enabled ) {
			wp_enqueue_style( 'egoi-style', plugin_dir_url( __FILE__ ) . '../public/css/egoi-for-wp-public.css' );

			extract( $args );
			$this->egoi_id = ! empty( $args['widget_id'] ) ? sanitize_key( $args['widget_id'] ) : sanitize_key( $instance['widget_id'] );

			$title              = sanitize_text_field( $instance['title'] );
			$list               = $this->listID ? sanitize_key( $this->listID ) : sanitize_key( $instance['list'] );
			$fname              = sanitize_text_field( $instance['fname'] );
			$fname_label        = sanitize_text_field( $instance['fname_label'] );
			$fname_placeholder  = sanitize_text_field( $instance['fname_placeholder'] );
			$lname              = sanitize_text_field( $instance['lname'] );
			$lname_label        = sanitize_text_field( $instance['lname_label'] );
			$lname_placeholder  = sanitize_text_field( $instance['lname_placeholder'] );
			$email              = sanitize_email( $instance['email'] );
			$email_label        = sanitize_text_field( $instance['email_label'] );
			$email_placeholder  = sanitize_text_field( $instance['email_placeholder'] );
			$mobile             = sanitize_text_field( $instance['mobile'] );
			$mobile_label       = sanitize_text_field( $instance['mobile_label'] );
			$mobile_placeholder = sanitize_text_field( $instance['mobile_placeholder'] );
			$button             = sanitize_text_field( $instance['button'] );
			$default_tag        = sanitize_text_field( $instance['tag-egoi'] );
			$language           = sanitize_text_field( $instance['lang'] );

			$the_widget_list = $instance['list'];
			$list_id         = $the_widget_list ?: $list;

			$tag = '';

			// set default tag
			if ( $default_tag != '' ) {
				$tag = $default_tag;
			}
            ?>

			<style>
			.loader::before{
			    display: none !important;
			}
			</style>
			<script type="text/javascript">
					jQuery(document).ready(function($){
						var cl = new CanvasLoader("Loading_<?php echo esc_attr($this->egoi_id) ?>");
						cl.setColor('#ababab');
						cl.setShape('spiral');
						cl.setDiameter(28);
						cl.setDensity(77); 
						cl.setRange(1);
						cl.setSpeed(5);
						cl.show(); 
						$("#egoi-submit-sub<?php echo esc_attr($this->egoi_id) ?>").on("click", function() {

							$(".error<?php echo esc_attr($this->egoi_id) ?>").hide();
							$("#Loading_<?php echo esc_attr($this->egoi_id) ?>").show();
							$.ajax({
								type: "POST",
								data: 
								{
									egoi_subscribe: "submited",
									widget_list: $("input#egoi-list-sub<?php echo esc_attr($this->egoi_id) ?>").val(),
									widget_fname: $("input#egoi-fname-sub<?php echo esc_attr($this->egoi_id) ?>").val(),
									widget_lname: $("input#egoi-lname-sub<?php echo esc_attr($this->egoi_id) ?>").val(),
									widget_email: $("input#egoi-email-sub<?php echo esc_attr($this->egoi_id) ?>").val(),
									widget_mobile: $("input#egoi-mobile-sub<?php echo esc_attr($this->egoi_id) ?>").val(),
									widget_id: $("input#egoi-id-sub<?php echo esc_attr($this->egoi_id) ?>").val(),
									widget_tag: $("input#egoi-tag").val(),
									widget_lang: $("input#egoi-lang").val(),
									widget_double_optin: $("input#egoi-double-optin").val(),
								},
								success: function(response) {
									$("#Loading_<?php echo esc_attr($this->egoi_id) ?>").hide();
									if(response == "hide"){
										$("#<?php echo esc_attr($this->egoi_id) ?>").html("<div class='egoi-success' ><?php echo esc_attr($this->subscribed) ?></div>");
									}else{
										$(".egoi-widget-error").remove();
										$(response).appendTo($("#<?php echo esc_attr($this->egoi_id) ?>"));
									}
									if(response == "redirect"){
										$("#<?php echo esc_attr($this->egoi_id) ?>").html("<div class='egoi-success'><?php echo esc_attr($this->subscribed) ?></div>");
										window.location.href="<?php echo esc_attr($this->redirect) ?>";
									}
								}
							});
							return false;
						});
					});
				</script>
			
			<div class="widget egoi_widget_style" id="<?php echo esc_attr($this->egoi_id) ?>" style="<?php echo esc_attr($this->bcolor) ?>">

            <?php
			if ( $title ) {
				echo esc_textarea( $title );
			}
            ?>

			<form name="egoi_contact" id="egoi-widget-form-<?php echo esc_attr($this->egoi_id) ?>" action="" method="post">
				<input type="hidden" id="egoi-title" name="egoi-title" value="<?php echo esc_attr($title) ?>"/>
				<input type="hidden" id="egoi-list" name="egoi-list" value="<?php echo esc_attr($list_id) ?>"/>
				<input type="hidden" id="egoi-lang" name="egoi-lang" value="<?php echo esc_attr($language) ?>"/>
				<input type="hidden" id="egoi-tag" name="egoi-tag" value="<?php echo esc_attr($tag) ?>"/>
				<input type="hidden" id="egoi-double-optin" name="egoi-double-optin" value="<?php echo esc_attr($this->double_optin) ?>"/>

            <?php

			if ( $fname ) {
                ?>
				<label><?php echo esc_attr($fname_label) ?></label>
				<div class='widget-text'><input type='text' placeholder='<?php echo esc_attr($fname_placeholder) ?>' name='egoi-fname-sub<?php echo esc_attr($this->egoi_id) ?>' id='egoi-fname-sub<?php echo esc_attr($this->egoi_id) ?>' style='<?php echo esc_attr($this->input_width) ?>;' /></div>
			    <?php
            }

			if ( $lname ) {
                ?>
				<label><?php echo esc_attr($lname_label) ?></label>
				<div class='widget-text'><input type='text' placeholder='<?php echo esc_attr($lname_placeholder) ?>' name='egoi-lname-sub<?php echo esc_attr($this->egoi_id) ?>' id='egoi-lname-sub<?php echo esc_attr($this->egoi_id) ?>' style='<?php echo esc_attr($this->input_width) ?>;' /></div>
                <?php
            }

            ?>
			<label><?php echo esc_attr($email_label) ?></label>
			<div class='widget-text'><input type='text' placeholder='<?php echo esc_attr($email_placeholder) ?>' required name='egoi-email-sub<?php echo esc_attr($this->egoi_id) ?>' id='egoi-email-sub<?php echo esc_attr($this->egoi_id) ?>' style='<?php echo esc_attr($this->input_width) ?>;' /></div>
            <?php

            if ( $mobile ) {
                ?>
				<p><label><?php echo esc_attr($mobile_label) ?></label>
				<div class='widget-text'><input type='text' placeholder='<?php echo esc_attr($mobile_placeholder) ?>' name='egoi-mobile-sub<?php echo esc_attr($this->egoi_id) ?>' id='egoi-mobile-sub<?php echo esc_attr($this->egoi_id) ?>' style='<?php echo esc_attr($this->input_width) ?>;' /></div>
			    <?php
            }
			require_once dirname( __DIR__ ) . '/admin/index.php';
			out( $arr );
			$link = ( array_key_exists( $language, $arr ) ) ?  $arr[ $language ] : $arr['pt'] ;
			
            ?>
			<input type='hidden' name='egoi-list-sub<?php echo esc_attr($this->egoi_id) ?>' id='egoi-list-sub<?php echo esc_attr($this->egoi_id) ?>' value='<?php echo esc_attr($list) ?>' />
			<input type='hidden' name='egoi-id-sub<?php echo esc_attr($this->egoi_id) ?>' id='egoi-id-sub<?php echo esc_attr($this->egoi_id) ?>' value='<?php echo esc_attr($this->egoi_id) ?>' />
			<input type='submit' class='submit_button' name='egoi-submit-sub<?php echo esc_attr($this->egoi_id) ?>' id='egoi-submit-sub<?php echo esc_attr($this->egoi_id) ?>' value='<?php echo esc_attr($button) ?>' style='<?php echo esc_attr($this->btn_width) ?>' />
                <p><?php echo esc_url($link) ?></p>
            </form>
			<div id='Loading_<?php echo esc_attr($this->egoi_id) ?>' class='loader' style='display:none;'>
			</div>
			</div>
            <?php
		}
	}

	/* To save/update widget configurations */
	public function update( $new_instance, $old_instance ) {

		$instance                       = $old_instance;
		$instance['widgetid']           = sanitize_text_field( $new_instance['widgetid'] );
		$instance['list']               = sanitize_text_field( $new_instance['list'] );
		$instance['title']              = sanitize_text_field( $new_instance['title'] );
		$instance['fname']              = sanitize_text_field( $new_instance['fname'] );
		$instance['fname_label']        = sanitize_text_field( $new_instance['fname_label'] );
		$instance['fname_placeholder']  = sanitize_text_field( $new_instance['fname_placeholder'] );
		$instance['lname']              = sanitize_text_field( $new_instance['lname'] );
		$instance['lname_label']        = sanitize_text_field( $new_instance['lname_label'] );
		$instance['lname_placeholder']  = sanitize_text_field( $new_instance['lname_placeholder'] );
		$instance['email']              = sanitize_text_field( $new_instance['email'] );
		$instance['email_label']        = sanitize_text_field( $new_instance['email_label'] );
		$instance['email_placeholder']  = sanitize_text_field( $new_instance['email_placeholder'] );
		$instance['mobile']             = sanitize_text_field( $new_instance['mobile'] );
		$instance['mobile_label']       = sanitize_text_field( $new_instance['mobile_label'] );
		$instance['mobile_placeholder'] = sanitize_text_field( $new_instance['mobile_placeholder'] );
		$instance['button']             = sanitize_text_field( $new_instance['button'] );
		$instance['tag']                = sanitize_text_field( $new_instance['tag'] );
		$instance['tag_name']           = sanitize_text_field( $new_instance['tag_name'] );
		$instance['tag-egoi']           = sanitize_text_field( $this->tag_egoi );

		if ( isset($instance['tag_name']) ) {

			$apikey = $this->getApikey();
			if ( ! empty( $apikey ) ) {
				$this->egoiWpApiV3 = new EgoiApiV3( $apikey );
			}

			$getTag = $this->egoiWpApiV3->getTag( $instance['tag_name'] );

			if ( isset( $getTag->tag_id ) ) {
				$instance['tag'] = $getTag->tag_id;
				$instance['tag_name'] = $getTag->name;
			}
		}

		if ( isset($new_instance['widget_lang']) ) {
			$instance['lang'] = strip_tags( $new_instance['widget_lang'] );
		} else {
			$instance['lang'] = $this->lang;
		}

		return $instance;
	}

	/* Form with widget configurations (wp-admin/widgets) */
	public function form( $instance ) {

		if ( $this->widget_enabled ) {
			$instance = wp_parse_args(
				(array) $instance,
				array(
					'widgetid'           => '',
					'list'               => '',
					'title'              => '',
					'fname'              => '',
					'fname_label'        => '',
					'fname_placeholder'  => '',
					'lname'              => '',
					'lname_label'        => '',
					'lname_placeholder'  => '',
					'email'              => '',
					'email_label'        => '',
					'email_placeholder'  => '',
					'mobile'             => '',
					'mobile_label'       => '',
					'mobile_placeholder' => '',
					'button'             => '',
					'tag_name'           => '',
					'widget_lang'        => '',
				)
			);

			$widgetid          = sanitize_text_field( $instance['widgetid'] );
			$list_id           = $this->listID;
			$title             = sanitize_text_field( $instance['title'] );
			$fname             = sanitize_text_field( $instance['fname'] );
			$fname_label       = sanitize_text_field( $instance['fname_label'] );
			$fname_placeholder = sanitize_text_field( $instance['fname_placeholder'] );

			$lname             = sanitize_text_field( $instance['lname'] );
			$lname_label       = sanitize_text_field( $instance['lname_label'] );
			$lname_placeholder = sanitize_text_field( $instance['lname_placeholder'] );

			$email             = sanitize_text_field( $instance['email'] );
			$email_label       = sanitize_text_field( $instance['email_label'] );
			$email_placeholder = sanitize_text_field( $instance['email_placeholder'] );

			$mobile             = sanitize_text_field( $instance['mobile'] );
			$mobile_label       = sanitize_text_field( $instance['mobile_label'] );
			$mobile_placeholder = sanitize_text_field( $instance['mobile_placeholder'] );
			$button             = sanitize_text_field( $instance['button'] );

			$tag         = sanitize_text_field( $instance['tag_name'] );

			$default_tag = '';


			if ( isset($instance['tag-egoi']) ) {

				$apikey = $this->getApikey();
				if ( ! empty( $apikey ) ) {
					$this->egoiWpApiV3 = new EgoiApiV3( $apikey );
				}

				$getTag = $this->egoiWpApiV3->getTagById( $instance['tag-egoi'] );
	
				if ( isset( $getTag->tag_id ) ) {
					$default_tag = $getTag->name;
				}
			}
			
            ?>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ) ?>"><?php _e( 'Widget Title', 'egoi-for-wp' ) ?></label>
				<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ) ?>" id="<?php echo esc_attr( $this->get_field_name( 'title' ) ) ?>"  value="<?php echo esc_attr( $title ) ?>" />
			</p>

            <?php
			$checked_fname = 0;
			$style_fname   = 'display:none;';
			if ( $fname ) {
				$checked_fname = 1;
				$style_fname   = '';
			}
            ?>
            <p>
			<input class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'fname' ) ) ?>" name="<?php echo esc_attr( $this->get_field_name( 'fname' ) ) ?>" type="checkbox" value="First Name" data-attribute="fname_id" <?php checked($checked_fname, 1) ?> />
				<label for="<?php echo esc_attr( $this->get_field_id( 'fname' ) ) ?>"><?php _e( 'First Name', 'egoi-for-wp' ) ?></label>

                <input type="text" name="<?php echo esc_attr( $this->get_field_name( 'fname_label' ) ) ?>" id="<?php echo esc_attr( $this->get_field_id( 'fname_label' ) ) ?>" placeholder="<?php _e( 'Label', 'egoi-for-wp' ) ?>" value="<?php echo esc_attr($fname_label) ?>" data-attribute="fname_label" style="width:100%;margin-bottom:10px"/>

                <input type="text" name="<?php echo esc_attr( $this->get_field_name( 'fname_placeholder' ) ) ?>" id="<?php echo esc_attr( $this->get_field_id( 'fname_placeholder' ) ) ?>" placeholder="Placeholder" value="<?php echo esc_attr($fname_placeholder) ?>" data-attribute="fname_placeholder" style="width:100%;"/>
			</p>
            <?php
			$checked_lname = 0;
			$style_lname   = 'display:none;';
			if ( $lname ) {
				$checked_lname = 1;
				$style_lname   = '';
			}
            ?>
			<p>
                <input class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'lname' ) ) ?>" name="<?php echo esc_attr( $this->get_field_name( 'lname' ) ) ?>" type="checkbox" value="Last Name" data-attribute="lname_id" <?php checked($checked_lname, 1) ?> />

                <label for="<?php echo esc_attr( $this->get_field_name( 'lname' ) ) ?>"><?php _e( 'Last Name', 'egoi-for-wp' ) ?></label>

                <input type="text" name="<?php echo esc_attr( $this->get_field_name( 'lname_label' ) ) ?>" id="<?php echo esc_attr( $this->get_field_id( 'lname_label' ) ) ?>" placeholder="<?php _e( 'Label', 'egoi-for-wp' ) ?>" value="<?php echo esc_attr( $lname_label ) ?>" data-attribute="lname_label" style="width:100%;margin-bottom:10px"/>

                <input type="text" name="<?php echo esc_attr( $this->get_field_name( 'lname_placeholder' ) ) ?>" id="<?php echo esc_attr( $this->get_field_id( 'lname_placeholder' ) ) ?>" placeholder="Placeholder" value="<?php echo esc_attr( $lname_placeholder ) ?>" data-attribute="lname_placeholder" style="width:100%;"/>
            </p>
            <?php
			if ( ! $email ) {
				$email = 'Email';
			}
            ?>

			<p>
				<input class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'email' ) ) ?>" name="<?php echo esc_attr( $this->get_field_name( 'email' ) ) ?>" <?php checked(!empty($email)); ?> type="checkbox" checked="checked" value="Email" disabled="disabled"/>
				<label for="<?php echo esc_attr( $this->get_field_id( 'email' ) ) ?>">
                <?php _e( 'Email:', 'egoi-for-wp' ); ?>
                </label>

                <input type="text" name="<?php echo esc_attr( $this->get_field_name( 'email_label' ) ) ?>" id="<?php echo esc_attr( $this->get_field_id( 'email_label' ) ) ?>" placeholder="<?php _e( 'Label', 'egoi-for-wp' ) ?>" value="<?php echo esc_attr( $email_label ) ?>" style="width:100%;margin-bottom:10px"/>

			    <input type="text" name="<?php echo esc_attr( $this->get_field_name( 'email_placeholder' ) ) ?>" id="<?php echo esc_attr( $this->get_field_id( 'email_placeholder' ) ) ?>" placeholder="Placeholder" value="<?php echo esc_attr( $email_placeholder ) ?>" style="width:100%;"/>
			</p>

            <?php

			$checked_mobile = 0;
			$style_mobile   = 'display:none;';
			if ( $mobile ) {
				$checked_mobile = 1;
				$style_mobile   = '';
			}
            ?>

            <p>
                <input class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'mobile' ) ) ?>" name="<?php echo esc_attr( $this->get_field_name( 'mobile' ) ) ?>" type="checkbox" value="Mobile" data-attribute="mobile_id" <?php checked($checked_mobile, 1); ?> />
				<label for="<?php echo esc_attr( $this->get_field_id( 'mobile' ) ) ?>"><?php _e( 'Mobile', 'egoi-for-wp' ) ?></label>

                <input type="text" name="<?php echo esc_attr( $this->get_field_name( 'mobile_label' ) ) ?>" id="<?php echo esc_attr( $this->get_field_id( 'mobile_label' ) ) ?>" placeholder="<?php _e( 'Label', 'egoi-for-wp' ) ?>" value="<?php echo esc_attr( $mobile_label ) ?>" data-attribute="mobile_label" style="width:100%;margin-bottom:10px"/>

                <input type="text" name="<?php echo esc_attr( $this->get_field_name( 'mobile_placeholder' ) ) ?>" id="<?php echo esc_attr( $this->get_field_id( 'mobile_placeholder' ) ) ?>" placeholder="Placeholder" value="<?php echo esc_attr( $mobile_placeholder ) ?>" data-attribute="mobile_placeholder" style="width:100%;"/>
            </p>
            <p>
            <?php
			if ( ! $button ) {
				$button = __( 'Subscribe', 'egoi-for-wp' );
			}
            if ( isset($default_tag) ) {
            ?>
				<label><?php _e( 'Tag', 'egoi-for-wp' ) ?><span class="e-goi-tooltip">
						 <span class="dashicons dashicons-info"></span>
					  	 <span class="e-goi-tooltiptext e-goi-tooltiptext--active">
                             <?php _e( 'Tag set by default', 'egoi-for-wp' ) ?>:
                             <?php echo esc_textarea( $default_tag ); ?>
					 	</span>
					</span>
				</label>
            <?php
			} else {
            ?>
			    <label><?php _e( 'Tag', 'egoi-for-wp' ) ?></label>
			<?php
            }
            ?>

			<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'tag' ) ) ?>" id="<?php echo esc_attr( $this->get_field_id( 'tag' ) ) ?>" placeholder="<?php _e( 'Tag Name', 'egoi-for-wp' ) ?>" value="<?php echo esc_attr($tag) ?>" style="width:100%;"/>
			</p>
			<p>

			<label for="<?php echo esc_attr( $this->get_field_name( 'button' ) ) ?>"><?php _e( 'Subscribe Button', 'egoi-for-wp' ) ?></label>
			<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'button' ) ) ?>" id="<?php echo esc_attr( $this->get_field_id( 'button' ) ) ?>" placeholder="<?php _e( 'Subscribe', 'egoi-for-wp' ) ?>" value="<?php echo esc_attr( $button ) ?>" style="width:100%;"/>
			</p>
            <?php
		} else {
            ?>
			<p>
			<?php _e( 'Form disabled! Please enable it in', 'egoi-for-wp' ); ?>
			<b>Smart Marketing -> Widgets</b>
            </p>
		    <?php
        }
	}

	private function getApikey() {
		$apikey = get_option( 'egoi_api_key' );
		if ( ! empty( $apikey['api_key'] ) ) {
			return $apikey['api_key'];
		}
		return false;
	}
}

/* To save widget submissions*/
function egoi_widget_request() {

	if ( isset( $_POST['egoi_subscribe'] ) && ( $_POST['egoi_subscribe'] == 'submited' ) ) {

		$id = sanitize_key( $_POST['widget_id'] );

		$fname = sanitize_text_field( $_POST['widget_fname'] );
		$lname = sanitize_text_field( $_POST['widget_lname'] );

		$tag  = sanitize_key( $_POST['widget_tag'] );

		$opt     = get_option( 'egoi_widget' );
		$Egoi4WP = $opt['egoi_widget'];

		$list = $Egoi4WP['list'];

		// new options
		$bcolor_success = 'background: ' . esc_textarea( $Egoi4WP['bcolor_success'] ) . '!important';
		$bcolor_error   = 'background: ' . esc_textarea( $Egoi4WP['bcolor_error'] ) . '!important';

		if ( isset( $_POST['widget_email'] ) ) {

			if ( $_POST['widget_email'] != '' ) {

				$email = sanitize_email( $_POST['widget_email'] );
				if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
                    ?>
					<div style='<?php echo esc_attr($bcolor_success) ?>' class='egoi-widget-error error<?php echo esc_attr( $id ) ?>'><?php echo esc_html( $Egoi4WP['msg_invalid'] ) ?></div>
					<?php
                    exit;
				}
			} else {
                ?>
				<div style='<?php echo esc_attr($bcolor_error) ?>' class='egoi-widget-error error<?php echo esc_attr( $id ) ?>'><?php echo esc_html( $Egoi4WP['msg_empty'] ) ?></div>
				<?php
                exit;
			}
		}

		if ( isset( $_POST['widget_mobile'] ) ) {

			if ( $_POST['widget_mobile'] != '' ) {
				$mobile = Egoi_For_Wp::smsnf_get_valid_phone( $_POST['widget_mobile'] );
			} else {
                ?>
				<div class='egoi-widget-error error<?php echo esc_attr( $id ) ?>'>
				<?php _e( 'There is no number! Please insert your number', 'egoi-for-wp' ); ?>
				</div>
                <?php
                exit;
			}
		}

		$apikey = get_option( 'egoi_api_key' );
		if ( ! empty( $apikey['api_key'] ) ) {
				$egoiWpApiV3 = new EgoiApiV3( $apikey['api_key'] );
	
				$status = ! isset( $_POST['widget_double_optin'] ) || $_POST['widget_double_optin'] == 0 ? 'active' : 'unconfirmed';

				$refFields = array();

				if(isset($mobile)){
					$refFields['cell'] = $mobile;
				}

				$add = $egoiWpApiV3->addContact(
					$list,
					$email,
					$fname,
					$lname,
					array(),
					0,
					$refFields,
					$status,
					array( intval( $tag ) )
				);


				if ( isset($add) && !isset($add['error'])) {
					$client = new Egoi_For_Wp();
					$form_id = explode( '-', $id );

					$client->smsnf_save_form_subscriber( $form_id[1], 'widget', $add, $list, $email );

					$redirect  = $Egoi4WP['redirect'];
					$hide_form = $Egoi4WP['hide_form'];
					if ( isset($redirect) && !empty($redirect)) {
						echo 'redirect';
					} else {
	
						if ( isset($hide_form) ) {
							echo 'hide';
						} else {
							?>
							<div style='<?php echo esc_attr($bcolor_success) ?>' class='egoi-widget-success <?php echo esc_attr( $id ) ?>'><?php echo esc_html( $Egoi4WP['msg_subscribed'] ) ; ?></div>
							<?php
						}
					}
					exit;
				}

				?>
				<div style='<?php echo esc_attr($bcolor_error) ?>' class='egoi-widget-error error <?php echo esc_attr( $id ) ?>'><?php echo esc_html( $Egoi4WP['msg_error'] ) ; ?></div>
				<?php
				exit;
		}
	}
}
