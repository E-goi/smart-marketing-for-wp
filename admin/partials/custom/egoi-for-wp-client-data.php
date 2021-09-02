<?php
if ( ! defined( 'ABSPATH' ) ) {
    die();
}

$key = sanitize_text_field($_POST["key"]);
if(isset($key)){

	function getContent($url){

        $res = wp_remote_request( $url,
            array(
                'method'     => 'GET',
                'timeout'    => 30,
                'headers'    => []
            )
        );

        return $res['body'];
    }

	$url = 'http://api.e-goi.com/v2/rest.php?type=json&method=getClientData&'.http_build_query(array('functionOptions' => array('apikey' => $key)),'','&');
    $result_client = json_decode(getContent($url));

    echo "SUCCESS";

}else{
	header('HTTP/1.1 403 Forbidden');
	exit;
}

exit;