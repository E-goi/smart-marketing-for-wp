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

class EgoiPopUp
{
    const OPTION_NAME = 'egoi_popups';

    protected $id;

    public function __construct($id='new')
    {
        $this->id = $id;
    }

    public function getPopupSavedData(){
        if($this->id == 'new'){
            return $this->getDefaultPopupData();
        }
        return json_decode(get_option('egoi_popup_'.$this->id), true);
    }

    public static function getSavedPopUps(){
        $popups = json_decode(get_option(self::OPTION_NAME), true);
        if(empty($popups)){
            return [];
        }
        return $popups;
    }

    public static function savePostPopup($post){

        if(!self::isValidPreviewPost($post)){
            return false;
        }
        $post = self::createConfigFromPost($post['data']);

        if($post['popup_id'] == 'new'){
            $post['popup_id'] = self::generateNextPopupId();
        }

        update_option("egoi_popup_{$post['popup_id']}", json_encode($post));

        return true;
    }

    private static function generateNextPopupId(){
        $popups = self::getSavedPopUps();
        if(empty($popups)){
            update_option(self::OPTION_NAME, json_encode([1]));
            return 1;
        }

        $id = max($popups) + 1;
        $popups[] = $id;
        update_option(self::OPTION_NAME, json_encode($popups));
        return $id;
    }

    public static function deletePopup($popup_id){
        $popups = self::getSavedPopUps();
        delete_option("egoi_popup_$popup_id");
        update_option(self::OPTION_NAME, json_encode(array_diff($popups, [$popup_id])));
    }

    public static function checkFormSafeDelete($form_id){
        $popups = self::getSavedPopUps();

        foreach ($popups as $popup_id){
            $data = json_decode(get_option("egoi_popup_$popup_id"), true);
            if($data['form_id'] == $form_id){
                return false;
            }
        }
        return true;
    }

    private function getDefaultPopupData(){
        return [
            'popup_id'          => $this->id,
            'type'              => 'center',
            'form_id'           => 0,
            'border_radius'     => 0,
            'content'           => '<h1 style="text-align: center;">Newsletter</h1>',
            'trigger'           => 'delay',
            'trigger_option'    => '10',
            'page_trigger_rule' => 'contains',
            'page_trigger'      => [],
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
        ];
    }

    public static function isValidPreviewPost($post){
        if(!empty($post['data']))
            return true;
        return false;
    }

    public static function createConfigFromPost($post, $first_time = false){

        $output = [];
        foreach ($post as $property){
            if($first_time && empty($property['value'])){continue;}
            if($property['name'] == 'page_trigger'){//select2 problem on already saved forms
                if(empty($output[$property['name']])){
                    $output[$property['name']] = [];
                }
                if(is_array($property['value'])){
                    $output[$property['name']] = array_merge($output[$property['name']], $property['value']);
                }else if(!is_null($property['value'])){
                    $output[$property['name']][] = $property['value'];
                }else if (empty($property['value']) && !is_null($property['value'])){
                    $output[$property['name']] = [];
                }
                continue;
            }
            $output[$property['name']] = $property['value'];
        }

        return $output;
    }

    public function printPopup(){
        $config = $this->getPopupSavedData();

        if(($config['show_logged'] == 'no' && is_user_logged_in()) || ($config['show_logged'] == 'no' && !empty($_SESSION['egoi_tracking_uid']))){
            return false;
        }

        self::getModal($config);
        self::getStyles($config, true);
        self::getScripts($config);
    }

    public static function getPreviewFromPost($post){

        $config = self::createConfigFromPost($post['data'],!empty($post['first_time'])?$post['first_time']:false);

        self::getModal($config);
        self::getStyles($config);


        do_action('wp_head');//add default public styles
    }


