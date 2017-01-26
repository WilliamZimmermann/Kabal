<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Product for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Product\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Product\Model\Product;
use Product\Model\ProductHasCategory;

class ProductController extends AbstractActionController
{
    protected $moduleId = 10;
    protected $productTable;
    protected $productLanguageTable;
    protected $categoryTable;
    protected $categoryLanguageTable;
    protected $productHasCategoryTable;
    
    public function indexAction()
    {
        // Check if this user can access this page
        $logedUser = $this->getServiceLocator()
        ->get('user')
        ->getUserSession();
        $permission = $this->getServiceLocator()
        ->get('permissions')
        ->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if ($this->getServiceLocator()->get('user')->checkPermission($permission, "insert")) {
            return array();
        }else{
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
        if ($this->getServiceLocator()->get('user')->checkPermission($permission, "edit")) {
            $message = $this->getServiceLocator()->get('productMessages');
            
            //Get the Language and product id
            $id = (int) $this->params()->fromRoute('id', 0);
            $idLanguage = (int) $this->params()->fromRoute('idLanguage', 0);
            
            $languageData = $this->getServiceLocator()->get('language')->fetchAll();
            
            $l=0;
            foreach($this->getCategoryTable()->fetchAll($logedUser["idWebsite"]) as $category){
                $categories[$l]["category"] = $category;
                $subcategories = null;
                foreach($this->getCategoryTable()->fetchAllSubcategories($category->idCategory) as $subcategory){
                    $subcategories[] = $subcategory;
                }
                $categories[$l]["subcategories"] = $subcategories;
                $l++;
            }
            
            //Aqui começa a inserção de novos produtos
            $product = new Product();
            $request = $this->getRequest();
            if($request->isPost()){
                $dataPost = $request->getPost();
                $product->exchangeArray($dataPost);
                $product->website_id = $logedUser["idWebsite"];
                $product->log = "Produto adicionado por meio do site pelo usuário ".$logedUser["name"]." (".$logedUser["idUser"].") em ".date("d/m/Y H:i:s").".";
                
                if($product->validation()){
                    $result = $this->getProductTable()->saveProduct($product);
                    $message->setCode($result["code"]);
                    if($result != "PRO002"){ //If saved
                        $prodtCategory = new ProductHasCategory();
                        foreach ($dataPost["categories"] as $category){
                            $prodtCategory->exchangeArray(array("product_idProduct"=>$result, "product_idCategory"=>$category));
                            $this->getProductHasCategoryTable()->saveCategory($prodtCategory);
                        }
                        $message->setCode("PRO001");
                    }
                }else{
                    $message->setCode("PRO003");
                }
                $this->getServiceLocator()->get('systemLog')->addLog(0, $message->getMessage(), $message->getLogPriority());
            }
            
            return array("product"=>$product, "categories"=>$categories, "languages"=>$languageData,  "message"=>$message->getMessage());
        }else{
            return $this->redirect()->toRoute("noPermission");
        }
    }
    
    public function stockAction()
    {
        // Check if this user can access this page
        $logedUser = $this->getServiceLocator()
        ->get('user')
        ->getUserSession();
        $permission = $this->getServiceLocator()
        ->get('permissions')
        ->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if ($this->getServiceLocator()->get('user')->checkPermission($permission, "edit")) {
            $message = $this->getServiceLocator()->get('systemMessages');
    
            //Get the Language and product id
            $id = (int) $this->params()->fromRoute('id', 0);
            $idLanguage = (int) $this->params()->fromRoute('idLanguage', 0);
    
            $languageData = $this->getServiceLocator()->get('language')->getLanguage($idLanguage);
    
            $l=0;
            foreach($this->getCategoryTable()->fetchAll($logedUser["idWebsite"]) as $category){
                $categories[$l]["category"] = $category;
                $subcategories = null;
                foreach($this->getCategoryTable()->fetchAllSubcategories($category->idCategory) as $subcategory){
                    $subcategories[] = $subcategory;
                }
                $categories[$l]["subcategories"] = $subcategories;
                $l++;
            }
    
            return array("categories"=>$categories, "languages"=>$languageData);
        }else{
            return $this->redirect()->toRoute("noPermission");
        }
    }
    
    public function getProductTable(){
        if(!$this->productTable){
            $sm = $this->getServiceLocator();
            $this->productTable = $sm->get('Product\Model\ProductTable');
        }
        return $this->productTable;
    }
    
    public function getCategoryTable(){
        if(!$this->categoryTable){
            $sm = $this->getServiceLocator();
            $this->categoryTable = $sm->get('Product\Model\CategoryTable');
        }
        return $this->categoryTable;
    }
    
    public function getCategoryLanguageTable(){
        if(!$this->categoryLanguageTable){
            $sm = $this->getServiceLocator();
            $this->categoryLanguageTable = $sm->get('Product\Model\CategoryLanguageTable');
        }
        return $this->categoryLanguageTable;
    }
    
    public function getProductHasCategoryTable(){
        if(!$this->productHasCategoryTable){
            $sm = $this->getServiceLocator();
            $this->productHasCategoryTable = $sm->get('Product\Model\ProductHasCategoryTable');
        }
        return $this->productHasCategoryTable;
    }
    

}
