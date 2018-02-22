<?php
namespace Models\Domain;
use \Exception;

class Event
{
    protected $_id;
    protected $_title;
    protected $_desc;
    protected $_date;

    public function getId(){
        return $this->_id;
    }
    public function getTitle(){
        return $this->_title;
    }
    public function getDesc(){
        return $this->_desc;
    }
    public function getDate(){
        return $this->_date;
    }

    public function getDateFormat(){
        return date('c', $this->_date);
    }

    public function __get($variable)
    {
        if(!isset($this->$variable))
        {
            throw new Exception('Erreur lors de la lecture de la variable "'. $variable .'"');
        }
        return $this->$variable;
    }

    public function setId($newId)
    {
        if(!preg_match('#^[0-9]{1,5}$#', $newId))
        {
            throw new Exception('L\'id doit comporter un nombre entier positif.');
        }
        $this->_id = $newId;
    }
    public function setTitle($newTitle)
    {
        if(!preg_match('#^[a-zA-Z0-9\' éàèç\-_.,!?]{1,150}$#', $newTitle))
        {
            throw new Exception('Le titre doit comporter un minimum de 1 et un maximum de 150 caractéres.');
        }
        $this->_title = $newTitle;
    }
    public function setDesc($newDesc)
    {
        if(!preg_match('#^[a-zA-Z0-9\' éàèç\-_.,!?:()]{1,255}$#', $newDesc))
        {
            throw new Exception('Le contenus doit comporter un minimum de 1 caractéres et un maximum de 4000 caractéres.');
        }
        $this->_desc = $newDesc;
    }
    public function setDate($newDate){
        if(!preg_match('#^[0-9]{1,11}$#', $newDate))
        {
            throw new Exception('Le contenus doit comporter un timestamp valide.');
        }
        $this->_date = $newDate;
    }
}

?>
