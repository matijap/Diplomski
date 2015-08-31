<?php 
    error_reporting(E_ALL);
    // Define path to application directory
    if (!defined('APPLICATION_PATH')){
        define("APPLICATION_PATH","/Users/sredoje/Desktop/git/Diplomski/application"); 
    }
      
    if (!defined('WEB_ROOT_PATH')){
        define("WEB_ROOT_PATH","/Users/sredoje/Desktop/git/Diplomski/public"); 
    }
    
    if (!defined('ROOT_PATH')) {
      define("ROOT_PATH", "/Users/sredoje/Desktop/git/Diplomski");
    }
    
    if (!defined('APP_URL')){
        define("APP_URL","http://local.dip"); 
    }
        
    // Define application environment
    defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
?>