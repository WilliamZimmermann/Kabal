<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Page for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Page\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Page\Model\Page;
use Page\Model\PageLanguage;
use ImagesDatabase\Model\ModuleImage;

class PageController extends AbstractActionController
{
    protected $moduleId = 5;
    protected $pageTable;
    protected $pageLanguageTable;
    
    public function indexAction()
    {
        //Check if this user can access this page
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "insert") || $this->getServiceLocator()->get('user')->checkPermission($permission, "edit") || $this->getServiceLocator()->get('user')->checkPermission($permission, "delete") || $logedUser["idCompany"]==1){
            $pages = $this->getPageTable()->fetchAll($logedUser["idWebsite"]);
            return array("pages"=>$pages);
        }else{
            return $this->redirect()->toRoute("noPermission");
        }
    }

    /**
     * Function to add a new page
     * @return systemMessages[]|\Zend\Http\Response
     */
    public function newAction()
    {
        //Check if this user can access this page
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "insert") || $logedUser["idCompany"]==1){
            $page = new Page();
            //If was a POST
            $message = $this->getServiceLocator()->get('systemMessages');
            $request = $this->getRequest();
            if($request->isPost()){
                $page->exchangeArray($request->getPost());
                $page->website_id = $logedUser["idWebsite"];
                if($page->validation()){
                    $result = $this->getPageTable()->savePage($page);
                    $message->setCode($result);
                }else{
                    $message->setCode("COMPANY003");
                }
                //Save log
                $this->getServiceLocator()->get('systemLog')->addLog(0, $message->getMessage(), $message->getLogPriority());
            }
            return array("message"=>$message->getMessage(), "page"=>$page);
        }else{
            return $this->redirect()->toRoute("noPermission");
        }
    }
    
    /**
     * Function to edit a page
     * @return systemMessages[]|\Zend\Http\Response
     */
    public function editAction()
    {
        //Check if this user can access this page
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "edit") || $logedUser["idCompany"]==1){
            $page = new Page();
            $message = $this->getServiceLocator()->get('systemMessages');
            
            //Get the Company ID
            $id = (int) $this->params()->fromRoute('id', 0);
            //First, will check if this page exist
            $pageData = $this->getPageTable()->getPage($id);
            if($pageData->website_id==$logedUser["idWebsite"]){
    
                //If was a POST
                $request = $this->getRequest();
                if($request->isPost()){
                    $page->exchangeArray($request->getPost());
                    $page->idPage = $id;
                    $page->website_id = $logedUser["idWebsite"];
                    if($page->validation()){
                        $result = $this->getPageTable()->savePage($page);
                        $message->setCode($result);
                        //Get again the data, now updated
                        $pageData = $this->getPageTable()->getPage($id);
                    }else{
                        $message->setCode("PAGE003");
                    }
                    //Save log
                    $this->getServiceLocator()->get('systemLog')->addLog(0, $message->getMessage(), $message->getLogPriority());
                }
                $websiteLanguages = $this->getServiceLocator()->get("website_language")->fetchAll($logedUser["idWebsite"]);
                
                return array("message"=>$message->getMessage(), "page"=>$pageData, "websiteLanguages"=>$websiteLanguages);
            }else{
                return $this->redirect()->toRoute("noPermission");
            }
        }else{
            return $this->redirect()->toRoute("noPermission");
        }
    }
    
    /**
     * Function to add a new language for a page
     * @return systemMessages[]|\Zend\Http\Response
     */
    public function editLanguageAction()
    {
        //Check if this user can access this page
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "new") || $logedUser["idCompany"]==1){
            $page = new PageLanguage();
            $message = $this->getServiceLocator()->get('systemMessages');
    
            //Get the Language and page id
            $id = (int) $this->params()->fromRoute('id', 0);
            $idLanguage = (int) $this->params()->fromRoute('idLanguage', 0);
            
            $languageData = $this->getServiceLocator()->get('language')->getLanguage($idLanguage);
            
            //First, will check if this page exist
            $pageData = $this->getPageTable()->getPage($id);
            if($pageData->website_id==$logedUser["idWebsite"]){
    
                //If was a POST
                $request = $this->getRequest();
                if($request->isPost()){
                    $data = $request->getPost();
                    $page->exchangeArray($request->getPost());
                    $page->page_id = $id;
                    $page->language_id = $idLanguage;
                    if($page->validation()){
                        $result = $this->getPageLanguageTable()->savePage($page);
                        //Delete all relationships
                        $this->getServiceLocator()->get('moduleImages')->deleteImage(5, null, $id);
                        if($data->imageLabel){
                            $images = array_keys($data->imageLabel);
                            $labels = $data->imageLabel;
                            $alts = $data->imageAlt;
                            $imageModule = new ModuleImage();
                            foreach($images as $image){
                                $data["system_module_idModule"] = 5; //Id do módulo de Páginas
                                $data["image_idImage"] = $image;
                                $data["id_item"] = $id;
                                $data["label"] = $labels[$image];
                                $data["alt"] = $alts[$image];
                                $imageModule->exchangeArray($data);
                                $this->getServiceLocator()->get('moduleImages')->saveImage($imageModule);
                            }
                        }
                        $message->setCode($result);
                        //Get again the data, now updated
                        $pageData = $this->getPageTable()->getPage($id);
                    }else{
                        $message->setCode("PAGEL003");
                    }
                    //Save log
                    $this->getServiceLocator()->get('systemLog')->addLog(0, $message->getMessage(), $message->getLogPriority());
                }
                $websiteLanguages = $this->getServiceLocator()->get("website_language")->fetchAll($logedUser["idWebsite"]);
                
                $langaugePageData = $this->getPageLanguageTable()->getPage($id, $idLanguage);
                
                $imagesSelected = $this->getServiceLocator()->get('moduleImages')->fetchAll(5, $id);
                
                return array(
                    "message"=>$message->getMessage(), 
                    "page"=>$pageData, 
                    "pageLanguage"=>$langaugePageData,
                    "websiteLanguages"=>$websiteLanguages, 
                    "idLanguage"=>$idLanguage, 
                    "languageData"=>$languageData,
                    "websiteId" => $logedUser["idWebsite"],
                    "images" => $imagesSelected
                );
            }else{
                return $this->redirect()->toRoute("noPermission");
            }
        }else{
            return $this->redirect()->toRoute("noPermission");
        }
    }
    
    /**
     * This action delete a page from the system
     * @return \Zend\Http\Response|NULL[]
     */
    public function deleteAction(){
        //Check if this user can access this page
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "delete") || $logedUser["idCompany"]==1){
            $id = (int) $this->params()->fromRoute('id', 0);
            if (!$id) { //If there is no ID
                $this->getServiceLocator()->get('systemLog')->addLog(0, "Page ".$id." not found to delete.", 5);
                return $this->redirect()->toRoute('page');
            }
    
            $message = $this->getServiceLocator()->get('systemMessages');
            //Before to delete a page, if exist, will delete langauge pages associated
            $this->getPageLanguageTable()->deletePage(null, $id);
            $message->setCode("PAGEL009", array("id"=>$id));
            
            $result = $this->getPageTable()->deletePage($id);
            $message->setCode($result, array("id"=>$id));
    
            //Save log
            $this->getServiceLocator()->get('systemLog')->addLog(0, $message->getMessage(), $message->getLogPriority());
    
            return array("message"=>$message->getMessage());
        }else{
            return $this->redirect()->toRoute("noPermission");
        }
    }
    
    public function getPageTable(){
        if(!$this->pageTable){
            $sm = $this->getServiceLocator();
            $this->pageTable = $sm->get('Page\Model\PageTable');
        }
        return $this->pageTable;
    }
    
    public function getPageLanguageTable(){
        if(!$this->pageLanguageTable){
            $sm = $this->getServiceLocator();
            $this->pageLanguageTable = $sm->get('Page\Model\PageLanguageTable');
        }
        return $this->pageLanguageTable;
    }
}
