<?php

namespace ExPage;

class EhentaiLoader extends Page
{
    
    private $username = null;
    private $password = null;
    private $query_data = array("returntype" => 8, "CookieDate" => 1, "b" => "d", "submit" => "Login!");
    private $loginUrl = "https://forums.e-hentai.org/index.php?act=Login&CODE=01";
    private $cookie = "cookie/ehentai";


    public function __construct(array $setting)
    {
        if (!(isset($setting["username"]) || isset($setting["password"]))) {
            throw new Exception("Request filed missing", 1);
        }

        $this->username = $setting["username"];
        $this->password = $setting["password"];
        $this->login();
    }

    public function parse($url, $save, $pack = false)
    {
        $data = $this->getPage($url, $this->getEhentaiCookieFile());
        return new ImageFactory($data, $this->getEhentaiCookieFile(), $save, array(), $pack);
    }

    protected function getUserName()
    {
        return $this->username;
    }

    protected function getPassWord()
    {
        return $this->password;
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