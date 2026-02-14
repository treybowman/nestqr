<?php

namespace App\Console\Commands;

use App\Models\ActiveDomain;
use Illuminate\Console\Command;

class SyncDomains extends Command
{
    protected $signature = 'domains:sync';

    protected $description = 'Sync domains from configuration or display current active domains';

    public function handle(): int
    {
        $configDomains = config('nestqr.domains', []);

        if (empty($configDomains)) {
            $this->info('No domains found in configuration. Displaying current active domains:');
            $this->displayActiveDomains();
            return self::SUCCESS;
        }

        $this->info('Syncing domains from configuration...');

        $synced = 0;
        foreach ($configDomains as $domainConfig) {
            $domain = ActiveDomain::updateOrCreate(
                ['domain' => $domainConfig['domain']],
                [
                    'market_name' => $domainConfig['market_name'] ?? 'National',
                    'is_active' => $domainConfig['is_active'] ?? true,
                    'launched_at' => $domainConfig['launched_at'] ?? now(),
                ],
            );

            $action = $domain->wasRecentlyCreated ? 'Created' : 'Updated';
            $this->line("  {$action}: {$domain->domain} ({$domain->market_name})");
            $synced++;
        }

        $this->newLine();
        $this->info("Synced {$synced} domain(s).");

        $this->newLine();
        $this->displayActiveDomains();

        return self::SUCCESS;
    }

    protected function displayActiveDomains(): void
    {
        $domains = ActiveDomain::orderBy('domain')->get();

        if ($domains->isEmpty()) {
            $this->warn('No domains found in the database.');
            return;
        }

        $this->table(
            ['ID', 'Domain', 'Market', 'Active', 'Launched At'],
            $domains->map(fn (ActiveDomain $d) => [
                $d->id,
                $d->domain,
                $d->market_name,
                $d->is_active ? 'Yes' : 'No',
                $d->launched_at?->format('Y-m-d') ?? 'N/A',
            ])->toArray(),
        );
    }
}
