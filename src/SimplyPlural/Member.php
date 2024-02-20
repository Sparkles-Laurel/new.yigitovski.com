<?php

declare(strict_types=1);

namespace App\SimplyPlural;

readonly class Member
{
    public string $name;
    public ?string $avatarUrl;
    public ?string $pronouns;
    public string $uid;
    public ?string $pkId;
    public bool $private;
    public bool $preventTrusted;
    public bool $preventsFrontNotifs;
    public ?string $avatarUuid;
    public ?string $color;
    public string $desc;
    public ?int $lastOperationTime;
    public bool $supportDescMarkdown;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->avatarUrl = $data['avatarUrl'] ?? null;
        $this->pronouns = $data['pronouns'] ?? null;
        $this->uid = $data['uid'];
        $this->pkId = $data['pkId'];
        $this->private = $data['private'];
        $this->preventTrusted = $data['preventTrusted'] ?? false;
        $this->preventsFrontNotifs = $data['preventsFrontNotifs'] ?? false;
        $this->avatarUuid = $data['avatarUuid'] ?? null;
        $this->color = $data['color'] ?? null;
        $this->desc = $data['desc'] ?? '';
        $this->lastOperationTime = $data['lastOperationTime'] ?? null;
        $this->supportDescMarkdown = $data['supportDescMarkdown'] ?? false;
    }
}
