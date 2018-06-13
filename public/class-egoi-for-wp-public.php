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

		if(isset($bar_post['enabled']) && ($bar_post['enabled'])) {
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/e-goi.min.js', array( 'jquery' ), $this->version, false );
			wp_localize_script( $this->plugin_name, 'url_egoi_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		}
	}

	/* 
	*	Generate E-goi bar
	*/
	public function generate_bar($regenerate = null) {
		
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
					<input type="button" class="egoi_sub_btn" value="'.$bar_post['text_button'].'" />
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
		echo $output;
		
		if($regenerate){
			exit;
		}
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
		
		global $error;

		$html = $this->generate_bar($submitted);
		return $html;
	}

	public function subscribe() {
		
		$bar = get_option(Egoi_For_Wp_Admin::BAR_OPTION_NAME);

		$action = $_POST['action'];	
		$email = $_POST['email'];
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

			if($get->subscriber->UID){
				$error = $bar['text_already_subscribed'];
			}

            if (!isset($bar['double_optin']) || $bar['double_optin'] == 0) {
                $status = 1;
            } else {
                $status = 0;
            }

			$add = $client->addSubscriber($bar['list'], $name, $email, $lang, $status, '', $tag);

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
	public function subscribe_egoi_simple_form( $atts ){
		global $wpdb;

		$id = $atts['id'];
        $simple_form = 'egoi_simple_form_'.$id;

		$post = '<form id="'.$simple_form.'" method="post" action="/">';

		$options = get_option($simple_form);
		$data = json_decode($options);

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
		$post .= '<div id="simple_form_result" style="margin:10px 0px; padding:12px; display:none;"></div>';
		$post .= '</form>';
		
		

		$post .= '
			<script type="text/javascript" >
				jQuery(document).ready(function() {
					jQuery("#egoi_country_code").empty();
				';
		foreach (unserialize(COUNTRY_CODES) as $key => $value) {
		 	$string = ucwords(strtolower($value['name']))." (+".$value['code'].")";
		 	$post .= 'jQuery("#egoi_country_code").append("<option value='.$value['code'].'>'.$string.'</option>");';
		}
		$post .= '
				});

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

					simple_form.find( "#simple_form_result" ).hide();

					var ajaxurl = "'.admin_url('admin-ajax.php').'";
					var egoi_name = simple_form.find("#egoi_name").val();
					var egoi_email = simple_form.find("#egoi_email").val();
					var egoi_country_code	= simple_form.find("#egoi_country_code").val();
					var egoi_mobile	= simple_form.find("#egoi_mobile").val();
					var egoi_list = simple_form.find("#egoi_list").val();
					var egoi_lang = simple_form.find("#egoi_lang").val();
					var egoi_tag = simple_form.find("#egoi_tag").val();
					var egoi_double_optin = simple_form.find("#egoi_double_optin").val();

					var data = {
						"action": "my_action",
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

							simple_form.find( "#simple_form_result" ).css({
								"color": "#4F8A10",
								"background-color": "#DFF2BF"
							});

							jQuery( "#'.$simple_form.'" )[0].reset();

						} else {
							simple_form.find( "#simple_form_result" ).css({
								"color": "#9F6000",
								"background-color": "#FFD2D2"
							});
						}

						simple_form.find( "#simple_form_result" ).empty().append( data ).slideDown( "slow" );
						clearInterval(button_effect);
						if (button_original_style) {
						    button_obj.prop("disabled",false).attr("style", button_original_style).html(button_text);
						} else {
						    button_obj.prop("disabled",false).removeAttr("style").html(button_text);
						}
					});

				});
			</script>
		';

		return $post;
	}

	/**
	 * Visual Composer Shortcode output
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
        if (isset($options['track']) && $options['track'] == 1) {
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

}
