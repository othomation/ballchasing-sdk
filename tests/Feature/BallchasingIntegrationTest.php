<?php

namespace Lucie\BallchasingLaravel\Tests\Feature;

use Lucie\BallchasingLaravel\Services\BallchasingClient;
use Lucie\BallchasingLaravel\Tests\TestCase;

class BallchasingIntegrationTest extends TestCase
{
    private BallchasingClient $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new BallchasingClient('NlnjktkFtbXhcQtLU3LG6V73Z0Cv5ytLwkN6Qifo');
    }

    public function test_can_get_replays_from_api(): void
    {
        $result = $this->client->getReplays(['count' => 5]);

        $this->assertNotNull($result);
        $this->assertIsArray($result->replays);
        $this->assertLessThanOrEqual(5, count($result->replays));

        if (!empty($result->replays)) {
            $replay = $result->replays[0];
            $this->assertNotEmpty($replay->id);
            $this->assertNotEmpty($replay->title);
            $this->assertNotEmpty($replay->created);
        }
    }

    public function test_can_get_maps_from_api(): void
    {
        $maps = $this->client->getMaps();

        $this->assertIsArray($maps);
        $this->assertNotEmpty($maps);
        $this->assertArrayHasKey('stadium_p', $maps);
    }

}