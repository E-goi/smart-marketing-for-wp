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

		$this->widget_enabled = $opt['egoi_widget']['enabled'];
		$this->redirect       = $opt['egoi_widget']['redirect'];
		$this->subscribed     = $opt['egoi_widget']['msg_subscribed'];
		$this->input_width    = $opt['egoi_widget']['input_width'] ? 'width:' . $opt['egoi_widget']['input_width'] : '100%';
		$this->btn_width      = $opt['egoi_widget']['btn_width'] ? 'width:' . $opt['egoi_widget']['btn_width'] : '';
		$this->bcolor         = $opt['egoi_widget']['bcolor'] ? 'border: 1px solid ' . $opt['egoi_widget']['bcolor'] : '';
		$this->listID         = $opt['egoi_widget']['list'];
		$this->lang           = $opt['egoi_widget']['lang'];
		$this->tag_egoi       = $opt['egoi_widget']['tag-egoi'];
		$this->double_optin   = $opt['egoi_widget']['double_optin'];

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
			$this->egoi_id = !empty($args['widget_id']) ? sanitize_key($args['widget_id']) : sanitize_key($instance['widget_id']);

			$title              = apply_filters( 'widget_title', $instance['title'] );
			$list               = $this->listID ? sanitize_key($this->listID) : sanitize_key($instance['list']);
			$fname              = sanitize_text_field($instance['fname']);
			$fname_label        = sanitize_text_field($instance['fname_label']);
			$fname_placeholder  = sanitize_text_field($instance['fname_placeholder']);
			$lname              = sanitize_text_field($instance['lname']);
			$lname_label        = sanitize_text_field($instance['lname_label']);
			$lname_placeholder  = sanitize_text_field($instance['lname_placeholder']);
			$email              = sanitize_email($instance['email']);
			$email_label        = sanitize_text_field($instance['email_label']);
			$email_placeholder  = sanitize_text_field($instance['email_placeholder']);
			$mobile             = sanitize_text_field($instance['mobile']);
			$mobile_label       = sanitize_text_field($instance['mobile_label']);
			$mobile_placeholder = sanitize_text_field($instance['mobile_placeholder']);
			$button             = sanitize_text_field($instance['button']);
			$widget_tag         = sanitize_text_field($instance['tag']);
			$default_tag        = sanitize_text_field($instance['tag-egoi']);
			$language           = sanitize_text_field($instance['lang']);

			$the_widget_list = $instance['list'];
			$list_id         = $the_widget_list ?: $list;

			$tag = '';

			// set tag defined on widget
			if ( $widget_tag != '' ) {
				$tag = $widget_tag;
			}

			// set default tag
			if ( $default_tag != '' && $widget_tag == '' ) {
				$tag = $default_tag;
			}

			echo '
			<style>
			.loader::before{
			    display: none !important;
			}
			</style>
			<script type="text/javascript">
					jQuery(document).ready(function($){
						var cl = new CanvasLoader("Loading_' . $this->egoi_id . '");
						cl.setColor(\'#ababab\');
						cl.setShape(\'spiral\');
						cl.setDiameter(28);
						cl.setDensity(77); 
						cl.setRange(1);
						cl.setSpeed(5);
						cl.show(); 
						$("#egoi-submit-sub' . $this->egoi_id . '").on("click", function() {

							$(".error' . $this->egoi_id . '").hide();
							$("#Loading_' . $this->egoi_id . '").show();
							$.ajax({
								type: "POST",
								data: 
								{
									egoi_subscribe: "submited",
									widget_list: $("input#egoi-list-sub' . $this->egoi_id . '").val(),
									widget_fname: $("input#egoi-fname-sub' . $this->egoi_id . '").val(),
									widget_lname: $("input#egoi-lname-sub' . $this->egoi_id . '").val(),
									widget_email: $("input#egoi-email-sub' . $this->egoi_id . '").val(),
									widget_mobile: $("input#egoi-mobile-sub' . $this->egoi_id . '").val(),
									widget_id: $("input#egoi-id-sub' . $this->egoi_id . '").val(),
									widget_tag: $("input#egoi-tag").val(),
									widget_lang: $("input#egoi-lang").val(),
									widget_double_optin: $("input#egoi-double-optin").val(),
								},
								success: function(response) {
									$("#Loading_' . $this->egoi_id . '").hide();
									if(response == "hide"){
										$("#' . $this->egoi_id . '").html("<div class=\'egoi-success\'>' . $this->subscribed . '</div>");
									}else{
										$(".egoi-widget-error").remove();
										$(response).appendTo($("#' . $this->egoi_id . '"));
									}
									if(response == "redirect"){
										$("#' . $this->egoi_id . '").html("<div class=\'egoi-success\'>' . $this->subscribed . '</div>");
										window.location.href="' . $this->redirect . '";
									}
								}
							});
							return false;
						});
					});
				</script>
			
			<div class="widget egoi_widget_style" id="' . $this->egoi_id . '" style="' . $this->bcolor . '">';

			if ( $title ) {
				echo esc_textarea( $before_title . $title . $after_title );
			}

			echo '
			<form name="egoi_contact" id="egoi-widget-form-' . esc_attr($this->egoi_id) . '" action="" method="post">
				<input type="hidden" id="egoi-list" name="egoi-list" value="' . esc_attr($list_id) . '">
				<input type="hidden" id="egoi-lang" name="egoi-lang" value="' . esc_attr($language) . '">
				<input type="hidden" id="egoi-tag" name="egoi-tag" value="' . esc_attr($tag) . '">
				<input type="hidden" id="egoi-double-optin" name="egoi-double-optin" value="' . esc_attr($this->double_optin) . '">
 			';

			if ( $fname ) {
				echo '<label>' . esc_attr( $fname_label ) . '</label>';
				echo "<div class='widget-text'><input type='text' placeholder='" . esc_attr($fname_placeholder) . "' name='egoi-fname-sub" . esc_attr($this->egoi_id) . "' id='egoi-fname-sub" . esc_attr($this->egoi_id) . "' style='" . esc_attr($this->input_width) . ";' /></div>";
			}

			if ( $lname ) {
				echo '<label>' . esc_attr( $lname_label ) . '</label>';
				echo "<div class='widget-text'><input type='text' placeholder='" . esc_attr($lname_placeholder) . "' name='egoi-lname-sub" . esc_attr($this->egoi_id) . "' id='egoi-lname-sub" . esc_attr($this->egoi_id) . "' style='" . esc_attr($this->input_width) . ";' /></div>";
			}

			echo '<label>' . esc_attr( $email_label ) . "</label>
			<div class='widget-text'><input type='text' placeholder='" . esc_attr($email_placeholder) . "' required name='egoi-email-sub" . esc_attr($this->egoi_id) . "' id='egoi-email-sub" . esc_attr($this->egoi_id) . "' style='" . esc_attr($this->input_width) . ";' /></div>";
			if ( $mobile ) {
				echo '<p><label>' . esc_attr( $mobile_label ) . '</label>';
				echo "<div class='widget-text'><input type='text' placeholder='" . esc_attr($mobile_placeholder) . "' name='egoi-mobile-sub" . esc_attr($this->egoi_id) . "' id='egoi-mobile-sub" . esc_attr($this->egoi_id) . "' style='" . esc_attr($this->input_width) . ";' /></div>";
			}
			require_once dirname( __DIR__ ) . '/admin/index.php';
			out( $arr );
			$link = ( array_key_exists( $language, $arr ) ) ? '<p>' . $arr[ $language ] . '</p>' : '<p>' . $arr['en'] . '</p>';
			echo "<input type='hidden' name='egoi-list-sub" . $this->egoi_id . "' id='egoi-list-sub" . esc_attr($this->egoi_id) . "' value='" . esc_attr($list) . "' />
			<input type='hidden' name='egoi-id-sub" . $this->egoi_id . "' id='egoi-id-sub" . esc_attr($this->egoi_id) . "' value='" . esc_attr($this->egoi_id) . "' />
			<input type='submit' class='submit_button' name='egoi-submit-sub" . esc_attr($this->egoi_id) . "' id='egoi-submit-sub" . esc_attr($this->egoi_id) . "' value='" . esc_attr($button) . "' style='" . esc_attr($this->btn_width) . "' />
            " . $link . "
            </form>
			<div id='Loading_" . esc_attr($this->egoi_id) . "' class='loader' style='display:none;'>
			</div>
			</div>";
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
		$instance['tag-egoi']           = sanitize_text_field($this->tag_egoi);

		if ( $new_instance['tag'] ) {
			$api  = new Egoi_For_Wp();
			$tags = $api->getTag( $instance['tag'] );

			if ( $tags['NEW_ID'] ) {
				$instance['tag']      = $tags['NEW_ID'];
				$instance['tag_name'] = $tags['NEW_NAME'];
			} else {
				$instance['tag']      = $tags['ID'];
				$instance['tag_name'] = $tags['NAME'];
			}
		}

		if ( $new_instance['widget_lang'] ) {
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
			$lang_widget = sanitize_text_field( $instance['lang'] );

			$default_tag = '';

			$api = new Egoi_For_Wp();
			if ( $instance['tag-egoi'] != '' ) {
				$default_tag = $api->getTagByID( $instance['tag-egoi'] );
			} else {
				$default_tag = $api->getTagByID( $this->tag_egoi );
			}

			$Egoi4WP = get_option( 'Egoi4WpBuilderObject' );
			$lists   = $Egoi4WP->getLists();

			$languages = array();
			foreach ( $lists as $key => $value ) {
				if ( $this->listID == $value->listnum ) {
					$languages[] = $value->idioma;

					foreach ( $value->idiomas_extra as $lang ) {
						$languages[] = $lang;
					}
				}
			}

			echo '
			<script>
			jQuery(document).ready(function ($){
				$(\'input[data-attribute="fname_id"]\').click(function (){
					if($(this).is(":checked")) {
						$(\'input[data-attribute="fname_label"]\').show();
						$(\'input[data-attribute="fname_placeholder"]\').show();
					}else{
						$(\'input[data-attribute="fname_label"]\').hide();
						$(\'input[data-attribute="fname_placeholder"]\').hide();
					}
				});
				
				$(\'input[data-attribute="lname_id"]\').click(function (){
					if($(this).is(":checked")) {
						$(\'input[data-attribute="lname_label"]\').show();
						$(\'input[data-attribute="lname_placeholder"]\').show();
					}else{
						$(\'input[data-attribute="lname_label"]\').hide();
						$(\'input[data-attribute="lname_placeholder"]\').hide();
					}
				});
				
				
				$(\'input[data-attribute="mobile_id"]\').click(function (){
					if($(this).is(":checked")) {
						$(\'input[data-attribute="mobile_label"]\').show();
						$(\'input[data-attribute="mobile_placeholder"]\').show();
					}else{
						$(\'input[data-attribute="mobile_label"]\').hide();
						$(\'input[data-attribute="mobile_placeholder"]\').hide();
					}
				});
			});
			</script>
			<p>
				<label for="' . esc_attr($this->get_field_id( 'title' )) . '">' . __( 'Widget Title', 'egoi-for-wp' ) . '</label>
				<input class="widefat" id="' . esc_attr($this->get_field_id( 'title' )) . '" name="' . esc_attr($this->get_field_name( 'title' )) . '" type="text" value="' . esc_attr($title) . '" />
			</p>
			
			<p>';
			$checked_fname = '';
			$style_fname   = 'display:none;';
			if ( $fname ) {
				$checked_fname = 'checked="checked"';
				$style_fname   = '';
			}

			echo '<input class="checkbox" id="' . $this->get_field_id( 'fname' ) . '" name="' . $this->get_field_name( 'fname' ) . '" type="checkbox" value="First Name" data-attribute="fname_id" ' . $checked_fname . ' />
				<label for="' . $this->get_field_id( 'fname' ) . '">' . __( 'First Name', 'egoi-for-wp' ) . '</label>';

			echo '<input type="text" name="' . esc_textarea( $this->get_field_name( 'fname_label' ) ) . '" id="' . esc_textarea( $this->get_field_id( 'fname_label' ) ) . '" placeholder="' . __( 'Label', 'egoi-for-wp' ) . '" value="' . esc_attr($fname_label) . '" data-attribute="fname_label" style="width:100%;' . esc_attr($style_fname) . '">';

			echo '<input type="text" name="' . esc_textarea( $this->get_field_name( 'fname_placeholder' ) ) . '" id="' . esc_textarea( $this->get_field_id( 'fname_placeholder' ) ) . '" placeholder="Placeholder" value="' . esc_attr($fname_placeholder) . '" data-attribute="fname_placeholder" style="width:100%;' . esc_attr($style_fname) . '">';

			echo '
			</p>
			<p>';

			$checked_lname = '';
			$style_lname   = 'display:none;';
			if ( $lname ) {
				$checked_lname = 'checked="checked"';
				$style_lname   = '';
			}

			echo '<input class="checkbox" id="' . esc_textarea( $this->get_field_id( 'lname' ) ) . '" name="' . $this->get_field_name( 'lname' ) . '" type="checkbox" value="Last Name" data-attribute="lname_id" ' . $checked_lname . ' />';

			echo '<label for="' . esc_textarea( $this->get_field_id( 'lname' ) ) . '">' . __( 'Last Name', 'egoi-for-wp' ) . '</label>';

			echo '<input type="text" name="' . esc_textarea( $this->get_field_name( 'lname_label' ) ) . '" id="' . esc_textarea( $this->get_field_id( 'lname_label' ) ) . '" placeholder="' . __( 'Label', 'egoi-for-wp' ) . '" value="' . esc_attr($lname_label) . '" data-attribute="lname_label" style="width:100%;' . esc_attr($style_lname) . '">';

			echo '<input type="text" name="' . esc_textarea( $this->get_field_name( 'lname_placeholder' ) ) . '" id="' . esc_textarea( $this->get_field_id( 'lname_placeholder' ) ) . '" placeholder="Placeholder" value="' . esc_attr($lname_placeholder) . '" data-attribute="lname_placeholder" style="width:100%;' . esc_attr($style_lname) . '">';

			if ( ! $email ) {
				$email = 'Email';
			}

			echo '
			</p>
			<p>
				<input class="checkbox" id="' . esc_textarea( $this->get_field_id( 'email' ) ) . '" name="' . esc_textarea( $this->get_field_id( 'email' ) ) . '"';
			if ( $email ) {
				echo 'checked="checked"'; } echo 'type="checkbox" checked="checked" value="Email" disabled="disabled"/>
				<label for="' . esc_textarea( $this->get_field_id( 'email' ) ) . '">';
			_e( 'Email:', 'egoi-for-wp' );
			echo '</label>';

			echo '<input type="text" name="' . esc_textarea( $this->get_field_name( 'email_label' ) ) . '" id="' . esc_textarea( $this->get_field_id( 'email_label' ) ) . '" placeholder="' . __( 'Label', 'egoi-for-wp' ) . '" value="' . $email_label . '" style="width:100%;">';

			echo '<input type="text" name="' . esc_textarea( $this->get_field_name( 'email_placeholder' ) ) . '" id="' . esc_textarea( $this->get_field_id( 'email_placeholder' ) ) . '" placeholder="Placeholder" value="' . $email_placeholder . '" style="width:100%;">';

			echo '
			</p>
			<p>';

			$checked_mobile = '';
			$style_mobile   = 'display:none;';
			if ( $mobile ) {
				$checked_mobile = 'checked="checked"';
				$style_mobile   = '';
			}

			echo '<input class="checkbox" id="' . esc_textarea( $this->get_field_id( 'mobile' ) ) . '" name="' . esc_textarea( $this->get_field_name( 'mobile' ) ) . '" type="checkbox" value="Mobile" data-attribute="mobile_id" ' . $checked_mobile . ' />
				<label for="' . esc_textarea( $this->get_field_id( 'mobile' ) ) . '">' . __( 'Mobile', 'egoi-for-wp' ) .
				'</label>';

			echo '<input type="text" name="' . esc_textarea( $this->get_field_name( 'mobile_label' ) ) . '" id="' . esc_textarea( $this->get_field_id( 'mobile_label' ) ) . '" placeholder="' . __( 'Label', 'egoi-for-wp' ) . '" value="' . $mobile_label . '" data-attribute="mobile_label" style="width:100%;' . $style_mobile . '">';

			echo '<input type="text" name="' . esc_textarea( $this->get_field_name( 'mobile_placeholder' ) ) . '" id="' . esc_textarea( $this->get_field_id( 'mobile_placeholder' ) ) . '" placeholder="Placeholder" value="' . $mobile_placeholder . '" data-attribute="mobile_placeholder" style="width:100%;' . $style_mobile . '">';

			if ( ! $button ) {
				$button = __( 'Subscribe', 'egoi-for-wp' );
			}

			echo '
			</p>
			<p>';
			if ( $default_tag ) {
				echo '<label>' . __( 'Tag', 'egoi-for-wp' ) . '<span class="e-goi-tooltip">
						 <span class="dashicons dashicons-info"></span>
					  	 <span class="e-goi-tooltiptext e-goi-tooltiptext--active">
					  	 	' . __( 'Tag set by default', 'egoi-for-wp' ) . ":\n" . $default_tag['NAME'] . '
					 	</span>
					</span>
				</label>';
			} else {
				echo '<label>' . __( 'Tag', 'egoi-for-wp' ) . '</label>';
			}

			echo '<input type="text" name="' . esc_attr( $this->get_field_name( 'tag' ) ) . '" id="' . esc_textarea( $this->get_field_id( 'tag' ) ) . '" placeholder="' . __( 'Tag Name', 'egoi-for-wp' ) . '" value="' . $tag . '" style="width:100%;">';

			echo '
			</p>
			<p>';

			if ( $this->lang != '' ) {
				echo '<label>' . __( 'Languages', 'egoi-for-wp' ) . '</label><span class="e-goi-tooltip">
						 <span class="dashicons dashicons-info"></span>
					  	 <span class="e-goi-tooltiptext e-goi-tooltiptext--active">
					  	 	' . __( 'List', 'egoi-for-wp' ) . ":\n" . esc_attr($this->listID) . '<br>
					  	 	' . __( 'Language set by default', 'egoi-for-wp' ) . ":\n" . esc_attr($this->lang) . '
					 	</span>
					</span>
				</label><br>';
			} else {
				echo '<label>' . __( 'Languages for list', 'egoi-for-wp' ) . ":\n" . esc_attr($this->listID) . '</label><br>';
			}

			echo '<select id="' . esc_textarea( $this->get_field_name( 'widget_lang' ) ) . '" name="' . esc_textarea( $this->get_field_name( 'widget_lang' ) ) . '" type="text" >';
			echo '<option value="" selected disabled>' . __( 'Select a language', 'egoi-for-wp' ) . '</option>';

			foreach ( $languages as $value ) {
				if ( $value == $lang_widget ) {
					echo '<option selected value="' . esc_attr($value) . '">' . esc_html($value) . '</option>';
				} else {
					echo '<option value="' . esc_attr($value) . '">' . esc_html($value) . '</option>';
				}
			}
			echo '</select>				
			</p>
			<p>
				<label for="' . esc_textarea( $this->get_field_id( 'button' ) ) . '">' . __( 'Subscribe Button', 'egoi-for-wp' ) . '</label>';
			echo '<input type="text" name="' . esc_textarea( $this->get_field_name( 'button' ) ) . '" id="' . esc_textarea( $this->get_field_id( 'button' ) ) . '" placeholder="' . __( 'Subscribe', 'egoi-for-wp' ) . '" value="' . esc_attr($button) . '" style="width:100%;">';

			echo '
			</p>';

		} else {
			echo '<p>';
			echo __( 'Form disabled! Please enable it in', 'egoi-for-wp' );
			echo ' <b>Smart Marketing -> Widgets</b></p>';
		}
	}
}

/* To save widget submissions*/
function egoi_widget_request() {

	if ( isset( $_POST['egoi_subscribe'] ) && ( $_POST['egoi_subscribe'] == 'submited' ) ) {

		$id = sanitize_key( $_POST['widget_id'] );

		$fname = sanitize_text_field( $_POST['widget_fname'] );
		$lname = sanitize_text_field( $_POST['widget_lname'] );

		$lang = sanitize_text_field( $_POST['widget_lang'] );
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
					echo "<div style='$bcolor_success' class='egoi-widget-error error" . esc_attr($id) . "'>" . esc_html($Egoi4WP['msg_invalid']) . '</div>';
					exit;
				}
			} else {
				echo "<div style='$bcolor_error' class='egoi-widget-error error" . esc_attr($id) . "'>" . esc_html($Egoi4WP['msg_empty']) . '</div>';
				exit;
			}
		}

		if ( isset( $_POST['widget_mobile'] ) ) {

			if ( $_POST['widget_mobile'] != '' ) {
				$mobile = sanitize_email( $_POST['widget_mobile'] );
			} else {
				echo "<div class='egoi-widget-error error" . $id . "'>";
				echo __( 'There is no number! Please insert your number', 'egoi-for-wp' );
				echo '</div>';
				exit;
			}
		}

		$name = $fname . ' ' . $lname;

		$api = new Egoi_For_Wp();
		$get = $api->getSubscriber( $list, $email );

		if ( ( ! $get->subscriber->REMOVE_METHOD ) && ( $get->subscriber->UID ) ) {

			echo "<div style='$bcolor_error' class='egoi-widget-error error " . esc_attr($id) . "'>" . esc_html($Egoi4WP['msg_exists_subscribed']) . '</div>';
			exit;

		} else {

			$status = ! isset( $_POST['widget_double_optin'] ) || $_POST['widget_double_optin'] == 0 ? 1 : 0;

			$result = $api->addSubscriber( $list, $name, $email, $lang, $status, $mobile, intval( $tag ) );

			if ( ! isset( $result->ERROR ) && ! isset( $result->MODIFICATION_DATE ) ) {
				$form_id = explode( '-', $id );
				$api->smsnf_save_form_subscriber( $form_id[1], 'widget', $result );
			}

			if ( $result ) {

				$redirect  = $Egoi4WP['redirect'];
				$hide_form = $Egoi4WP['hide_form'];
				if ( $redirect ) {
					echo 'redirect';
				} else {

					if ( $hide_form ) {
						echo 'hide';
					} else {
						echo "<div style='$bcolor_success' class='egoi-widget-success " . esc_attr($id) . "'>" . esc_html($Egoi4WP['msg_subscribed']) . '</div>';
					}
				}
				exit;
			} else {
				echo "<div style='$bcolor_error' class='egoi-widget-error error " . esc_attr($id) . "'>" . esc_html($Egoi4WP['msg_error']) . '</div>';
				exit;
			}
		}
	}
}
