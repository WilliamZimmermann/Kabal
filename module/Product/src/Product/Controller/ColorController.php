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
            // Page number
            $currentPage = $this->params()->fromQuery('page');
            // Number of records per page
            $countPerPage = "30";
            $colors = $this->getColorTable()->fetchAll($logedUser["idWebsite"], $currentPage, $countPerPage);
            return array("colors"=>$colors, "permission"=>$permission, "idWebsite"=>$logedUser["idWebsite"]);
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
                    $idColor = $this->getColorTable()->saveColor($color);
                    $files = $request->getFiles()->toArray();
                    if($files["image"]["name"]!=null && $idColor!="PCOL002"){
                        $temp = explode(".", $files["image"]["name"]);
                        $newName = $idColor;
                        $newfilename = $newName . '.' . end($temp);
                        if (move_uploaded_file($_FILES["image"]["tmp_name"], "public/files_database/" . $logedUser["idWebsite"] . "/" . $newfilename)) {
                            $result = $this->getColorTable()->updateImageName($idColor, $newfilename);
                        }else {
                            if($idColor=="PCOL002"){
                                $result = "PCOL002";
                            }else{
                                $result = "PCOL005";
                            }
                        }
                    }else{
                        $result = "PCOL001";
                    }
                }else{
                    $result = "PCOL003";
                }
                $message->setCode($result);

                $this->getServiceLocator()
                ->get('systemLog')
                ->addLog(0, $message->getMessage(), $message->getLogPriority());
            }
            return array("color"=>$color, "permission"=>$permission, "languages"=>$languages, "message"=>$message->getMessage());
        }else{
            return $this->redirect()->toRoute("noPermission");
        }
    }
    
    public function editAction(){
        //Check if this user can access this page
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "edit")){
            $color = new Color();
            //Get the Language and product id
            $id = (int) $this->params()->fromRoute('id', 0);
            
            $languages = $this->getServiceLocator()->get('language')->fetchAll();
            //If was a POST
            $message = $this->getServiceLocator()->get('productMessages');
            $request = $this->getRequest();
            if($request->isPost()){
                $color->exchangeArray($request->getPost());
                $color->website_id = $logedUser["idWebsite"];
                if($color->validation()){
                    $color->idColor = $id;
                    $save = $this->getColorTable()->saveColor($color);
                    $files = $request->getFiles()->toArray();
                    if($files["image"]["name"]!=null && $save=="PCOL004"){
                        //Then check if there was another file before
                        $colorData = $this->getColorTable()->getColor($id);
                        if($colorData->image){
                            unlink("public/files_database/".$logedUser["idWebsite"]."/".$colorData->image);
                            $this->getColorTable()->updateImageName($id, "");
                        }
                        $temp = explode(".", $files["image"]["name"]);
                        $newName = $id;
                        $newfilename = $newName . '.' . end($temp);
                        if (move_uploaded_file($_FILES["image"]["tmp_name"], "public/files_database/" . $logedUser["idWebsite"] . "/" . $newfilename)) {
                            $result = $this->getColorTable()->updateImageName($id, $newfilename);
                        }
                        $result = $save;
                    }else{
                        $result = $save;
                    }
                }else{
                    $result = "PCOL003";
                }
                $message->setCode($result);
    
                $this->getServiceLocator()
                ->get('systemLog')
                ->addLog(0, $message->getMessage(), $message->getLogPriority());
            }
            $color = $this->getColorTable()->getColor($id);
            return array("color"=>$color, "permission"=>$permission, "languages"=>$languages, "message"=>$message->getMessage(), "idWebsite"=>$logedUser["idWebsite"]);
        }else{
            return $this->redirect()->toRoute("noPermission");
        }
    }
    
    public function deleteColorImageAction(){
        //Check if this user can access this page
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "edit")){
            //Get color id
            $id = (int) $this->params()->fromRoute('id', 0);
            $colorData = $this->getColorTable()->getColor($id);
            unlink("public/files_database/".$logedUser["idWebsite"]."/".$colorData->image);
            $this->getColorTable()->updateImageName($id, "");
            die("success");
        }else{
            die("forbiden");
        }
    }
    
    public function deleteColorAction(){
        //Check if this user can access this page
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "edit")){
            //If was a POST
            $message = $this->getServiceLocator()->get('productMessages');
            $request = $this->getRequest();
            
            //Get color id
            $id = (int) $this->params()->fromRoute('id', 0);
            $colorData = $this->getColorTable()->getColor($id);
            if($colorData->image){
                unlink("public/files_database/".$logedUser["idWebsite"]."/".$colorData->image);
            }
            $result = $this->getColorTable()->deleteColor($id);
            $message->setCode($result);
            
            $this->getServiceLocator()
            ->get('systemLog')
            ->addLog(0, $message->getMessage(), $message->getLogPriority());
            
            $this->redirect()->toRoute("product/color");
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

