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
    protected $_address_line;
    protected $_adresse_city;
    protected $_adress_postal_code;

    /**
     * Getters des Attributs des comptes
     */
    public function __get($variable)
    {
        if(!isset($this->$variable))
        {
            throw new Exception('Erreur lors de la lecture de la variable "'. $variable .'"');
        }
        return $this->$variable;
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
        if(!preg_match('#^.{3,80}$#', $newName)){
            throw new Exception('nom doit être une string de 3 à 80 caractères');
        } else {
            $this->_name = $newName;
        }
    }

    public function setFirstname($newFirstname){
        if(!preg_match('#^.{3,60}$#', $newFirstname)){
            throw new Exception('prenom doit être une string de 3 à 60 caractères');
        } else {
            $this->_firstname = $newFirstname;
        }
    }

    public function setAddressLine($_adresseLine){
        if(!preg_match('#^[a-zA-Z0-9,\' -éèàôç]{5,200}$#', $_adresseLine)){
            throw new Exception('Adresse doit être une string de 5 à 200 caractères');
        } else {
            $this->_address_line = $_adresseLine;
        }
    }

    public function setAddressCity($_adresseCity){
        if(!preg_match('#^[a-zA-Z0-9, \'-]{3,200}$#', $_adresseCity)){
            throw new Exception('la ville doit être de 3 à 200 caractères');
        } else {
            $this->_adresse_city = $_adresseCity;
        }
    }

    public function setAddressPostalCode($_adressPostalCode){
        if(!preg_match('#^.{5}$#', $_adressPostalCode)){
            throw new Exception('le code postal doit être une string de 5 caractere');
        } else {
            $this->_adress_postal_code = $_adressPostalCode;
        }
    }

}
