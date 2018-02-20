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
    
    public function articleAction(Application $app, $id)
    {
        if(!preg_match("#^[0-9]{1,5}$#", $id))
        {
            $errors[] = 'L\'id saisie est invalide !';
        }
        if(!isset($errors))
        {
            $article = $app['dao.article']->getArticleById($id);
            if(empty($article))
            {
                $app->abort(404);
            }
            return $app['twig']->render('templates/article.html.twig', array( "article" => $article));
        }
    }
}
?>
