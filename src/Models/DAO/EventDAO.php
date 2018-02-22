<?php
namespace Models\DAO;

use \PDO;
use Silex\Application;
use Doctrine\DBAL\Connection;
use Models\Domain\Event;

/**
 * Manager DAO de la classe Article (design pattern singleton pour éviter de l'instancier plus d'une fois)
 */
class EventDAO{
    /**
     * Contient une instance de connexion Doctrine
     */
    protected $_db;
    /**
     * Contient une instance de app
     */
    protected $_app;
    /**
     * Contient l'instance de accountDAO (singleton)
     */
    private static $_instance;
    /**
     * Constructeur privé pour empêcher l'instanciation (singleton)
     */
    private function __construct($app){
        $this->setDb($app['db']);
        $this->setApp($app);
    }
    /**
     * Clonage privé pour empêcher la copie de l'instance (singleton)
     */
    private function __clone(){}
    /**
     * Méthode de récupération de l'instance unique de AccountDAO (singleton)
     */
    public static function getInstance($db){
        // Stockage de l'unique instance de AccountDAO dans l'attribut $_instance si elle n'existe pas déjà
        if(is_null(self::$_instance)){
            self::$_instance = new EventDAO($db);
        }
        // return l'instance unique de AccountDAO
        return self::$_instance;
    }
    /**
     * Getter de $_db
     */
    public function getDb(){
        return $this->_db;
    }
    /**
     * Setter de $_db
     */
    public function setDb(Connection $newDb){
        $this->_db = $newDb;
    }
    /**
     * Getter de $_app
     */
    public function getApp(){
        return $this->_app;
    }
    /**
     * Setter de $_app
     */
    public function setApp(Application $newApp){
        $this->_app = $newApp;
    }

    private function buildEventDomain(array $data){
        $event = new Event();

        $event->setId($data['id']);
        $event->setTitle($data['title']);
        $event->setDesc($data['event_desc']);
        $event->setDate($data['start']);

        return $event;
    }
    public function save(Event $event){

        $eventData = array(
            'start'=>$event->getDate(),
            'title'=>$event->getTitle(),
            'event_desc'=>$event->getDesc()
        );

        if($event->getId() == null){
            $this->getDb()->insert('calendar', $eventData);
        } else {
            $this->getDb()->update('calendar', $eventData, array('id' => $event->getId()));
        }
    }
    public function getEvents(){
        $response = $this->getDb()->query('SELECT * FROM calendar');
        $response->execute();
        while($data = $response->fetch(PDO::FETCH_ASSOC)){
            $events[] = $this->buildEventDomain($data);
        }
        return $events;
    }
}
