<?php

$f3=require('lib/base.php');

$f3->config('config/config.ini');

$f3->config('config/routes.ini');

$f3->set('ONERROR',function($f3){
    echo \View::instance()->render('404.html');
});

$f3->run();

?>
