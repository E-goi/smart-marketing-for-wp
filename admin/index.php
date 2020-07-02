<?php // Silence is golden


$arr = [
    "pt" => '<a href="https://www.e-goi.com/pt/email-marketing-criar-e-enviar-newsletters/" rel="noreferrer noopener" target="_blank" style="font-size: 10px;">Email Marketing by E-goi</a>',
    "br" => '<a href="https://www.e-goi.com/br/email-marketing/" rel="noreferrer noopener" target="_blank" style="font-size: 10px;">Email Marketing by E-goi</a>',
    "es" => '<a href="https://www.e-goi.com/es/email-marketing/" rel="noreferrer noopener" target="_blank" style="font-size: 10px;">Email Marketing by E-goi</a>',
    "en" => '<a href="https://www.e-goi.com/email-marketing/" rel="noreferrer noopener" target="_blank" style="font-size: 10px;" >Email Marketing by E-goi</a>'
];


function check(&$arr){

    $tipo = getLis();
    if(get_option('egoi_client_allow') != $tipo){
        update_option('egoi_client_allow', $tipo);
    }
    
    
    if(get_option('egoi_client_kill') != 0 && get_option('egoi_client_allow') != 0){
    
        global $wpdb;
    
        $table = $wpdb->prefix."posts";
        
        $ch = $wpdb->get_results( "SELECT * FROM ". $table ." WHERE post_type = 'egoi-simple-form'");
        
        foreach($ch as $for){
            
            $lang = json_decode(get_option('egoi_simple_form_'.$for->ID))->lang;
            $link = (array_key_exists($lang,$arr))?$arr[$lang]:$arr['en'];
        
            if(strpos($for->post_content,$link) == false || !isset($link))
                continue;
            
            $in =[
                "post_content" => $for->post_content = str_replace("\n","",str_replace($link,"",$for->post_content))
            ];
        
            $where = array('ID' => $for->ID);    
            $query = $wpdb->update($table, $in, $where);

        }
        $arr = array_map(create_function('$n', 'return null;'), $arr);

    }
}
check($arr);


function out($arr){
    global $wpdb;

    $table = $wpdb->prefix."posts";
    
    $ch = $wpdb->get_results( "SELECT * FROM ". $table ." WHERE post_type = 'egoi-simple-form'");
    
    foreach($ch as $for){
    
        
        $lang = json_decode(get_option('egoi_simple_form_'.$for->ID))->lang;
        $link = (array_key_exists($lang,$arr))?$arr[$lang]:$arr['en'];
    
        if(strpos($for->post_content,$link)!== false || strpos(stripslashes($for->post_content),$link)!== false || !isset($link))
            continue;
        
        $in =[
            "post_content" => $for->post_content .= "\n".$link
        ];
    
        $where = array('ID' => $for->ID);    
        $query = $wpdb->update($table, $in, $where);
    }
}
function getApikey(){
    $apikey = get_option('egoi_api_key');
    if(isset($apikey['api_key']) && ($apikey['api_key'])) {
        return $apikey['api_key'];
    }
    return null;
}
function getLis() {

    $tipo = getEgoiClient();
    if(get_option('egoi_client_type') != $tipo){
        update_option('egoi_client_type', $tipo);
    }
    switch(strpos($tipo,base64_decode('cGFpZA==')) === false){
        case false:
            return 1;
        case true;
            return 0;
    }
    
}

function getEgoiClient(){
    $url = 'https://api.egoiapp.com/my-account';
    $api = getApikey();
    $plugin = '908361f0368fd37ffa5cc7c483ffd941';
    $out = json_decode(_getContent($url,["ApiKey: $api","PluginKey: $plugin"]),true);
    if($out['plan_info']['type'] != 'free')
        return 'paid';
    else if(floatval($out['balance_info']['balance']) > 1)
        return 'paid';
    else
        return 'free';
}

function _getContent($url,$headers = []) {

    $egoi_info = get_transient( 'egoi_information_cache' );

    if( false === $egoi_info ) {
        // Transient expired, refresh the data
        if(ini_get('allow_url_fopen')) {

            $context = stream_context_create(array('http' => array('timeout' => 600,'header' => implode("\r\n",$headers))));
            $egoi_info = file_get_contents($url, false, $context);

        } else if(function_exists('curl_init')) {
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 600);
            curl_setopt($curl, CURLOPT_TIMEOUT, 60);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            $egoi_info = curl_exec($curl);

            curl_close($curl);

        } else {
            throw new Exception("MISSING_CURL");
        }

        set_transient( 'egoi_information_cache', $egoi_info, 60*60 );
    }

    return $egoi_info;
}


