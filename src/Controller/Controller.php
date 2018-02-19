<?php
/*
le namespace doit ere identiqueau chemin d'acces de ce fichier dans src/

le nom de la classe doit etre strictement identique au nom du fichier = avec
'controller.php' la classe doit s'appeler 'controller'

*/
namespace Controller;

use Silex\Application;
use Models\Domain\Account;



class Controller{
    public function indexAction(Application $app){

        return $app['twig']->render('templates/index.html.twig', array( "articles" => $app['dao.article']->findArticleByLimit($app['index.articles'])));
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
    
    public function articleAction(Application $app, $id){
        echo $id;
        return $app['twig']->render('templates/article.html.twig');
    }


}

?>
