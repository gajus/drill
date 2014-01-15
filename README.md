# Drill

[Mandrill API](https://mandrillapp.com/api/docs/) interface.

The only provided method is `api`:

```php
$drill = new \gajus\drill\Drill([$key]);
$drill->api($path, [array $parameters = []]);
```

The output is the JSON response converted to an associative array:

```
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

Request is considered successful when `status` is no eq. to "error".