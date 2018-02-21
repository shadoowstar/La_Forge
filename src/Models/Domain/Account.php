<?php

namespace Models\Domain;    // Espace de nom de la classe Account

use \Exception; // Obligatoire pour pouvoir utiliser throw new Exception(); dans ce fichier

class Account{
    
    /**
     * Attributs des comptes
     */
    protected $_id;
    protected $_email;
    protected $_password;
    protected $_name;
    protected $_firstname;
    
    /**
     * Getters des Attributs des comptes
     */
    public function getId(){
        return $this->_id;
    }
    
    public function getEmail(){
        return $this->_email;
    }
    
    public function getPassword(){
        return $this->_password;
    }
    
    public function getName(){
        return $this->_name;
    }
    
    public function getFirstname(){
        return $this->_firstname;
    }
    
    /**
     * Setters des Attributs des comptes
     */
    public function setId($newId){
        if(!preg_match('#^[0-9]{1,11}$#', $newId)){
            throw new Exception('id doit être un entier positif');
        } else {
            $this->_id = $newId;
        }
    }
    
    public function setEmail($newEmail){
        if(!filter_var($newEmail, FILTER_VALIDATE_EMAIL)){
            throw new Exception('email doit être un email valide');
        } else {
            $this->_email = $newEmail;
        }
    }
    
    public function setPassword($newPassword){
        if(!preg_match('#^.{60}$#', $newPassword)){
            throw new Exception('password doit être un hash BCRYPT valide');
        } else {
            $this->_password = $newPassword;
        }
    }
    
    public function setName($newName){
        if(!preg_match('#^.{2,25}$#', $newName)){
            throw new Exception('name doit être une string de 2 à 25 caractères');
        } else {
            $this->_name = $newName;
        }
    }
    
    public function setFirstname($newFirstname){
        if(!preg_match('#^.{2,25}$#', $newFirstname)){
            throw new Exception('firstname doit être une string de 2 à 25 caractères');
        } else {
            $this->_firstname = $newFirstname;
        }
    }
}