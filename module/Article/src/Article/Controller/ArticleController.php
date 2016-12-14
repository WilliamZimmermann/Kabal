<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Article for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Article\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Article\Model\Article;
use Article\Model\ArticleLanguage;
use ImagesDatabase\Model\ModuleImage;
use Article\Model\ArticleHasCategory;

class ArticleController extends AbstractActionController
{
    protected $moduleId = 8;
    protected $articleTable;
    protected $articleLanguageTable;
    protected $categoryTable;
    protected $categoryLanguageTable;
    protected $articleHasCategoryTable;
    
    
    public function indexAction()
    {
        //Check if this user can access this article
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "insert") || $this->getServiceLocator()->get('user')->checkPermission($permission, "edit") || $this->getServiceLocator()->get('user')->checkPermission($permission, "delete") || $logedUser["idCompany"]==1){
            $articles = $this->getArticleTable()->fetchAll($logedUser["idWebsite"]);
            return array("articles"=>$articles);
        }else{
            return $this->redirect()->toRoute("noPermission");
        }
    }

    /**
     * Function to add a new article
     * @return systemMessages[]|\Zend\Http\Response
     */
    public function newAction()
    {
        //Check if this user can access this article
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "insert") || $logedUser["idCompany"]==1){
            $article = new Article();
            //If was a POST
            $message = $this->getServiceLocator()->get('systemMessages');
            $request = $this->getRequest();
            if($request->isPost()){
                $article->exchangeArray($request->getPost());
                $article->website_id = $logedUser["idWebsite"];
                if($article->validation()){
                    $result = $this->getArticleTable()->saveArticle($article);
                    if(is_array($result)){
                        $this->redirect()->toRoute("article/edit", array("id"=>$result["id"], "code"=>$result["code"]));
                        $message->setCode($result["code"]);
                    }
                    $message->setCode($result);
                }else{
                    $message->setCode("ART003");
                }
                //Save log
                $this->getServiceLocator()->get('systemLog')->addLog(0, $message->getMessage(), $message->getLogPriority());
            }
            return array("message"=>$message->getMessage(), "article"=>$article);
        }else{
            return $this->redirect()->toRoute("noPermission");
        }
    }
    
    /**
     * Function to edit a article
     * @return systemMessages[]|\Zend\Http\Response
     */
    public function editAction()
    {
        //Check if this user can access this article
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "edit") || $logedUser["idCompany"]==1){
            $article = new Article();
            $message = $this->getServiceLocator()->get('systemMessages');

            if($this->params()->fromRoute('code')){
                $message->setCode($this->params()->fromRoute('code'));
            }
            //Get the Company ID
            $id = (int) $this->params()->fromRoute('id', 0);
            //First, will check if this article exist
            $articleData = $this->getArticleTable()->getArticle($id);
            if($articleData->website_id==$logedUser["idWebsite"]){
    
                //If was a POST
                $request = $this->getRequest();
                if($request->isPost()){
                    $article->exchangeArray($request->getPost());
                    $article->idArticle = $id;
                    $article->website_id = $logedUser["idWebsite"];
                    if($article->validation()){
                        $result = $this->getArticleTable()->saveArticle($article);
                        $message->setCode($result);
                        //Get again the data, now updated
                        $articleData = $this->getArticleTable()->getArticle($id);
                    }else{
                        $message->setCode("ART003");
                    }
                    //Save log
                    $this->getServiceLocator()->get('systemLog')->addLog(0, $message->getMessage(), $message->getLogPriority());
                }
                $websiteLanguages = $this->getServiceLocator()->get("website_language")->fetchAll($logedUser["idWebsite"]);
                
                return array("message"=>$message->getMessage(), "article"=>$articleData, "websiteLanguages"=>$websiteLanguages);
            }else{
                return $this->redirect()->toRoute("noPermission");
            }
        }else{
            return $this->redirect()->toRoute("noPermission");
        }
    }
    
    /**
     * Function to add a new language for a article
     * @return systemMessages[]|\Zend\Http\Response
     */
    public function editLanguageAction()
    {
        //Check if this user can access this article
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "new") || $logedUser["idCompany"]==1){
            $article = new ArticleLanguage();
            $message = $this->getServiceLocator()->get('systemMessages');
    
            //Get the Language and article id
            $id = (int) $this->params()->fromRoute('id', 0);
            $idLanguage = (int) $this->params()->fromRoute('idLanguage', 0);
            
            $languageData = $this->getServiceLocator()->get('language')->getLanguage($idLanguage);
            $categories = $this->getCategoryLanguageTable()->fetchAllCategories($idLanguage, $logedUser["idWebsite"]);
            
            //First, will check if this article exist
            $articleData = $this->getArticleTable()->getArticle($id);
            if($articleData->website_id==$logedUser["idWebsite"]){
    
                //If was a POST
                $request = $this->getRequest();
                if($request->isPost()){
                    $data = $request->getPost();
                    $article->exchangeArray($request->getPost());
                    $article->article_id = $id;
                    $article->language_id = $idLanguage;
                    if($article->validation()){
                        $result = $this->getArticleLanguageTable()->saveArticle($article);
                        $langaugeArticleData = $this->getArticleLanguageTable()->getArticle($id, $idLanguage);
                        
                        //Insert categories
                        if($data->categories){
                            //Delete all categories relationships
                            $articleCategory = new ArticleHasCategory();
                            $articleCategory->language_idArticle = $langaugeArticleData->idArticleLanguage;
                            $this->getArticleHasCategoryTable()->deleteCategory($articleCategory);
                            foreach($data->categories as $category){
                                $articleCategory->language_idCategory = $category;
                                if($this->getArticleHasCategoryTable()->saveCategory($articleCategory)){
                                    $this->getServiceLocator()->get('systemLog')->addLog(0, "A category was related with an Article", 5);
                                }else{
                                    $this->getServiceLocator()->get('systemLog')->addLog(0, "A problem happened when tried relate a category with an article", 3);
                                }
                            }
                        }
                        
                        //Delete all images relationships
                        $this->getServiceLocator()->get('moduleImages')->deleteImage($this->moduleId, null, $langaugeArticleData->idArticleLanguage);
                        if($data->imageLabel){
                            $images = array_keys($data->imageLabel);
                            $labels = $data->imageLabel;
                            $alts = $data->imageAlt;
                            $imageModule = new ModuleImage();
                            foreach($images as $image){
                                $data["system_module_idModule"] = $this->moduleId; //Id do módulo de Páginas
                                $data["image_idImage"] = $image;
                                $data["id_item"] = $langaugeArticleData->idArticleLanguage;
                                $data["label"] = $labels[$image];
                                $data["alt"] = $alts[$image];
                                $imageModule->exchangeArray($data);
                                $this->getServiceLocator()->get('moduleImages')->saveImage($imageModule);
                            }
                        }
                        $message->setCode($result);
                        //Get again the data, now updated
                        $articleData = $this->getArticleTable()->getArticle($id);
                    }else{
                        $message->setCode("ARTL003");
                    }
                    //Save log
                    $this->getServiceLocator()->get('systemLog')->addLog(0, $message->getMessage(), $message->getLogPriority());
                }
                $websiteLanguages = $this->getServiceLocator()->get("website_language")->fetchAll($logedUser["idWebsite"]);
                
                $langaugeArticleData = $this->getArticleLanguageTable()->getArticle($id, $idLanguage);
                
                $imagesSelected = $this->getServiceLocator()->get('moduleImages')->fetchAll($this->moduleId, $langaugeArticleData->idArticleLanguage);
                $categoriesSelectedData = $this->getArticleHasCategoryTable()->fetchAll($langaugeArticleData->idArticleLanguage, "language_idArticle");
                $categoriesSelected = array();
                foreach($categoriesSelectedData as $selected){
                    $categoriesSelected[] = $selected->language_idCategory;
                }
                return array(
                    "message"=>$message->getMessage(), 
                    "article"=>$articleData, 
                    "articleLanguage"=>$langaugeArticleData,
                    "websiteLanguages"=>$websiteLanguages, 
                    "idLanguage"=>$idLanguage, 
                    "languageData"=>$languageData,
                    "websiteId" => $logedUser["idWebsite"],
                    "images" => $imagesSelected,
                    "categories"=>$categories,
                    "categoriesSelected"=>$categoriesSelected
                );
            }else{
                return $this->redirect()->toRoute("noPermission");
            }
        }else{
            return $this->redirect()->toRoute("noPermission");
        }
    }
    
    /**
     * This action delete a article from the system
     * @return \Zend\Http\Response|NULL[]
     */
    public function deleteAction(){
        //Check if this user can access this article
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "delete") || $logedUser["idCompany"]==1){
            $id = (int) $this->params()->fromRoute('id', 0);
            if (!$id) { //If there is no ID
                $this->getServiceLocator()->get('systemLog')->addLog(0, "Article ".$id." not found to delete.", 5);
                return $this->redirect()->toRoute('article');
            }
    
            $message = $this->getServiceLocator()->get('systemMessages');
            /*Before to delete a article, if exist, will delete language articles associated
            * But, before to delete language articles associated, must to delete associated categories
            */
            $articleCategory = new ArticleHasCategory();
            $articleLanguages = $this->getArticleLanguageTable()->fetchAll($id);
            foreach($articleLanguages as $articleLanguage){
                $articleCategory->language_idArticle = $articleLanguage->idArticleLanguage;
                //Delete the category Relationship
                $this->getArticleHasCategoryTable()->deleteCategory($articleCategory);  
                //Delete image relationships also
                $this->getServiceLocator()->get('moduleImages')->deleteImage($this->moduleId, null, $articleLanguage->idArticleLanguage);
            }
            
            //Now it can delete the language article
            $this->getArticleLanguageTable()->deleteArticle(null, $id);
            $message->setCode("ARTL009", array("id"=>$id));
            
            $result = $this->getArticleTable()->deleteArticle($id);
            $message->setCode($result, array("id"=>$id));
    
            //Save log
            $this->getServiceLocator()->get('systemLog')->addLog(0, $message->getMessage(), $message->getLogPriority());
    
            return array("message"=>$message->getMessage());
        }else{
            return $this->redirect()->toRoute("noPermission");
        }
    }
    
    public function getArticleTable(){
        if(!$this->articleTable){
            $sm = $this->getServiceLocator();
            $this->articleTable = $sm->get('Article\Model\ArticleTable');
        }
        return $this->articleTable;
    }
    
    public function getArticleLanguageTable(){
        if(!$this->articleLanguageTable){
            $sm = $this->getServiceLocator();
            $this->articleLanguageTable = $sm->get('Article\Model\ArticleLanguageTable');
        }
        return $this->articleLanguageTable;
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
