<?php

use App\SimplyPlural\Member;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use function App\fetch_context_member;
use function App\fetch_current_fronters;

header('Content-Type: application/json');

try {
    $fronters = fetch_current_fronters($_ENV['SP_TOKEN'], $_ENV['SP_ID']);
} catch (HttpExceptionInterface|DecodingExceptionInterface|TransportExceptionInterface $e) {
    error_log($e->getMessage());
    echo json_encode([]);
    exit;
}

echo json_encode(array_map(fn(Member $member) => fetch_context_member($member), $fronters));
