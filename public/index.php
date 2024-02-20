<?php

declare(strict_types=1);

use App\SimplyPlural\Member;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;
use function App\fetch_context_member;
use function App\fetch_current_fronters;
use const App\FRONTERS_CACHE_KEY;

require_once(__DIR__ . '/../vendor/autoload.php');

if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../');
    $dotenv->load();
}

$loader = new FilesystemLoader(__DIR__ . '/../templates');
$twig = new Environment($loader, [
//    'cache' => __DIR__ . '/../cache'
]);

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($path) {
    case '/f':
    case '/':
    case '':
        // if scraper, force fetch, they care less about response time
        $mapped_fronters = null;

        if (apcu_exists(FRONTERS_CACHE_KEY)
            || !str_contains($_SERVER['HTTP_USER_AGENT'], 'Mozilla')
            || (isset($_GET['force']))
        ) {
            try {
                $fronters = fetch_current_fronters(getenv('SP_TOKEN'), getenv('SP_ID'));
                $mapped_fronters = array_map(fn(Member $member) => (array)fetch_context_member($member), $fronters);
                header('Cache-Control: no-cache');
            } catch (HttpExceptionInterface|DecodingExceptionInterface|TransportExceptionInterface $e) {
                error_log($e->getMessage());
            }
        }

        try {
            echo $twig->render(
                'index.twig',
                [
                    'fronters' => $mapped_fronters,
                    'secret' => !($path == '/f')
                ]
            );
        } catch (LoaderError|SyntaxError $e) {
            // shouldn't happen
            http_response_code(500);
            error_log("this shouldn't happen..." . $e->getMessage());
            echo "this shouldn't happen...";
            exit;
        } catch (RuntimeError $e) {
            // unlikely
            http_response_code(500);
            error_log($e->getMessage());
            echo 'failed to load page';
            exit;
        }

        break;
    case '/fronters':
        header('Cache-Control: no-cache');
        require(__DIR__ . '/../views/fronters.php');
        break;
    default:
        http_response_code(404);
        break;
}
