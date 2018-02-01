<?php
if (isset($_SERVER['HTTP_CLIENT_IP'])
    || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
    || !in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', 'fe80::1', '::1'))
    ){
    header('HTTP/1.0 403 Forbiden');
    die('Acces interdit!!!');
}

require __DIR__.'/../vendor/autoload.php';//chargement Silex + tout les plugins

require __DIR__.'/../app/app.php';//inclusion du fichier d'enregistrement de silex et des service

require __DIR__.'/../app/dev.php';

require __DIR__.'/../app/routes.php';

$app->run(); //lancement de l'app

?>
