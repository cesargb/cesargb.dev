<?php

declare(strict_types=1);

namespace App\Packagist\Responses;

final readonly class Stats
{
    public function __construct(
        public int $downloads,
        public int $downloadsMonthly,
        public int $downloadsDaily,
        public int $githubStars,
    ) {}
}
