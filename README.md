ExPage
======

##Usage 
There are two class \ExPage\ExhentaiLoader, \ExPage\Ehentailoader

    require_once "autoload.php";
    $config = array("username" => {YOUR_USER_NAME}, "password" => {YOUR_PASSWORD});    
    $loader = new \ExPage\Ehentailoader($config);
    $loader->parseHome(1, 1, {TMP_FOLDER}, {ZIP_SAVE_FOLDER}, true);
