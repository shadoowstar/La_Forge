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
        // echo $app->['url_generator']->generate('contact');//lien relatif
        // echo $app->['url_generator']->generate('contact', array(),$app['url_generator']::ABSOLUTE_URL);//lien absolu
        $app['session']->set('name','Jean');

        return $app['twig']->render('templates/index.html.twig');
    }
    public function contactAction(Application $app){
        return $app['twig']->render('templates/contact.html.twig');
    }
    public function profilAction(Application $app){
        $accounts = $app['dao.account']->findAll();
        return $app['twig']->render('templates/profil.html.twig', array('accounts' => $accounts));
    }

    public function registerAction(Application $app){


        $regexPW = '#^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{3,50}$#';
        $regex = '#^.{3,25}$#';

        if (isset($_POST['email'])
            && isset($_POST['password'])
            && isset($_POST['valpassword'])
            && isset($_POST['firstname'])
            && isset($_POST['name'])
        ){

            
            if (!filter_var($mail,FILTER_VALIDATE_EMAIL)){
                $errors[] ='email non valide';
            }
            if (!preg_match($regex , $password)){
                $errors[] = 'Mots De Pass non valide';
            }
            if($_POST['password'] != $_POST['valpassword']){
                $errors[] = 'la verrification a echouer';
            }
            if(!preg_match($regex, $firstname)){
                $errors[] = 'Prenom non valide';
            }
            if(!preg_match($regex , $name)){
                $errors[] = 'Nom non valide';
            }

            // if (!isset($errors)){
            //     $verif=$app['dao.account']->verif(
            //         $_POST['email']
            //     );
            //     if ($verif = true) {
            //         $errors[] = 'l\'email existe deja';
            //     }
            // }

            if (!isset($errors)){
                $accounts = new Account();
                $accounts->setEmail($_POST['email']);
                $accounts->setName( $_POST['name']);
                $accounts->setFirstname($_POST['firstname']);
                $accounts->setPassword(password_hash( $_POST['password'], PASSWORD_BCRYPT));

                $status=$app['dao.account']->save($accounts);
                if (!$status) {
                    $errors = 'un probleme est survenue';
                }else {
                    return $app['twig']->render('templates/register.html.twig', array('success' => 'compte crée'));
                }
            }
            if (isset($errors)) {
                return $app['twig']->render('templates/register.html.twig', array('errors' => $errors));
            }
        }

        return $app['twig']->render('templates/register.html.twig');

    }
    public function getAccountListAction(Application $app)
    {
        $accountList= $app['dao.account']->findAll();
        $arrayList = array();
        foreach ($accountList as $account) {
            $arrayList[] = array(
                'email' => $account->getEmail(),
                'password' => $account->getPassword(),
                'name' => $account->getName(),
                'firstname' => $account->getFirstname(),
                'id' => $account->getId()
            );
        }

        return $app->json($arrayList);
    }

    // public function saluerAction($name,$age)
    // {
    //     return' Hello '.$name.' '.$age.' ans :D ';
    // }


}

?>
