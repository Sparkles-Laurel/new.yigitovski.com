<?php

declare(strict_types=1);

namespace App\SimplyPlural;

readonly class Front
{
    public bool $custom;
    public int $startTime;
    public ?string $member;
    public bool $live;
    public ?int $endTime;
    public string $uid;
    public ?int $lastOperationTime;

    public function __construct(array $data)
    {
        $this->custom = $data['custom'];
        $this->startTime = $data['startTime'];
        $this->member = $data['member'] ?? null;
        $this->live = $data['live'];
        $this->endTime = $data['endTime'] ?? null;
        $this->uid = $data['uid'];
        $this->lastOperationTime = $data['lastOperationTime'] ?? null;
    }
}
