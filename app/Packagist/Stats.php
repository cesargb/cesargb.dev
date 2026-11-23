<?php

declare(strict_types=1);

namespace App\Packagist;

final class Stats
{
    public function refresh(array $names): void
    {
        $stored = $this->stored();

        foreach ($names as $name) {
            $stats = Client::stats($name);

            if ($stats) {
                $stored[$name] = [
                    'github_starts' => $stats->githubStars,
                    'downloads' => $stats->downloads,
                ];
            }
        }

        file_put_contents(
            $this->filename(),
            '<?php return '.var_export($stored, true).';'.PHP_EOL
        );

    }

    public function stored(): array
    {
        $filename = $this->filename();

        if (file_exists($filename)) {
            return include $filename;
        }

        return [];
    }

    /**
     * The stored stats of one package ("owner/repository"), or null when it was never refreshed.
     *
     * @return array{github_starts: int, downloads: int}|null
     */
    public function for(string $name): ?array
    {
        return $this->stored()[$name] ?? null;
    }

    private function filename(): string
    {
        return storage_path('app/repositories_stats.php');
    }
}
