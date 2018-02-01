<?php

use Symfony\Component\HttpFoundation\Request;// necessaire au system d'erreur
use Symfony\Component\HttpFoundation\Response;// necessaire au system d'erreur

$app->get('/', 'Controller\Controller::indexAction')
    ->bind('home');//donne un nom a cette route

$app->get('/contact/', 'Controller\Controller::contactAction')
    ->bind('contact');

$app->get('/profil/', 'Controller\Controller::profilAction')
    ->bind('profil');

$app->match('/register/', 'Controller\Controller::registerAction')
    ->method('GET|POST')
    ->bind('register');

$app->get('/get-account-list/', 'Controller\Controller::getAccountListAction')
    ->bind('getaccountlist');
// $app->get('/saluer/{name}/{age}', 'Controller\Controller::saluerAction')
//     ->assert('name', '[a-zA-Z]{3,25}')
//     ->assert('age', '[1-9]{1,3}');

$app->error(function(Exception $e, Request $request, $code) use($app)
{
    if ($app['debug']) {
        return;
    }

    $templates =  array(
        'errors/'.$code.'.html.twig',
        'errors/'.substr($code, 0, 1).'xx.html.twig',
        'errors/default.html.twig'
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code'=>$code)),$code);
});
