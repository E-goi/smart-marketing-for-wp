<?php


class TrackingEngageSDK {

	protected $client_id;
	protected $list_id;
	protected $social_id;
	protected $order_id;
	protected $options;
	const OPTION_FLAG = 'order_trigger_';
	const SESSION_TAG = 'egoi_tracking_uid';

	public function __construct( $client_id, $list_id, $order_id = false, $social_id = null ) {
		if ( ! empty( $list_id ) ) {
			$this->list_id = $list_id;}
		if ( ! empty( $client_id ) ) {
			$this->client_id = $client_id;}
		if ( ! empty( $order_id ) ) {
			$this->order_id = $order_id; }
		if ( ! empty( $social_id ) ) {
			$this->social_id = $social_id; }

		$this->options = get_option( 'egoi_sync' );
	}

	public function getStartUp() {
		$this->setOrder();
		if ( ! isset( $_GET['wc-ajax'] ) ) {
			?>
			<script>
				(function () {
						window._egoiaq = window._egoiaq || [];
						var url = (("https:" == document.location.protocol) ? "https://egoimmerce.e-goi.com/" : "http://egoimmerce.e-goi.com/");
						var url_cdn = (("https:" == document.location.protocol) ? "https://cdn-te.e-goi.com/" : "http://cdn-te.e-goi.com/");
						var d = document, g = d.createElement('script'), s = d.getElementsByTagName('script')[0];
						g.type = 'text/javascript';
						g.defer = true;
						g.async = true;
						g.src = url_cdn + 'egoimmerce.js';
						s.parentNode.insertBefore(g, s);
						window._egoiaq.push(['setClientId', <?php echo esc_attr($this->client_id); ?>]);
						window._egoiaq.push(['setListId', <?php echo esc_attr($this->list_id); ?>]);
						<?php
						if ( $this->checkSubscriber() !== false ) { ?>
							window._egoiaq.push(['setSubscriber', "<?php echo esc_attr($this->checkSubscriber()); ?>"]);
                        <?php } ?>

						window._egoiaq.push(['setTrackerUrl', url + 'collect']);
						window._egoiaq.push(['trackPageView']);
					}
				)();
			</script>
			<?php
		}
		if ( ! class_exists( 'WooCommerce' ) ) {
			return false;}
		$this->getProductView();
		$this->getProductsInCart();
	}

	public function getStartUpCS( $domain ) {
		if ( isset( $_GET['wc-ajax'] ) ) {
			return false;
		}
		?>

		<script>
			(function () {
					window._egoiaq = window._egoiaq || [];
					window._egoiaq.push(['setListId', <?php echo esc_attr($this->list_id); ?>]);
					<?php
					if ( $this->checkSubscriber() !== false ) {
						?>
						window._egoiaq.push(['setSubscriber', "<?php echo esc_attr($this->checkSubscriber()); ?>"]);
						<?php
					}
					?>

					var _mtm = window._mtm = window._mtm || [];
					_mtm.push({'mtm.startTime': (new Date().getTime()), 'event': 'mtm.Start'});
					var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
					g.type='text/javascript'; g.async=true; g.src='https://egoi.site/<?php echo esc_attr( $this->client_id ); ?>_<?php echo esc_attr( $domain ); ?>.js';
					s.parentNode.insertBefore(g,s);
				}
			)();
		</script>

		<?php
		if ( ! class_exists( 'WooCommerce' ) ) {
			return false;
		}
		$this->getProductView();
		$this->getProductsInCart();
	}

