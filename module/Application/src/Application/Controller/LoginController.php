<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

class LoginController extends AbstractActionController
{
    public function indexAction()
    {
        $this->layout("layout/login_layout");
        
        //Get the post information
        $request = $this->getRequest();
        if ($request->isPost()) {
            $logService = $this->getServiceLocator()->get("systemLog");
            $data = $request->getPost();
            $user = strip_tags($data["user"]);
            $password = md5($data["password"]);
            if($user && $password){
                $usersDB = $this->getServiceLocator()->get("userDb");
                $login = $usersDB->login($user, $password);
                if($login){
                    $logService->addLog(0, "The user ".$login->idUser." made login.", 6);
                    $logService->addLog($login->idUser, "User made login.", 6);
                    $session = new Container('Auth');
                    $session->adm = true;
                    $session->__set("userName", $login->name);
                    $session->__set("idUser", $login->idUser);
                    $session->__set("idCompany", $login->company_id);
                    $session->__set("websiteName", 0);
                    $session->__set("websiteId", 0);
                    
                    //Check if remember me was activated
                    if ($request->getPost('rememberme') == 1 ) {
                        $this->setRememberMe(1);
                        //set storage again
                        $this->getAuthService()->setStorage($this->getSessionStorage());
                    }
                    
                    return $this->redirect()->toRoute('home');
                }else{
                    $message["flag"] = 2;
                    $message["message"] = "Falha no login, dados incorretos. Verifique se o email ".$user." ou a senha estão corretos.";
                    $logService->addLog(0, $message, 6);
                }
            }else{
                $message["flag"] = 2;
                $message["message"] = "Falha no login, tente novamente.";
                $logService->addLog(0, $message, 5);
            }
        }
        return new ViewModel();
    }
    
    public function logOutAction()
    {
        $logService = $this->getServiceLocator()->get("systemLog");
        $session = new Container('Auth');
        $logService->addLog($session->__get('idUser'), "User ".$session->__get('idUser')." made LOGOUT.", 6);
        $session->getManager()->getStorage()->clear();
        return $this->redirect()->toRoute('login');
    }
    
    /**
     * TODO
     * @param number $rememberMe
     * @param number $time
     */
    public function setRememberMe($rememberMe = 0, $time = 1209600)
    {
        /**
        if ($rememberMe == 1) {
            $this->session->getManager()->rememberMe($time);
        }
        **/
    }
}
