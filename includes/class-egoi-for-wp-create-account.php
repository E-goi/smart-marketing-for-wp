<?php
// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
    die();
}

class EgoiCreateAccount
{

    protected $_valid = array(
        'apikey' => '14d4e28ba6c45e42ab464addfc0c1e705cf7634d',
        'utilizador' => '',
        'telefone_ind' => '',
        'telefone_numero' => '',
        'origem' => '',
        'codigo_revendedor' => 'RES43db572', // RES43db572
        'onde_conheceu' => '',
        'aff' => '',
        'aff_extra' => '',
        'empresa' => '',
        'pais_cod' => '',
        'localidade' => '',
        'estado' => '1',
        'email' => '',
        'primeiro_nome' => '',
        'ultimo_nome' => '',
        'senha' => '',
        'idioma' => '',
        'ipAddress' => '',
        'active_afiliado' => '',
        'pais' => '',
        'egoiFan' => '',
        'egoi_cookie'=> '',
        'server_id' => '', // servidor onde vai conter a conta
        'aditional_products' => '' //--------------------ESPECIFICAR
    );

    public function __construct($post = [])
    {
        require_once plugin_dir_path( __FILE__ ) . 'class-egoi-for-wp-smtp.php';

        if(preg_match('/(pt|mz)/', $_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $lang = 'pt';
        } else if(preg_match('/(br)/', $_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $lang = 'br';
        } else if(preg_match('/(es)/', $_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $lang = 'es';
        } else {
            $lang = 'en';
        }

        $this->_valid['utilizador'] = $post['email'];
        $this->_valid['empresa'] = $post['empresa'];
        $this->_valid['email'] = $post['email'];

        if( $post['empresa'] !== str_replace(' ','',$post['empresa']) ){
            //Have whitespace
            $user = explode(" ", $post['empresa']);
            $this->_valid['primeiro_nome'] = $user[0];
            $this->_valid['ultimo_nome'] = $user[1];
        }
        else{
            $this->_valid['primeiro_nome'] = $post['empresa'];
            //dont have whitespace
        }

        $this->_valid['telefone_numero'] = $post['phone'];
        $this->_valid['telefone_ind'] = $post['indicative'];

        $this->_valid['idioma'] = 'br';
        $this->_valid['senha'] = $post['password'];

        $ip = $_SERVER['REMOTE_ADDR'];
        $this->_valid['ipAddress'] = $ip;
    }

    public function __set($key,$value) {

        if(!isset($this->_valid[$key]))
        {
            throw new Exception('Invalid key: ' . $key);
        }
        $this->_valid[$key] = $value;
        if($key == 'email')
        {
            $this->_valid['utilizador'] = $value;
        }
    }

    public function checkUser() {

        $sender = 'info@e-goi.com';
        $SMTP_Valid = new EgoiSmtp();
        $result = $SMTP_Valid->validate(array($this->_valid['email']),$sender);

        if(empty($this->_valid['email'])) {
            return false;
        } else if($result[$this->_valid['email']] === true) {
            $url = 'http://api.e-goi.com/v2/rest.php?type=json&method=checkUser&' . http_build_query(array('functionOptions' => array('apikey' => $this->_valid['apikey'],'email' => $this->_valid['email'])),'','&');
            $result = $this->_getContent($url);
            $result = json_decode($result);

            if(isset($result->Egoi_Api->checkUser->RESULT) && $result->Egoi_Api->checkUser->RESULT == 'OK') {
                return true;
            }
        }
        return false;
    }

    public function checkLogin($data){
        $url = 'http://api.e-goi.com/v2/rest.php?type=json&method=checklogin&'.http_build_query(array('functionOptions' => array( 'username' => $data['username'], 'password' => $data['password']) ),'','&');

        return json_decode($this->_getContent($url));
    }

    public function createAccount() {

        if($this->checkUser()) {

            if (isset($_SERVER['HTTP_COOKIE'])) {
                $this->_valid['egoi_cookie'] = $_SERVER['HTTP_COOKIE'];
            }

            $url = 'http://api.e-goi.com/v2/rest.php?type=json&method=createAccount&'.http_build_query(array('functionOptions' => $this->_valid),'','&');
            $result = json_decode($this->_getContent($url));

            if(isset($result->Egoi_Api->createAccount->RESULT) && $result->Egoi_Api->createAccount->RESULT == 'OK')
            {
                return true;
            }
        }

        return false;
    }

    protected function _getContent($url) {

        if(ini_get('allow_url_fopen') == true) {
            $context = stream_context_create(array('http' => array( 'timeout' => 600)));
            $result = file_get_contents($url,false,$context);
        } else if(function_exists('curl_init')) {
            $curl = curl_init();
            curl_setopt($curl,CURLOPT_URL,$url);
            curl_setopt($curl,CURLOPT_HEADER,0);
            curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
            curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,600);
            curl_setopt($curl,CURLOPT_TIMEOUT,60);
            curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($curl);
            curl_close($curl);
        } else
        {
            // Enable 'allow_url_fopen' or install cURL.
            throw new Exception("Can't create account.");
        }
        return $result;
    }

}