ExPage
======

##Usage
There are two class ExPage\ExhentaiLoader is for exhentai, ExPage\Ehentailoader is for ehentai

```
require_once "vendor/autoload.php";
$config = ["username" => {YOUR_USER_NAME}, "password" => {YOUR_PASSWORD}];    
$loader = new ExPage\Ehentailoader($config);
$loader->contain({DEFINE_YOUR_SEARCH_TERM});
$loader->parseFromIndex(1, 1, {TMP_FOLDER}, {ZIP_SAVE_FOLDER}, true);
```
