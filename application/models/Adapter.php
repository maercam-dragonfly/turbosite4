<?php
/**
 * Description of Adapter
 *
 * @author Marcin
 */
class Application_Model_Adapter implements Zend_Auth_Adapter_Interface{
    public $login;
    public $pass;
    public $role;
    
    
    public function authenticate() {
            
            $users = Doctrine_Query::create()
                    ->select('u.login, u.password, r.name')
                    ->from('Application_Model_User u')
                    ->leftJoin('u.Role r')
                    ->where('u.login = ?', $this->login)
                    ->fetchArray();
                    
                  //echo $users->getSqlQuery();
            
            
            //exit();
            if(count($users) != 1 || $users[0]['password'] != sha1($this->pass)){
                $result = new Zend_Auth_Result(Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, null);
            }
            else{
                $this->role = $users[0]['Role']['name'];
                $result = new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $this);
	    } 
	    return $result;
        }
}


