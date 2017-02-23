<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Webservices for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Webservices\Controller;

use Zend\Mvc\Controller\AbstractActionController;

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
    protected $websiteTable;
    
    public function indexAction()
    {
        //Check if this user can access this page
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "insert") || $this->getServiceLocator()->get('user')->checkPermission($permission, "edit") || $this->getServiceLocator()->get('user')->checkPermission($permission, "delete") || $logedUser["idCompany"]==1){
            $website = $this->getWebsiteTable()->getWebsite($logedUser["idWebsite"]);
            
            
            return array("website"=>$website);
        }else{
            return $this->redirect()->toRoute("noPermission");
        }
        return array();
    }

    public function getAction(){
        $request = $this->getRequest();
        if($request->isGet()){
            $apiKey = strip_tags($this->params()->fromRoute('apiKey', 0));
            $website = $this->getWebsiteTable()->getWebsite(null, $apiKey);
            if($website){
                $ip =  $request->getServer('REMOTE_ADDR');
                if($ip == $website->apiIp){
                    $language = strip_tags($this->params()->fromRoute('language', 0));
                    $param = strip_tags($this->params()->fromRoute('param', 0));
                    $value = strip_tags($this->params()->fromRoute('value', 0));
                    
                    //Get language
                    $languageData = $this->getServiceLocator()->get('language')->getLanguage(null, $language);
                    
                    if($param && $value){ //Get a single product
                        $data = $this->getProductTable()->getProduct($value, $param);
                        $productData["products"]["product"] = $data;
                        
                        //Get images related with this module and product
                        $images = $this->getServiceLocator()->get('moduleImages')->fetchAll($this->moduleId, $data->idProduct);
                        foreach($images as $image){
                            $productData["products"]["images"] = array("image"=>$image);
                        }
                        $itens = $this->getProductItemTable()->fetchAll($value);
                        foreach($itens as $item){
                            $productData["products"]["itens"] = array("item"=>$item);
                        }
                    }else{ //Get all products
                        $allProducts = $this->getProductTable()->fetchAll($website->idWebsite);
                        foreach($allProducts as $product){
                            $data = $product;
                            $productData["products"][] = array("product"=>$data);
                            //Get images related with this module and page
                            $images = $this->getServiceLocator()->get('moduleImages')->fetchAll($this->moduleId, $data->idProduct);
                            foreach($images as $image){
                                $imagesData[] = array("image"=>$image);
                            }
                            $itens = $this->getProductItemTable()->fetchAll($value);
                            foreach($itens as $item){
                                $itensData[] = array("item"=>$item);
                            }
                           
                            $productData["products"][] = array("images"=>$imagesData);
                            $productData["products"][] = array("itens"=>$itensData); 
                        }
                    }
                    echo json_encode($productData);
                    
                    die();
                }else{
                    die("forbiden IP");
                }
            }else{
                die("forbiden API key");
            }
        }else{
            die("forbiden method");
        }
    }
    
    //Categories get
    public function categoriesAction(){
        $request = $this->getRequest();
        if($request->isGet()){
            $apiKey = strip_tags($this->params()->fromRoute('apiKey', 0));
            $website = $this->getWebsiteTable()->getWebsite(null, $apiKey);
            if($website){
                $ip =  $request->getServer('REMOTE_ADDR');
                if($ip == $website->apiIp){
                    $language = strip_tags($this->params()->fromRoute('language', 0));
                    $param = strip_tags($this->params()->fromRoute('param', 0));
                    $value = strip_tags($this->params()->fromRoute('value', 0));
                    $method = strtolower(strip_tags($this->params()->fromRoute('method', 0)));
                    switch($method){
                        case "post":
                            die("there is no support for post method for now");
                            break;
                        case "get":
                            //Get language
                            $languageData = $this->getServiceLocator()->get('language')->getLanguage(null, $language);
                            $categoryData = array();
                            if($param && $value){ //Get a single category
                                $data = $this->getCategoryLanguageTable()->getCategory($value, $languageData->idLanguage, $param);
                                $categoryData["categories"]["category"] = $data;
                            }else{ //Get all categories
                                $allCategories = $this->getCategoryLanguageTable()->fetchAllCategories($languageData->idLanguage, $website->idWebsite);
                                foreach($allCategories as $category){
                                    $categoryData["categories"][] = array("category"=>$category);
                                }                                
                            }
                            echo json_encode($categoryData);
                            die();
                            break;
                        default:
                            die("invalid method");
                    }
                }else{
                    die("forbiden IP");
                }
            }else{
                die("forbiden API key");
            }
        }else{
            die("forbiden method");
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
    
    //DON'T TOUCH
    public function getWebsiteTable()
    {
        if (! $this->websiteTable) {
            $sm = $this->getServiceLocator();
            $this->websiteTable = $sm->get('Website\Model\WebsiteTable');
        }
        return $this->websiteTable;
    }
    
}
