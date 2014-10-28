<?php

namespace ExPage;

class EhentaiLoader extends Page
{
    
    private $username = null;
    private $password = null;
    private $query_data = array("returntype" => 8, "CookieDate" => 1, "b" => "d", "submit" => "Login!");
    private $loginUrl = "https://forums.e-hentai.org/index.php?act=Login&CODE=01";
    private $cookie = "cookie/ehentai";
    protected $home = "http://g.e-hentai.org/";
    protected $header = array();

    public function __construct(array $setting)
    {
        if (!(isset($setting["username"]) || isset($setting["password"]))) {
            throw new Exception("Request filed missing", 1);
        }

        $this->username = $setting["username"];
        $this->password = $setting["password"];
        $this->login();
    }

    public function parse($url, $save, $pack = "", $deleteAfterPack = false)
    {
        $data = $this->getPage($url, $this->getCookieFile(), $this->header);
        return new ImageFactory($data, $this->getCookieFile(), $save, $this->header, $pack, $deleteAfterPack);
    }

    public function parseHome($from, $pages, $save, $pack = "", $deleteAfterPack = false)
    {
        $startPage = $from - 1;
        $endPage = $startPage + $pages;
        for ($i = $startPage; $i < $endPage; $i++) { 
            $content = $this->getPage($this->home . ($i > 0 ? ("?page=" . $i) : ""), $this->getCookieFile(), $this->header);
            \phpQuery::newDocument($content);
            foreach (pq(".it5>a") as $value) {
                $href = pq($value)->attr("href");
                $this->parse($href, $save, $pack, $deleteAfterPack);
            }
        }
    }

    public function getCookieFile()
    {
        return $this->getEhentaiCookieFile();
    }

    protected function getEhentaiCookieFile()
    {
        return dirname(__FILE__) . "/" . $this->cookie;
    }

    private function login()
    {
        Curl::http("post", $this->loginUrl, array_merge($this->query_data, array("UserName" => $this->username, "PassWord" => $this->password)), $this->getEhentaiCookieFile());
    }
}
?>