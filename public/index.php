<?php   
      //Including configuration php file, with all defined variables
      include("config.php");
      
      set_include_path(implode(PATH_SEPARATOR, array( 
          realpath(APPLICATION_PATH . '/modules'),
          realpath(APPLICATION_PATH . '/modules/core/models'),
          realpath(APPLICATION_PATH . '/modules/billing/models'),
          realpath(APPLICATION_PATH . '/modules/billing/views/forms'),
          realpath(APPLICATION_PATH . '/modules/core/views/forms'),
          realpath(APPLICATION_PATH . '/modules/api/models'),          
          realpath(APPLICATION_PATH . '/../library'),
          realpath(APPLICATION_PATH . '/../library/Platforma'),
          realpath(APPLICATION_PATH . '/../library/Zend/library'),
          realpath('/lib/Zend/library'),
          get_include_path(),
      )));
      
      /** Zend_Application */
      require_once ROOT_PATH . '/library/Zend/Application.php';
      // Create application, bootstrap, and run     
      $application = new Zend_Application(
          APPLICATION_ENV,
          APPLICATION_PATH . '/configs/application.xml'
      );
      
      //require_once 'Platforma/Acl.php';

    /**
     * Composer autoloads *** This must happen after bootstrap for proper loading order priority ***
     */
    //require_once realpath(APPLICATION_PATH . '/../../vendor/') . '/autoload.php';

      
    function fb($msg, $label=null)
    {
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
?>