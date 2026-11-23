<?php

declare(strict_types=1);

namespace App\Packagist;

use App\Packagist\Commands\RefreshStatsCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;

final class PackagistServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([RefreshStatsCommand::class]);

        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
            $schedule->command(RefreshStatsCommand::class)
                ->dailyAt('03:00')
                ->timezone('Europe/Madrid');
        });
    }
}
