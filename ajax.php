<?php

$src = 'ElephantIO';

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
 

use ElephantIO\Client as Elephant;
use ElephantIO\Engine\SocketIO\Version1X as Version1X;

$elephant = new Elephant(new Version1X('http://localhost:3000'));

$elephant->initialize();
$elephant->emit('quiz_starting', ['status' => true, 'quiz_id' => 1]);
$elephant->close();

//http://www.tamas.io/simple-chat-application-using-node-js-and-socket-io/