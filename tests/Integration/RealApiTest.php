<?php

namespace Lucie\BallchasingLaravel\Tests\Integration;

use Lucie\BallchasingLaravel\Tests\IntegrationTestCase;

/**
 * Integration tests with real Ballchasing API
 *
 * To run these tests:
 * 1. Copy .env.testing to .env
 * 2. Add your real API key to .env
 * 3. Run: composer test-integration
 */
class RealApiTest extends IntegrationTestCase
{
    public function test_can_get_maps_from_real_api(): void
    {
        $maps = $this->client->getMaps();

        $this->assertIsArray($maps);
        $this->assertNotEmpty($maps);
        $this->assertArrayHasKey('stadium_p', $maps);
        $this->assertEquals('DFH Stadium', $maps['stadium_p']);
    }

    public function test_can_get_replays_from_real_api(): void
    {
        $result = $this->client->getReplays(['count' => 1]);

        $this->assertNotNull($result);
        $this->assertIsArray($result->replays);
        $this->assertGreaterThanOrEqual(0, $result->count);

        if (!empty($result->replays)) {
            $replay = $result->replays[0];
            $this->assertNotEmpty($replay->id);
            $this->assertNotEmpty($replay->title);
            $this->assertNotEmpty($replay->created);
        }
    }

    public function test_can_get_groups_from_real_api(): void
    {
        $result = $this->client->getGroups(['count' => 1]);

        $this->assertNotNull($result);
        $this->assertIsArray($result->groups);
        $this->assertGreaterThanOrEqual(0, $result->count);
    }
}