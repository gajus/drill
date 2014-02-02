# Drill

[![Build Status](https://travis-ci.org/gajus/drill.png?branch=master)](https://travis-ci.org/gajus/drill)
[![Coverage Status](https://coveralls.io/repos/gajus/drill/badge.png)](https://coveralls.io/r/gajus/drill)

[Mandrill API](https://mandrillapp.com/api/docs/) interface with no bells and whistles. Drill Client implementation makes no assumptions about the underlying API schema or required parameters. However, it will throw an exception when you do not provide the required parameters based on the response. Therefore, it is not an abstraction that will prevent your code from braking if/when Mandrill API changes. It is marely used to interact with the API endpoint and handle errors. That said, Mandrill RESTful API implements versioning, which ought to prevent breaking code that is using Drill implementation when API changes occur.

The only provided method is `api`

```php
$drill = new \gajus\drill\Gajus('fxBTBjWKxJ05K9MjkFak1A' /* api key*/);
$response = $drill->api('messages/send' /* endpoint */, [
    'message' => [
        'text' => 'Test',
        'subject' => 'test',
        'from_email' => 'dummy@gajus.com',
        'to' => [
            ['email' => 'dummy@gajus.com']
        ],
    ]
] /* parameters */);
```

Mandrill response is converted to an associative array:

```php
array(1) {
  [0]=>
  array(4) {
    ["email"]=>
    string(15) "dummy@gajus.com"
    ["status"]=>
    string(4) "sent"
    ["_id"]=>
    string(32) "f65f65c266f74e2884344ccfff3bb337"
    ["reject_reason"]=>
    NULL
  }
}
```

## Handling errors

Cases that can be before making request to the API or rules that are enforced by Drill implmentation, as opposed to to API spec, will throw `InvalidArgumentException`.

All errors that occur during the runtime will result in `gajus\drill\exception\Error` exception, which in turn extends `RuntimeException`. All error specific exceptions extend `gajus\drill\exception\Error`.

Beware that errors returned from Mandrill API have inconsistent naming convention (PascalCase vs underscore, e.g. "UserError", "Invalid_Key"). Drill will cast all errors to underscore convention (e.g. "UserError" becomes "User_Error").