<?php

$app['twig.path']=  array( __DIR__.'/../views');//chemin du dossier des vue

$app['twig.options']=  array(
    'cache'=>__DIR__.'/../var/cache/twig',
    'auto_reload' => true
);

$app['monolog.logfile'] = __DIR__.'/../var/logs/silex_dev.log';
$app['monolog.name'] = 'nomdusite';
$app['monolog.level'] = 'warning' ;//debug/info/warning/error

$app['db.options'] = array(
    'driver' => 'pdo_mysql',
    'host' => 'e89458-mysql.services.easyname.eu',
    'dbname' => 'u141134db1',
    'user' => 'u141134db1',
    'password' => 'laforge123',
    'charset' => 'utf8'
 );

$app['index.articles'] = 4;
?>
