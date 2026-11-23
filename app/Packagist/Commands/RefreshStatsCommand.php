<?php

declare(strict_types=1);

namespace App\Packagist\Commands;

use App\Data\Repositories;
use App\Packagist\Stats;
use Illuminate\Console\Command;

final class RefreshStatsCommand extends Command
{
    protected $signature = 'packagist:refresh-stats';

    protected $description = 'Refresh Packagist stats (GitHub stars and downloads) for all repositories';

    public function handle(Stats $stats): int
    {
        $names = collect(Repositories::all())
            ->map(fn (array $repository) => $repository['owner'].'/'.$repository['repository'])
            ->values()
            ->toArray();

        $stats->refresh($names);

        $this->info('Refreshed stats for '.count($names).' packages.');

        $this->table(
            ['Package', 'GitHub stars', 'Downloads'],
            collect($stats->stored())
                ->map(fn (array $row, string $name) => [
                    $name,
                    $row['github_starts'],
                    number_format($row['downloads']),
                ])
                ->values()
                ->toArray(),
        );

        return self::SUCCESS;
    }
}
