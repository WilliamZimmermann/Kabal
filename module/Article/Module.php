<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Page for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Article;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Article\Model\CategoryTable;
use Article\Model\CategoryLanguageTable;
use Article\Model\CategoryLanguage;
use Article\Model\Category;
use Article\Model\ArticleTable;
use Article\Model\Article;
use Article\Model\ArticleLanguageTable;
use Article\Model\ArticleLanguage;
use Article\Model\ArticleHasCategoryTable;
use Article\Model\ArticleHasCategory;

class Module implements AutoloaderProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
		    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        // You may not need to do this if you're doing it elsewhere in your
        // application
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }
    
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Article\Model\CategoryTable' => function ($sm) {
                    $tableGateway = $sm->get('CategoryTableGateway');
                    $table = new CategoryTable($tableGateway);
                    return $table;
                },
                'CategoryTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Category());
                    return new TableGateway('article_category', $dbAdapter, null, $resultSetPrototype);
                },
                'Article\Model\CategoryLanguageTable' => function ($sm) {
                    $tableGateway = $sm->get('CategoryLanguageTableGateway');
                    $table = new CategoryLanguageTable($tableGateway);
                    return $table;
                },
                'CategoryLanguageTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new CategoryLanguage());
                    return new TableGateway('article_category_has_language', $dbAdapter, null, $resultSetPrototype);
                },
                'Article\Model\ArticleTable' => function ($sm) {
                    $tableGateway = $sm->get('ArticleTableGateway');
                    $table = new ArticleTable($tableGateway);
                    return $table;
                },
                'ArticleTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Article());
                    return new TableGateway('website_article', $dbAdapter, null, $resultSetPrototype);
                },
                'Article\Model\ArticleLanguageTable' => function ($sm) {
                    $tableGateway = $sm->get('ArticleLanguageTableGateway');
                    $table = new ArticleLanguageTable($tableGateway);
                    return $table;
                },
                'ArticleLanguageTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new ArticleLanguage());
                    return new TableGateway('article_has_language', $dbAdapter, null, $resultSetPrototype);
                },
                'Article\Model\ArticleHasCategoryTable' => function ($sm) {
                    $tableGateway = $sm->get('ArticleHasCategoryTableGateway');
                    $table = new ArticleHasCategoryTable($tableGateway);
                    return $table;
                },
                'ArticleHasCategoryTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new ArticleHasCategory());
                    return new TableGateway('article_has_category', $dbAdapter, null, $resultSetPrototype);
                }
            )
        );
    }
}
