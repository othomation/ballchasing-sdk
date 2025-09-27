<?php

namespace Lucie\BallchasingLaravel\DTOs;

class GroupDetails
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $created,
        public readonly ?string $link = null,
        public readonly ?array $stats = null,
        public readonly ?bool $shared = null,
        public readonly ?string $type = null,
        public readonly ?array $uploader = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            name: $data['name'],
            created: $data['created'],
            link: $data['link'] ?? null,
            stats: $data['stats'] ?? null,
            shared: $data['shared'] ?? null,
            type: $data['type'] ?? null,
            uploader: $data['uploader'] ?? null
        );
    }
}