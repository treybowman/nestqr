<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Append .com to any existing domain that doesn't already contain a dot
        DB::table('active_domains')
            ->whereRaw("domain NOT LIKE '%.%'")
            ->update(['domain' => DB::raw("CONCAT(domain, '.com')")]);

        DB::table('users')
            ->whereNotNull('preferred_domain')
            ->whereRaw("preferred_domain NOT LIKE '%.%'")
            ->update(['preferred_domain' => DB::raw("CONCAT(preferred_domain, '.com')")]);
    }

    public function down(): void
    {
        // Strip .com suffix — best-effort rollback
        DB::table('active_domains')
            ->where('domain', 'like', '%.com')
            ->update(['domain' => DB::raw("REPLACE(domain, '.com', '')")]);

        DB::table('users')
            ->whereNotNull('preferred_domain')
            ->where('preferred_domain', 'like', '%.com')
            ->update(['preferred_domain' => DB::raw("REPLACE(preferred_domain, '.com', '')")]);
    }
};
