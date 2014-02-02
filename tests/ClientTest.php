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

    public function testPing () {
        $response = $this->drill->api('users/ping2');

        $this->assertArrayHasKey('PING', $response);
        $this->assertSame($response['PING'], 'PONG!');
    }
}