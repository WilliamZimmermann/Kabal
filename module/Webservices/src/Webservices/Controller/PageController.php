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
    protected $pageLanguageTable;
    protected $websiteTable;
    
    public function indexAction()
    {
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
                    if($language=="pt"){
                        $language = 1;
                    }
                    $pageData = $this->getPageLanguageTable()->getPage($value, $language, $param);
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
