<?php

declare(strict_types=1);

namespace App\Packagist;

use App\Packagist\Responses\Stats;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

final class Client
{
    public static function stats(string $name): ?Stats
    {
        try {
            $response = Http::asJson()
                ->acceptJson()
                ->timeout(3)
                ->get("https://packagist.org/packages/{$name}.json")
                ->json();

            return new Stats(
                githubStars: data_get($response, 'package.github_stars', 0),
                downloads: data_get($response, 'package.downloads.total', 0),
                downloadsDaily: data_get($response, 'package.downloads.daily', 0),
                downloadsMonthly: data_get($response, 'package.downloads.monthly', 0),
            );
        } catch (ConnectionException) {
            return null;
        }

    }
}
