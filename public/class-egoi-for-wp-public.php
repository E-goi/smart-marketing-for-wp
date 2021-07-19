<?php
// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
    die();
}
/**
 * Class responsible to handle public interactions with plugin
 */
class Egoi_For_Wp_Public {

	/**
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name = 'Smart Marketing for WordPress';
	const DISABLE_SUBSCRIBER_BAR_PERMISSION = 'egoi_disable_sub_bar';

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
	 * @param    string    $plugin_name       The name of the plugin.
	 * @param    string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name = '', $version = '') {
		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_filter('shortcode_instance', array('Egoi_For_Wp_Public', 'get_shortcode'));
	}

	public function get_shortcode() {

		return $this;
	}

	public function shortcode_default() {

		return $this->generate_html($submit, '1');
	}

	public function shortcode_second() {

		return $this->generate_html($submit, '2');
	}

	public function shortcode_last1() {

		return $this->generate_html($submit, '3');
	}

	public function shortcode_last2() {

		return $this->generate_html($submit, '4');
	}

	public function shortcode_last3() {

		return $this->generate_html($submit, '5');
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		$bar_post = get_option(Egoi_For_Wp_Admin::BAR_OPTION_NAME);
		if (!wp_style_is( 'dashicons', 'enqueued')) {
			wp_enqueue_style('dashicons');
		}
		
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/egoi-for-wp-public.css', array(), $this->version, false );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		$bar_post = get_option(Egoi_For_Wp_Admin::BAR_OPTION_NAME);

        wp_enqueue_script( 'ajax-script', plugin_dir_url( __FILE__ ) . 'js/egoi-for-wp-forms.js', array( 'jquery' ), $this->version, false );
        wp_localize_script( 'ajax-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')) );

		if(isset($bar_post['enabled']) && ($bar_post['enabled'])) {
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/e-goi.min.js', array( 'jquery' ), $this->version, false );
			wp_localize_script( $this->plugin_name, 'url_egoi_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		}
	}

	/* 
	*	Generate E-goi bar
	*/
	public function generate_bar($regenerate = null) {
		if( current_user_can(self::DISABLE_SUBSCRIBER_BAR_PERMISSION) ){return false;}
		$bar_post = get_option(Egoi_For_Wp_Admin::BAR_OPTION_NAME);

		//add new tag to E-goi
		if($bar_post['tag'] != ""){
			$data = new Egoi_For_Wp();
			$info = $data->getTag($bar_post['tag']);
		    $tag = $info['ID'];
		}
		else{
			$tag = $bar_post['tag-egoi'];
		}

		// if defined some redirection
		if($bar_post['redirect']){
			if($_POST['egoi_action_sub']){
				$this->subscribe();
			}
		}

		if($bar_post['open']){

			$enable = '0';
			$hidden = '';
			setcookie('hide_bar', $enable, 1);
			$_COOKIE['hide_bar'] = $enable;

		}else{

			$enable = '';
			$hidden = '';

			if(isset($_COOKIE['hide_bar']) && ($_COOKIE['hide_bar'] == 1)){
				$hidden = 'display:none;';
			}
		}

		$pos = $bar_post['position'];
		if($pos == 'top'){
			$pos = '1';
		}else{
			$pos = '0';
		}
		
		if($bar_post['sticky']){
			$style_pos = 'style="position: fixed;"';
			$id_tab = 'tab_egoi_footer_fixed';
		}else{
			$id_tab = 'tab_egoi_footer';
		}

		$bar_post['color_bar'] = !empty($bar_post['color_bar_transparent'])?$bar_post['color_bar']:'transparent';

		
		if($bar_post['position'] == 'top'){

			$bar_content = '<span style="display:none;" id="e-goi-bar-session">'.$enable.'</span>
			<div class="egoi-bar" id="egoi-bar" style="'.$hidden.'">
				<input type="hidden" name="list" value="'.$bar_post['list'].'">
				<input type="hidden" name="lang" value="'.$bar_post['lang'].'">
				<input type="hidden" name="tag" value="'. $tag .'">
				<input type="hidden" name="double_optin" value="'. $bar_post['double_optin'] .'">
				<label class="egoi-label" style="display:inline-block;">'.$bar_post['text_bar'].'</label>
				<input type="email" name="email" placeholder="'.$bar_post['text_email_placeholder'].'" class="egoi-email" required>
				<input type="button" class="egoi_sub_btn" value="'.$bar_post['text_button'].'" />
				<span id="process_data_egoi" class="loader_btn_egoi" style="display:none;"></span>
			</div>
			<span class="egoi-action" id="tab_egoi" style="background:'.$bar_post['color_bar'].';"></span>';

		}else{

			$bar_content = '<span style="display:none;" id="e-goi-bar-session">'.$enable.'</span>
				<span class="egoi-bottom-action" id="'.$id_tab.'" style="background:'.$bar_post['color_bar'].';"></span>
				<div class="egoi-bar" id="egoi-bar" style="'.$hidden.'">
					<input type="hidden" name="list" value="'.$bar_post['list'].'">
					<input type="hidden" name="lang" value="'.$bar_post['lang'].'">
					<input type="hidden" name="tag" value="'. $tag .'">
				    <input type="hidden" name="double_optin" value="'. $bar_post['double_optin'] .'">
					<label class="egoi-label">'.$bar_post['text_bar'].'</label>
					<input type="email" name="email" placeholder="'.$bar_post['text_email_placeholder'].'" class="egoi-email" required style="display:inline-block;width:20%;">
					<input type="button" class="egoi_sub_btn" disabled value="'.$bar_post['text_button'].'" />
					<span id="process_data_egoi" class="loader_btn_egoi" style="display:none;"></span>	
				</div>';
		}

		if($regenerate){
			$top_content = '';
			$bottom_content = '';
		}else{
			$top_content = '<div id="smart-marketing-egoi">';
			$bottom_content = '</div>';
		}

		$open_comment = '<!-- Smart Marketing Bar -->';
		$close_comment = '<!-- / Smart Marketing Bar -->';

		$output = $open_comment.$top_content.$bar_content.$bottom_content.$close_comment;

		$this->set_custom_css($bar_post);
        $this->set_custom_script();
		echo $output;
		
		if($regenerate){
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
	private function set_custom_css($css) {
		
		$position = 'absolute';
		if($css['sticky']) $position = 'fixed';
		
		if($css['position'] == 'top'){
			$top = 'top: 0;';
			$border = 'border-bottom';
		}else{
			$top = 'bottom: 0;';
			$border = 'border-top';
			$position = 'relative';
			if($css['sticky']){
				$position = 'fixed';
				$tab_bar = 'position:fixed !important; bottom:50px;';
			}
		}

		echo '<style type="text/css">' . PHP_EOL;
	
			echo ".egoi-bar { height: auto; left: 0px; background:".$css['color_bar']." !important; ".$border.":".$css['border_px']." solid ".$css['border_color']." !important; ".$top." position:".$position."; }" . PHP_EOL;
			echo ".egoi-label { color: ".$css['bar_text_color']." !important; }" . PHP_EOL;
			
			echo ".egoi-close { ".$tab_bar." background-color:".$css['color_bar'].";border-left:".$css['border_px']." solid ".$css['border_color']." !important; }" . PHP_EOL;
			
			$background = $css['color_button'];
			echo ".egoi_sub_btn { color: ".$css['color_button_text']." !important; background-color: ".$background." !important; padding: 2px 5px !important; height: 30px !important; }" . PHP_EOL;	

		echo '</style>';
	}

	public function get_bar($element_id = 'egoi_bar_sync', $submitted = '') {
		global $_egoiFiltersbar;

		if ( ! isset( $_egoiFiltersbar ) ) {
			$_egoiFiltersbar = true;
		}

		global $error;
		$html = '';
		if($_egoiFiltersbar == true){
			$_egoiFiltersbar = false;
			$html = $this->generate_bar($submitted);
		}
		return $html;
	}

	public function subscribe() {
		
		$bar = get_option(Egoi_For_Wp_Admin::BAR_OPTION_NAME);

		$action = $_POST['action'];	
		$email = $_POST['email'];

		if(empty($email)){
            if($bar['position'] == 'top'){
                $close_btn = '<span class="egoi-action-error top" id="tab_egoi_submit_close"></span>';
            }else{
                $close_btn = '<span class="egoi-action-error bottom" id="tab_egoi_submit_close"></span>';
            }
            $bar_content_error = '<div class="egoi-bar" id="egoi-bar" style="background:'.$bar['error_bgcolor'].'!important;border:none!important;"><div class="egoi-bar-error">'. __('Email can not be empty','egoi-for-wp') .'</div>'.$close_btn.'</div>';
            echo $bar_content_error;
            exit;
        }

		$fname = explode('@', $email);
		$name = $fname[0];

		$lang = $bar['lang'];

		if(isset($bar['tag-egoi']) && $bar['tag-egoi'] != ''){
		    $tag = $bar['tag-egoi'];
        }
        else{
	    	$data = new Egoi_For_Wp();
	    	$new = $data->getTag($bar['tag']);
	    	$tag = $new['ID'];
        }




		if($action){

			$error = '';
			if(empty($email)) {
				$error = $bar['text_error'];
			}

			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$error = $bar['text_invalid_email'];
			}

			$client = new Egoi_For_Wp();
			$get = $client->getSubscriber($bar['list'], $email);

			if($get->subscriber->ERROR == 'LIST_MISSING'){
				$error = $bar['text_error'];
			}

            if (!empty($get->subscriber->UID)) {
                switch ($get->subscriber->STATUS) {
                    case "3":
                    case "0":
                        if(empty($bar['text_waiting_for_confirmation'])){
                            $bar['text_waiting_for_confirmation'] = 'Already subscribed and waiting for confirmation e-mail';
                            update_option( Egoi_For_Wp_Admin::BAR_OPTION_NAME, $bar );
                        }
                        $error = $bar['text_waiting_for_confirmation'];
                        break;
                    default:
                        $error = $bar['text_already_subscribed'];
                        break;
                }
            }

            $status = !isset($bar['double_optin']) || $bar['double_optin'] == 0 ? 1 : 0;

			$add = $client->addSubscriber($bar['list'], $name, $email, $lang, $status, '', intval($tag));
            if (!isset($add->ERROR) && !isset($add->MODIFICATION_DATE) ) {
                $client->smsnf_save_form_subscriber(1, 'bar', $add);
            }

			$success = $bar['text_subscribed'];

			if($error){
				if($bar['position'] == 'top'){
					$close_btn = '<span class="egoi-action-error top" id="tab_egoi_submit_close"></span>';
				}else{
					$close_btn = '<span class="egoi-action-error bottom" id="tab_egoi_submit_close"></span>';
				}
				$bar_content_error = '<div class="egoi-bar" id="egoi-bar" style="background:'.$bar['error_bgcolor'].'!important;border:none!important;"><div class="egoi-bar-error">'.$error.'</div>'.$close_btn.'</div>';
				echo $bar_content_error;

			}else{
				if($bar['redirect']){
					wp_redirect($bar['redirect']);

				}else{
					$success_btn = '<span class="egoi-action-success" id="tab_egoi_submit_close"></span>';
					$bar_content_success = '<div class="egoi-bar" id="egoi-bar" style="background:'.$bar['success_bgcolor'].'!important;border:none!important;"><div class="egoi-bar-success">'.$success.'</div>'.$success_btn.'</div>';
					echo $bar_content_success;
				}
			}
		}
		exit;
	}

	public function subscribe_egoi_form()
	{
		$post = $_POST;

		$ch = curl_init($post['action_url']);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$server_output = curl_exec($ch);
		curl_close($ch);

		$outputs = explode('</style>', $server_output);
		$content = strip_tags($outputs[1], '<div></div><p></p><br>');
		echo $content;
		exit;
	}


	/* 
	*	Generate a E-goi Form
	*/
	public function generate_html($submit = '', $id) {

		$form = get_option('egoi_form_sync_'.$id);
		$show_title = $form['egoi_form_sync']['show_title'];

		$form_enabled = $form['egoi_form_sync']['enabled'];
		if($form_enabled){

			$form_post = get_option('egoi_form_sync_'.$id);
			$type = $form_post['egoi_form_sync']['egoi'];
			$title = $form_post['egoi_form_sync']['form_name'];
			$px = $form_post['egoi_form_sync']['border'];
			$color = ' solid '.$form_post['egoi_form_sync']['border_color'];
			if(!$px)
				$px = '0';

			if(!$color)
				$color = 'none';

			$width = $form_post['egoi_form_sync']['width'];
			$height = $form_post['egoi_form_sync']['height'];

			if($type == 'iframe'){
				$content = explode(' - ', $form_post['egoi_form_sync']['form_content']);
				$iframe = '<iframe src="//'.$content[1].'" width="'.$width.'" height="'.$height.'" style="border:'.$px.$color.';" onload="window.parent.parent.scrollTo(0,0);">
				</iframe>';
				$fields = $iframe;

			}else{
				$content = stripslashes(html_entity_decode($form_post['egoi_form_sync']['form_content']));
				//$fields = str_ireplace('type="submit"', 'type="button" data-egoi_form_submit="1"', $content);
				$fields = $content;
			}
			
			if($show_title){
				$html = '<h1>'.$title.'</h1>';
			}
			$html .= '<div class="egoi4wp-form-fields" id="form-fields">'.$fields.$this->hidden_fields().'</div><span id="egoi_form_loader"></span>';
			
			if($submit){
				$output = $html.$error_div;
			}else{
				$output = $html;
			}

			return $output;
		}
	}

	public function hidden_fields(){

		$hidden = '<input type="hidden" name="form_action" value="form_handler">';
		$hidden .= '<input type="hidden" name="url" value="'.$_SERVER['REQUEST_URI'].'">';
		return $hidden;
	}

	public function get_html($element_id = 'egoi_form_sync', $submitted = '') {

		$html = $this->generate_html( $submitted );
		return $html;
	}

	public function get_errors($submitted, $error_div, $form_post) {

		$html = (string) apply_filters('egoi_form_response_html', $error_div, $form_post);
		$html = $this->generate_html($submitted, $error_div);
		return $html;
	}

	
	// Simple form shortcode output
	public function subscribe_egoi_simple_form( $atts, $qt = 1 ){
		global $wpdb;

		$id = $atts['id'];
        $simple_form = 'egoi_simple_form_'.$id.'_'.$qt;
        $simple_form_result = $simple_form . "_result";

		$post = '<form id="'.$simple_form.'" method="post" action="/">';

		$options = get_option('egoi_simple_form_'.$id);
		$data = json_decode($options);

        $post .= '<input type="hidden" name="egoi_simple_form" id="egoi_simple_form" value="'.$id.'">';
		$post .= '<input type="hidden" name="egoi_list" id="egoi_list" value="'.$data->list.'">';
		$post .= '<input type="hidden" name="egoi_lang" id="egoi_lang" value="'.$data->lang.'">';
		$post .= '<input type="hidden" name="egoi_tag" id="egoi_tag" value="'.$data->tag.'">';
        $post .= '<input type="hidden" name="egoi_double_optin" id="egoi_double_optin" value="'.$data->double_optin.'">';

		$table = $wpdb->prefix.'posts';
		$html_code = $wpdb->get_row(" SELECT post_content, post_title FROM $table WHERE ID = '$id' ");
		$tags = array('name','email','mobile','submit');
		foreach ($tags as $tag) {
			$html_code->post_content = str_replace('[e_'.$tag.']','',$html_code->post_content);
			$html_code->post_content = str_replace('[/e_'.$tag.']','',$html_code->post_content);
		}

		$post .= stripslashes($html_code->post_content);
		$post .= '<div id="'.$simple_form_result.'" class="egoi_simple_form_success_wrapper" style="margin:10px 0px; padding:12px; display:none;"></div>';
		$post .= '</form>';
		
		$post .= '
			<script type="text/javascript" >
				jQuery(document).ready(function() {
					jQuery("#'.$simple_form.' select[name=egoi_country_code]").empty();
				';
		foreach (unserialize(COUNTRY_CODES) as $key => $value) {
		 	$string = ucwords(strtolower($value['country_pt']))." (+".$value['prefix'].")";
		 	$post .= 'jQuery("#'.$simple_form.' select[name=egoi_country_code]").append("<option value='.$value['prefix'].'>'.$string.'</option>");';
		}
		$post .= '
				});

                jQuery("#'.$simple_form.' select[name=egoi_country_code]").val(jQuery("#'.$simple_form.' select[name=egoi_country_code]").data("selected"));

				jQuery("#'.$simple_form.'").submit(function(event) {
					
					var simple_form = jQuery(this);
					event.preventDefault(); // Stop form from submitting normally
                    
					var button_obj = jQuery("button[type=submit]", "#'.$simple_form.'");
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

					jQuery( "#'.$simple_form_result.'" ).hide();

					var ajaxurl = "'.admin_url('admin-ajax.php').'";
					var egoi_simple_form = jQuery("#'.$simple_form.' input[name=egoi_simple_form]").val();
					var egoi_name = jQuery("#'.$simple_form.' input[name=egoi_name]").val();
					var egoi_email = jQuery("#'.$simple_form.' input[name=egoi_email]").val();
					var egoi_country_code	= jQuery("#'.$simple_form.' select[name=egoi_country_code]").val();
					var egoi_mobile	= jQuery("#'.$simple_form.' input[name=egoi_mobile]").val();
					var egoi_list = jQuery("#'.$simple_form.' input[name=egoi_list]").val();
					var egoi_lang = jQuery("#'.$simple_form.' input[name=egoi_lang]").val(); 
					var egoi_tag = jQuery("#'.$simple_form.' input[name=egoi_tag]").val();
					var egoi_double_optin = jQuery("#'.$simple_form.' input[name=egoi_double_optin]").val();

					var data = {
						"action": "egoi_simple_form_submit",
						"egoi_simple_form": egoi_simple_form,
						"egoi_name": egoi_name,
						"egoi_email": egoi_email,
						"egoi_country_code": egoi_country_code,
						"egoi_mobile": egoi_mobile,
						"egoi_list": egoi_list,
						"egoi_lang": egoi_lang,
						"egoi_tag": egoi_tag,
						"egoi_double_optin" : egoi_double_optin
					};

					var posting = jQuery.post(ajaxurl, data);

					posting.done(function( data ) {
						if (data.substring(0, 5) != "ERROR" && data.substring(0, 4) != "ERRO") {
                               
                            var event = new Event("'.$simple_form.'");
                            var elem = document.getElementsByTagName("html");
                            elem[0].dispatchEvent(event);
                            
							jQuery( "#'.$simple_form_result.'" ).css({
								"color": "#4F8A10",
								"background-color": "#DFF2BF"
							});

							jQuery( "#'.$simple_form.'" )[0].reset();

						} else {
							jQuery( "#'.$simple_form_result.'" ).css({
								"color": "#9F6000",
								"background-color": "#FFD2D2"
							});
						}

						jQuery( "#'.$simple_form_result.'" ).empty().append( data ).slideDown( "slow" );
						clearInterval(button_effect);
						if (button_original_style) {
						    button_obj.prop("disabled",false).attr("style", button_original_style).html(button_text);
						} else {
						    button_obj.prop("disabled",false).removeAttr("style").html(button_text);
						}
					});

                    

				});
				setTimeout(function(){ jQuery("#'.$simple_form.' select[name=egoi_country_code]").val(jQuery("#'.$simple_form.' select[name=egoi_country_code]").data("selected")); }, 100);
			</script>
		';

		return $post;
	}

	/**
	 * WPBakery Page Builder Shortcode output
	 */
	public function egoi_vc_shortcode_output( $atts, $content = null ) {

		if (function_exists('vc_map_get_attributes')) {
			// Extract shortcode attributes (based on the vc_lean_map function - see next function)
			extract( vc_map_get_attributes( 'egoi_vc_shortcode', $atts ) );
			
			return $this->subscribe_egoi_simple_form( array('id'=>$shortcode_id) );
		} else {
			return false;
		}

	}


    /**
     * Web Push Output
     */
    public function add_webpush() {
		$options = get_option('egoi_webpush_code');
		global $_egoiFilterswebpush;

		if ( ! isset( $_egoiFilterswebpush ) ) {
			$_egoiFilterswebpush = true;
		}

        if (isset($options['track']) && $options['track'] == 1 && $_egoiFilterswebpush == true) {
			$_egoiFilterswebpush = false;
            $cod = trim($options['code']);
            $js = "
                <script type=\"text/javascript\">
                    var _egoiwp = _egoiwp || {};
                    (function(){
                    var u=\"https://cdn-static.egoiapp2.com/\";
                    _egoiwp.code = \"$cod\";
                    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
                    g.type='text/javascript';
                    g.defer=true;
                    g.async=true;
                    g.src=u+'webpush.js';
                    s.parentNode.insertBefore(g,s);
                    })();
                </script>
                ";
            return $js;
        } else {
            return false;
        }
    }

    public function add_egoi_rss_feeds(){
        global $wpdb;
        $table = $wpdb->prefix."options";
        $options = $wpdb->get_results( " SELECT option_name FROM ".$table." WHERE option_name LIKE 'egoi_rssfeed_%' ");
        foreach ($options as $option) {
            add_feed($option->option_name, 'egoi_rss_feeds' );
        }
    }

    public function egoi_add_newsletter_signup_hide(){
        if(is_user_logged_in()){
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

    public function egoi_add_newsletter_signup(){
        $options = get_option(Egoi_For_Wp_Admin::OPTION_NAME);

        $fields = Egoi_For_Wp::egoi_subscriber_signup_fields();
        global $current_user;

        foreach ( $fields as $key => $field_args ) {
            if ( $current_user ) {
                $checked = get_user_meta($current_user->ID, $key, true);
            }
            woocommerce_form_field( $key, $field_args, ((empty($checked)?0:true) || (!empty($options[$key]))) );
        }
    }
    public function egoi_save_account_fields_order( $order_id ) {
        $api = new Egoi_For_Wp();

        if(is_user_logged_in()){
            return;
        }else{//guest buyer
            $options = $this->load_options();
            $user = $_POST;
            $sub = $this->get_default_map($user);
            if(get_option('egoi_mapping')){
                $sub = $this->egoi_map_subscriber($user, $sub);
            }

        }
        $subscriber_tags = [ $api->createTagVerified(Egoi_For_Wp::GUEST_BUY) ];
        if(!empty($user_meta['egoi_newsletter_active']) || !empty($_POST['egoi_newsletter_active']) ){
            $subscriber_tags[] = $api->createTagVerified(Egoi_For_Wp::TAG_NEWSLETTER);
        }
        $api->addSubscriberBulk($options['list'], $subscriber_tags ,[$sub]);
    }

    public function egoi_save_account_fields( $customer_id ) {
        $fields = Egoi_For_Wp::egoi_subscriber_signup_fields();
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

    private function get_default_map($subscriber){
        if(is_array($subscriber)){
            return [//basic info
                'status' => 1,
                'email' => empty($subscriber['user_email'])?$subscriber['billing_email']:$subscriber['user_email'],
                'cellphone' => empty($subscriber['billing_phone'])?Egoi_For_Wp::smsnf_get_valid_phone($subscriber['shipping_phone'],(empty($subscriber['billing_country'])?$subscriber['shipping_country']:$subscriber['billing_country'])):Egoi_For_Wp::smsnf_get_valid_phone($subscriber['billing_phone']),
                'first_name' => empty($subscriber['first_name'])?$subscriber['billing_first_name']:$subscriber['first_name'],
                'last_name' => empty($subscriber['last_name'])?$subscriber['billing_last_name']:$subscriber['last_name']
            ];
        }
    }

    private function egoi_map_subscriber($subscriber, $defaultMap){

        $api = new Egoi_For_Wp();

        if (class_exists('WooCommerce')) {
            $wc = new WC_Admin_Profile();
            $woocommerce = [];
            foreach ($wc->get_customer_meta_fields() as $key => $value_field) {
                foreach($value_field['fields'] as $key_value => $label){
                    $row_new_value = $api->getFieldMap(0, $key_value);
                    if($row_new_value){
                        $woocommerce[$row_new_value] = $key_value;
                    }
                }
            }
        }

        foreach ($subscriber as $key => $value) {
            $row = $api->getFieldMap(0, $key);
            if($row){
                preg_match('/^key_[0-9]+/', $row, $output);
                if(count($output) > 0){
                    $defaultMap[str_replace('key_','extra_', $row)] = $value;
                }else{
                    $defaultMap[$row] = $value;
                }
            }
        }

        foreach($woocommerce as $key => $value){
            if (isset($subscriber->$value)) {
                $defaultMap[str_replace('key', 'extra', $key)] = $subscriber->$value;
            } else if (isset($subscriber[$value]) && !is_array($subscriber[$value]) ) {
                $defaultMap[str_replace('key', 'extra', $key)] = $subscriber[$value];
            } else if (isset($subscriber[$value][0])) {
                $defaultMap[str_replace('key', 'extra', $key)] = $subscriber[$value][0];
            }
        }

        return $defaultMap;
    }

    public function process_simple_form_add(){
        $api = new Egoi_For_Wp();

        // double opt-in
        $status = filter_var(stripslashes($_POST['egoi_double_optin']), FILTER_SANITIZE_STRING) == '1' ? 0 : 1;
        $form_data = [];

        if(empty($_POST['elementorEgoiForm'])){//old simple forms
            $form_data = array(
                'email' => filter_var($_POST['egoi_email'], FILTER_SANITIZE_EMAIL),
                'cellphone' => filter_var($_POST['egoi_country_code']."-".$_POST['egoi_mobile'], FILTER_SANITIZE_STRING),
                'first_name' => filter_var(stripslashes($_POST['egoi_name']), FILTER_SANITIZE_STRING),
                'lang' => filter_var($_POST['egoi_lang'], FILTER_SANITIZE_EMAIL),
                'tags' => array(filter_var($_POST['egoi_tag'], FILTER_SANITIZE_NUMBER_INT)),
                'status' => $status,
            );
        }else{
            $_POST['status'] = $status;
            $_POST['tags'] = array(filter_var($_POST['egoi_tag'], FILTER_SANITIZE_NUMBER_INT));
            //$_POST['lang'] = filter_var($_POST['egoi_lang'], FILTER_SANITIZE_EMAIL);
        }

        $result = $api->addSubscriberWpForm(
            filter_var($_POST['egoi_list'], FILTER_SANITIZE_NUMBER_INT),
            empty($form_data)?$_POST:$form_data
        );

        if (!isset($result->ERROR) && !isset($result->MODIFICATION_DATE) ) {

            $form_id = filter_var($_POST['egoi_simple_form'], FILTER_SANITIZE_NUMBER_INT);
            if(empty($_POST['elementorEgoiForm'])){
                $api->smsnf_save_form_subscriber($form_id, 'simple-form', $result);
            }

            echo $this->check_subscriber($result).' ';
            _e('was successfully registered!', 'egoi-for-wp');
        } else if (isset($result->MODIFICATION_DATE)) {
            _e('Subscriber data from', 'egoi-for-wp');
            echo ' '.$this->check_subscriber($result).' ';
            _e('has been updated!', 'egoi-for-wp');
        } else if (isset($result->ERROR)) {
            if ($result->ERROR == 'NO_DATA_TO_INSERT') {
                _e('ERROR: no data to insert', 'egoi-for-wp');
            } else if ($result->ERROR == 'EMAIL_ADDRESS_INVALID_MX_ERROR') {
                _e('ERROR: e-mail address is invalid', 'egoi-for-wp');
            } else {
                _e('ERROR: invalid data submitted', 'egoi-for-wp');
            }

        }

        wp_die(); // this is required to terminate immediately and return a proper response
    }

    public function check_subscriber($subscriber_data) {
        $data = array('FIRST_NAME','EMAIL','CELLPHONE');
        foreach ($data as $value) {
            if ($subscriber_data->$value) {
                $subscriber = $subscriber_data->$value;
                break;
            }
        }
        return $subscriber;
    }

    public function smsnf_save_advanced_form_subscriber() {
        $success_messages = array(
            'Está quase! Só falta confirmar o seu email.',
            'You\'re almost there! We just need to confirm your email address.',
            'Solamente necesitamos confirmar su correo electrónico.'
        );

        $form_data = array();
        parse_str($_POST['form_data'], $form_data);

        $url = strpos($_POST['url'], 'http') !== false ? $_POST['url'] : 'http:'.$_POST['url'];
        $response = wp_remote_post($url, array(
                'method' => 'POST',
                'headers' => [
                    'Content-Type' => "application/x-www-form-urlencoded"
                ],
                'timeout' => 60,
                'body' => $form_data,
            )
        );

        if ($response['response']['code'] != 200) { 
            echo false;
        } else {
            $output = 200;
            foreach ($success_messages as $message) {
                if (strpos($response['body'], $message) !== false) {
                    $output = $message;

                    $subscriber = (object) array(
                        'UID' => null,
                        'FIRST_NAME' => $_POST['fname'].' '.$_POST['lname'],
                        'EMAIL' => $_POST['email'],
                        'LIST' => $form_data['lista']
                    );

                    for ($i=1; $i<=10; $i++) {
                        $form = get_option('egoi_form_sync_'.$i);
                        if ($form && strpos($form['egoi_form_sync']['form_content'], $_POST['form_id']) !== false) {
                            $form_id = $form['egoi_form_sync']['form_id'];
                            $form_title = $form['egoi_form_sync']['form_name'];
                            break;
                        }
                    }

                    $api = new Egoi_For_Wp();
                    $api->smsnf_save_form_subscriber($form_id, 'advanced-form', $subscriber, $form_title);
                    break;
                }
            }
        }
        echo $output;
        wp_die();
    }

    public function hookEcommerce(){

        $options = $this->load_options();

        $client_info = get_option('egoi_client');
        $client_id = $client_info->CLIENTE_ID;
		$track_social_id = !empty($options['social_track']) && !empty($options['social_track_id']) ? $options['social_track_id'] : null;

        require_once plugin_dir_path( __FILE__ ) . 'includes/TrackingEngageSDK.php';
		$track = new TrackingEngageSDK($client_id, $options['list'], false, $track_social_id);
		if(!empty($options['list']) && !empty($options['track'])){
			$track->getStartUp();
		}
		if(isset($options['social_track']) && $options['social_track']){
			$track->getStartUpSocial();
		}
        if(class_exists('WooCommerce') && is_product() && isset($options['social_track_json'])){
            $track->getProductLdJSON();          
        }
    }

    public function hookEcommerceSetOrder($order_id = false){

        $options = $this->load_options();

        if(empty($options['list']) || empty($options['track'])){return false;} //list && tracking&engage setup check
        $client_info = get_option('egoi_client');
        $client_id = $client_info->CLIENTE_ID;

        require_once plugin_dir_path( __FILE__ ) . 'includes/TrackingEngageSDK.php';
        $track = new TrackingEngageSDK($client_id, $options['list'], $order_id);
        $track->setOrder();
    }

    public function hookEcommerceGetOrder($order = false){

        $options = $this->load_options();

        if(empty($options['list']) || empty($options['track'])){return false;} //list && tracking&engage setup check
        $client_info = get_option('egoi_client');
        $client_id = $client_info->CLIENTE_ID;

        if( version_compare( get_option( 'woocommerce_version' ), '3.0.0', ">=" ) ) {
            if(is_numeric($order)){
                $order_id = $order;
            }else{
                $order_id = $order->get_id();
            }
        } else {
            if(is_numeric($order)){
                $order_id = $order;
            }else{
                $order_id = $order->id;
            }
        }
        require_once plugin_dir_path( __FILE__ ) . 'includes/TrackingEngageSDK.php';
        $track = new TrackingEngageSDK($client_id, $options['list'], $order_id);
        $track->getOrder();
    }

    public function loadPopups(){

        require_once plugin_dir_path( __FILE__ ) . '../includes/class-egoi-for-wp-popup.php';

        $popups = EgoiPopUp::getSavedPopUps();

        foreach ($popups as $popup_id){
            $popup = new EgoiPopUp($popup_id);
            $popup->printPopup();
        }

    }

    private function load_options() {

        static $defaults = array(
            'list' => '',
            'enabled' => 0,
            'egoi_newsletter_active' => 0,
            'track' => 1,
            'social-track' => 0,
            'role' => 'All'
        );

        if(!get_option( Egoi_For_Wp_Admin::OPTION_NAME, array() )) {
            add_option( Egoi_For_Wp_Admin::OPTION_NAME, array($defaults) );
        }else{
            $options = (array) get_option( Egoi_For_Wp_Admin::OPTION_NAME, array() );

            $options = array_merge($defaults, $options);
            return (array) apply_filters( 'egoi_sync_options', $options );
        }
    }

}
