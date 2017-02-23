<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Page for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Product\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Article\Model\Category;
use Article\Model\CategoryLanguage;
use Article\Model\ArticleHasCategory;

class CategoryController extends AbstractActionController
{
    protected $moduleId = 8;
    protected $categoryTable;
    protected $categoryLanguageTable;
    protected $articleHasCategoryTable;
    
    public function indexAction()
    {
        //Check if this user can access this page
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "insert")){
            $categories = $this->getCategoryTable()->fetchAll($logedUser["idWebsite"]);
            return array("categories"=>$categories, "permission"=>$permission);
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
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "edit")){
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
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "edit")){
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
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "edit")){
            $category = new CategoryLanguage();
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
                    $category->category_id = $id;
                    $category->language_id = $idLanguage;
                    if($category->validation()){
                        $result = $this->getCategoryLanguageTable()->saveCategory($category);                        
                        $message->setCode($result);
                        //Get again the data, now updated
                        $categoryData = $this->getCategoryTable()->getCategory($id);
                    }else{
                        $message->setCode("ACATL003");
                    }
                    //Save log
                    $this->getServiceLocator()->get('systemLog')->addLog(0, $message->getMessage(), $message->getLogPriority());
                }
                $websiteLanguages = $this->getServiceLocator()->get("website_language")->fetchAll($logedUser["idWebsite"]);
                
                $languageCategoryData = $this->getCategoryLanguageTable()->getCategory($id, $idLanguage);
                                
                return array(
                    "message"=>$message->getMessage(), 
                    "category"=>$categoryData, 
                    "categoryLanguage"=>$languageCategoryData,
                    "websiteLanguages"=>$websiteLanguages, 
                    "idLanguage"=>$idLanguage, 
                    "languageData"=>$languageData,
                    "websiteId" => $logedUser["idWebsite"],
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
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "delete")){
            $id = (int) $this->params()->fromRoute('id', 0);
            if (!$id) { //If there is no ID
                $this->getServiceLocator()->get('systemLog')->addLog(0, "Category ".$id." not found to delete.", 5);
                return $this->redirect()->toRoute('category');
            }
    
            $message = $this->getServiceLocator()->get('systemMessages');
            
            //Before to delete language categories associated must to delete relationships between articles
            $categoriesLanguages = $this->getCategoryLanguageTable()->fetchAll($id);
            $articleCategory = new ArticleHasCategory();
            foreach($categoriesLanguages as $categoryLanguage){
                //Delete any relationships that can exists between a category and an article
                $articleCategory->language_idCategory = $categoryLanguage->idCategoryLanguage;
                $this->getArticleHasCategoryTable()->deleteCategory($articleCategory);
            }
            
            //Before to delete a category, if exist, will delete language categories associated
            $this->getCategoryLanguageTable()->deleteCategory(null, $id);
            $message->setCode("ACATL009", array("id"=>$id));
            $this->getServiceLocator()->get('systemLog')->addLog(0, $message->getMessage(), $message->getLogPriority());
            
            //Delete category
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
    
    public function getArticleHasCategoryTable(){
        if(!$this->articleHasCategoryTable){
            $sm = $this->getServiceLocator();
            $this->articleHasCategoryTable = $sm->get('Article\Model\ArticleHasCategoryTable');
        }
        return $this->articleHasCategoryTable;
    }
}
