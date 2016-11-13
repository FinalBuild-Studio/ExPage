<?php

namespace CapsLockStudio\ExPage;

class Curl
{

    public static function http($type = "get", $url = "", $params = [], $saveCookie = false, $injectCookie = false, $requestHeader = [])
    {
        $header = self::getHeader();
        $header = array_merge($header, $requestHeader);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);

        if ($saveCookie) {
            curl_setopt($ch, CURLOPT_COOKIEJAR, $saveCookie);
        }

        if ($params) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }

        if ($injectCookie) {
            curl_setopt($ch, CURLOPT_COOKIEFILE, $injectCookie);
        }

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 600);

        return curl_exec($ch);
    }

    private static function getHeader()
    {
        $userAgent = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2188.2 Safari/537.36';

        $requestHeaders   = [];
        $requestHeaders[] = "User-Agent: {$userAgent}";

        return $requestHeaders;
    }
}
?>
