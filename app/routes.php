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

$app->match('/connection/', 'Controller\Controller::connectionAction')
    ->method('GET|POST')
    ->bind('connection');//donne un nom a cette route

$app->match('/registerSubmit/', 'Controller\Controller::registerSubmitAction')
    ->method('GET|POST')
    ->bind('registerSubmit');//donne un nom a cette route

$app->get('/deconnection/', 'Controller\Controller::logoutAction')
    ->bind('deconnection');

$app->get('/article/{id}', 'Controller\Controller::articleAction')
    ->bind('article');

$app->get('/event/', 'Controller\Controller::eventAction')
    ->bind('event');

$app->match('/calendarAdmin/', 'Controller\Controller::calendarAdminAction')
    ->method('GET|POST')
    ->bind('calendarAdmin');

$app->post('/event-submit/', 'Controller\Controller::eventSubmitAction')
    ->bind('event-submit');

$app->post('/get-event/', 'Controller\Controller::getEventAction')
    ->bind('get-event');

$app->get('/espace-membre/', 'Controller\Controller::memberAction')
    ->bind('member-area');

$app->get('/search/', 'Controller\Controller::searchAction')
    ->bind('search');

$app->match('/sign-in/', 'Controller\Controller::signinAction')
    ->method('GET|POST')
    ->bind('sign-in');

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
