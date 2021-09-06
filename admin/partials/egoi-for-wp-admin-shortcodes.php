<?php
// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die();
}
function get_optionsform( $form_id ) {
	switch ( $form_id ) {
		case 1:
			$FORM_OPTION = Egoi_For_Wp_Admin::FORM_OPTION_1;
			break;
		case 2:
			$FORM_OPTION = Egoi_For_Wp_Admin::FORM_OPTION_2;
			break;
		case 3:
			$FORM_OPTION = Egoi_For_Wp_Admin::FORM_OPTION_3;
			break;
		case 4:
			$FORM_OPTION = Egoi_For_Wp_Admin::FORM_OPTION_4;
			break;
		case 5:
			$FORM_OPTION = Egoi_For_Wp_Admin::FORM_OPTION_5;
			break;
	}
	return $FORM_OPTION;
}

