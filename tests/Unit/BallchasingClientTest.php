<?php

namespace Lucie\BallchasingLaravel\Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Lucie\BallchasingLaravel\Services\BallchasingClient;
use Lucie\BallchasingLaravel\Tests\TestCase;
use Lucie\BallchasingLaravel\Exceptions\BallchasingException;

class BallchasingClientTest extends TestCase
{
    private BallchasingClient $client;
    private MockHandler $mockHandler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockHandler = new MockHandler();
        $handlerStack = HandlerStack::create($this->mockHandler);
        $httpClient = new Client(['handler' => $handlerStack]);

        $this->client = new BallchasingClient(
            apiKey: 'test-api-key',
            baseUrl: 'https://ballchasing.com/api',
            timeout: 30,
            retryAttempts: 0,
            retryDelay: 0,
            httpClient: $httpClient
        );
    }

    public function test_client_can_be_instantiated(): void
    {
        $client = new BallchasingClient('test-api-key');

        $this->assertInstanceOf(BallchasingClient::class, $client);
    }

    public function test_get_replays_makes_correct_http_request(): void
    {
        $responseData = [
            'list' => [
                [
                    'id' => 'replay-1',
                    'title' => 'Test Replay 1',
                    'created' => '2024-01-01T00:00:00Z'
                ]
            ],
            'count' => 1
        ];

        $this->mockHandler->append(
            new Response(200, [], json_encode($responseData))
        );

        $result = $this->client->getReplays(['title' => 'test']);

        $this->assertNotNull($result);
        $this->assertEquals(1, $result->count);
        $this->assertEquals('replay-1', $result->replays[0]->id);
    }

    public function test_get_replay_makes_correct_http_request(): void
    {
        $responseData = [
            'id' => 'replay-1',
            'title' => 'Test Replay',
            'created' => '2024-01-01T00:00:00Z',
            'map_code' => 'stadium_p',
            'duration' => 300
        ];

        $this->mockHandler->append(
            new Response(200, [], json_encode($responseData))
        );

        $result = $this->client->getReplay('replay-1');

        $this->assertNotNull($result);
        $this->assertEquals('replay-1', $result->id);
        $this->assertEquals('Test Replay', $result->title);
    }

    public function test_client_throws_exception_on_api_error(): void
    {
        $this->mockHandler->append(
            new Response(401, [], json_encode(['error' => 'Unauthorized']))
        );

        $this->expectException(BallchasingException::class);
        $this->expectExceptionMessage('Invalid or missing API key');

        $this->client->getReplays();
    }

    public function test_client_throws_exception_on_invalid_response(): void
    {
        $this->mockHandler->append(
            new Response(200, [], 'invalid-json')
        );

        $this->expectException(BallchasingException::class);
        $this->expectExceptionMessage('Failed to decode API response');

        $this->client->getReplays();
    }

    public function test_get_groups_makes_correct_http_request(): void
    {
        $responseData = [
            'list' => [
                [
                    'id' => 'group-1',
                    'name' => 'Test Group',
                    'created' => '2024-01-01T00:00:00Z'
                ]
            ],
            'count' => 1
        ];

        $this->mockHandler->append(
            new Response(200, [], json_encode($responseData))
        );

        $result = $this->client->getGroups();

        $this->assertNotNull($result);
        $this->assertEquals(1, $result->count);
        $this->assertEquals('group-1', $result->groups[0]->id);
    }
}