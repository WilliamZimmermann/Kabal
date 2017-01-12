<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ImagesDatabase for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace ImagesDatabase\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use ImagesDatabase\Model\Image;

class ImagesDatabaseController extends AbstractActionController
{

    protected $imageTable;

    protected $moduleId = 4;

    public function indexAction()
    {
        // Check if this user can access this page
        $logedUser = $this->getServiceLocator()
            ->get('user')
            ->getUserSession();
        $permission = $this->getServiceLocator()
            ->get('permissions')
            ->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if ($this->getServiceLocator()
            ->get('user')
            ->checkPermission($permission, "insert")) {
            
            if ($this->getServiceLocator()
                ->get('user')
                ->checkPermission($permission, "edit")) {
                $request = $this->getRequest();
                if ($request->isPost()) {
                    $message = $this->getServiceLocator()->get('systemMessages');
                    $data = $request->getPost();
                    $image = new Image();
                    $image->exchangeArray($data);
                    $result = $this->getImageTable()->saveStandardInfo($image);
                    $message->setCode($result);
                    // Log
                    $this->getServiceLocator()
                        ->get('systemLog')
                        ->addLog(0, $message->getMessage(), $message->getLogPriority());
                }
            }
            
            $images = $this->getImageTable()->fetchAll($logedUser["idWebsite"]);
            
            return array(
                "images" => $images,
                "idWebsite" => $logedUser["idWebsite"],
                "permission"=>$permission
            );
        } else {
            return $this->redirect()->toRoute("noPermission");
        }
    }

    public function newAction()
    {
        // Check if this user can access this page
        $logedUser = $this->getServiceLocator()
            ->get('user')
            ->getUserSession();
        $permission = $this->getServiceLocator()
            ->get('permissions')
            ->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if ($this->getServiceLocator()
            ->get('user')
            ->checkPermission($permission, "edit")) {
            
            return array();
        } else {
            return $this->redirect()->toRoute("noPermission");
        }
    }

    public function uploadAction()
    {
        // Check if this user can access this page
        $logedUser = $this->getServiceLocator()
            ->get('user')
            ->getUserSession();
        $permission = $this->getServiceLocator()
            ->get('permissions')
            ->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if ($this->getServiceLocator()
            ->get('user')
            ->checkPermission($permission, "edit")) {
            $request = $this->getRequest();
            if ($request->isPost()) {
                // Get Message Service
                $message = $this->getServiceLocator()->get('systemMessages');
                
                $files = $request->getFiles()->toArray();
                $temp = explode(".", $files["file"]["name"]);
                $newName = round(microtime(true));
                $newfilename = $newName . '.' . end($temp);
                $image = new Image();
                $image->exchangeArray(array(
                    "website_id" => $logedUser["idWebsite"],
                    "name" => $newName,
                    "extension" => end($temp)
                ));
                if ($image->validation()) {
                    if (move_uploaded_file($_FILES["file"]["tmp_name"], "public/files_database/" . $logedUser["idWebsite"] . "/" . $newfilename)) {
                        $result = $this->getImageTable()->saveImage($image);
                        $message->setCode($result);
                        // Log
                        $this->getServiceLocator()
                            ->get('systemLog')
                            ->addLog(0, $message->getMessage(), $message->getLogPriority());
                        die('uploadSucess');
                    } else {
                        die('uploadError');
                    }
                } else {
                    die('Invalid extension');
                }
            }
            die("forbiden");
        } else {
            die("forbiden - without permission");
        }
    }

    public function imagesAction()
    {
        // Check if this user can access this page
        $logedUser = $this->getServiceLocator()
            ->get('user')
            ->getUserSession();
        $permission = $this->getServiceLocator()
            ->get('permissions')
            ->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if ($this->getServiceLocator()
            ->get('user')
            ->checkPermission($permission, "edit")) {
            
                
            if ($this->getServiceLocator()
                ->get('user')
                ->checkPermission($permission, "edit") || $logedUser["idCompany"] == 1) {
                $request = $this->getRequest();
            }
            
            $images = $this->getImageTable()->fetchAll($logedUser["idWebsite"]);
            $this->layout("layout/images.phtml");
            return array(
                "images" => $images,
                "idWebsite" => $logedUser["idWebsite"]
            );
        } else {
            return $this->redirect()->toRoute("noPermission");
        }
    }

    public function deleteAction()
    {
        // Check if this user can access this page
        $logedUser = $this->getServiceLocator()
            ->get('user')
            ->getUserSession();
        $permission = $this->getServiceLocator()
            ->get('permissions')
            ->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if ($this->getServiceLocator()
            ->get('user')
            ->checkPermission($permission, "delete")) {
            $id = (int) $this->params()->fromRoute('id', 0);
            if (! $id) { // If there is no ID
                $this->getServiceLocator()
                    ->get('systemLog')
                    ->addLog(0, "Image " . $id . " not found to delete.", 5);
                return $this->redirect()->toRoute('company');
            }
            
            $message = $this->getServiceLocator()->get('systemMessages');
            
            // First, get image data
            $imageData = $this->getImageTable()->getImage($id);
            if ($imageData->website_id == $logedUser["idWebsite"]) { // Check if this image website is the same that user are loged
                $result = $this->getImageTable()->deleteImage($id);
                if ($result == "DBIMAGE006") {
                    // Remove image from the directory
                    unlink("public/files_database/" . $logedUser["idWebsite"] . "/" . $imageData->name . "." . $imageData->extension);
                }
                $message->setCode($result);
                
                // Save log
                $this->getServiceLocator()
                    ->get('systemLog')
                    ->addLog(0, $message->getMessage(), $message->getLogPriority());
                
                return $this->redirect()->toRoute("images-database");
            } else {
                return $this->redirect()->toRoute("noPermission");
            }
        } else {
            return $this->redirect()->toRoute("noPermission");
        }
    }

    public function getImageTable()
    {
        if (! $this->imageTable) {
            $sm = $this->getServiceLocator();
            $this->imageTable = $sm->get('ImagesDatabase\Model\ImageTable');
        }
        return $this->imageTable;
    }
}
