<?php

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class EgoiElementorWidget extends Widget_Base {

	const PLUGINKEY = 'a777da1d2b30a3063488003ae0c9ffcd';

	public function get_name() {
		return 'egoi-simple-form';
	}


	public function get_title() {
		return __( 'Egoi Form', 'egoi-for-wp' );
	}


	public function get_icon() {
		return 'egoi-icon-simple-form';
	}


	public function get_categories() {
		return array( 'basic' );
	}


	public function get_script_depends() {
		return array( 'elementor-egoi-css' );
	}


	protected function register_controls() {
		$fields = $this->getAccountListFields();
		$tags   = $this->getAccountListTags();

		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Fields', 'egoi-for-wp' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'field_title',
			array(
				'label'       => __( 'Title', 'egoi-for-wp' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => __( 'Field Title', 'egoi-for-wp' ),
				'label_block' => true,
			)
		);
		$repeater->add_control(
			'field_placeholder',
			array(
				'label'       => __( 'Placeholder', 'egoi-for-wp' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => __( '...', 'egoi-for-wp' ),
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'field_name',
			array(
				'label'   => __( 'E-goi Field', 'egoi-for-wp' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'email',
				'options' => $fields,
			)
		);

		$this->add_control(
			'fields',
			array(
				'label'       => __( 'Form Fields', 'egoi-for-wp' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'title_field'       => __( 'Email', 'egoi-for-wp' ),
						'field_placeholder' => __( 'Put your e-mail here.', 'egoi-for-wp' ),
						'field_name'        => 'email',
					),
				),
				'title_field' => '{{{ field_title }}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'button_section',
			array(
				'label' => __( 'Button', 'egoi-for-wp' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'button_title',
			array(
				'label'       => __( 'Name', 'egoi-for-wp' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => __( 'Submit', 'egoi-for-wp' ),
				'placeholder' => __( 'Type your title here', 'egoi-for-wp' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'configuration_section',
			array(
				'label' => __( 'Configuration', 'egoi-for-wp' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'tag_name',
			array(
				'label'   => __( 'E-goi Tag', 'egoi-for-wp' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => '0',
				'options' => $tags,
			)
		);

		$this->add_control(
			'double_optin',
			array(
				'label'        => __( 'Double-Optin', 'egoi-for-wp' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'egoi-for-wp' ),
				'label_off'    => __( 'No', 'egoi-for-wp' ),
				'return_value' => '1',
				'default'      => '0',
			)
		);

		$this->add_control(
			'hr3',
			array(
				'type' => \Elementor\Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'term_option',
			array(
				'label'        => __( 'Show Terms', 'egoi-for-wp' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'egoi-for-wp' ),
				'label_off'    => __( 'Hide', 'egoi-for-wp' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->add_control(
			'term_url',
			array(
				'label'   => __( 'Page Localization', 'egoi-for-wp' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => $this->getAvailablePages(),
			)
		);

		$this->add_control(
			'redirect_option',
			array(
				'label'        => __( 'Redirect on Success', 'egoi-for-wp' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'egoi-for-wp' ),
				'label_off'    => __( 'No', 'egoi-for-wp' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->add_control(
			'redirect_url',
			array(
				'label'   => __( 'Page Localization', 'egoi-for-wp' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => $this->getAvailablePages(),
			)
		);

		$this->add_control(
			'external_redirect_text',
			array(
				'label'       => __( 'External Redirect', 'egoi-for-wp' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'Type your external url here', 'egoi-for-wp' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_section',
			array(
				'label' => __( 'Style', 'egoi-for-wp' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'button_color',
			array(
				'label'     => __( 'Button Color', 'egoi-for-wp' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => class_exists('Elementor\\Scheme_Color')?\Elementor\Scheme_Color::get_type():\Elementor\Core\Schemes\Color::get_type(),
					'value' => class_exists('Elementor\\Scheme_Color')?\Elementor\Scheme_Color::COLOR_1:\Elementor\Core\Schemes\Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .button_title' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'button_text_color',
			array(
				'label'     => __( 'Button Text Color', 'egoi-for-wp' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => class_exists('Elementor\\Scheme_Color')?\Elementor\Scheme_Color::get_type():\Elementor\Core\Schemes\Color::get_type(),
					'value' => class_exists('Elementor\\Scheme_Color')?\Elementor\Scheme_Color::COLOR_1:\Elementor\Core\Schemes\Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .button_title' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'position_button',
			array(
				'label'   => __( 'Button Position', 'egoi-for-wp' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'egoi_elementor_button_start',
				'options' => array(
					'egoi_elementor_button_start'   => __( 'Beginning', 'egoi-for-wp' ),
					'egoi_elementor_button_center'  => __( 'Center', 'egoi-for-wp' ),
					'egoi_elementor_button_end'     => __( 'End', 'egoi-for-wp' ),
					'egoi_elementor_button_full_width' => __( 'Full Width', 'egoi-for-wp' ),
				),
			)
		);

		$this->add_control(
			'hr',
			array(
				'type' => \Elementor\Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'direction_form',
			array(
				'label'   => __( 'Form Direction', 'egoi-for-wp' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'egoi_elementor_form_column',
				'options' => array(
					'egoi_elementor_form_column' => __( 'Column', 'egoi-for-wp' ),
					'egoi_elementor_form_row'    => __( 'Row', 'egoi-for-wp' ),

				),
			)
		);

		$this->add_control(
			'direction_field',
			array(
				'label'   => __( 'Field Direction', 'egoi-for-wp' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'egoi_elementor_form_field_column',
				'options' => array(
					'egoi_elementor_form_field_column' => __( 'Column', 'egoi-for-wp' ),
					'egoi_elementor_form_field_row'    => __( 'Row', 'egoi-for-wp' ),

				),
			)
		);

		$this->add_control(
			'hr2',
			array(
				'type' => \Elementor\Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'custom_css',
			array(
				'label'    => __( 'Custom CSS', 'egoi-for-wp' ),
				'type'     => \Elementor\Controls_Manager::CODE,
				'language' => 'css',
				'rows'     => 40,
				'default'  => '.egoi_elementor_form_wrapper_custom{
            
}
.egoi_elementor_entry_wrapper_custom{

}
.egoi_simple_form_message_wrapper_custom{

}
.egoi_simple_form_tof_wrapper_custom{

}',
			)
		);

		$this->end_controls_section();
	}

	private function displayError( $error ) {
        ?>
		<h2 style="color: red;text-align: center;margin-top: 0.4em;"><?php echo esc_textarea($error) ?></h2>
        <?php
    }

	private function getAvailablePages() {
		$args   = array(
			'sort_order'   => 'asc',
			'sort_column'  => 'post_title',
			'hierarchical' => 1,
			'exclude'      => '',
			'include'      => '',
			'meta_key'     => '',
			'meta_value'   => '',
			'authors'      => '',
			'child_of'     => 0,
			'parent'       => -1,
			'exclude_tree' => '',
			'number'       => '',
			'offset'       => 0,
			'post_type'    => 'page',
			'post_status'  => 'publish',
		);
		$pages  = get_pages( $args );
		$output = array();

		foreach ( $pages as $page ) {
			$output[ $page->guid ] = $page->post_title;
		}

		return $output;
	}

	protected function render() {
		$options = get_option( Egoi_For_Wp_Admin::OPTION_NAME );

		if ( empty( $options['list'] ) ) {
			$this->displayError( __( 'Please set up a list in E-goi Plugin panel before creating a form.', 'egoi-for-wp' ) );
			return false;
		}

		$settings  = $this->get_settings_for_display();
		$widget_id = $this->get_id();

		$classes_fields  = $settings['direction_field'];
		$classes_form    = $settings['direction_form'];
		$position_button = $settings['position_button'];
        ?>
		<form id="elementor-egoi-form" method="post" action="/">
		<div class="egoi_elementor_form_wrapper_custom <?php echo esc_attr($classes_form) ?>" >
		<input type="hidden" id="egoi_tag" name="egoi_tag" value="<?php echo esc_attr($settings['tag_name']) ?>">
		<input type="hidden" id="elementorEgoiForm" name="elementorEgoiForm" value="<?php echo esc_attr($widget_id) ?>">
		<input type="hidden" id="egoi_list" name="egoi_list" value="<?php echo esc_attr($options['list']) ?>">
		<input type="hidden" id="egoi_double_optin" name="egoi_double_optin" value="<?php echo esc_attr($settings['double_optin']) ?>">

        <?php
		if ( 'yes' == $settings['redirect_option'] ) {
			if($settings['external_redirect_text']){
				?>
					<input type="hidden" id="egoi_redirect" name="egoi_redirect" value="<?php echo esc_url($settings['external_redirect_text']) ?>">
				<?php
			}else{
				?>
					<input type="hidden" id="egoi_redirect" name="egoi_redirect" value="<?php echo esc_url($settings['redirect_url']) ?>">
				<?php
			}
        }

		if ( $settings['fields'] ) {

			foreach ( $settings['fields'] as $item ) {
        ?>
				<p class="egoi_elementor_entry_wrapper_custom egoi_elementor_entry_wrapper egoi_elementor_form_field <?php echo esc_attr($classes_fields) ?>">
				<label for="egoiElementor_<?php echo esc_attr($item['field_name']) ?>"><?php echo esc_textarea($item['field_title']) ?></label>
				<input type="text" class="egoi_elementor_form_field" name="<?php echo esc_attr($item['field_name']) ?>" id="egoiElementor_<?php echo esc_attr($item['field_name']) ?>" placeholder="<?php echo esc_attr($item['field_placeholder']) ?>" />
				</p>
            <?php
			}
		}
		$message_box_id         = 'message_' . $widget_id;
		$egoi_elm_submit_button = 'egoi_elm_submit_button_' . $widget_id;
		?>
        <p class="egoi_elementor_entry_wrapper <?php echo esc_attr($position_button) ?>"><button style="background-color: <?php echo esc_attr($settings['button_color']); ?>;color: <?php echo esc_attr($settings['button_text_color']); ?>" type="submit" id="<?php echo esc_attr($egoi_elm_submit_button) ?>" ><?php echo esc_textarea($settings['button_title']) ?></button></p>
		</div>

        <?php
		if ( 'yes' == $settings['term_option'] ) {
            ?>
			<p class="egoi_simple_form_tof_wrapper <?php echo esc_attr($position_button) ?> egoi_simple_form_tof_wrapper_custom"><input type="checkbox" id="egoi_tof" name="egoi_tof" value="true"><span><?php _e( 'I agree to', 'egoi-for-wp' )  ?> <a target="_blank" href="<?php echo esc_url($settings['term_url']) ?>" ><?php _e( 'terms & conditions', 'egoi-for-wp' ) ?></a> <span style="color: red;font-weight: bold">*</span></span></p>
		<?php
        } else {
        ?>
            <input type="hidden" id="egoi_tof" name="egoi_tof" value="true">
        <?php
        }
        ?>

		<p id="<?php echo esc_attr($message_box_id) ?>" class="egoi_simple_form_message_wrapper_custom egoi_simple_form_success_wrapper" style="margin:10px 0px; padding:12px; display:none;"></p>
		</form>

		<script>
                jQuery(document).ready(function($) {
                    var ajaxurl = "<?php echo esc_url(admin_url( 'admin-ajax.php' )) ?>";
                    var submitButton = $("#<?php echo esc_attr($egoi_elm_submit_button) ?>");
                    function getFormData($form){
                        var unindexed_array = $form.serializeArray();
                        var indexed_array = {};             
                        $.map(unindexed_array, function(n, i){
                            indexed_array[n['name']] = n['value'];
                        });
                        return indexed_array;
                    }
                    
                    function displayMessage(message, type = "error"){
                            if(type == "error"){
                                jQuery( "#<?php echo esc_attr($message_box_id) ?>" ).css({
                                    "color": "#9F6000",
                                    "background-color": "#FFD2D2"
                                });
                            }else{
                                 jQuery( "#<?php echo esc_attr($message_box_id) ?>" ).css({
                                    "color": "#4F8A10",
                                    "background-color": "#DFF2BF"
                                });

                            }
                            jQuery( "#<?php echo esc_attr($message_box_id) ?>" ).empty().append( message ).slideDown( "slow" );
                            setTimeout(function(){
                                jQuery( "#<?php echo esc_attr($message_box_id) ?>" ).slideUp( "slow" );
                             }, 5000);
                    }
                    
                    var form = $("#elementor-egoi-form");
                    form.submit(function(e){
                        e.preventDefault();
                        jQuery( "#<?php echo esc_attr($message_box_id) ?>" ).slideUp( "slow" );
                        var inputData = getFormData($(this));
                        inputData["action"] = "egoi_simple_form_submit";
                        if(inputData.egoi_tof === undefined){
                            displayMessage("<?php _e( 'You must agree with terms & conditions.', 'egoi-for-wp' ) ?>")
                            return false;
                        }
                        submitButton.prop("disabled", true);
                        var posting = jQuery.post(ajaxurl, inputData);
    
                        posting.done(function( data ) {
                            console.log(data)
                            if (data.substring(0, 5) != "ERROR") {
                                displayMessage(data, "success")
								
                                if(inputData.egoi_redirect !== undefined){
                                    setTimeout(function(){
                                        window.location.href = inputData.egoi_redirect;
                                     }, 3000);
                                }
                            } else {
                                displayMessage(data)
                            }
                            submitButton.prop("disabled", false);
                            
                        });
                        
                        
                    });
                });

        </script>

		<style><?php echo esc_textarea($settings['custom_css']) ?></style>

        <?php
	}

	protected function getAccountListFields() {

		$fields = Egoi_For_Wp::getFullListFields( self::PLUGINKEY );
		return array_filter(
			$fields,
			function ( $key ) {
				return ! is_numeric( $key );
			},
			ARRAY_FILTER_USE_KEY
		);
	}

	protected function getAccountListTags() {

		$tags      = Egoi_For_Wp::getAccountTags();
		$tags['0'] = __( 'No Tag', 'egoi-for-wp' );
		return $tags;
	}


	protected function content_template() {
		$options = get_option( Egoi_For_Wp_Admin::OPTION_NAME );
		// $settings = $this->get_settings_for_display();

		if ( empty( $options['list'] ) ) {
			$this->displayError( __( 'Please set up a list in E-goi Plugin panel before creating a form.', 'egoi-for-wp' ) );
			return false;
		}

		$widget_id = $this->get_id();

		$message_box_id         = 'message_' . $widget_id;
		$egoi_elm_submit_button = 'egoi_elm_submit_button_' . $widget_id;

		?>

		<# if ( settings.fields.length ) {
			var field_name = {};
			settings.fields.forEach(function(item){
				field_name[item['field_name']] = true;
			});
			if(settings.fields.length != Object.keys(field_name).length){ #>
				<?php $this->displayError( __( 'You have configured repeated fields!', 'egoi-for-wp' ) ); ?>
		 <# }
		 } #>

		<# if ( settings.fields.length ) { #>
		<form id="elementor-egoi-form" method="post" action="/">
			<div class="egoi_elementor_form_wrapper_custom {{ settings.direction_form }}">
			<# _.each( settings.fields, function( item ) { #>
			<p class="egoi_elementor_entry_wrapper_custom egoi_elementor_entry_wrapper egoi_elementor_form_field {{ settings.direction_field }}">
				<label for="egoi_{{ item.field_name }}">{{ item.field_title }}</label>
				<input class="egoi_elementor_form_field" type="text" name="egoi_{{ item.field_name }}" id="egoi_{{ item.field_name }}" placeholder="{{ item.field_placeholder }}" />
			</p>
			<# }); #>
			<p class="egoi_elementor_entry_wrapper {{ settings.position_button }}" ><button style="background-color: {{ settings.button_color }};color: {{ settings.button_text_color }}" type="submit" id="<?php echo $egoi_elm_submit_button; ?>">{{ settings.button_title }}</button></p>
			</div>
			<# if ( 'yes' === settings.term_option ) { #>
				<p class="egoi_simple_form_tof_wrapper {{ settings.position_button }} egoi_simple_form_tof_wrapper_custom"><input type="checkbox" id="egoi_tof" name="egoi_tof" value="true"><span><?php echo __( 'I agree to', 'egoi-for-wp' ); ?> <a target="_blank" href="{{ settings.term_url }}" ><?php echo __( 'terms & conditions', 'egoi-for-wp' ); ?></a><span style="color: red;font-weight: bold">*</span></span></p>
			<# } #>

			<p id="<?php echo $message_box_id; ?>" class="egoi_simple_form_message_wrapper_custom egoi_simple_form_success_wrapper" style="margin:10px 0px; padding:12px; display:none;"></p>
		</form>

		<style>
			{{settings.custom_css}}
		</style>

		<# } #>
		<?php
	}
}
