<?php
ini_set('display_errors', 0);

require __DIR__.'/../vendor/autoload.php';//chargement Silex + tout les plugins

require __DIR__.'/../app/app.php';//inclusion du fichier d'enregistrement de silex et des service

require __DIR__.'/../app/prod.php';

require __DIR__.'/../app/routes.php';


$app->run(); //lancement de l'app

?>
