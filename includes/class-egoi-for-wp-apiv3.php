<?php
/**
 * Created by PhpStorm.
 * User: tmota
 * Date: 25/07/2019
 * Time: 16:44
 */

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
    die();
}

class EgoiApiV3
{
    const APIV3     = 'https://api.egoiapp.com';
    const PLUGINKEY = '908361f0368fd37ffa5cc7c483ffd941';
    const APIURLS   = [
        'deployEmailRssCampaign'    => '/campaigns/email/rss/{campaign_hash}/actions/send',
        'createEmailRssCampaign'    => '/campaigns/email/rss',
        'getSenders'                => '/senders/{channel}?status=active',
        'getLists'                  => '/lists?limit=10&order=desc&order_by=list_id'
    ];
    protected $apiKey;
    protected $headers;
    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
        $this->headers = ['ApiKey: '.$this->apiKey,'PluginKey: '.self::PLUGINKEY,'Content-Type: application/json'];
    }

    /**
     * @param $data
     * @return false|string
     */
    public function createEmailRssCampaign($data){

        $client = new ClientHttp(
            self::APIV3.self::APIURLS[__FUNCTION__],
            'POST',
            $this->headers,
            json_encode($data)
        );

        if($client->success() !== true){
            return $this->processErrors('curl');
        }

        return $client->getCode()==200
            ?$client->getResponse()
            :$this->processErrors();

    }

    /**
     * @param $id
     * @return false|string
     */
    public function deployEmailRssCampaign($id){
        $path = self::APIV3.$this->replaceUrl(self::APIURLS[__FUNCTION__],'{campaign_hash}', $id);
        $client = new ClientHttp($path,'POST',
           $this->headers
        );

        if($client->success() !== true){
            return $this->processErrors('curl');
        }

        return $client->getCode()==200
            ?$client->getResponse()
            :$this->processErrors();
    }

    /**
     * @param string $channel
     * @return false|string
     */
    public function getSenders($channel = 'email'){
        $path = self::APIV3.$this->replaceUrl(self::APIURLS[__FUNCTION__],'{channel}', $channel);
        $client = new ClientHttp(
            $path,
            'GET',
            $this->headers
        );

        if($client->success() !== true){
            return $this->processErrors('curl');
        }
        $resp = json_decode($client->getResponse(),true);
        return $client->getCode()==200 && isset($resp['items'])
            ?json_encode($resp['items'])
            :$this->processErrors();
    }

    /**
     * @return false|string
     */
    public function getLists(){

        $client = new ClientHttp(
            self::APIV3.self::APIURLS[__FUNCTION__],
            'GET',
            $this->headers
        );

        if($client->success() !== true){
            return $this->processErrors('curl');
        }

        $resp = json_decode($client->getResponse(),true);
        return $client->getCode()==200 && isset($resp['items'])
            ?json_encode($resp['items'])
            :$this->processErrors();

    }

    /**
     * @param $url
     * @param $search
     * @param $replace
     * @return null|string|string[]
     */
    protected function replaceUrl($url, $search, $replace){
        if(is_array($replace)){
            foreach ($replace as $key => $value){
                $url = $this->privReplaceUrl($url, $search[$key], $replace[$key]);
            }
            return $url;
        }else{
            return $this->privReplaceUrl($url, $search, $replace);
        }
    }

    /**
     * @param bool $error
     * @return false|string
     */
    private function processErrors($error=false){
        if($error == false)
            return json_encode(['status' => 'error']);
        else return json_encode(['error' => $error]);
    }

    /**
     * @param $url
     * @param $search
     * @param $replace
     * @return null|string|string[]
     */
    private function privReplaceUrl($url, $search, $replace){
        return preg_replace("/$search/", "$replace", $url );
    }

}

class ClientHttp {

    protected $headers;
    protected $response;
    protected $err;
    protected $http_code;


    public function __construct($url, $method = 'GET', $headers = ['Accept: application/json'], $body = '')
    {

        $curl = curl_init($url);
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => $headers,
        ]);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        // this function is called by curl for each header received
        curl_setopt($curl, CURLOPT_HEADERFUNCTION,
            function($curl, $header) use (&$headers)
            {
                $len = strlen($header);
                $header = explode(':', $header, 2);
                if (count($header) < 2) // ignore invalid headers
                    return $len;

                $name = strtolower(trim($header[0]));
                if (!array_key_exists($name, $headers))
                    $headers[$name] = [trim($header[1])];
                else
                    $headers[$name][] = trim($header[1]);

                return $len;
            }
        );

        $response = curl_exec($curl);
        $this->http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        $this->headers = $headers;
        $this->response = $response;

        if(curl_errno($curl)){
            $this->err = curl_error($curl);
        }

        curl_close($curl);


    }

    public function success(){
        if(empty($this->err))
            return true;
        return $this->err;
    }

    public function getError(){
        return $this->err;
    }

    public function getCode(){
        return $this->http_code;
    }

    public function getResponse(){
        return $this->response;
    }
    public function getHeaders(){
        return $this->headers;
    }

}