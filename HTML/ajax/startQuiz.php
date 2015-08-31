<?php

require_once("autoload.php");

use ElephantIO\Client as Elephant;
use ElephantIO\Engine\SocketIO\Version1X as Version1X;


$elephant = new Elephant(new Version1X('http://localhost:3000'));

$elephant->initialize();
$elephant->emit('quiz_starting', ['status' => true, 'quiz_id' => 1]);

$elephant->close();

//http://www.tamas.io/simple-chat-application-using-node-js-and-socket-io/