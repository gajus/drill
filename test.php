<?php
namespace ay\mindrill;

require __DIR__ . '/mindrill.class.php';
require __DIR__ . '/mindrill_exception.class.php';

$mapi = new Mindrill('Wy_E0fNY5PX5LCJIo6mf8Q'); // This API key works only from certain IPs. Test case included for reference only.

var_dump($mapi->api('users/ping'), $mapi->api('messages/send', ['message' => ['text' => 'test', 'subject' => 'test', 'from_email' => 'g.kuizinas@anuary.com', 'from_name' => 'Gajus Kuizinas', 'to' => ['email' => 'g.kuizinas@anuary.com']]]));