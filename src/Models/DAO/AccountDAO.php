<?php
namespace Models\DAO;

use \PDO;
use Models\Domain\Account;
use Doctrine\DBAL\Connection;

class AccountDAO{
    protected $_db;

    public function __construct($db){
        $this->setDb($db);
    }

    public function getDb(){
        return $this->_db;
    }

    public function setDb(Connection $newDb){
        $this->_db = $newDb;
    }

    public function findAll(){
        $response = $this->getDb()->query('SELECT * FROM accounts');
        $accounts = $response->fetchAll(PDO::FETCH_ASSOC);

        $accountsList = array();

        foreach ($accounts as $row) {
            $accountsList[] = $this->buildAccount($row);
        }

        return $accountsList;
    }

    protected function buildAccount($array)
    {
        $newAccount = new Account();
        $newAccount->setEmail($array['email']);
        $newAccount->setPassword($array['password']);
        $newAccount->setName($array['name']);
        $newAccount->setFirstname($array['firstname']);
        $newAccount->setId($array['id']);
        return $newAccount;
    }
    public function save(Account $accounts){
        $userData = array(
            'email'=>$accounts->getEmail(),
            'password'=>$accounts->getPassword(),
            'name'=>$accounts->getName(),
            'firstname'=>$accounts->getFirstname()
        );
        return $this->getDb()->insert('accounts', $userData);
    }
    // public function verif($email){
    //     $response =$this->getDb()->prepare("SELECT * FROM users WHERE email = ?");
    //     $response->bindValue(1, $email);
    //     $response->execute();
    //     $affectedRows = $response->rowCount();
    //     $response->closeCursor();
    //
    //     if ($affectedRows > 0) {
    //         return true;
    //     }else {
    //         return false;
    //     }
    // }

}

?>
