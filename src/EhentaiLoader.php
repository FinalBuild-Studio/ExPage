<?php

namespace ExPage;

use phpQuery;

class EhentaiLoader extends Page
{

    public  $title     = [];
    private $username  = null;
    private $password  = null;
    private $queryData = ["returntype" => 8, "CookieDate" => 1, "b" => "d", "submit" => "Login!"];
    private $loginUrl  = "https://forums.e-hentai.org/index.php?act=Login&CODE=01";
    private $cookie    = "ehentai";
    protected $home    = "http://g.e-hentai.org/";
    protected $header  = [];

    public function __construct(array $setting)
    {
        if (!(isset($setting["username"]) || isset($setting["password"]))) {
            throw new Exception("Request field missing");
        }

        $this->username = $setting["username"];
        $this->password = $setting["password"];
        $this->login();
    }

    public function __destruct()
    {
        @unlink($this->getCookieFile());
    }

    public function parseFromPage($url, $save, $pack = "", $deleteAfterPack = false)
    {
        $data = $this->getPage($url, $this->getCookieFile(), $this->header);
        new Image($data, $this->getCookieFile(), $save, $this->header, $pack, $deleteAfterPack);
    }

    public function parseFromIndex($from, $pages, $save, $pack = "", $deleteAfterPack = false)
    {
        $startPage = $from - 1;
        $endPage   = $startPage + $pages;
        for ($i = $startPage; $i < $endPage; $i++) {
            $content = $this->getPage($this->home . ($i > 0 ? ("?page={$i}") : ""), $this->getCookieFile(), $this->header);
            phpQuery::newDocument($content);
            foreach (pq(".it5>a") as $title) {
                $match = $this->isMatch(pq($title)->text());
                if (!$match) {
                    continue;
                }

                $href = pq($title)->attr("href");
                $this->parseFromPage($href, $save, $pack, $deleteAfterPack);
            }
        }
    }

    public function getCookieFile()
    {
        return $this->getEhentaiCookieFile();
    }

    public function contain($text)
    {
        $this->title[] = $text;
    }

    protected function getEhentaiCookieFile()
    {
        return dirname(__FILE__) . "/" . $this->cookie;
    }

    protected function isMatch($str)
    {
        foreach ($this->title as $title) {
            if (!is_bool(strpos($str, $title))) {
                return true;
            }
        }

        return false;
    }

    private function login()
    {
        Curl::http("post", $this->loginUrl, array_merge($this->queryData, ["UserName" => $this->username, "PassWord" => $this->password]), $this->getEhentaiCookieFile());
    }
}
?>
