<?php

namespace Lucie\BallchasingLaravel\Contracts;

use Lucie\BallchasingLaravel\DTOs\ReplayCollection;
use Lucie\BallchasingLaravel\DTOs\ReplayDetails;
use Lucie\BallchasingLaravel\DTOs\GroupCollection;
use Lucie\BallchasingLaravel\DTOs\GroupDetails;

interface BallchasingClientInterface
{
    public function getReplays(array $filters = []): ReplayCollection;

    public function getReplay(string $replayId): ReplayDetails;

    public function uploadReplay(string $filePath, array $metadata = []): ReplayDetails;

    public function deleteReplay(string $replayId): bool;

    public function updateReplay(string $replayId, array $data): ReplayDetails;

    public function getGroups(array $filters = []): GroupCollection;

    public function getGroup(string $groupId): GroupDetails;

    public function createGroup(array $data): GroupDetails;

    public function updateGroup(string $groupId, array $data): GroupDetails;

    public function deleteGroup(string $groupId): bool;

    public function getMaps(): array;

}