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
            $message = $this->getServiceLocator()->get('systemMessages');
            
            //Get the Language and product id
            $id = (int) $this->params()->fromRoute('id', 0);
            $idLanguage = (int) $this->params()->fromRoute('idLanguage', 0);
            
            $languageData = $this->getServiceLocator()->get('language')->getLanguage($idLanguage);
            
            $categoriesData = $this->getCategoryLanguageTable()->fetchAllCategories($idLanguage, $logedUser["idWebsite"]);
            
            return array("categories"=>$categoriesData, "languages"=>$languageData);
        }else{
            return $this->redirect()->toRoute("noPermission");
        }
    }
    
    public function getCategoryLanguageTable(){
        if(!$this->categoryLanguageTable){
            $sm = $this->getServiceLocator();
            $this->categoryLanguageTable = $sm->get('Product\Model\CategoryLanguageTable');
        }
        return $this->categoryLanguageTable;
    }
    
    

}
