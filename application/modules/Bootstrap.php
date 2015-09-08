<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap 
{
    protected function _initDoctype()
    {
         $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML1_STRICT');
    }
    
    protected function _initDb()
    {
        $dbResource = $this->getPluginResource('db');
        $dbResource->init();
        $db = $dbResource->getDbAdapter();
        
        Zend_Db_Table_Abstract::setDefaultAdapter($db);
        Zend_Registry::set('dbAdapter', $db);
    }
        
    protected function _initAutoloader()
    {
        // Require the autoloader class file
        require_once 'Zend/Loader/Autoloader.php';
        
        // Fetch the Singleton instance of Zend_Loader_Autoloader
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace("Main");
        $autoloader->registerNamespace("Login");
        $autoloader->registerNamespace('Sportalize_');
        
        // Set the autoloader as a fallback autoloader (loads all namespaces by default)
        $autoloader->setFallbackAutoloader(true);
        
        // Return the autoloader
        return $autoloader;
    }

    protected function _initView() {
        $view = new Zend_View();
        $view->doctype('HTML5');

        // add our view helper path and filter path
        $view->addHelperPath(realpath(APPLICATION_PATH . '/../library/Sportalize/View/Helper'), 'Sportalize_View_Helper');

        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
        $viewRenderer->setView($view);

        return $view;
    }
    
    protected function _initConfig() {
      $configPath   = APPLICATION_PATH . '/configs';
      $config       = new Zend_Config_Xml("$configPath/application.xml", APPLICATION_ENV,true);
      $config->setReadOnly();
      $this->setOptions($config->toArray());

      Zend_Registry::set('config', $config);
      return $config;
    }

    protected function _initCache()
    {  
        $config       = Zend_Registry::get('config');
        $cachePrefix  = 'mp_';
        if (isset($config->cachePrefix)) {
            $cachePrefix = $config->cachePrefix;
        }
        if (class_exists('Memcache',false)) {
            $frontendOptions = array(
                'automatic_serialization' => true,
                'caching'                 => true,
                'cache_id_prefix'         => $cachePrefix
            );
            $backendOptions  = array(
                'compression'      => false,
                'file_name_prefix' => $cachePrefix
            );
             
            $cache = Zend_Cache::factory(
                'Core',
                'Memcached',
                $frontendOptions,
                $backendOptions
            );
         } else {
            $frontendOptions = array(
                'automatic_serialization' => true
            );

            $backendOptions  = array(
                'cache_dir'        => ROOT_PATH . '/temporary',
                'file_name_prefix' => $cachePrefix
            );
            $cache = Zend_Cache::factory(
                'Core',
                'File',
                $frontendOptions,
                $backendOptions
            );
        }
        
        $frontendOptions = array(
            'automatic_serialization' => true
        );

        $backendOptions  = array(
            'cache_dir' => ROOT_PATH . '/temporary'
        );
        
        
        $cacheFile = Zend_Cache::factory(
            'Core',
            'File',
            $frontendOptions,
            $backendOptions
        );
        
        Zend_Registry::set('cacheFile', $cacheFile);
                
        Zend_Registry::set('cache', $cache);
       
        Zend_Db_Table_Abstract::setDefaultMetadataCache($cache);
        
        return $cache;
        
    } 

    protected function _initTranslate() {
        // We use the Swedish locale as an example
        $locale = new Zend_Locale('en_US');
        Zend_Registry::set('Zend_Locale', $locale);

        $session = new Zend_Session_Namespace(APP_URL);
        $langLocale = isset($session->lang) ? $session->lang : $locale;

        $translate = new Zend_Translate('gettext', APPLICATION_PATH . '/languages/en.mo', $langLocale,array('disableNotices' => true));
        $translate->setLocale($langLocale);
        Zend_Registry::set('Zend_Translate', $translate);
    }

    protected function _initLogging()
    {
        $logger = new Zend_Log();
        $writer = new Zend_Log_Writer_Stream('php://stderr');
        $logger->addWriter($writer);
        Zend_Registry::set('logger',$logger);
        return $logger;
    }
}