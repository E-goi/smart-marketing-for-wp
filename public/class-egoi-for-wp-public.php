<?php

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
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/egoi-for-wp-public.js', array( 'jquery' ), $this->version, false );
			wp_localize_script( $this->plugin_name, 'url_egoi_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		}
	}

	/* 
	*	Generate E-goi bar
	*/
	public function generate_bar($regenerate = null) {
		
		$bar_post = get_option(Egoi_For_Wp_Admin::BAR_OPTION_NAME);
		
		// if defined some redirection
		if($bar_post['redirect']){
			if($_POST['egoi_action_sub']){
				$this->subscribe();
			}
		}

		$hidden = '';
		if(isset($_COOKIE['hide_bar']) && ($_COOKIE['hide_bar'] == 1)){
			$hidden = 'display:none;';
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
		
		if($bar_post['position']=='top'){

			$bar_content = '
			<div class="egoi-bar" id="egoi-bar" style="'.$hidden.'">
				<label class="egoi-label" style="display:inline-block;">'.$bar_post['text_bar'].'</label>
				<input type="email" name="email" placeholder="'.$bar_post['text_email_placeholder'].'" class="egoi-email" required>
				<input type="button" class="egoi_sub_btn" value="'.$bar_post['text_button'].'" />
				<span id="process_data_egoi" class="loader_btn_egoi" style="display:none;"></span>
			</div>
			<span class="egoi-action" id="tab_egoi" style="background:'.$bar_post['color_bar'].';"></span>';

		}else{

			$bar_content = '
				<span class="egoi-bottom-action" id="'.$id_tab.'" style="background:'.$bar_post['color_bar'].';"></span>
				<div class="egoi-bar" id="egoi-bar" style="'.$hidden.'">
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
	
			echo ".egoi-bar { height: 45px; left: 0px; background:".$css['color_bar']." !important; ".$border.":".$css['border_px']." solid ".$css['border_color']." !important; ".$top." position:".$position."; }" . PHP_EOL;
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

			$add = $client->addSubscriber($bar['list'], $name, $email);
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

		$content = strip_tags(explode('</style>', $server_output)[1], '<div></div><p></p><br>');
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

	public function getTrackEngage($content){

		echo $content;
		return true;
	}


}
