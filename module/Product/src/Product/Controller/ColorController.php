<?php
namespace Product\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Product\Model\Color;

class ColorController extends AbstractActionController
{
    protected $moduleId = 10;
    protected $colorTable;
    
    public function indexAction(){
        //Check if this user can access this page
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "insert")){
            $colors = $this->getColorTable()->fetchAll($logedUser["idWebsite"]);
            return array("colors"=>$colors, "permission"=>$permission);
        }else{
            return $this->redirect()->toRoute("noPermission");
        }
    }
    
    public function newAction(){
        //Check if this user can access this page
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "edit")){
            $color = new Color();
            $languages = $this->getServiceLocator()->get('language')->fetchAll();
            //If was a POST
            $message = $this->getServiceLocator()->get('productMessages');
            $request = $this->getRequest();
            if($request->isPost()){
                $color->exchangeArray($request->getPost());
                $color->website_id = $logedUser["idWebsite"];
                if($color->validation()){
                    $colorTable = $this->getColorTable()->saveColor($color);
                    $files = $request->getFiles()->toArray();
                    $temp = explode(".", $files["file"]["name"]);
                    $newName = round(microtime(true));
                    $newfilename = $newName . '.' . end($temp);
                    if (move_uploaded_file($_FILES["file"]["tmp_name"], "public/files_database/" . $logedUser["idWebsite"] . "/" . $newfilename)) {
                        /*
                        $result = $this->getImageTable()->saveImage($image);
                        $message->setCode($result);
                        // Log
                        $this->getServiceLocator()
                        ->get('systemLog')
                        ->addLog(0, $message->getMessage(), $message->getLogPriority()); */
                        die('uploadSucess');
                    } else {
                        die('uploadError');
                    }
                }else{
                    die("Falha na validação");
                }
                die(var_dump($color));
            }
            return array("color"=>$color, "permission"=>$permission, "languages"=>$languages);
        }else{
            return $this->redirect()->toRoute("noPermission");
        }
    }
    
    public function getColorTable(){
        if(!$this->colorTable){
            $sm = $this->getServiceLocator();
            $this->colorTable = $sm->get('Product\Model\ColorTable');
        }
        return $this->colorTable;
    }
    
}

