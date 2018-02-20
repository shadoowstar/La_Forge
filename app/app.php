<?php
/*instanciation de Silex + enregistrement de tout les plugin de Prod*/

use Silex\Provider;
use Models\DAO\AccountDAO;
use Models\DAO\ArticleDAO;

$app = new Silex\Application();

$app->register(new Provider\TwigServiceProvider());

$app->register(new Provider\AssetServiceProvider());

$app->register(new Provider\ServiceControllerServiceProvider());

$app->register(new Provider\HttpFragmentServiceProvider());

$app->register(new Provider\MonologServiceProvider());

$app->register(new Provider\DoctrineServiceProvider());

$app->register(new Provider\SessionServiceProvider());




$app['dao.account'] = function($app){
    return new AccountDAO($app['db']);
};
$app['dao.article'] = function($app)
{
    return new ArticleDAO($app['db']);
};

?>
