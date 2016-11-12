ExPage
======

##Composer
```sh
composer require "capslock-studio/ex-page" dev-master
```

##Usage
There are two class ExPage\ExhentaiLoader is for exhentai, ExPage\Ehentailoader is for ehentai

```php
require_once "vendor/autoload.php";

$config = ["username" => {YOUR_USER_NAME}, "password" => {YOUR_PASSWORD}];    
$loader = new ExPage\Ehentailoader($config);
$loader->search({DEFINE_YOUR_SEARCH_TERM});
$loader->contain({DEFINE_YOUR_CONTAIN_TERM});
$loader->parseFromIndex(1, 1, {TMP_FOLDER}, {ZIP_SAVE_FOLDER}, true);
```
