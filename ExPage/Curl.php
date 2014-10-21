<?php

namespace ExPage;

class Curl
{

    /**
     * [http description]
     * @param  string  $type    [description]
     * @param  string  $url     [description]
     * @param  array   $param   [description]
     * @param  boolean $JsonRaw [description]
     * @param  array   $header  [description]
     * @return Phalcon\Http\clieny\Respond           [description]
     */
    static function http($type = "get", $url = "", $param = array(), $saveCookie = false, $injectCookie = false, $requestHeader = array(), $encode = true)
    {
        echo "[{$type}] " , $url, PHP_EOL;
        $provider  = \Phalcon\Http\Client\Request::getProvider();
        $header = self::getHeader();
        $header = array_merge($header, $requestHeader);
        if(is_array($header) && count($header)) {
            foreach($header as $key => $var) {
                $provider->header->set($key, $var);
            }
        }

        if(!method_exists($provider, $type)) {
            $type = "get";
        }

        if ($saveCookie) {
            $provider->setOption(CURLOPT_COOKIEJAR, $saveCookie);
        }
        
        if ($injectCookie) {
            $provider->setOption(CURLOPT_COOKIEFILE, $injectCookie);
        }

        $provider->setOption(CURLOPT_SSL_VERIFYPEER, false);
        $provider->setOption(CURLOPT_CONNECTTIMEOUT, 0);
        $provider->setOption(CURLOPT_TIMEOUT, 600);
 
        sleep(rand(5, 7));

        $response = $provider->$type($url, $param, $encode);
        return $response->body;
    }

    private static function getHeader()
    {
        $User_Agent = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2188.2 Safari/537.36';

        $request_headers = array();
        $request_headers[] = 'User-Agent: '. $User_Agent;

        return $request_headers;
    }
}
?>