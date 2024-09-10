<?php
/**
 * Created by PhpStorm.
 * User: tmota
 * Date: 25/07/2019
 * Time: 16:44
 */

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

class EgoiPopUp {

	const OPTION_NAME = 'egoi_popups';

	protected $id;

	public function __construct( $id = 'new' ) {
		$this->id = $id;
	}

	public function getPopupSavedData() {
		if ( $this->id == 'new' ) {
			return $this->getDefaultPopupData();
		}
		return array_merge( $this->getDefaultPopupData(), json_decode( get_option( 'egoi_popup_' . $this->id ), true ) );
	}

	public static function getSavedPopUps() {
		$popups = json_decode( get_option( self::OPTION_NAME ), true );
		if ( empty( $popups ) ) {
			return array();
		}
		return $popups;
	}

	public static function savePostPopup( $post ) {
		if ( $post['popup_id'] == 'new' ) {
			$post['popup_id'] = self::generateNextPopupId();
		}

		$popup_id = esc_attr( $post['popup_id'] );
		update_option( "egoi_popup_{$popup_id}", wp_json_encode( $post ) );

		return $post['popup_id'];
	}

	private static function generateNextPopupId() {
		$popups = self::getSavedPopUps();
		if ( empty( $popups ) ) {
			update_option( self::OPTION_NAME, wp_json_encode( array( 1 ) ) );
			return 1;
		}

		$id       = max( $popups ) + 1;
		$popups[] = $id;
		update_option( self::OPTION_NAME, wp_json_encode( $popups ) );
		return $id;
	}

	public static function deletePopup( $popup_id ) {
		$popups = self::getSavedPopUps();
		delete_option( "egoi_popup_$popup_id" );
		update_option( self::OPTION_NAME, wp_json_encode( array_diff( $popups, array( $popup_id ) ) ) );
	}

	public static function checkFormSafeDelete( $form_id ) {
		$popups = self::getSavedPopUps();

		foreach ( $popups as $popup_id ) {
			$data = json_decode( get_option( "egoi_popup_$popup_id" ), true );
			if ( $data['form_id'] == $form_id ) {
				return false;
			}
		}
		return true;
	}

	private function getDefaultPopupData() {
		return array(
			'popup_id'          => $this->id,
			'type'              => 'center',
			'form_id'           => 0,
			'border_radius'     => 0,
			'content'           => '<h1 style="text-align: center;">Newsletter</h1>',
			'trigger'           => 'delay',
			'trigger_option'    => '10',
			'page_trigger_rule' => 'contains',
			'page_trigger'      => array(),
			'form_orientation'  => 'vertical',
			'show_until'        => 'one_time',
			'background_color'  => '#ffffff',
			'font_color'        => '',
			'custom_css'        => '',
			'max_width'         => '700px',
			'popup_layout'      => 'simple',
			'side_image'        => 0,
			'show_logged'       => 'no',
			'title'             => '',
			'show_device'       => 'all',
		);
	}

	public static function isValidPreviewPost( $post ) {
		if ( ! empty( $post['data'] ) ) {
			return true;
		}
		return false;
	}

	public static function createConfigFromPost( $post, $first_time = false ) {

		$output = array();
		foreach ( $post as $property ) {
			if ( $first_time && empty( $property['value'] ) ) {
				continue;}
			if ( $property['name'] == 'page_trigger' ) {// select2 problem on already saved forms
				if ( empty( $output[ $property['name'] ] ) ) {
					$output[ $property['name'] ] = array();
				}
				if ( is_array( $property['value'] ) ) {
					$output[ $property['name'] ] = array_merge( $output[ $property['name'] ], $property['value'] );
				} elseif ( ! is_null( $property['value'] ) ) {
					$output[ $property['name'] ][] = $property['value'];
				} elseif ( empty( $property['value'] ) && ! is_null( $property['value'] ) ) {
					$output[ $property['name'] ] = array();
				}
				continue;
			}
			$output[ $property['name'] ] = $property['value'];
		}

		return $output;
	}

