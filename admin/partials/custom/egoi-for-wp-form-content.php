<?php /*


$listID = $opt['egoi_form_sync']['list'];
$forms = $Egoi4WpBuilderObject->getForms($listID, 1);

if(is_null($opt['egoi_form_sync']['show_title'])){
	$opt['egoi_form_sync']['show_title'] = 0;
}
?>
	
	<a href="#TB_inline?width=700&height=450&inlineId=egoi_form_inter&modal=true" id="form_egoint" class="thickbox button-secondary" style="display:none;"></a>

	<table class="" style="table-layout: fixed;">

		<?php
		
		if (($_GET['type'] == 'popup') || ($_GET['type'] == 'html')){

			$content = stripslashes($opt['egoi_form_sync']['form_content']);
			if($opt['egoi_form_sync']['egoi'] == $_GET['type']) {?>
				<textarea name="egoi_form_sync[form_content]" rows="20" cols="105">
				<?php echo $content;?></textarea>

				<?php

			}else{ ?>
				<textarea style="padding:10px; font-size:14px; font-family: Lucida Console, Monaco, monospace;" 
				placeholder="<?php _e( 'Cole aqui o código HTML Avançado do Formulário E-goi', 'egoi-for-wp' ); ?>" 
				name="egoi_form_sync[form_content]" rows="20" cols="90"></textarea><?php 
			}

		}else{ ?>

			<tr valign="top">
				<th scope="row"><?php _e( 'List to Subscribe', 'egoi-for-wp' ); ?></th>
				<?php
				if(empty($lists)) { ?>
					<td colspan="2"><?php printf(__('Lists not found, <a href="%s">are you connected to egoi</a>?', 'egoi-for-wp'), admin_url('admin.php?page=egoi-4-wp-account'));?></td><?php
				}else{ ?>
					<td>
						<div class="e-goi-tooltip"><span class="dashicons dashicons-editor-help"></span>
						  <span class="e-goi-tooltiptext">Tooltip text</span>
						</div>

						<select name="egoi_form_sync[list]" id="egoi4wp-lists"><?php
							$index = 1;
							foreach($lists as $list) {
								if($list->title!=''){ ?>
									<option value="<?php echo esc_attr($list->listnum);?>" <?php selected($list->listnum, $opt['egoi_form_sync']['list']);?>><?php echo esc_html($list->title);?></option><?php
								}
								$index++;
							} ?>
						</select>
						<!-- <p class="help">
							<?php // _e( 'Select the list to which people who submit this form should be subscribed.' ,'egoi-for-wp' ); ?>
						</p> -->
					</td><?php 
				} ?>

			</tr>

			<tr valign="top">
				<th scope="row"><?php _e( 'E-goi Form to Subscribe', 'egoi-for-wp' ); ?></th>
				<?php
				if($listID) { ?>
					<td>
						<select name="egoi_form_sync[form_content]" id="formid_egoi">
							<option value=""><?php _e('Select your form', 'egoi-for-wp');?></option><?php
							foreach ($forms as $value) {
								if($value->title){ ?>
									<option value="<?php echo $value->id.' - '.$value->url;?>" <?php selected($value->id.' - '.$value->url, $opt['egoi_form_sync']['form_content']);?>>
										<?php echo $value->title;?>
									</option><?php
								}
							} ?>
						</select>
					</td><?php
				}else{ ?>
					<td colspan="2"><?php printf(__('First you need to select and save your list then you can get your form from E-goi', 'egoi-for-wp'));?></td><?php
				} ?>
			</tr><?php


			if($opt['egoi_form_sync']['form_content']){
				$url = explode(' - ', $opt['egoi_form_sync']['form_content']); ?>
				<div id="egoi_form_inter" style="display:none;">
					<a id="TB_closeWindowButton">X</a>
		    		<iframe src="http://<?php echo $url[1];?>" width="700" height="600" style="border: 0 none;" onload="window.parent.parent.scrollTo(0,0);"></iframe>
		    	</div><?php
		    }else{ ?>
				<div id="egoi_form_inter" style="display:none;"></div><?php 
			} 
		} ?>

	</table>
	*/ ?>
