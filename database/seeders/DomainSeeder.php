<?php

namespace Database\Seeders;

use App\Models\ActiveDomain;
use Illuminate\Database\Seeder;

class DomainSeeder extends Seeder
{
    /**
     * Seed the active_domains table with default domains.
     */
    public function run(): void
    {
        $domains = [
            [
                'domain' => 'nestqr',
                'market_name' => 'National',
                'is_active' => true,
                'launched_at' => now(),
            ],
            [
                'domain' => 'nestatl',
                'market_name' => 'Atlanta',
                'is_active' => true,
                'launched_at' => now(),
            ],
        ];

        foreach ($domains as $domainData) {
            ActiveDomain::updateOrCreate(
                ['domain' => $domainData['domain']],
                $domainData,
            );
        }

        $this->command->info('Seeded ' . count($domains) . ' domain(s).');
    }
}
