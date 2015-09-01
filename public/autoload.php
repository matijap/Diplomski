<?php
    $modules = array('main', 'login');
    $toLoad  = array('models', 'views/forms');

    $array = array();
    foreach ($modules as $oneModule) {
        foreach ($toLoad as $oneToLoad) {
            $array[] = realpath(APPLICATION_PATH . "/modules/$oneModule/$oneToLoad");
        }
    }
    $array[] = realpath(APPLICATION_PATH . "/classes");
    $array[] = realpath(APPLICATION_PATH . '/../library');
    $array[] = realpath('/lib/Zend/library');
    $array[] = get_include_path();

    set_include_path(implode(PATH_SEPARATOR, $array));