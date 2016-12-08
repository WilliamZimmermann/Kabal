<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Page for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Article\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Page\Model\Page;
use Page\Model\PageLanguage;
use ImagesDatabase\Model\ModuleImage;
use Article\Model\Category;

class CategoryController extends AbstractActionController
{
    protected $moduleId = 8;
    protected $categoryTable;
    protected $categoryLanguageTable;
    
    public function indexAction()
    {
        //Check if this user can access this page
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "insert") || $this->getServiceLocator()->get('user')->checkPermission($permission, "edit") || $this->getServiceLocator()->get('user')->checkPermission($permission, "delete") || $logedUser["idCompany"]==1){
            $categories = $this->getCategoryTable()->fetchAll($logedUser["idWebsite"]);
            return array("categories"=>$categories);
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
            $category = new Category();
            //If was a POST
            $message = $this->getServiceLocator()->get('systemMessages');
            $request = $this->getRequest();
            if($request->isPost()){
                $category->exchangeArray($request->getPost());
                $category->website_id = $logedUser["idWebsite"];
                if($category->validation()){
                    $result = $this->getCategoryTable()->saveCategory($category);
                    $message->setCode($result);
                }else{
                    $message->setCode("ACAT003");
                }
                //Save log
                $this->getServiceLocator()->get('systemLog')->addLog(0, $message->getMessage(), $message->getLogPriority());
            }
            return array("message"=>$message->getMessage(), "category"=>$category);
        }else{
            return $this->redirect()->toRoute("noPermission");
        }
    }
    
    /**
     * Function to edit a category
     * @return systemMessages[]|\Zend\Http\Response
     */
    public function editAction()
    {
        //Check if this user can access this page
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "edit") || $logedUser["idCompany"]==1){
            $category = new Category();
            $message = $this->getServiceLocator()->get('systemMessages');
            
            //Get the Company ID
            $id = (int) $this->params()->fromRoute('id', 0);
            //First, will check if this category exist
            $categoryData = $this->getCategoryTable()->getCategory($id);
            if($categoryData->website_id==$logedUser["idWebsite"]){
    
                //If was a POST
                $request = $this->getRequest();
                if($request->isPost()){
                    $category->exchangeArray($request->getPost());
                    $category->idCategory = $id;
                    $category->website_id = $logedUser["idWebsite"];
                    if($category->validation()){
                        $result = $this->getCategoryTable()->saveCategory($category);
                        $message->setCode($result);
                        //Get again the data, now updated
                        $categoryData = $this->getCategoryTable()->getCategory($id);
                    }else{
                        $message->setCode("ACAT003");
                    }
                    //Save log
                    $this->getServiceLocator()->get('systemLog')->addLog(0, $message->getMessage(), $message->getLogPriority());
                }
                $websiteLanguages = $this->getServiceLocator()->get("website_language")->fetchAll($logedUser["idWebsite"]);
                
                return array("message"=>$message->getMessage(), "category"=>$categoryData, "websiteLanguages"=>$websiteLanguages);
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
            $category = new PageLanguage();
            $message = $this->getServiceLocator()->get('systemMessages');
    
            //Get the Language and page id
            $id = (int) $this->params()->fromRoute('id', 0);
            $idLanguage = (int) $this->params()->fromRoute('idLanguage', 0);
            
            $languageData = $this->getServiceLocator()->get('language')->getLanguage($idLanguage);
            
            //First, will check if this page exist
            $categoryData = $this->getCategoryTable()->getCategory($id);
            if($categoryData->website_id==$logedUser["idWebsite"]){
    
                //If was a POST
                $request = $this->getRequest();
                if($request->isPost()){
                    $data = $request->getPost();
                    $category->exchangeArray($request->getPost());
                    $category->page_id = $id;
                    $category->language_id = $idLanguage;
                    if($category->validation()){
                        $result = $this->getCategoryLanguageTable()->savePage($category);
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
                        $categoryData = $this->getCategoryTable()->getPage($id);
                    }else{
                        $message->setCode("PAGEL003");
                    }
                    //Save log
                    $this->getServiceLocator()->get('systemLog')->addLog(0, $message->getMessage(), $message->getLogPriority());
                }
                $websiteLanguages = $this->getServiceLocator()->get("website_language")->fetchAll($logedUser["idWebsite"]);
                
                $langaugePageData = $this->getCategoryLanguageTable()->getPage($id, $idLanguage);
                
                $imagesSelected = $this->getServiceLocator()->get('moduleImages')->fetchAll(5, $id);
                
                return array(
                    "message"=>$message->getMessage(), 
                    "page"=>$categoryData, 
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
                $this->getServiceLocator()->get('systemLog')->addLog(0, "Category ".$id." not found to delete.", 5);
                return $this->redirect()->toRoute('category');
            }
    
            $message = $this->getServiceLocator()->get('systemMessages');
            //Before to delete a category, if exist, will delete langauge pages associated
            //TODO
            //$this->getCategoryLanguageTable()->deletePage(null, $id);
            //$message->setCode("ACATL009", array("id"=>$id));
            
            $result = $this->getCategoryTable()->deleteCategory($id);
            $message->setCode($result, array("id"=>$id));
    
            //Save log
            $this->getServiceLocator()->get('systemLog')->addLog(0, $message->getMessage(), $message->getLogPriority());
    
            return array("message"=>$message->getMessage());
        }else{
            return $this->redirect()->toRoute("noPermission");
        }
    }
    
    public function getCategoryTable(){
        if(!$this->categoryTable){
            $sm = $this->getServiceLocator();
            $this->categoryTable = $sm->get('Article\Model\CategoryTable');
        }
        return $this->categoryTable;
    }
    
    public function getCategoryLanguageTable(){
        if(!$this->categoryLanguageTable){
            $sm = $this->getServiceLocator();
            $this->categoryLanguageTable = $sm->get('Article\Model\CategoryLanguageTable');
        }
        return $this->categoryLanguageTable;
    }
}