	public function printPopup() {
		$config = $this->getPopupSavedData();

		if ( ( $config['show_logged'] == 'no' && is_user_logged_in() ) || ( $config['show_logged'] == 'no' && ! empty( $_SESSION['egoi_tracking_uid'] ) ) ) {
			return false;
		}
		if ( $config['show_device'] != 'all' && $config['show_device'] != self::getDevice() ) {
			return false;
		}

		self::getModal( $config );
		self::getStyles( $config, true );
		self::getScripts( $config );
	}

	public static function getPreviewFromPost( $post ) {

		$config = self::createConfigFromPost( $post['data'], ! empty( $post['first_time'] ) ? $post['first_time'] : false );

		self::getModal( $config );
		self::getStyles( $config );

		do_action( 'wp_head' );// add default public styles
	}


	private static function getModal( $config ) {
		$popup_id = $config['popup_id'];
		if ( empty( $popup_id ) ) {
			return;
		}
		?>
		<div id="egoi_popup_<?php echo esc_attr( $popup_id ); ?>" class="egoi_modal_<?php echo esc_attr( $popup_id ); ?>">
			<!-- Modal content -->
			<div class="egoi_modal_content_<?php echo esc_attr( $popup_id ); ?>">
				<span class="popup_close_<?php echo esc_attr( $popup_id ); ?> dashicons dashicons-no"></span>
				<div style="border-radius: inherit;">
					<?php if ( $config['popup_layout'] == 'left_image' ) { ?>
						<div class="egoi_popup_side_image_<?php echo esc_attr( $popup_id ); ?>" style="background-image: url(<?php echo wp_get_attachment_url( $config['side_image'] ); ?>);">

						</div>
					<?php } ?>
					<div style="padding: 20px;border-radius: inherit;">
						<?php
						echo wp_kses_post(stripslashes( $config['content'] ));
						self::getFormShortCodeById( $config, $config['form_id']);
						?>
					</div>
					<?php if ( $config['popup_layout'] == 'right_image' ) { ?>
						<div class="egoi_popup_side_image_<?php echo esc_attr( $popup_id ); ?>" style="background-image: url(<?php echo wp_get_attachment_url( $config['side_image'] ); ?>);">

						</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php
	}

	private static function getScripts( $config ) {
		$popup_id = esc_textarea( $config['popup_id'] );
		?>

		<script>
			jQuery(document).ready(function($) {

				var targetPopup = $("#egoi_popup_<?php echo esc_attr( $popup_id ); ?>");
				var targetForm = $("#egoi_popup_<?php echo esc_attr( $popup_id ); ?> ").find("#egoi_simple_form_<?php echo esc_attr( $config['form_id'] ); ?>");

				var closeButton = $(".popup_close_<?php echo esc_attr( $popup_id ); ?>");

				closeButton.on('click', function(){
					closePopup();
				});

				var elem = document.getElementsByTagName("html");
				elem[0].addEventListener("egoi_simple_form_<?php echo esc_attr( $popup_id ); ?>", function (e) {
					setTimeout(function () {
						<?php self::getFormSubmit( $config ); ?>
						closePopup();
					}, 4000);
				}, false);

				function closePopup() {
					targetPopup.hide();
				}

				function triggerPopup(){
					if(localStorage.getItem('popup_trigger_<?php echo esc_attr( $popup_id ); ?>') !== null){
						return;
					}
					<?php
						self::getPageTrigger( $config );
						self::getShowUntil( $config );
					?>

					targetPopup.css('display', 'flex');
					<?php self::getPopUpDisplayed( $config ); ?>
				}

				function startPopup(){
					<?php
					switch ( $config['trigger'] ) {
						case 'delay':
							self::getTriggerDelayJs( $config['trigger_option'] );
							break;
						case 'on_leave':
							self::getTriggerOnLeaveJs();
							break;
					}

					?>

				}

				startPopup();


			});
		</script>

		<?php
	}

	private static function getFormSubmit( $config ) {
		$popup_id = esc_textarea( $config['popup_id'] );
		if ( $config['show_until'] == 'until_submition' ) {
			?>

			localStorage.setItem('popup_trigger_<?php echo esc_attr( $popup_id ); ?>', true);

			<?php
		}
	}

	private static function getPopUpDisplayed( $config ) {
		$popup_id = esc_textarea( $config['popup_id'] );
		if ( $config['show_until'] == 'one_time' ) {
			?>

			localStorage.setItem('popup_trigger_<?php echo esc_attr( $popup_id ); ?>', true);
			<?php
		}
	}

	private static function getPageTrigger( $config ) {
		if ( ! empty( $config['page_trigger'] ) ) {

			?>

			var pages = [
			<?php
			foreach ( $config['page_trigger'] as $page_id ) {
				echo "'" . str_replace( home_url(), '', get_permalink( $page_id ) ) . "',";
			}
			?>
			];

			<?php

			switch ( $config['page_trigger_rule'] ) {
				case 'contains':
					?>
					if(!pages.includes(window.location.pathname)){
						return;
					}
					<?php
					break;
				case 'not_contains':
					?>
					if(pages.includes(window.location.pathname)){
						return;
					}
					<?php
					break;
				default:
					break;
			}
		}
	}

	private static function getShowUntil( $config ) {

		?>

		<?php
	}


	private static function getTriggerOnLeaveJs() {
		?>

		var input_timeout;

		$("html").mouseenter(function(){
			clearTimeout(input_timeout);
		} );

		$("html").mouseleave(function(){
			input_timeout = setTimeout(function () {
				triggerPopup();
			},500);

		} );

		<?php
		return;
	}

	private static function getTriggerDelayJs( $seconds ) {
		?>

		setTimeout(function () {
			triggerPopup();
		},<?php echo 1000 * intval( $seconds ); ?>)

		<?php
		return;
	}

	private static function getFormShortCodeById( $config, $id = 'new' ) {
		if ( empty( $id ) || $id == 'new' ) {
			?>
			<?php
			return;
		}

        ?>
        <div class="egoi_popup_wrapper_<?php echo esc_attr($id) ?>">
        <?php
		echo wp_kses(do_shortcode( '[egoi-simple-form id="' . esc_attr( $id ) . '"]' ), Egoi_For_Wp_Public::WP_KSES_OPTION_SIMPLE_FORM);
        ?>
        </div>
        <?php

		switch ( $config['form_orientation'] ) {
			case 'vertical':
				self::makeFormVertical($id);
				break;
			case 'horizontal':
				self::makeFormHorizontal($id);
				break;
			default:
				break;
		}
	}

	private static function makeFormVertical($id) {
		?>
            <style>
                .egoi_popup_wrapper_<?php echo esc_attr($id) ?> > .egoi_simple_form > p{
                    display: flex;
                    flex-direction: column;
                }

                .egoi_popup_wrapper_<?php echo esc_attr($id) ?> > .egoi_simple_form{
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    border-radius: inherit;
                }

            </style>
        <?php
	}


	private static function makeFormHorizontal($id) {
        ?>
            <style>
                .egoi_popup_wrapper_<?php echo esc_attr($id) ?> > .egoi_simple_form > p{
                    display: flex;
                    flex-direction: column;
                    margin-right: 10px;
                    flex-grow: 1;
                    border-radius: inherit;
                }

                .egoi_popup_wrapper_<?php echo esc_attr($id) ?> > .egoi_simple_form{
                    display: flex;
                    flex-direction: row;
                    justify-content: center;
                    align-items: flex-end;
                    border-radius: inherit;
                }

            </style>
        <?php
	}

	/*
	 * desktop, mobile
	 * */
	private static function getDevice() {
		$useragent = sanitize_text_field($_SERVER['HTTP_USER_AGENT']);
		if ( preg_match( '/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent ) || preg_match( '/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr( $useragent, 0, 4 ) ) ) {
			return 'mobile';
		}
		return 'desktop';

	}

	private static function getStyles( $config, $production = false ) {
		$popup_id = esc_textarea( $config['popup_id'] );
		?>
		<style>

			/* The Modal (background) */
			.egoi_modal_<?php echo esc_attr( $popup_id ); ?> {
				position: fixed; /* Stay in place */
				z-index: 1001; /* Sit on top */
				left: 0;
				top: 0;
				width: 100%; /* Full width */
				height: 100%; /* Full height */
				overflow: auto; /* Enable scroll if needed */
				background-color: rgb(0,0,0); /* Fallback color */
				background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
				<?php if ( $production ) { ?>
				display: none;
				<?php } else { ?>
				display: flex; /* Hidden by default */
				<?php } ?>
			}

			/* Modal Content */
			.egoi_modal_content_<?php echo esc_attr( $popup_id ); ?> {
				background-color: <?php echo esc_attr( $config['background_color'] ); ?>;
				margin: auto;
				width: 100%;
				max-width: <?php echo esc_attr( $config['max_width'] ); ?>;
				display: flex;
				flex-direction: column;
				<?php if ( $production ) { ?>

				<?php } else { ?>
					transform: scale(0.5);
				<?php } ?>
		<?php
		if ( ! empty( $config['border_radius'] ) ) {
			echo "border: 0px solid {$config['background_color']};";
			echo "border-radius: {$config['border_radius']}px;";
		}
		if ( $config['type'] == 'rightside' && $production ) {
			?>
			position: fixed;
			bottom: 0px;
			right: 0px;
			margin: 20px;
		<?php } ?>
			}

			.egoi_modal_content_<?php echo esc_attr( $popup_id ); ?> > *,
			.egoi_modal_content_<?php echo esc_attr( $popup_id ); ?> > * > * > *,
			.egoi_modal_content_<?php echo esc_attr( $popup_id ); ?> > * > * > * > *,
			.egoi_modal_content_<?php echo esc_attr( $popup_id ); ?> > * > * > * > * > *,
			.egoi_modal_content_<?php echo esc_attr( $popup_id ); ?> > * > * > * > * > * > * {
				border-radius: inherit !important;
			}

			<?php if ( $config['popup_layout'] != 'simple' ) { ?>
			.egoi_modal_content_<?php echo esc_attr( $popup_id ); ?> > div{
				grid-template-columns: 1fr 1fr;
				display: grid;
			}
			<?php } ?>

			.egoi_popup_side_image_<?php echo esc_attr( $popup_id ); ?>{
				background-position: center;
				background-repeat: no-repeat;
				background-size: cover;
				<?php if ( $config['popup_layout'] == 'left_image' ) { ?>
				border-top-left-radius: inherit;
				border-bottom-left-radius: inherit;
				<?php } elseif ( $config['popup_layout'] == 'right_image' ) { ?>
				border-top-right-radius: inherit;
				border-bottom-right-radius: inherit;
				<?php } ?>
			}

			<?php if ( ! empty( $config['font_color'] ) ) { ?>
			.egoi_modal_content_<?php echo esc_attr( $popup_id ); ?> > *,
			.egoi_modal_content_<?php echo esc_attr( $popup_id ); ?> > * > * > *,
			.egoi_modal_content_<?php echo esc_attr( $popup_id ); ?> > * > * > * > *
			{
				color: <?php echo ! empty( $config['font_color'] ) ? esc_attr( $config['font_color'] ) : ''; ?> !important;
			}
			<?php } ?>

			/* The Close Button */
			.popup_close_<?php echo esc_attr( $popup_id ); ?> {
				color: #aaaaaa;
				float: right;
				font-size: 28px;
				/*font-weight: bold;*/
				padding-right: 26px;
				position: absolute;
				align-self: flex-end;
			}

			.popup_close_<?php echo esc_attr( $popup_id ); ?>:hover,
			.popup_close_<?php echo esc_attr( $popup_id ); ?>:focus {
				color: #000;
				text-decoration: none;
				cursor: pointer;
			}

			<?php
			if ( ! empty( $config['custom_css'] ) ) {
				echo esc_textarea( $config['custom_css'] );
			}
			?>
		</style>
		<?php
	}




}
