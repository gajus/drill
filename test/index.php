<?php
namespace gajus\drill;

set_include_path( __DIR__ . '/../src/' );

spl_autoload_register();

$mapi = new Drill('Wy_E0fNY5PX5LCJIo6mf8Q'); // This API key works only from certain IPs.

var_dump(
	$mapi->api('users/ping'),
	$mapi->api('messages/send',
		['message' => [
				'text' => 'test',
				'subject' => 'test',
				'from_email' => 'gk@anuary.com',
				'from_name' => 'Gajus Kuizinas',
				'to' => [
					['name' => 'Gajus Kuizinas', 'email' => 'g.kuizinas@anuary.com']
				]
			]
		]),
	$mapi->api('messages/send',
		['message' => [
				'text' => 'test',
				'subject' => 'test',
				'from_email' => 'invalid email',
				'from_name' => 'Gajus Kuizinas',
				'to' => [
					['name' => 'Gajus Kuizinas', 'email' => 'g.kuizinas@anuary.com']
				]
			]
		])
);