<?php
// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
    die();
}
$bar_content = '<div class="egoi-bar" style="border:'.$bar_post['border_px'].' solid '.$bar_post['border_color'].';background:'.$bar_post['color_bar'].';'.$bar_post['postion'].'">
			
				<label class="egoi-label" style="color:'.$bar_post['bar_text_color'].'">'.$bar_post['text_bar'].'</label>
					<input type="email" name="email" placeholder="'.$bar_post['text_email_placeholder'].'" class="egoi-email" />
					<input class="button" class="egoi-button" style="text-align:-webkit-center;padding:10px;height:31px;" value="'.$bar_post['text_button'].'" />
			</div>';