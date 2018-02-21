<?php

use Symfony\Component\HttpFoundation\Request;// necessaire au system d'erreur
use Symfony\Component\HttpFoundation\Response;// necessaire au system d'erreur

$app->get('/', 'Controller\Controller::indexAction')
    ->bind('home');//donne un nom a cette route

$app->get('/contact/', 'Controller\Controller::contactAction')
    ->bind('contact');//donne un nom a cette route

$app->match('/register/', 'Controller\Controller::registerAction')
    ->method('GET|POST')
    ->bind('register');//donne un nom a cette route

$app->get('/connection/', 'Controller\Controller::connectionAction')
    ->bind('connection');//donne un nom a cette route

$app->get('/article/{id}', 'Controller\Controller::articleAction')
    ->bind('article');


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
