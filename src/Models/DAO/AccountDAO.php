<?php
namespace Models\DAO;

use \PDO;
use Silex\Application;
use Models\Domain\Account;
use Doctrine\DBAL\Connection;

class AccountDAO
{
    protected $_db;

    public function __construct($_db){
        $this->setDb($_db);
    }

    public function getDb(){
        return $this->_db;
    }

    public function setDb(Connection $newDb){
        $this->_db = $newDb;
    }

    public function isConnected(Application $app){
        if (isset($app['session']->get('user')['id'])) {
            return true;
        }
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
        $newAccount->setAddressLine($array['addressLine']);
        $newAccount->setAddressCity($array['addressCity']);
        $newAccount->setAddressPostalCode($array['addressPostalCode']);
        $newAccount->setId($array['id']);
        return $newAccount;
    }

    public function save(Account $accounts){
        $userData = array(
            'email'=>$accounts->_email,
            'password'=>$accounts->_password,
            'name'=>$accounts->_name,
            'firstname'=>$accounts->_firstname,
            'addressLine'=>$accounts->_address_line,
            'addressCity'=>$accounts->_adresse_city,
            'addressPostalCode'=>$accounts->_adress_postal_code
        );
        return $this->getDb()->insert('users', $userData);
    }

    public function findByEmail($email){
        $response = $this->getDb()->prepare('SELECT * FROM users WHERE email = ?');
        $response ->bindvalue(1, $email);
        $response->execute();
        $account = $response->fetch(PDO::FETCH_ASSOC);

        if (!empty($account)) {
            return $this->buildAccount($account);
        }
        return false;
    }
}

?>
