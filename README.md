# Drill

[Mandrill API](https://mandrillapp.com/api/docs/) interface with no bells and whistles. Drill Client implementation makes no assumptions about the underlying API schema or required parameters. Therefore, it is not an abstraction that will prevent your code from braking if/when Mandrill API changes. It is marely used to interfact with the API endpoint and handle errors.

The only provided method is `api`

```php
$drill = new \gajus\drill\Gajus([$key]);
$drill->api($path, [array $parameters = []]);
```

Mandrill response is converted to an associative array:

```php
array(1) {
  [0]=>
  array(4) {
    ["email"]=>
    string(21) "g.kuizinas@anuary.com"
    ["status"]=>
    string(4) "sent"
    ["_id"]=>
    string(32) "67224c7d591042f9bb083b81dad67fa0"
    ["reject_reason"]=>
    NULL
  }
}
```

Request is considered successful when `status` is no equal to "error".