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
use Product\Model\ProductItem;

class ProductController extends AbstractActionController
{
    protected $moduleId = 10;
    protected $productTable;
    protected $productLanguageTable;
    protected $categoryTable;
    protected $categoryLanguageTable;
    protected $productHasCategoryTable;
    protected $productItemTable;
    protected $colorTable;
    
    
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
            // Page number
            $currentPage = $this->params()->fromQuery('page');
            // Number of records per page
            $countPerPage = "30";
            $products = $this->getProductTable()->fetchAll($logedUser["idWebsite"], $currentPage, $countPerPage);
            return array("products"=>$products, "permission"=>$permission);
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
    
    public function editAction()
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
            $product = $this->getProductTable()->getProduct($id);
            $oldLog = $product->log;
            
            $request = $this->getRequest();
            if($request->isPost()){
                $product = new Product();
                
                $dataPost = $request->getPost();
                $product->exchangeArray($dataPost);
                $product->website_id = $logedUser["idWebsite"];
                $product->log = $oldLog."\n"."Produto alterado por meio do site pelo usuário ".$logedUser["name"]." (".$logedUser["idUser"].") em ".date("d/m/Y H:i:s").".";
    
                if($product->validation()){
                    $product->idProduct = $id;
                    $result = $this->getProductTable()->saveProduct($product);
                    $message->setCode($result["code"]);
                    if($result == "PRO004"){ //If saved
                        $prodtCategory = new ProductHasCategory();
                        $prodtCategory->product_idProduct = $id;
                        //Deleta todos os relacionamentos com categorias
                        $this->getProductHasCategoryTable()->deleteCategory($prodtCategory);
                        //Insere os novos relacionamentos
                        foreach ($dataPost["categories"] as $category){
                            $prodtCategory->product_idCategory = $category;
                            $this->getProductHasCategoryTable()->saveCategory($prodtCategory);
                        }
                        $message->setCode("PRO004");
                    }
                }else{
                    $message->setCode("PRO006");
                }
                $this->getServiceLocator()->get('systemLog')->addLog(0, $message->getMessage(), $message->getLogPriority());
            }
            $categoriesSelectedData = $this->getProductHasCategoryTable()->fetchAll($id);
            foreach($categoriesSelectedData as $category){
                $categoriesSelected[] = $category->product_idCategory;
            }
    
            return array("product"=>$product, "categories"=>$categories, "categoriesSelected"=>$categoriesSelected, "languages"=>$languageData,  "message"=>$message->getMessage());
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
            //Get the Language and product id
            $id = (int) $this->params()->fromRoute('id', 0);
            $product = $this->getProductTable()->getProduct($id);
            
            $message = $this->getServiceLocator()->get('productMessages');
    
            $request = $this->getRequest();
            if($request->isPost()){
                $data = $request->getPost();
                if($data->idProductItem){ //Save edition
                    if($data->idProductItem == $this->params()->fromRoute('idItem', 0)){
                        $this->addItemToStock($id, $data);
                    }else{ //Se usuário tentar alguma gracinha
                        return $this->redirect()->toRoute("noPermission");
                    }
                }else{ //Insert New
                    $result = $this->addItemToStock($id, $data);
                    if($result!="PROI002" || $result!="PROI003" || $result!="PROI005"){
                        $result = "PROI001";
                    }
                }
               
                $message->setCode($result);
                $this->getServiceLocator()->get('systemLog')->addLog(0, $message->getMessage(), $message->getLogPriority());
            }
            if($this->params()->fromRoute('idItem', 0)){
                $idItem = $this->params()->fromRoute('idItem', 0);   
                $itemData = $this->getProductItemTable()->getItem($idItem);
            }else{
                $itemData = new ProductItem();
            }
            
            
            $itens = $this->getProductItemTable()->fetchAll($id);
            $colors = $this->getColorTable()->fetchByLanguage($logedUser["idWebsite"], $product->language_id);
            return array("product"=>$product, "itemData"=>$itemData, "itens"=>$itens, "colors"=>$colors, "message"=>$message->getMessage());
        }else{
            return $this->redirect()->toRoute("noPermission");
        }
    }
    
    public function addItemToStock($idProduct, $data){
        $productItem = new ProductItem();
        $productItem->exchangeArray($data);
        $productItem->product_id = $idProduct;
        switch($data["size_kind"]){
            case 1:  //Vestuário
                $productItem->size = $data["vest_size"];
                break;
            case 2: // Bebidas
                $productItem->size = $data["drinks_size"];
                break;
            case 3: //Metragem
                break;
            case 4: //Peso
                break;
            case 5: //Rodas e Pneus
                $productItem->size = $data["weels_size"];
                break;
            default:
                break;
        }
        if($productItem->validation()){
            $result = $this->getProductItemTable()->saveProductItem($productItem);
            return $result;
        }else{
            return "PROI003";
        }
    }
    
    public function getProductItemTable(){
        if(!$this->productItemTable){
            $sm = $this->getServiceLocator();
            $this->productItemTable = $sm->get('Product\Model\ProductItemTable');
        }
        return $this->productItemTable;
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
    
    public function getColorTable(){
        if(!$this->colorTable){
            $sm = $this->getServiceLocator();
            $this->colorTable = $sm->get('Product\Model\ColorTable');
        }
        return $this->colorTable;
    }

}
