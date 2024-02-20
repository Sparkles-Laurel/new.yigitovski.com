<?php

declare(strict_types=1);

namespace App\SimplyPlural;

readonly class Response
{
    public bool $exists;
    public string $id;
    public mixed $content;

    public function __construct(array $data)
    {
        $this->exists = $data['exists'];
        $this->id = $data['id'];
        $this->content = $data['content'];
    }

    public function asMember(): Member
    {
        return new Member($this->content);
    }

    public function asFront(): Front
    {
        return new Front($this->content);
    }
}
