<?php

namespace Lucie\BallchasingLaravel\DTOs;

class ReplayDetails
{
    public function __construct(
        public readonly string $id,
        public readonly string $title,
        public readonly string $created,
        public readonly ?string $mapCode = null,
        public readonly ?int $duration = null,
        public readonly ?string $link = null,
        public readonly ?string $downloadLink = null,
        public readonly ?array $teams = null,
        public readonly ?array $players = null,
        public readonly ?array $visibility = null,
        public readonly ?array $uploader = null,
        public readonly ?string $status = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            title: $data['title'],
            created: $data['created'],
            mapCode: $data['map_code'] ?? null,
            duration: $data['duration'] ?? null,
            link: $data['link'] ?? null,
            downloadLink: $data['download'] ?? null,
            teams: $data['teams'] ?? null,
            players: $data['players'] ?? null,
            visibility: $data['visibility'] ?? null,
            uploader: $data['uploader'] ?? null,
            status: $data['status'] ?? null
        );
    }
}