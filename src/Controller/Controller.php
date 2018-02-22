<?php
/*
le namespace doit ere identiqueau chemin d'acces de ce fichier dans src/

le nom de la classe doit etre strictement identique au nom du fichier = avec
'controller.php' la classe doit s'appeler 'controller'

*/
namespace Controller;

use Silex\Application;
use Models\Domain\Account;
use Models\Domain\Event;
use Models\Domain\Article;



class Controller{
    public function indexAction(Application $app){
        dump($app['session']->get('user'));
        return $app['twig']->render('templates/index.html.twig', array( "articles" => $app['dao.article']->findArticleByLimit($app['index.articles'])));
    }

    public function contactAction(Application $app){

        return $app['twig']->render('templates/contact.html.twig');
    }

    public function signinAction(Application $app){
        if(isset($_POST['email']) AND isset($_POST['password'])){



            if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
                $errors['email'] = true;
            }

            if(!preg_match('#^.{4,200}$#', $_POST['password'])){
                $errors['password'] = true;
            }

            if(!isset($errors)){

                $account = $app['dao.account']->findByEmail($_POST['email']);

                if(empty($userInfos)){
                    $errors['notExist'] = true;
                }
                if(password_verify($_POST['password'], $account->getPassword())){

                        $app['session']->set('user', array(
                            'id'=>$account->_id,
                            'email'=>$account->_email,
                            'password'=>$account->_password,
                            'name'=>$account->_name,
                            'firstname'=>$account->_firstname,
                            'adresse_line'=>$account->_address_line,
                            'address_city'=>$account->_adresse_city,
                            'address_postal_code'=>$account->_adress_postal_code,
                        ));
                        $sessionParams = array(
                            'ip' => $_SERVER['REMOTE_ADDR'],
                            'lastRefresh' => time()
                        );
                        $app['session']->set('accountSession', $sessionParams);

                        return $app->json(array(
                            'success' => true
                        ));
                }else {
                    $errors['invalidPassword'] = true;
                }

            }
        }
        if(isset($errors)){
            return $app->json(array('success' => false, 'errors' => $errors));
        }
        return $app->json(array('errors' => 'Incorrect post data'));
    }
    //-----------VERIFICATION APRES INSCRIPTION------------//

    public function registerAction(Application $app){

        return $app['twig']->render('templates/register.html.twig');
    }

    public function registerSubmitAction(Application $app){

        $regex_name = '#^[a-zA-Z0-9 -éèàôç]{3,80}$#';
        $regex_firstname = '#^[a-zA-Z0-9 -éèàôç]{3,60}$#';
        $regex_password = '#^.{5,50}$#';
        $regex_addressLine = '#^[a-zA-Z0-9,\' -éèàôç]{5,200}$#';
        $regex_addressCity = '#^[a-zA-Z0-9, \'-]{3,200}$#';

        if(isset($_POST['name']) && isset($_POST['firstname']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['passwordVerif']) && isset($_POST['addressLine']) && isset($_POST['addressCity']) && isset($_POST['addressPostalCode']) ){

            if(!preg_match($regex_name, $_POST['name']))
            {
                $errors['name'] = true;

            }

            if(!preg_match($regex_firstname, $_POST['firstname']))
            {
                $errors['firstname']= true;

            }

            if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
                $errors['email'] = true;

            }

            if(!preg_match($regex_password, $_POST['password']))
            {
                $errors['password'] = true;

            }

            if($_POST['password'] != $_POST['passwordVerif'])
            {
                $errors['passwordVerif'] = true;
            }

            if(!preg_match($regex_addressLine, $_POST['addressLine']))
            {
                $errors['addressLine'] = true;

            }

            if(!preg_match($regex_addressCity, $_POST['addressCity']))
            {
                $errors['addressCity'] = true;

            }

            if(!preg_match('#^[0-9]{5}$#', $_POST['addressPostalCode'])){
                $errors['addressPostalCode'] = true;
            }
                if(!isset($errors)){
                    $emailExist = $app['dao.account']->findByEmail($_POST['email']);
                    if (!empty ($emailExist)) {
                        $errors['alreadyExists'] = true;
                    }

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
                        return $app->json(array(
                            'success' => true
                        ));
                    }
                }
        }
        if($app['dao.account']->isConnected($app))
        {
            $app->abort(403);
            return $app['twig']->render('templates/403.html.twig');
        }
        if(isset($errors)){
                return $app->json(array(
                    'success' => false,
                    'errors' => $errors
                ));
            }
    }

    public function connectionAction(Application $app){
        if($app['dao.account']->isConnected($app))
        {
            $app->abort(403);
        }
        return $app['twig']->render('templates/connection.html.twig');
    }

    public function memberAction(Application $app){

        return $app['twig']->render('templates/member-area.html.twig');
    }
    public function eventAction(Application $app ){
        return $app['twig']->render('templates/event.html.twig');
    }

    public function calendarAdminAction(Application $app){
        // if($app['session']->get('user') === null){
        //     $app->abort(403);
        // }
        return $app['twig']->render('templates/calendarAdmin.html.twig');
    }

    public function eventSubmitAction(Application $app){
        // if($app['session']->get('user') === null){
        //     $app->abort(403);
        // }
        if(isset($_POST['eventTitle']) && isset($_POST['eventDate']) && isset($_POST['eventDesc'])){
            // Bloc vérifs champs
            if (!preg_match('#^[a-záàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ \-\'_]{1,100}$#i', $_POST['eventTitle'])) {
                $errors['title'] = true;
            }

            if (!preg_match('#^((((19|[2-9]\d)\d{2})\-(0[13578]|1[02])\-(0[1-9]|[12]\d|3[01]))|(((19|[2-9]\d)\d{2})\-(0[13456789]|1[012])\-(0[1-9]|[12]\d|30))|(((19|[2-9]\d)\d{2})\-02\-(0[1-9]|1\d|2[0-8]))|(((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))\-02\-29))$#',$_POST['eventDate'])) {
                $errors['date'] = true;
            }
            if (!preg_match('#^[a-záàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ \-\'_]{1,255}$#i', $_POST['eventDesc'])) {
                $errors['desc'] = true;
            }
            if(!isset($errors)){
                $event = new Event();
                $event->setTitle($_POST['eventTitle']);
                $event->setDesc($_POST['eventDesc']);
                $event->setDate(strtotime($_POST['eventDate']));

                // Création du compte en BDD
                $app['dao.event']->save($event);
                return $app->json(array('success' => true));
            }
        }
        if(isset($errors)){
            return $app->json(array('success' => false, 'errors' => $errors));
        }
        return $app->json(array('errors' => 'Incorrect post data'));
    }
    public function getEventAction(Application $app){
        $events = $app['dao.event']->getEvents();

        foreach($events as $event){
            $datas[] = array(
                'title' => $event->getTitle(),
                'event_desc' => $event->getDesc(),
                'start' => $event->getDateFormat()
            );
        }
        return $app->json(array('events' => $datas));
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

    public function logoutAction(Application $app){
        if ($app['dao.account']->isConnected($app)) {
            $app['session']->remove('user');
        }
        return $app->redirect($app['url_generator']->generate('home'));
    }
}
?>
