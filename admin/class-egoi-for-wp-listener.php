<?php
// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
    die();
}
/**
*  E-goi Listener 4 Syncronize Users with WP
*/
class Egoi_For_Wp_Listener {
	
	private $options_listen = '';
	private $user = '';
	private $enabled = '';

	public function __construct( $plugin_name, $version ){

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	public function init($user_id){

		if ($this->isActive()){
			if($user_id){
				$this->Listen($user_id);
			}
		}

	}

	public function isActive(){

		$options = get_option(Egoi_For_Wp_Admin::OPTION_NAME);
		$this->options_listen = $options;

		if($options['enabled']){
			$this->enabled = $options['enabled'];
			return $this->enabled;
		}

	}

	private function getMapping($user_id){

		global $wpdb;
		
		$table = $wpdb->prefix."egoi_map_fields";
		$sql="SELECT * FROM $table order by id DESC";
        $rows = $wpdb->get_results($sql);

        return $rows;
	}

	private function Listen($user_id){

		$user = get_userdata($user_id);

		$list = $this->options_listen['list'];

		$admin = new Egoi_For_Wp();

        $subscriber_tags=[];
		if(!empty($user->egoi_newsletter_active) || !empty($_POST['egoi_newsletter_active'])){
            $subscriber_tags[] = $admin->createTagVerified(Egoi_For_Wp::TAG_NEWSLETTER);
        }

		//mapping fields
		if(get_option('egoi_mapping')){
			
			$op = 1;
			$all_fields = get_user_meta($user_id);
			$all_fields['user_email'][0] = $user->user_email;
			$all_fields['user_url'][0] = $user->user_url;
			$all_fields['user_login'][0] = $user->user_login;

            if (!empty($_POST['billing_first_name'])) {
                $all_fields['billing_first_name'][0] = $_POST['billing_first_name'];
            }
            if (!empty($_POST['billing_last_name'])) {
                $all_fields['billing_last_name'][0] = $_POST['billing_last_name'];
            }
            if (!empty($_POST['billing_phone'])) {
                $all_fields['billing_phone'][0] = $_POST['billing_phone'];
            }
            if (!empty($_POST['billing_cellphone'])) {
                $all_fields['billing_cellphone'][0] = $_POST['billing_cellphone'];
            }

			foreach ($all_fields as $key => $value) {
				$row = $admin->getFieldMap(0, $key);
				if($row){
					$fields[$row] = $value[0];
				}
			}
		}

		$role = $this->options_listen['role'];
		$email = $user->user_email;

        $fields['telephone'] = $admin->smsnf_get_valid_phone($fields['telephone']);
        $fields['cellphone'] = $admin->smsnf_get_valid_phone($fields['cellphone']);

		if(empty($role) || $role == $user->roles[0]){

            $subscriber_tags[] = $admin->createTagVerified($user->roles[0]);
            $admin->addSubscriberTags($list, $email, $subscriber_tags, '', '', $role, $fields, $op);

		}
			
	}

    public function Listen_update($user_id) {

        if ($this->isActive()){
            $user = get_userdata($user_id);
            $role = $user->roles[0];
            $email = $user->user_email;
            $fname = ucfirst($user->display_name);
            $role_option = $this->options_listen['role'];
            $list = $this->options_listen['list'];

            $admin = new Egoi_For_Wp();

            if(!empty($role) && $role != $user->roles[0]){//role not to sync
                return;
            }

            $subscriber_tags=[];
            if(!empty($user->egoi_newsletter_active) || !empty($_POST['egoi_newsletter_active'])){
                $subscriber_tags[] = $admin->createTagVerified(Egoi_For_Wp::TAG_NEWSLETTER);
            }

            //mapping fields
            if(get_option('egoi_mapping')){

                $op = 1;
                $all_fields = get_user_meta($user_id);

                $all_fields['user_email'][0] = $email;
                $all_fields['user_url'][0] = $user->user_url;
                $all_fields['user_login'][0] = $user->user_login;

                if (!empty($_POST['billing_first_name'])) {
                    $all_fields['billing_first_name'][0] = $_POST['billing_first_name'];
                }
                if (!empty($_POST['billing_last_name'])) {
                    $all_fields['billing_last_name'][0] = $_POST['billing_last_name'];
                }
                if (!empty($_POST['billing_phone'])) {
                    $all_fields['billing_phone'][0] = $_POST['billing_phone'];
                }
                if (!empty($_POST['billing_cellphone'])) {
                    $all_fields['billing_cellphone'][0] = $_POST['billing_cellphone'];
                }

                foreach ($all_fields as $key => $value) {
                    $row = $admin->getFieldMap(0, $key);
                    if($row){
                        $fields[$row] = $value[0];
                    }
                }

            }

            $fields['telephone'] = $admin->smsnf_get_valid_phone($fields['telephone']);
            $fields['cellphone'] = $admin->smsnf_get_valid_phone($fields['cellphone']);

            $get_user = $admin->editSubscriber($list, $email, $role, $fname, '', $fields, $op,[],$subscriber_tags);

            if($get_user->ERROR == 'SUBSCRIBER_NOT_FOUND'){
                $admin->addSubscriber($list, $fname, $email, '',true,!empty($fields['cellphone'])?$fields['cellphone']:'',$subscriber_tags,!empty($fields['telephone'])?$fields['telephone']:'');
            }

        }

    }

	public function Listen_delete($user_id) {

		if ($this->isActive()){
			$user = get_userdata($user_id);
			$list = $this->options_listen['list'];

			$admin = new Egoi_For_Wp($this->plugin_name, $this->version);
			$del_user = $admin->delSubscriber($list, $user->user_email);

			if($del_user->ERROR == 'SUBSCRIBER_NOT_FOUND'){
				return false;
			}
		}

	}

}