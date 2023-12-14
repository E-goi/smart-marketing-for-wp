<?php if ( isset( $_GET['add'] ) || isset( $_GET['edit'] ) ) { ?>
	<?php
	$args['hide_empty'] = false;

	$args['taxonomy'] = 'category';
	$post_categories  = get_terms( $args );

	$args['taxonomy'] = 'post_tag';
	$post_tags        = get_terms( $args );

	$args['taxonomy']   = 'product_cat';
	$product_categories = get_terms( $args );

	$args['taxonomy'] = 'product_tag';
	$product_tags     = get_terms( $args );

	if ( isset( $_GET['edit'] ) ) {
		$feed = get_option( sanitize_text_field( $_GET['edit'] ) );
	}

	if ( ! isset( $_GET['edit'] ) ) {
		$code = wp_generate_password( 16, false );
	} else {
		$code = substr( sanitize_text_field( $_GET['edit'] ), -16 );
	}
	?>

	<div class="smsnf-grid">
		<div>
			<form id="egoi_simple_form" method="post" action="<?php echo esc_url($this->prepareUrl( '&sub=rss-feed&edit=egoi_rssfeed_' . $code )); ?>">
				<?php
				settings_fields( Egoi_For_Wp_Admin::OPTION_NAME );
				if(get_settings_errors()){
					echo get_notification( __( 'Saved Configurations', 'egoi-for-wp' ), __( 'Rss Feed configurations saved with success.', 'egoi-for-wp' ) );
				}
		
				?>
				<input name="code" type="hidden" value="<?php echo $code; ?>">

				<?php if ( isset( $_GET['edit'] ) ) { ?>
					<div class="smsnf-input-group">
						<label for="campaign_subject"><?php _e( 'URL', 'egoi-for-wp' ); ?></label>
						<div class="smsnf-wrapper" style="display: flex;">
							<input id="input_<?php echo $code; ?>" name="input_url" value="<?php echo get_home_url() . '/?feed=egoi_rssfeed_' . $code; ?>" readonly type="text" />
							<button type="button" class="copy_url button button--custom" style="padding: 0 5px; height: 40px !important; line-height: 0 !important;margin-top: 12px;" onclick="copyToClipboard('input_<?php echo $code; ?>')" data-rss-feed="input_<?php echo $code; ?>"><i class="far fa-copy"></i></button>
						</div>
					</div>
				<?php } ?>

				<div class="smsnf-input-group">
					<label for="name"><?php _e( 'Name', 'egoi-for-wp' ); ?></label>
					<input id="name" name="name" type="text" placeholder="<?php _e( 'Choose a name for your new RSS Feed', 'egoi-for-wp' ); ?>" value="<?php echo isset( $feed ) ? $feed['name'] : null; ?>" required autocomplete="off" />
				</div>

				<div class="smsnf-input-group">
					<label for="max_characters"><?php _e( 'Maximum of Description characters', 'egoi-for-wp' ); ?></label>
					<input id="max_characters" name="max_characters" type="text" placeholder="<?php _e( 'Set the maximum characters for RSS Feed Description', 'egoi-for-wp' ); ?>" value="<?php echo isset( $feed['max_characters']) ? $feed['max_characters'] : null; ?>" required autocomplete="off" />
				</div>

				<div class="smsnf-input-group">
					<label for="max_characters_content"><?php _e( 'Maximum of Content characters', 'egoi-for-wp' ); ?></label>
					<input id="max_characters_content" name="max_characters_content" type="text" placeholder="<?php _e( 'Set the maximum characters for RSS Feed Content', 'egoi-for-wp' ); ?>" value="<?php echo isset( $feed['max_characters_content'] ) ? $feed['max_characters_content'] : null; ?>" required autocomplete="off" />
				</div>

				<div class="smsnf-input-group">
					<label for="image_size"><?php _e( 'Image Size', 'egoi-for-wp' ); ?></label>
					<p class="subtitle"><?php _e( 'Select a default size for RSS feed images.', 'egoi-for-wp' ); ?></p>
					<div class="smsnf-wrapper">
						<select id="image_size" class="form-select" name="image_size">
							<option value="" <?php !empty($feed)?selected( $feed['image_size'], null ):''; ?> ><?php _e( 'Select size..', 'egoi-for-wp' ); ?></option>
							<option value="full" <?php !empty($feed)?selected( $feed['image_size'], 'full' ):''; ?> ><?php _e( 'Full', 'egoi-for-wp' ); ?></option>
							<option value="large" <?php !empty($feed)?selected( $feed['image_size'], 'large' ):''; ?> ><?php _e( 'Large', 'egoi-for-wp' ); ?></option>
							<option value="medium" <?php !empty($feed)?selected( $feed['image_size'], 'medium' ):''; ?> ><?php _e( 'Medium', 'egoi-for-wp' ); ?></option>
							<option value="medium_large" <?php !empty($feed)?selected( $feed['image_size'], 'medium_large' ):''; ?> ><?php _e( 'Medium Large', 'egoi-for-wp' ); ?></option>
							<option value="thumbnail" <?php !empty($feed)?selected( $feed['image_size'], 'thumbnail' ):''; ?> ><?php _e( 'Thumbnail', 'egoi-for-wp' ); ?></option>
						</select>
					</div>
				</div>

				<div class="smsnf-input-group">
					<label for="type"><?php _e( 'Type', 'egoi-for-wp' ); ?></label>
					<p class="subtitle"><?php _e( 'Choose between Posts or Products to fill out the RSS Feed', 'egoi-for-wp' ); ?></p>
					<div class="smsnf-wrapper" style="display: flex;align-items: flex-end;margin-top: 12px;">
						<label><input type="radio"  name="type" <?php !empty($feed)?checked( $feed['type'], 'posts' ):''; ?> value="posts"><?php _e( 'Posts', 'egoi-for-wp' ); ?></label> &nbsp;
						<label><input type="radio" name="type" <?php !empty($feed)?checked( $feed['type'], 'products' ):''; ?> value="products"><?php _e( 'Products', 'egoi-for-wp' ); ?></label>
					</div>
				</div>

				<div class="smsnf-input-group">
					<label for="product_categories_include"><?php _e( 'Categories', 'egoi-for-wp' ); ?></label>
					<p class="subtitle"><?php _e( 'Please select at least one Category or All will be displayed', 'egoi-for-wp' ); ?></p>
					<div class="smsnf-wrapper post_cats_tags" >
						<select class="js-example-basic-multiple" name="post_categories_include[]" multiple="multiple" style="width:100%;">
							<?php foreach ( $post_categories as $category ) { ?>
								<option id="posts_cats_include_<?php echo $category->term_id; ?>" value="<?php echo $category->term_id; ?>"
									<?php
									if ( !empty($feed) && isset($feed['categories']) && in_array( $category->term_id, $feed['categories'] ) ) {
										echo 'selected';
									} elseif ( !empty($feed) && isset($feed['categories_exclude']) && in_array( $category->term_id, $feed['categories_exclude'] ) ) {
										echo 'disabled';
									}
									?>
									 >
									<?php echo $category->name; ?>
								</option>
							<?php } ?>
						</select>
					</div>
					<div class="smsnf-wrapper product_cats_tags" >
						<select class="js-example-basic-multiple" name="product_categories_include[]" multiple="multiple" style="width:100%;">
							<?php foreach ( $product_categories as $category ) { ?>
								<option id="products_cats_include_<?php echo $category->term_id; ?>" value="<?php echo $category->term_id; ?>"
									<?php
									if ( !empty($feed) && isset($feed['categories']) && in_array( $category->term_id, $feed['categories'] ) ) {
										echo 'selected';
									} elseif ( !empty($feed) && isset($feed['categories_exclude']) && in_array( $category->term_id, $feed['categories_exclude'] ) ) {
										echo 'disabled';
									}
									?>
									 >
									<?php echo $category->name; ?>
								</option>
							<?php } ?>
						</select>
					</div>
				</div>

				<div class="smsnf-input-group">
					<label for="post_categories_exclude"><?php _e( 'Categories to exclude', 'egoi-for-wp' ); ?></label>
					<div class="smsnf-wrapper post_cats_tags" >
						<select class="js-example-basic-multiple" name="post_categories_exclude[]" multiple="multiple" style="width:100%;">
							<?php foreach ( $post_categories as $category ) { ?>
								<option id="posts_cats_exclude_<?php echo $category->term_id; ?>" value="<?php echo $category->term_id; ?>"
									<?php
									if ( !empty($feed) && isset($feed['categories_exclude']) &&  in_array( $category->term_id, $feed['categories_exclude'] ) ) {
										echo 'selected';
									} elseif ( !empty($feed) && isset($feed['categories']) && in_array( $category->term_id, $feed['categories'] ) ) {
										echo 'disabled';
									}
									?>
									 >
									<?php echo $category->name; ?>
								</option>
							<?php } ?>
						</select>
					</div>
					<div class="smsnf-wrapper product_cats_tags" >
						<select class="js-example-basic-multiple" name="product_categories_exclude[]" multiple="multiple" style="width:100%;">
							<?php foreach ( $product_categories as $category ) { ?>
								<option id="products_cats_exclude_<?php echo $category->term_id; ?>" value="<?php echo $category->term_id; ?>"
									<?php
									if ( !empty($feed) && isset($feed['categories_exclude']) && in_array( $category->term_id, $feed['categories_exclude'] ) ) {
										echo 'selected';
									} elseif ( !empty($feed) && isset($feed['categories']) &&  in_array( $category->term_id, $feed['categories'] ) ) {
										echo 'disabled';
									}
									?>
									 >
									<?php echo $category->name; ?>
								</option>
							<?php } ?>
						</select>
					</div>
				</div>

				<div class="smsnf-input-group">
					<label for="post_tags_include"><?php _e( 'Tags', 'egoi-for-wp' ); ?></label>
					<p class="subtitle"><?php _e( 'Please select at least one Tag or All will be displayed', 'egoi-for-wp' ); ?></p>
					<div class="smsnf-wrapper post_cats_tags" >
						<select class="js-example-basic-multiple" name="post_tags_include[]" multiple="multiple" style="width:100%;">
							<?php foreach ( $post_tags as $tag ) { ?>
								<option id="posts_tags_include_<?php echo $tag->term_id; ?>" value="<?php echo $tag->term_id; ?>"
									<?php
									if ( !empty($feed) && isset($feed['tags']) && in_array( $tag->term_id, $feed['tags'] ) ) {
										echo 'selected';
									} elseif (!empty($feed) && isset($feed['tags_exclude']) &&  in_array( $tag->term_id, $feed['tags_exclude'] ) ) {
										echo 'disabled';
									}
									?>
									 >
									<?php echo $tag->name; ?>
								</option>
							<?php } ?>
						</select>
					</div>
					<div class="smsnf-wrapper product_cats_tags" >
						<select class="js-example-basic-multiple" name="product_tags_include[]" multiple="multiple" style="width:100%;">
							<?php foreach ( $product_tags as $tag ) { ?>
								<option id="products_tags_include_<?php echo $tag->term_id; ?>" value="<?php echo $tag->term_id; ?>"
									<?php
									if ( !empty($feed) && isset($feed['tags']) &&  in_array( $tag->term_id, $feed['tags'] ) ) {
										echo 'selected';
									} elseif ( !empty($feed) && isset($feed['tags_exclude']) &&  in_array( $tag->term_id, $feed['tags_exclude'] ) ) {
										echo 'disabled';
									}
									?>
									 >
									<?php echo $tag->name; ?>
								</option>
							<?php } ?>
						</select>
					</div>
				</div>

				<div class="smsnf-input-group">
					<label for="post_tags_exclude"><?php _e( 'Tags to exclude', 'egoi-for-wp' ); ?></label>
					<div class="smsnf-wrapper post_cats_tags" >
						<select class="js-example-basic-multiple" name="post_tags_exclude[]" multiple="multiple" style="width:100%;">
							<?php foreach ( $post_tags as $tag ) { ?>
								<option id="posts_tags_exclude_<?php echo $tag->term_id; ?>" value="<?php echo $tag->term_id; ?>"
									<?php
									if ( in_array( !empty($feed) && isset($feed['tags_exclude']) &&  $tag->term_id, $feed['tags_exclude'] ) ) {
										echo 'selected';
									} elseif ( in_array( !empty($feed) && isset($feed['tags']) &&  $tag->term_id, $feed['tags'] ) ) {
										echo 'disabled';
									}
									?>
									 >
									<?php echo $tag->name; ?>
								</option>
							<?php } ?>
						</select>
					</div>
					<div class="smsnf-wrapper product_cats_tags" >
						<select class="js-example-basic-multiple" name="product_tags_exclude[]" multiple="multiple" style="width:100%;" >
							<?php foreach ( $product_tags as $tag ) { ?>
								<option id="products_tags_exclude_<?php echo $tag->term_id; ?>" value="<?php echo $tag->term_id; ?>"
									<?php
									if ( !empty($feed) && isset($feed['tags_exclude']) &&  in_array( $tag->term_id, $feed['tags_exclude'] ) ) {
										echo 'selected';
									} elseif ( !empty($feed) && isset($feed['tags']) &&  in_array( $tag->term_id, $feed['tags'] ) ) {
										echo 'disabled';
									}
									?>
									 >
									<?php echo $tag->name; ?>
								</option>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="egoi-undertable-button-wrapper" style="bottom: 0;position: absolute;right: 30px;">
					<div class="smsnf-input-group">
						<input type="submit" value="<?php _e( 'Save', 'egoi-for-wp' ); ?>" />
					</div>
				</div>
			</form>
		</div>
	</div>

<?php } elseif ( $_GET['view'] ) { ?>
	<?php

	$feed = get_option( sanitize_text_field( $_GET['view'] ) );
	$args = $this->get_egoi_rss_feed_args( $feed );

	$query = new WP_Query( $args );

	?>
		<div style="width: 600px; margin: auto;">
			<h3><?php echo esc_textarea( $feed['name'] ); ?></h3>
			<?php
			if ( ! $query->have_posts() ) {
				?>
				 <p> <?php _e( 'No Posts', 'egoi-for-wp' ); ?> </p>
				<?php
			} else {
				while ( $query->have_posts() ) {
					$query->the_post();

					$content = get_the_content_feed( 'rss2' );

					$all_content = implode( ' ', get_extended( get_post_field( 'post_content', get_the_ID() ) ) );

					if(isset($feed['max_characters_content'])){
						$all_content = $this->egoi_rss_feed_content( $all_content, $feed['max_characters_content']);
					}

					$description = $this->egoi_rss_feed_description( get_the_excerpt(), $feed['max_characters'] );
					?>
					<p>
						<a href="<?php the_permalink_rss(); ?>" target="_blank">
							<?php the_title_rss(); ?>
						</a><br>
						<?php echo mysql2date( 'j M Y H:i', get_post_time( 'Y-m-d H:i:s', true ), false ); ?><br>
						<?php the_author(); ?>
					</p>
					<?php
					if ( has_post_thumbnail() ) {
						echo '<p  align="center">' . get_the_post_thumbnail( get_the_ID(), $feed['image_size'] ) . '</p>';
					} elseif ( $gallery = get_post_gallery_images( get_the_ID() ) ) {
						foreach ( $gallery as $image_url ) {
							?>
							<p  align="center"><img width="600" src="<?php echo esc_url( $image_url ); ?>" /></p>
							<?php
							break;
						}
					} else {
						preg_match( '~<img.*?src=["\']+(.*?)["\']+~', $all_content, $img );
						if ( isset( $img[1] ) ) {
							?>
							<p  align="center"><img width="600" src="<?php echo esc_url( $img[1] ); ?>"/></p>
							<?php
						}
					}
					
					?>
					<p><?php echo esc_textarea( $description ); ?> </p>
					<?php
				}
			}
			?>
		</div>
<?php } ?>

<script>
    function copyToClipboard(elementId) {
        var copyText = document.getElementById(elementId);
        copyText.select();
        document.execCommand("copy");
    }
</script>
