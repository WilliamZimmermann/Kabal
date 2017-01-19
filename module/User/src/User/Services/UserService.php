<?php
namespace User\Services;

use Zend\Session\Container;
use User\Model\UserPermissions;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class UserService extends AbstractPlugin
{
    /**
     * Return all the session data registred for this user
     * @return array["name", "idUser", "idCompany"]|mixed[]|NULL[]|\Zend\Session\Storage\StorageInterface[]
     */
    public function getUserSession(){
        
        $session = new Container('Auth');
        $name = $session->__get('userName');
        $idUser = (int)$session->__get('idUser');
        $idCompany = (int)$session->__get('idCompany');
        $idWebsite = (int)$session->__get("websiteId");
        $websiteName = $session->__get("websiteName");
        
        return array("name"=>$name, "idUser"=>$idUser, "idCompany"=>$idCompany, "idWebsite"=>$idWebsite, "websiteName"=>$websiteName);
    }
    
    /**
     * Get all permissions object
     * @param UserPermissions $permissions
     * @param string $action (insert, edit, delete)
     */
    public function checkPermission($permissions, $action){
        $havePermission = 0;
        switch($action){
            case "insert":
                if($permissions->insertP){
                    $havePermission = 1;
                }
                break;
            case "edit":
               if($permissions->editP){
                   $havePermission = 1;
               }
               break;
            case "delete":
               if($permissions->deleteP){
                   $havePermission = 1;
               }
               break;
            default:
                break;
        }
        if($havePermission){
            return true;
        }else{
            return false;
        }
    }
}

