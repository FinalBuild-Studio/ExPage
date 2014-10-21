<?php

namespace ExPage;

class ExhentaiLoader extends EhentaiLoader
{
    
    private $cookie = "cookie/exhantai";
    private $header = array("Referer: http://exhentai.org", "Host: exhentai.org");

    public function __construct(array $setting)
    {
        parent::__construct($setting);
        $this->login();
    }

    public function parse($url, $save, $pack = "")
    {
        $data = $this->getPage($url, $this->getExhentaiCookieFile(), $this->header);
        return new ImageFactory($data, $this->getExhentaiCookieFile(), $save, $this->header, $pack);
    }

    protected function getExhentaiCookieFile()
    {
        return dirname(__FILE__) . "/" . $this->cookie;
    }

    private function login()
    {
        Curl::http("get", "http://exhentai.org", array(), $this->getExhentaiCookieFile(), $this->getEhentaiCookieFile(), array("Referer: http://exhentai.org", "Host: exhentai.org"));
    }
}
?>