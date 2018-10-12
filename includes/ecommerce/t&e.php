<?php
// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
    die();
}

$te = "
		<script type='text/javascript'>
			var _egoiaq = _egoiaq || [];
			(function(){
			var u=((\"https:\" == document.location.protocol) ? \"https://egoimmerce.e-goi.com/\" : \"http://egoimmerce.e-goi.com/\");
			_egoiaq.push(['setClientId', \"$client_id\"]);
			_egoiaq.push(['setListId', \"$list_id\"]);
			_egoiaq.push(['setSubscriber', \"$user_email\"]);
			_egoiaq.push(['setTrackerUrl', u+'collect']);\n";

			if(!empty($products)){
				foreach($products as $product){
					$id = $product['id'];
					$name = htmlentities($product['name']);
					$cat = htmlentities($product['cat']);
					$price = floatval($product['price']);
					$qty = $product['quantity'];

					$te .= "_egoiaq.push(['addEcommerceItem',
				    	\"$id\",
				    	\"$name\",
				    	\"$cat\",
				    	$price,
				    	$qty]);\n";
				}
			} else if ($cart_zero == 1) {
				$id = 0;
				$name = '';
				$cat = '';
				$price = 0;
				$qty = 0;
				$sum_price = 0;

				$te .= "_egoiaq.push(['addEcommerceItem',
			    	\"$id\",
			    	\"$name\",
			    	\"$cat\",
			    	$price,
			    	$qty]);\n";

			    $te .= "_egoiaq.push(['trackEcommerceCartUpdate',
				    	$sum_price\n
				    ]);\n";
			}
			

			if(!empty($order_items)){
				
				$order_id = $order_items['order_id'];
				if(isset($order_id) && ($order_id)){

					$order_total = $order_items['order_total'];
					$order_subtotal = $order_items['order_subtotal'];
					$order_tax = $order_items['order_tax'];
					$order_shipping = $order_items['order_shipping'];
					$order_discount = $order_items['order_discount'];

					$te .= "_egoiaq.push(['trackEcommerceOrder',
					    \"$order_id\",
					    \"$order_total\",
					    \"$order_subtotal\",
					    $order_tax,
					    $order_shipping,
					    $order_discount]);\n";
				}

			}else{

				if($sum_price){
					$te .= "_egoiaq.push(['trackEcommerceCartUpdate',
				    	$sum_price\n
				    ]);\n";
				}
			}

		    $te .= "_egoiaq.push(['trackPageView']);
			var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
			g.type='text/javascript';
			g.defer=true;
			g.async=true;
			g.src=u+'egoimmerce.js';
			s.parentNode.insertBefore(g,s);

			})();
		</script>";
	return $te;
