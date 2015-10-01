<?php

require_once 'Notification/Row.php';

class Notification extends Notification_Row
{
    const STATUS_NEW  = 'NEW';
    const STATUS_SEEN = 'SEEN';

    const TYPE_FRIEND_REQUEST = 'FRIEND_REQUEST';
}