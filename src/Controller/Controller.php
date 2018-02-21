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



class Controller{
    public function indexAction(Application $app){

        return $app['twig']->render('templates/index.html.twig');
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
}

?>
