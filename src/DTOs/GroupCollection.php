<?php

namespace Lucie\BallchasingLaravel\DTOs;

class GroupCollection
{
    public function __construct(
        public readonly array $groups,
        public readonly int $count
    ) {}

    public static function fromArray(array $data): self
    {
        $groups = array_map(
            fn (array $group) => GroupDetails::fromArray($group),
            $data['list'] ?? []
        );

        return new self(
            groups: $groups,
            count: $data['count'] ?? count($groups)
        );
    }
}
