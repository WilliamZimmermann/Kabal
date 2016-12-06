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

class PageController extends AbstractActionController
{
    protected $moduleId = 7;
    protected $pageLanguageTable;
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
                    
                    if($param && $value){ //Get a single page
                        $data = $this->getPageLanguageTable()->getPage($value, $languageData->idLanguage, $param);
                        $pageData["pages"]["page"] = $data;
                        //Get images related with this module and page
                        $images = $this->getServiceLocator()->get('moduleImages')->fetchAll(5, $data->page_id);
                        foreach($images as $image){
                            $pageData["pages"]["images"] = array("image"=>$image);
                        }
                    }else{ //Get all pages
                        $allPages = $this->getPageLanguageTable()->fetchAllPages($languageData->idLanguage, $website->idWebsite);
                        foreach($allPages as $page){
                            $data = $page;
                            $pageData["pages"][] = array("page"=>$data);
                            //Get images related with this module and page
                            $images = $this->getServiceLocator()->get('moduleImages')->fetchAll(5, $data->page_id);
                            foreach($images as $image){
                                $imagesData[] = array("image"=>$image);
                            }
                            $pageData["pages"][] = array("images"=>$imagesData);
                        }
                    }
                    echo json_encode($pageData);
                    
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
    
    public function getWebsiteTable()
    {
        if (! $this->websiteTable) {
            $sm = $this->getServiceLocator();
            $this->websiteTable = $sm->get('Website\Model\WebsiteTable');
        }
        return $this->websiteTable;
    }
    
}
