
<div class="postbox--list">
	<div class="wrap"> 

		<h2 class="e-goi-account-list__title">
			<?php echo __('Information about your E-goi account lists', 'egoi-for-wp'); ?>
		</h2>
<<<<<<< HEAD
		<table border='0' class="widefat striped">
			<thead>
				<tr>
					<th><?php echo _e('List ID', 'egoi-for-wp');?></th>
					<th><?php echo _e('Public Title | Internal Title', 'egoi-for-wp');?></th>
					<th><?php echo _e('Internal Title', 'egoi-for-wp');?></th>
					<th><?php echo _e('Active Contacts | All Contacts', 'egoi-for-wp');?></th>
					<th><?php echo _e('Total Subscribers', 'egoi-for-wp');?></th>
					<th><?php echo _e('Language', 'egoi-for-wp');?></th>
					<th><?php echo _e('Settings', 'egoi-for-wp');?></th>
				</tr>
			</thead>
			<?php 
			foreach($lists as $key_list => $value_list) {
=======
				<table border='0' class="widefat striped">
				<thead>
					<tr>
						<th><?php echo _e('List ID', 'egoi-for-wp');?></th>
						<th><?php echo _e('Public Title | Internal Title', 'egoi-for-wp');?></th>
						<th><?php echo _e('Internal Title', 'egoi-for-wp');?></th>
						<th><?php echo _e('Active Contacts | All Contacts 
							<span class="e-goi-tooltip">
								 <span class="dashicons dashicons-editor-help"></span>
							  	 <span class="e-goi-tooltiptext">
							  	 	Estados de subscrição, veja <a target="_blank" href="https://helpdesk.e-goi.com/797063-Vejo-contatos-com-diferentes-estados-de-subscri%C3%A7%C3%A3o-O-que-%C3%A9-isso">Saiba mais aqui!</a>
							 	</span>
							</span>', 'egoi-for-wp');?></th>
						<th><?php echo _e('Total Subscribers', 'egoi-for-wp');?></th>
						<th><?php echo _e('Language', 'egoi-for-wp');?></th>
						<th><?php echo _e('Settings', 'egoi-for-wp');?></th>
					</tr>
				</thead>
				<?php 
				foreach($lists as $key_list => $value_list) {
>>>>>>> 273892304efad0435cad20f850445df8f75937b1

				if($value_list->listnum){ ?>
						<tr>
							<td>
								<?php echo $value_list->listnum; ?>
							</td>
							<td>
								<?php echo $value_list->title; ?>
							</td>
							<td>
								<?php echo $value_list->title_ref; ?>
							</td>
							<td>
								<?php echo $value_list->subs_activos; ?>
							</td>
							<td>
								<?php echo $value_list->subs_total; ?>
							</td>
							<td><?php
								if(strcmp($value_list->idioma,'pt') == 0) { 
									echo "Português (Portugal)";
								} else if(strcmp($value_list->idioma,'br') == 0) {
									echo "Português (Brasil)";
								} else if(strcmp($value_list->idioma,'es') == 0) {
									echo "Español";
								} else {
									echo "English";
								} ?>
							</td>
							<td>
								<a href="https://bo.e-goi.com/?from=<?php echo urlencode('/?action=lista_definicoes_principal&list='.$value_list->listnum);?>" class='button' target="_blank" />
								<?php _e('Change in E-goi', 'egoi-for-wp');?>
							</a>
							</td>
						</tr><?php
				}
			} ?>
		</table>

		<div class="e-goi-account--toogle">
			<a type="button" class="button-primary button-primary--custom-add dropdown-toggle">
				<?php echo _e('Create List +', 'egoi-for-wp');?>
			</a>
				
			<div style="position:relative">

				<form name='egoi_wp_createlist_form' method='post' action='<?php echo $_SERVER['REQUEST_URI']; ?>'>
					
					<div id="e-goi-create-list" style="display: none;">
						<div class="e-goi-account--create-name">
							<span>
								<label for="egoi_wp_title"><?php echo _e('Name', 'egoi-for-wp');?></label>
							</span>
							<span>
								<input type='text' size='60' name='egoi_wp_title' autofocus required="required" />
							</span>
						</div>
						<div class="e-goi-account--create-lang">
							<label for="egoi_wp_lang"><?php echo _e('Language', 'egoi-for-wp');?></label>
							<select name='egoi_wp_lang'>
								<option value='en'><?php echo _e('English', 'egoi-for-wp');?></option>
								<option value='pt'><?php echo _e('Portuguese', 'egoi-for-wp');?></option>
								<option value='br'><?php echo _e('Portuguese (Brasil)', 'egoi-for-wp');?></option>
								<option value='es'><?php echo _e('Spanish', 'egoi-for-wp');?></option>
							</select>
							<span class="e-goi-help-text-lang">
								<span style="display:inline-block; line-height:16px; margin-left:15px;"><i><?php echo _e("The emails you send for contacts of this list will then have E-goi's <br>header and footerautomatically translated into their language", "egoi-for-wp");?>
								</i></span>
							</span>
						</div>

						<input type='submit' class='button-primary' name='egoi_wp_createlist' id='egoi_wp_createlist' value='<?php echo _e('Save', 'egoi-for-wp');?>' />
						<a style="margin-left:10px;" class='link cancel-toggle'><?php echo _e('Cancelar', 'egoi-for-wp');?></a>
					</div>
				</form>
			</div>
		</div>
	</div><!-- .wrap -->
</div> <!-- .postbox -->