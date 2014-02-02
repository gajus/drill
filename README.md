# Drill

[![Build Status](https://travis-ci.org/gajus/drill.png?branch=master)](https://travis-ci.org/gajus/drill)
[![Coverage Status](https://coveralls.io/repos/gajus/drill/badge.png)](https://coveralls.io/r/gajus/drill)

[Mandrill API](https://mandrillapp.com/api/docs/) interface with no bells and whistles. Drill Client implementation makes no assumptions about the underlying API schema or required parameters. However, it will throw an exception when you do not provide the required parameters based on the response. Therefore, it is not an abstraction that will prevent your code from braking if/when Mandrill API changes. It is marely used to interact with the API endpoint and handle errors. That said, Mandrill RESTful API implements versioning, which ought to prevent breaking code that is using Drill implementation when API changes occur.

The only provided method is `api`

```php
$drill = new \gajus\drill\Client('fxBTBjWKxJ05K9MjkFak1A' /* api key*/);
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

```php
$drill = new \gajus\drill\Client('fxBTBjWKxJ05K9MjkFak1A');

try {
  $response = $drill->api('messages/send', [
      'message' => [
          'text' => 'Test',
          'subject' => 'test',
          'from_email' => 'invalidemail',
          'to' => [
              ['email' => 'dummy@gajus.com']
          ],
      ]
  ]);
} catch (\gajus\drill\exception\Validation_Error $e) {
    // If you are expecting one error more than anything else.
} catch (\gajus\drill\exception\Error $e) {
    // If you do not expect error but one might occur (you might run out of credit).
} catch (\RuntimeException $e) {
    // If error can occur only after the request.
} catch (\InvalidArgumentException $e) {
    // You should not be catching InvalidArgumentException.
    // These should be your guidance during the development only and should lead to a direct bug fix.
} catch (\Exception $e) {
    // Your last resource, catch all.
}
```