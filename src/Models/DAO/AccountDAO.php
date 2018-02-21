<?php
namespace Models\DAO;

use \PDO;
use Models\Domain\Account;
use Doctrine\DBAL\Connection;

class AccountDAO{
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
            'email'=>$accounts->getEmail(),
            'password'=>$accounts->getPassword(),
            'name'=>$accounts->getName(),
            'firstname'=>$accounts->getFirstname(),
            'addressLine'=>$accounts->getAddressLine(),
            'addressCity'=>$accounts->getAddressCity(),
            'addressPostalCode'=>$accounts->getAddressPostalCode(),
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

    public function passwordReinitAction(Application $app){

        // Si déjà connecté, accès interdit
        if($app['session']->get('users') !== null){
            $app->abort(403);
        }

        if(isset($_POST['email'])){

            if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
                $errors[] = 'Email invalide !';
            }

            if(!isset($errors)){
                $userData = $app['dao.account']->find($_POST['email']);
                if(empty($userData)){
                    $errors[] = 'Ce compte n\'existe pas';
                } else {

                    $lastReinitProcess = $app['dao.account']->getLastPasswordReinitProcess($userData);

                    if(!empty($lastReinitProcess) && $lastReinitProcess['status'] === '0' && $lastReinitProcess['expiration_date'] >= time()){
                        $errors[] = 'Il y a déjà une procédure de réinitialisation du mot de passe sur ce compte !';
                    } else {
                        $token = $app['dao.account']->createToken();
                        $app['dao.account']->createNewPasswordReinitProcess($userData, $app['params.passwordReinitProcessTimeout'], $token);

                        $link = $app['url_generator']->generate('password-reinit-complete', array(
                            'id' => $userData->getId(),
                            'token' => $token
                        ), $app['url_generator']::ABSOLUTE_URL);
                        $message = (new Swift_Message('Réinitialisation de mot de passe de votre compte sur WebXS.fr'))
                        ->setFrom(['contact@localhost.fr'])
                        ->setTo([$_POST['email']])
                        ->setBody('Bonjour ' . $userData->getFirstname() . '!
                            Une procédure de réinitialisation de mot de passe a été demandé pour votre compte WebXS.
                            Si c\'est vous qui l\'avez demandé, veuillez vous rendre sur le lien suivant, sinon ne faites rien :
                            '. $link . '

                            Cordialement,

                            L\'Équipe WebXS')
                            ->addPart($app['twig']->render('mails/password-reinit.html.twig', array('firstname' => $userData->getFirstname(), 'link' => $link) ), 'text/html');

                        $app['mailer']->send($message);

                        return $app['twig']->render('templates/password-reinit.html.twig', array('success' => 'Email de réinitialisation envoyé !'));
                    }

                }
            }
            if(isset($errors)){
                return $app['twig']->render('templates/password-reinit.html.twig', array('errors' => $errors));
            }
        }
    }


        public function passwordReinitCompleteAction(Application $app, $id, $token){

            $userData = $app['dao.account']->findById($id);

            if(empty($userData)){
                $accessError = 'Lien invalide !';
            } else{

                $lastProcess = $app['dao.account']->getLastPasswordReinitprocess($userData);
                if(empty($lastProcess) || $lastProcess['status'] === '1' || $lastProcess['token'] != $token){
                    $accessError = 'Lien invalide !';
                } else {

                    if($lastProcess['expiration_date'] < time()){
                        $accessError = 'Lien expiré, veuillez recommencer la procédure de réinitialisation de mot de passe !';
                    } else {

                        if(isset($_POST['password']) AND isset($_POST['passwordConfirm'])){

                            if (!preg_match('#^.{5,50}$#', $_POST['password'])) {
                                $errors[] = 'Mot de passe doit contenir entre 5 et 50 caractères';
                            }

                            if ($_POST['password'] != $_POST['passwordConfirm']) {
                                $errors[] = 'Confirmation du mot de passe non concordante';
                            }

                            if(!isset($errors)){

                                $userData->setPassword(password_hash($_POST['password'], PASSWORD_BCRYPT));
                                $app['dao.account']->save($userData);
                                $app['dao.account']->finishPasswordReinitPasswordProcess($userData);

                                return $app['twig']->render('templates/password-reinit-complete.html.twig', array('success' => 'Mot de passe changé avec succès !'));

                            }

                        }

                    }

                }
            }

            if(isset($accessError)){
                return $app['twig']->render('templates/message.html.twig', array('title' => 'Erreur', 'type' => 'danger', 'message' => $accessError));
            } elseif(isset($errors)){
                return $app['twig']->render('templates/password-reinit-complete.html.twig', array('errors' => $errors));
            }

            return $app['twig']->render('templates/password-reinit-complete.html.twig');

        //return $app['twig']->render('templates/password-reinit.html.twig');
    }

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


?>
