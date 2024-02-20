<?php

declare(strict_types=1);

namespace App;

use App\SimplyPlural\Client;
use App\SimplyPlural\Front;
use App\SimplyPlural\Member;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

const FRONTERS_CACHE_KEY = 'current_fronters';
const CONTEXT_MEMBER_CACHE_KEY = 'context_member';
const FRONTERS_TTL = 600;
// context_member lives forever as we'll just restart server
const MEMBER_DATA_LOCATION = __DIR__ . '/../data/members.json';

/**
 * @param string $token
 * @param string $id
 * @return Member[]|null
 * @throws ClientExceptionInterface
 * @throws DecodingExceptionInterface
 * @throws RedirectionExceptionInterface
 * @throws ServerExceptionInterface
 * @throws TransportExceptionInterface
 */
function fetch_current_fronters(string $token, string $id): ?array
{
    if (apcu_exists(FRONTERS_CACHE_KEY)) {
        return apcu_fetch(FRONTERS_CACHE_KEY);
    }

    $client = new Client($token);

    $fronters = $client->getFronters();
    $members = $client->getMembers($id, true);

    $member_map = [];

    foreach ($members as $member) {
        $member_map[$member['id']] = new Member($member['content']);
    }

    $result = array_map(fn(Front $front) => $member_map[$front->member], $fronters);

    apcu_store(FRONTERS_CACHE_KEY, $result, FRONTERS_TTL);

    return $result;
}

function fetch_context_member(Member $member): ?ContextMember
{
    if ($member->pkId == null) {
        return new ContextMember((array)$member);
    }

    if (apcu_exists(CONTEXT_MEMBER_CACHE_KEY)) {
        return apcu_fetch(CONTEXT_MEMBER_CACHE_KEY)[$member->pkId] ?? new ContextMember((array)$member);
    }

    $data = json_decode(file_get_contents(MEMBER_DATA_LOCATION), true);

    $members = array_map(fn(array $data): ContextMember => new ContextMember($data), $data);

    apcu_store(CONTEXT_MEMBER_CACHE_KEY, $members);

    return $members[$member->pkId] ?? new ContextMember((array)$member);
}
