<?php

namespace Lucie\BallchasingLaravel\DTOs;

class ReplayCollection
{
    public function __construct(
        public readonly array $replays,
        public readonly int $count
    ) {}

    public static function fromArray(array $data): self
    {
        $replays = array_map(
            fn(array $replay) => ReplayDetails::fromArray($replay),
            $data['list'] ?? []
        );

        return new self(
            replays: $replays,
            count: $data['count'] ?? count($replays)
        );
    }
}