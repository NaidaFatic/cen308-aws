<?php

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase {

    protected $client;

    protected function setUp(): void {
        $mock = new MockHandler([
            new Response(200, [], json_encode(['status' => 'online'])),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $this->client = new Client(['handler' => $handlerStack]);
    }

    public function testStatusEndpoint() {
        $response = $this->client->get('status');

        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('status', $data);
        $this->assertEquals('online', $data['status']);
    }
}