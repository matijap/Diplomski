<?php   
    //Including configuration php file, with all defined variables
    include("config.php");

    include("autoload.php");
    
    /** Zend_Application */
    require_once 'Zend/Application.php';
    // Create application, bootstrap, and run     
    $application = new Zend_Application(
        APPLICATION_ENV,
        APPLICATION_PATH . '/configs/application.xml'
    );

    function fb($msg, $label=null) {
        if ($label != null) {
            $msg = array($label,$msg);
        }
        $logger = Zend_Registry::get('logger');
        $msg    = print_r($msg,true);
        $logger->info($msg);
    }

    $application->bootstrap();

    if (!defined('RUN_APP')) {
        $application->run();
    }

    function getTranslate() {
        return Zend_Registry::getInstance()->Zend_Translate;
    }