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

    $src = '../vendor/ElephantIO';

    require_once("$src/Client.php");
    require_once("$src/EngineInterface.php");
    require_once("$src/AbstractPayload.php");
    require_once("$src/Exception/SocketException.php");
    require_once("$src/Exception/MalformedUrlException.php");
    require_once("$src/Exception/ServerConnectionFailureException.php");
    require_once("$src/Exception/UnsupportedActionException.php");
    require_once("$src/Exception/UnsupportedTransportException.php");

    require_once("$src/Engine/AbstractSocketIO.php");
    require_once("$src/Engine/SocketIO/Session.php");
    require_once("$src/Engine/SocketIO/Version0X.php");
    require_once("$src/Engine/SocketIO/Version1X.php");
    require_once("$src/Payload/Decoder.php");
    require_once("$src/Payload/Encoder.php"); 