	public function getStartUpSocial() {
		if ( ! isset( $_GET['wc-ajax'] ) && ! empty( $this->social_id ) ) {
			?>
			<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
				new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
				j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
				'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
				})(window,document,'script','dataLayer','<?php echo esc_attr($this->social_id); ?>');</script>
			<?php
		}
	}

	public function getProductLdJSON() {
		$product = wc_get_product( get_the_id() );

		$price = $product->get_sale_price() ? $product->get_sale_price() : $product->get_price();
		if ( '' !== $price ) {
			if ( $product->is_type( 'variable' ) ) {
				$price = $product->get_variation_price( 'min', false );
			}
		}
		?>
		<script type="application/ld+json" class="egoi-smart-marketing">
			{
			"@context":"https://schema.org",
			"@type":"Product",
			"productID":"<?php echo esc_attr($product->get_id()); ?>",
			"name":"<?php echo esc_attr(addslashes( $product->get_name() )); ?>",
			"description":"<?php echo esc_attr(addslashes( wp_strip_all_tags( do_shortcode( $product->get_short_description() ? $product->get_short_description() : $product->get_description() ) ) )); ?>",
			"url":"<?php echo esc_url($product->get_permalink()); ?>",
			"image":"<?php echo esc_url(wp_get_attachment_image_url( $product->get_image_id(), 'full' )); ?>",
			"brand":"<?php echo esc_attr($product->get_meta( '_egoi_brand' )); ?>",
			"offers": [
				{
				"@type": "Offer",
				"price": "<?php echo esc_attr($price); ?>",
				"priceCurrency": "<?php echo esc_attr(get_woocommerce_currency()); ?>",
				"itemCondition": "https://schema.org/NewCondition",
				"availability": "<?php echo esc_url('http://schema.org/' . ( $product->is_in_stock() ? 'InStock' : 'OutOfStock' )); ?>"
				}
			]
			}
			</script>
			<script>var egoi_product = { 'id':'<?php echo esc_attr($product->get_id()); ?>', 'client_id':'<?php echo esc_attr($this->client_id); ?>','name':'<?php echo esc_attr(addslashes( $product->get_name() )); ?>','price':'<?php echo esc_attr($price); ?>'};</script>
		<?php
	}

	protected function getProductView() {
		if ( ! is_product() ) {
			return false; }
		$product = wc_get_product( get_the_id() );

		?>

			<script>

				window._egoiaq.push(['setEcommerceView',
					"<?php echo esc_attr($product->get_id()); ?>",
					"<?php echo esc_attr(str_replace( '"', '\\"', $product->get_name() )); ?>",
					"",
					<?php echo esc_attr((float) $product->get_price()); ?>
				]);
				window._egoiaq.push(['trackPageView']);

			</script>

		<?php
	}

	protected function getProductsInCart() {

		$cart = WC()->cart->get_cart();
		require_once plugin_dir_path( __FILE__ ) . '../../includes/class-egoi-for-wp-products-bo.php';
		$options_catalogs = EgoiProductsBo::getCatalogOptions();
		$options_catalogs = empty( $options_catalogs ) ? array() : $options_catalogs;
		$variation        = false;
		foreach ( $options_catalogs as $options_catalog ) {
			$variation = $variation || ( empty( $options_catalog['variations'] ) ? false : true );
		}
		foreach ( $cart as $cart_item ) {
			?>
			<script>
				window._egoiaq.push(['addEcommerceItem',
					"<?php echo esc_attr( ( $variation && ! empty( $cart_item['variation_id'] ) ) ? $cart_item['variation_id'] : $cart_item['product_id'] ); ?>",
					"<?php echo esc_attr(str_replace( '"', '\\"', $cart_item['data']->get_title() )); ?>",
					"",
					<?php echo esc_attr(number_format( $cart_item['data']->get_price(), 2 )); ?>,
					<?php echo esc_attr((int) $cart_item['quantity']); ?>
				]);
			</script>
			<?php
		}

		if ( count( $cart ) == 0 ) {
			return false; }

		?>

		<script>
			window._egoiaq.push(['trackEcommerceCartUpdate',
				<?php echo esc_attr(number_format( WC()->cart->cart_contents_total, 2 )); ?>]);

			window._egoiaq.push(['trackPageView']);
		</script>

		<?php
		return true;
	}

	public function getOrder() {
		if ( empty( $this->order_id ) || ! empty( $this->options['backend_order'] ) ) {
			return false;
		}

		$order = self::getOrderObjectFromSaved( $this->order_id );
		if ( empty( $order ) ) {
			return false;
		}

		$this->printOrder( $order );

		return true;
	}

	public static function getOrderObjectFromSaved( $order_id ) {
		$order = get_option( self::OPTION_FLAG . $order_id );
		if ( empty( $order ) ) {
			return false;
		}
		delete_option( self::OPTION_FLAG . $order_id );
		return $order;
	}

	public function setOrder() {
		if ( empty( $this->order_id ) || ! empty( $this->options['backend_order'] ) ) {
			return false;
		}
		$order = wc_get_order( $this->order_id );
		update_option( self::OPTION_FLAG . $this->order_id, $order );
		return true;
	}

	public static function getSubInfo() {
		if ( ! empty( self::getUserMeta() ) ) {
			return array( 'contact_id' => self::getUserMeta() );
		}
		if ( isset( $_SESSION[ self::SESSION_TAG ] ) ) {
			return array( 'contact_id' => $_SESSION[ self::SESSION_TAG ] );
		}
		foreach ( $_COOKIE as $key => $value ) {
			preg_match( '/_pk_Subscriber[\.|_][0-9]+[\.|_][a-zA-Z0-9]{4}/', $key, $matches );
			if ( empty( $matches ) || count( $matches ) != 1 ) {
				continue;
			}
			return array( 'contact_id' => $value );
		}

		if (!empty($_GET['eg_sub']) && strlen( $_GET['eg_sub'] ) == 10 ) {
			return array( 'contact_id' => sanitize_key( $_GET['eg_sub'] ) );
		}

		$current_user = wp_get_current_user();
		if ( ! $current_user->exists() ) {
			return array();
		}
		return array( 'email' => $current_user->user_email );
	}

	/**
	 * @return bool | string
	 */
	private function checkSubscriber() {
		if ( ! empty( self::getUserMeta() ) ) {
			return self::getUserMeta();
		}
		if ( isset( $_SESSION[ self::SESSION_TAG ] ) ) {
			return $_SESSION[ self::SESSION_TAG ]; }
		$current_user = wp_get_current_user();
		if ( ! $current_user->exists() ) {
			return false; }
		return $current_user->user_email;
	}

	private function printOrder( $order ) {

		if ( ! empty( $this->options['backend_order'] ) ) {
			return false;}
		
		$order_id = $order->get_id(); // Get the order ID
		$items    = $order->get_items();
		if ( ! is_array( $items ) ) {
			$items = array(); }

		foreach ( $items as $item ) {

			?>

			<script>
				window._egoiaq.push(['addEcommerceItem',
					"<?php echo esc_attr($item->get_product_id()); ?>",
					"<?php echo esc_attr($item->get_name()); ?>",
					"",
					<?php echo esc_attr(number_format( $item->get_subtotal(), 2 )); ?>,
					<?php echo esc_attr((int) $item->get_quantity()); ?>
				]);
			</script>

			<?php
		}

		if ( count( $items ) > 0 ) {
			?>
			<script>
				window._egoiaq.push(['trackEcommerceOrder',
					"<?php echo esc_attr($order_id); ?>", // (required) Unique Order ID
					<?php echo esc_attr(number_format( $order->get_total(), 2 )); ?>, // (required) Order Revenue grand total (includes tax, shipping, and subtracted discount)
					<?php echo esc_attr(number_format( $order->get_subtotal(), 2 )); ?>, // (optional) Order sub total (excludes shipping)
					<?php echo esc_attr(number_format( $order->get_total_tax(), 2 )); ?>, // (optional) Tax amount
					<?php echo esc_attr(number_format( $order->get_shipping_total(), 2 )); ?>, // (optional) Shipping amount
					<?php echo  esc_attr(! empty( $order->get_total_discount() ) ? number_format($order->get_total_discount(), 2):0); ?> // (optional) Discount offered (set to false for unspecified parameter)
				]);

				window._egoiaq.push(['trackPageView']);
			</script>
			<?php
		}

		return true;

	}

	public static function setUidSession( $uid ) {
		if ( is_array( $uid ) && isset( $uid['UID'] ) ) {
			$_SESSION[ self::SESSION_TAG ] = $uid['UID'];
			self::setUserMeta( $uid['UID'] );
		} elseif ( ! is_array( $uid ) ) {
			$_SESSION[ self::SESSION_TAG ] = $uid;
			self::setUserMeta( $uid );
		}
	}

	private static function setUserMeta( $uid ) {
		$current_user = wp_get_current_user();
		if ( ! $current_user->exists() ) {
			return false; }
		update_user_meta( $current_user->ID, self::SESSION_TAG, $uid );
	}

	private static function getUserMeta() {
		$current_user = wp_get_current_user();
		if ( ! $current_user->exists() ) {
			return false; }
		return get_user_meta( $current_user->ID, self::SESSION_TAG, true );
	}

}
