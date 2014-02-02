<?php
class ClientTest extends PHPUnit_Framework_TestCase {
    private
        $drill;

    public function setUp () {
        $this->drill = new \gajus\drill\Client('fxBTBjWKxJ05K9MjkFak1A');
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Endpoint path must not start with /.
     */
    public function testPathStartsWithSlash () {
        $this->drill->api('/users/ping2');
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Endpoint must not include output format.
     */
    public function testPathIncludesOutputFormat () {
        $this->drill->api('users/ping2.json');
    }

    /**
     * @expectedException gajus\drill\exception\Invalid_Key
     * @expectedExceptionMessage Invalid API key
     */
    public function testInvalidCredentials () {
        $drill = new \gajus\drill\Client('foobar');

        $drill->api('users/ping2');
    }

    /**
     * @expectedException gajus\drill\exception\Validation_Error
     * @expectedExceptionMessage Validation error: {"message":{"from_email":"An email address must contain a single @"}}
     */
    public function testInvalidParameters () {
        $this->drill->api('messages/send', [
            'message' => [
                'text' => 'Test',
                'subject' => 'test',
                'from_email' => 'invalidemail',
                'to' => [
                    ['email' => 'dummy@gajus.com']
                ],
            ]
        ]);
    }

    /**
     * @expectedException gajus\drill\exception\User_Error
     * @expectedExceptionMessage Unknown method "users.ping3"
     */
    public function testNotExistingEndoint () {
        $this->drill->api('users/ping3');
    }

    /**
     * Unfortunately, it seems that Mandrill endpoint does not trigger an
     * error if you send a request without all the required parameters.
     * This seem to be a bug, as the expected behaviour is to at least get
     * non 200 HTTP response.
     *
     * @expectedException RuntimeException
     * @expectedExceptionMessage Missing required parameters.
     */
    public function testRequestWithoutRequiredParameters () {
        $response = $this->drill->api('messages/send', [
            'message' => []
        ]);
    }

    public function testPing () {
        $response = $this->drill->api('users/ping2');

        $this->assertArrayHasKey('PING', $response);
        $this->assertSame($response['PING'], 'PONG!');
    }
}