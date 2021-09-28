<div class="smsnf-grid">
	<div>

		<div class="smsnf-input-group">
			<label for="catalog_language"><?php _e( 'Contact Form 7 Integration', 'egoi-for-wp' ); ?></label>
			<div class="smsnf-wrapper">
				<span><strong><?php _e( 'Status', 'egoi-for-wp' ); ?></strong>:
					<?php if ( empty( $egoint['enable_cf'] ) ) { ?>
						<span style="color: orangered"><?php _e( 'Off', 'egoi-for-wp' ); ?></span>
					<?php } else { ?>
						<span style="color: #5cb85c"><?php _e( 'On', 'egoi-for-wp' ); ?></span>
					<?php } ?>
				</span>
			</div>
		</div>

		<div class="smsnf-input-group">
			<label for="catalog_language"><?php _e( 'Post Comment Integration', 'egoi-for-wp' ); ?></label>
			<div class="smsnf-wrapper">
				<span><strong><?php _e( 'Status', 'egoi-for-wp' ); ?></strong>:
					<?php if ( empty( $egoint['enable_pc'] ) ) { ?>
						<span style="color: orangered"><?php _e( 'Off', 'egoi-for-wp' ); ?></span>
					<?php } else { ?>
						<span style="color: #5cb85c"><?php _e( 'On', 'egoi-for-wp' ); ?></span>
					<?php } ?>
				</span>
			</div>
		</div>

		<div class="smsnf-input-group">
			<label for="catalog_language"><?php _e( 'Gravity Forms Integration', 'egoi-for-wp' ); ?></label>
			<div class="smsnf-wrapper">
				<span><strong><?php _e( 'Status', 'egoi-for-wp' ); ?></strong>:
					<?php if ( empty( $egoint['enable_gf'] ) ) { ?>
						<span style="color: orangered"><?php _e( 'Off', 'egoi-for-wp' ); ?></span>
					<?php } else { ?>
						<span style="color: #5cb85c"><?php _e( 'On', 'egoi-for-wp' ); ?></span>
					<?php } ?>
				</span>
			</div>
		</div>

	</div>
</div>
