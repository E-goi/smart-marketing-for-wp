<?php
// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
    die();
}
$te .= "
	<html>
	<body>
	<script type='text/javascript'>
		var _egoiaq = _egoiaq || [];
		(function(){
			var u=((\"https:\" == document.location.protocol) ? \"https://egoimmerce.e-goi.com/\" : \"http://egoimmerce.e-goi.com/\");
			_egoiaq.push(['setClientId', \"$client_id\"]);
			_egoiaq.push(['setListId', \"$list_id\"]);
			_egoiaq.push(['setSubscriber', \"$user_email\"]);
			_egoiaq.push(['setTrackerUrl', u+'collect']);\n";
			
			$sum_price = '';
			foreach($products as $key => $product){
				
				$product_id = $product['id_product'];
		 		$product_name = $product['name'];
		 		$product_cat = '-';
		 		$product_price = number_format($product['price'],1);
		 		$sum_price += $product_price;
		 		$product_quantity = $product['quantity'];

				$te .= "_egoiaq.push(['addEcommerceItem',
			    \"$product_id\",
			    \"$product_name\",
			    \"$product_cat\",
			    $product_price,
			    $product_quantity]);\n";
			}

			if(isset($order)){

				$te .= "_egoiaq.push(['trackEcommerceOrder',
			    \"$order_id\",
			    \"$order_total\",
			    \"$order_subtotal\",
			    $order_tax,
			    $order_shipping,
			    $order_discount]);\n";
			    
			}else{

				$te .= "_egoiaq.push(['trackEcommerceCartUpdate',
			    $sum_price\n
			    ]);\n";
			}
			
			$te .= "_egoiaq.push(['trackPageView']);
			var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
			g.type='text/javascript';
			g.defer=true;
			g.async=true;
			g.src=u+'egoimmerce.js';
			s.parentNode.insertBefore(g,s);

		})();
	</script>
	</body>
	</html>";