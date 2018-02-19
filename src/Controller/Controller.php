<?php
/*
le namespace doit ere identiqueau chemin d'acces de ce fichier dans src/

le nom de la classe doit etre strictement identique au nom du fichier = avec
'controller.php' la classe doit s'appeler 'controller'

*/
namespace Controller;

use Silex\Application;
use Models\Domain\Account;
use Models\Domain\Article;



class Controller{
    public function indexAction(Application $app){
        return $app['twig']->render('templates/index.html.twig', array('articles' => $app['dao.article']->findArticleByLimit(4)));
    }

    public function contactAction(Application $app){

        return $app['twig']->render('templates/contact.html.twig');
    }

    public function registerAction(Application $app){

        return $app['twig']->render('templates/register.html.twig');
    }

    public function connectionAction(Application $app){

        return $app['twig']->render('templates/connection.html.twig');
    }

    public function articleAction(Application $app, $id)
    {
        return $id;
    }



}

?>
