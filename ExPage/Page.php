<?php

namespace ExPage;

class Page
{
    
    public function getPage($url, $cookie, $header = array())
    {   
        return Curl::http("get", $url, array(), false, $cookie, $header);
    }
}
?>