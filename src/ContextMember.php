<?php

declare(strict_types=1);

namespace App;

readonly class ContextMember
{
    public string $name;
    public string $pronouns;
    public string $quote;
    public string $song;
    public string $emoji;
    public string $color;
    public ?string $page;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->pronouns = $data['pronouns'] ?? 'unknown';
        $this->quote = $data['quote'] ?? 'unknown';
        $this->song = $data['song'] ?? 'unknown';
        $this->emoji = $data['emoji'] ?? '💖';
        $this->color = $data['color'] ?? '#f5a9b8';
        $this->page = $data['page'] ?? null;
    }
}
