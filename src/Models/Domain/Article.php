<?php

namespace Models\Domain;
use \Exception;

class Article
{
    protected $_id;
    protected $_title;
    protected $_content;
    protected $_img_url;
    protected $_post_date;

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
        if(!preg_match('#^[a-zA-Z0-9\' éàèç\-_.,!?]{10,150}$#', $newTitle))
        {
            throw new Exception('Le titre doit comporter un minimum de 10 et un maximum de 150 caractéres.');
        }
        $this->_title = $newTitle;
    }
    public function setContent($newContent)
    {
        if(!preg_match('#^[a-zA-Z0-9\' éàèç\-_.,!?:()]{20,4000}$#', $newContent))
        {
            throw new Exception('Le contenus doit comporter un minimum de 20 caractéres et un maximum de 4000 caractéres.');
        }
        $this->_content = $newContent;
    }
    public function setImgUrl($newImgUrl)
    {
        if(!filter_var($newImgUrl, FILTER_VALIDATE_URL))
        {
            throw new Exception('Url de l\'image n\'est pas un url valide !');
        }
        $this->_img_url = $newImgUrl;
    }
    public function setPostDate($newPostDate)
    {
        if(!filter_var($newPostDate, FILTER_VALIDATE_INT))
        {
            throw new Exception('La date de l\'article est non valide !');
        }
        $this->_post_date = $newPostDate;
    }
}