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

    public function signinAction(Application $app)
    {
        if(isset($_POST['email']) && isset($_POST['password']))
        {
            if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
            {
                $errors[] = 'L\'adresse email saisie n\'est pas valide !';
            }

            if(!isset($errors))
            {
                $account = $app['dao.account']->findByEmail($_POST['email']);
                if($account && password_verify($_POST['password'], $account->_password))
                {
                    $app['session']->set('user', array(
                        'id' => $account->_id,
                        'email' => $account->_email,
                        'name' => $account->_name,
                        'adress_line' => $account->_address_line,
                        'adress_postal' => $account->_adresse_city,
                    ));
                    return $app->json("Tu es bien connectés mon amis !");
                }
                else
                {
                    $errors[] = 'Aucun compte n\' est existant !';
                }
            }
        }

        if(isset($errors))
        {
            $data = array(
                'type' => 'error',
                'content' => $errors
            );

            return $app->json($data);
        }
        return $app->json('prout');
    }
    //-----------VERIFICATION APRES INSCRIPTION------------//

    public function registerAction(Application $app){

        $regex_name = '#^[a-zA-Z0-9 -éèàôç]{3,80}$#';
        $regex_firstname = '#^[a-zA-Z0-9 -éèàôç]{3,60}$#';
        $regex_password = '#^.{5,50}$#';
        $regex_addressLine = '#^[a-zA-Z0-9,\' -éèàôç]{5,200}$#';
        $regex_addressCity = '#^[a-zA-Z0-9, \'-]{3,200}$#';

        if(isset($_POST['name']) && isset($_POST['firstname']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['passwordVerif']) && isset($_POST['addressLine']) && isset($_POST['addressCity']) && isset($_POST['addressPostalCode']) ){

            if(!preg_match($regex_name, $_POST['name']))
            {
                $errors['name'] = true;
                echo 'name';
            }

            if(!preg_match($regex_firstname, $_POST['firstname']))
            {
                $errors['firstname']= true;
                echo 'firstname';
            }

            if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
                $errors['email'] = true;
                echo 'email';
            }

            if(!preg_match($regex_password, $_POST['password']))
            {
                $errors['password'] = true;
                echo 'password';
            }

            if($_POST['password'] != $_POST['passwordVerif'])
            {
                $errors['passwordVerif'] = true;
                echo 'password verif';
            }

            if(!preg_match($regex_addressLine, $_POST['addressLine']))
            {
                $errors['addressLine'] = true;
                echo 'address_line';
            }

            if(!preg_match($regex_addressCity, $_POST['addressCity']))
            {
                $errors['addressCity'] = true;
                echo 'address_city';
            }

            if(!preg_match('#^[0-9]{5}$#', $_POST['addressPostalCode'])){
                $errors['addressPostalCode'] = true;
                echo 'address postal code';
            }

//-------------------------en cours----------------------//
                if(!isset($errors)){
                    $emailExist = $app['dao.account']->findByEmail($_POST['email']);
                    var_dump($emailExist);
                    if (!empty ($emailExist)) {
                        echo "email deja existant";
                        $errors[]= true;
                    }
                    //si pas d'erreur alors on injécte le compte en bdd:
                    if(!isset($errors)){
                        $account = new Account();
                        $account->setName($_POST['name']);
                        $account->setFirstname($_POST['firstname']);
                        $account->setEmail($_POST['email']);
                        $account->setPassword(password_hash($_POST['password'],PASSWORD_BCRYPT));
                        $account->setAddressLine($_POST['addressLine']);
                        $account->setAddressCity($_POST['addressCity']);
                        $account->setAddressPostalCode($_POST['addressPostalCode']);
                        $app['dao.account']->save($account);
                    }
                }
        }
        return $app['twig']->render('templates/register.html.twig');
    }


    public function connectionAction(Application $app){

            return $app['twig']->render('templates/connection.html.twig');
    }
    public function searchAction(Application $app)
    {
        if(isset($_GET['title']))
        {
            if(!preg_match('#^[a-zA-Z0-9 ]{1,150}$#', $_GET['title']))
            {
                return $app->json('Erreur');
            }
            $articles = $app['dao.article']->getArticlesByName($_GET['title']);
            if(empty($articles))
            {
                return $app->json('Erreur aucun article');
            }

            $jsonconvertlist = array();
            foreach($articles as $article)
            {
                $jsonconvertlist[] = array(
                    'id' => $article->_id,
                    'name' => $article->_title
                );
            }
            return $app->json($jsonconvertlist);

        }

        return $app->json('caca');
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
            if(empty($article)){
                $app->abort(404);
            }
            return $app['twig']->render('templates/article.html.twig', array( "article" => $article));
        }
    }
}
?>