    private static function getModal($config){
        ?>
        <div id="egoi_popup_<?php echo $config['popup_id']; ?>" class="egoi_modal_<?php echo $config['popup_id']; ?>">
            <!-- Modal content -->
            <div class="egoi_modal_content_<?php echo $config['popup_id']; ?>">
                <span class="popup_close_<?php echo $config['popup_id']; ?> dashicons dashicons-no"></span>
                <div style="border-radius: inherit;">
                    <?php if($config['popup_layout'] == 'left_image'){ ?>
                        <div class="egoi_popup_side_image_<?php echo $config['popup_id']; ?>" style="background-image: url(<?php echo wp_get_attachment_url( $config['side_image'] ); ?>);">

                        </div>
                    <?php } ?>
                    <div style="padding: 20px;border-radius: inherit;">
                        <?php echo stripslashes($config['content']);
                        self::getFormShortCodeById($config['form_id'], $config);
                        ?>
                    </div>
                    <?php if($config['popup_layout'] == 'right_image'){ ?>
                        <div class="egoi_popup_side_image_<?php echo $config['popup_id']; ?>" style="background-image: url(<?php echo wp_get_attachment_url( $config['side_image'] ); ?>);">

                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php
    }

    private static function getScripts($config){
        ?>

        <script>
            jQuery(document).ready(function($) {

                var targetPopup = $("#egoi_popup_<?php echo $config['popup_id']; ?>");
                var targetForm = $("#egoi_popup_<?php echo $config['popup_id']; ?> ").find("#egoi_simple_form_<?php echo $config['form_id']; ?>");

                var closeButton = $(".popup_close_<?php echo $config['popup_id']; ?>");

                closeButton.on('click', function(){
                    closePopup();
                });

                var elem = document.getElementsByTagName("html");
                elem[0].addEventListener("egoi_simple_form_<?php echo $config['form_id']; ?>", function (e) {
                    setTimeout(function () {
                        <?php self::getFormSubmit($config); ?>
                        closePopup();
                    }, 2000);
                }, false);

                function closePopup() {
                    targetPopup.hide();
                }

                function triggerPopup(){
                    if(localStorage.getItem('popup_trigger_<?php echo $config['popup_id']; ?>') !== null){
                        return;
                    }
                    <?php
                        self::getPageTrigger($config);
                        self::getShowUntil($config);
                    ?>

                    targetPopup.css('display', 'flex');
                    <?php self::getPopUpDisplayed($config); ?>
                }

                function startPopup(){
                    <?php
                        switch ($config['trigger']){
                            case 'delay':
                                self::getTriggerDelayJs($config['trigger_option']);
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

    private static function getFormSubmit($config){
        if($config['show_until'] == 'until_submition'){?>

            localStorage.setItem('popup_trigger_<?php echo $config['popup_id']; ?>', true);

            <?php
        }
    }

    private static function getPopUpDisplayed($config){
        if($config['show_until'] == 'one_time'){?>

            localStorage.setItem('popup_trigger_<?php echo $config['popup_id']; ?>', true);
            <?php
        }
    }

    private static function getPageTrigger($config){
        if(!empty($config['page_trigger'])){

            ?>

            var pages = [
            <?php
                foreach ($config['page_trigger'] as $page_id){
                    echo "'".str_replace( home_url(), "", get_permalink($page_id) )."',";
                }
            ?>
            ];

            <?php

            switch ($config['page_trigger_rule']){
                case'contains':
                    ?>
                    if(!pages.includes(window.location.pathname)){
                        return;
                    }
                    <?php
                    break;
                case'not_contains':
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

    private static function getShowUntil($config){

        ?>

        <?php
    }


    private static function getTriggerOnLeaveJs(){
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

    private static function getTriggerDelayJs($seconds){
        ?>

        setTimeout(function () {
            triggerPopup();
        },<?php echo 1000*intval($seconds) ?>)

        <?php
        return;
    }

    private static function getFormShortCodeById($id = 'new', $config){
        if(empty($id) || $id == 'new'){
            ?>
            <p>
                <?php _e('Select a Form first to have a accurate preview.','egoi-for-wp'); ?>
            </p>
            <?php
            return;
        }

        $form_code = do_shortcode('[egoi-simple-form id="'.$id.'"]');

        switch ($config['form_orientation']){
            case 'vertical':
                echo self::makeFormVertical($form_code);
                break;
            case 'horizontal':
                echo self::makeFormHorizontal($form_code);
                break;
            default:
                echo $form_code;
                breaK;
        }

        //wp_mail("tmota@e-goi.com", __FUNCTION__, var_export($shotcode,true));
    }

    private static function makeFormVertical($form_code){
        $form_code = str_replace("<form", "<form style=\"display: flex;flex-direction: column;justify-content: center;border-radius: inherit;\"", $form_code);
        $form_code = str_replace("<p>", "<p style=\"display: flex;flex-direction: column;\">", $form_code);
        return $form_code;
    }


    private static function makeFormHorizontal($form_code){
        $form_code = str_replace("<form", "<form style=\"display: flex;flex-direction: row;justify-content: center; align-items: flex-end;border-radius: inherit;\"", $form_code);
        $form_code = preg_replace('/<p>/', '<p style="display: flex;flex-direction: column; margin-right: 10px;flex-grow: 1;border-radius: inherit;">', $form_code, substr_count($form_code,'<p>') -1);
        $form_code = str_replace("<p>", "<p style=\"display: flex;flex-direction: column; margin-right: 10px;border-radius: inherit;\">", $form_code);
        return $form_code;
    }

    private static function getStyles($config, $production = false){
        ?>
        <style>

            /* The Modal (background) */
            .egoi_modal_<?php echo $config['popup_id']; ?> {
                position: fixed; /* Stay in place */
                z-index: 1001; /* Sit on top */
                left: 0;
                top: 0;
                width: 100%; /* Full width */
                height: 100%; /* Full height */
                overflow: auto; /* Enable scroll if needed */
                background-color: rgb(0,0,0); /* Fallback color */
                background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
                <?php if($production){ ?>
                display: none;
                <?php }else{ ?>
                display: flex; /* Hidden by default */
                <?php } ?>
            }

            /* Modal Content */
            .egoi_modal_content_<?php echo $config['popup_id']; ?> {
                background-color: <?php echo $config['background_color'] ?>;
                margin: auto;
                width: 100%;
                max-width: <?php echo $config['max_width'] ?>;
                display: flex;
                flex-direction: column;
                <?php if($production){ ?>

                <?php }else{ ?>
                    transform: scale(0.5);
                <?php } ?>
        <?php
        if(!empty($config['border_radius'])){
            echo "border: 0px solid {$config['background_color']};";
            echo "border-radius: {$config['border_radius']}px;";
        }
        if($config['type'] == 'rightside' && $production){ ?>
            position: fixed;
            bottom: 0px;
            right: 0px;
            margin: 20px;
        <?php } ?>
            }

            .egoi_modal_content_<?php echo $config['popup_id']; ?> > *,
            .egoi_modal_content_<?php echo $config['popup_id']; ?> > * > * > *,
            .egoi_modal_content_<?php echo $config['popup_id']; ?> > * > * > * > *,
            .egoi_modal_content_<?php echo $config['popup_id']; ?> > * > * > * > * > *,
            .egoi_modal_content_<?php echo $config['popup_id']; ?> > * > * > * > * > * > * {
                border-radius: inherit !important;
            }

            <?php if($config['popup_layout'] != 'simple'){ ?>
            .egoi_modal_content_<?php echo $config['popup_id']; ?> > div{
                grid-template-columns: 1fr 1fr;
                display: grid;
            }
            <?php } ?>

            .egoi_popup_side_image_<?php echo $config['popup_id']; ?>{
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;
                <?php if($config['popup_layout'] == 'left_image'){?>
                border-top-left-radius: inherit;
                border-bottom-left-radius: inherit;
                <?php }else if($config['popup_layout'] == 'right_image'){ ?>
                border-top-right-radius: inherit;
                border-bottom-right-radius: inherit;
                <?php } ?>
            }

            <?php if(!empty($config['font_color'])){ ?>
            .egoi_modal_content_<?php echo $config['popup_id']; ?> > *,
            .egoi_modal_content_<?php echo $config['popup_id']; ?> > * > * > *,
            .egoi_modal_content_<?php echo $config['popup_id']; ?> > * > * > * > *
            {
                color: <?php echo $config['font_color']; ?> !important;
            }
            <?php } ?>

            /* The Close Button */
            .popup_close_<?php echo $config['popup_id']; ?> {
                color: #aaaaaa;
                float: right;
                font-size: 28px;
                /*font-weight: bold;*/
                padding-right: 26px;
                position: absolute;
                align-self: flex-end;
            }

            .popup_close_<?php echo $config['popup_id']; ?>:hover,
            .popup_close_<?php echo $config['popup_id']; ?>:focus {
                color: #000;
                text-decoration: none;
                cursor: pointer;
            }

            <?php
            if(!empty($config['custom_css'])){
                echo $config['custom_css'];
            } ?>
        </style>
        <?php
    }




}