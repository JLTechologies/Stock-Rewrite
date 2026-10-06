<?php

namespace Database\Seeders;

use App\Models\AssetCategory;
use App\Models\Role;
use Illuminate\Database\Seeder;

/**
 * Starting data for a fresh install: default roles and equipment categories.
 * Create the first administrator with `php artisan erp:create-user --admin`.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'Beheerder' => ['description' => 'Volledige toegang, ook tot de beheerkant.', 'is_admin' => true, 'permissions' => []],
            'Ploegleider' => ['description' => 'Beheert de eigen ploeg, voertuigen en materieel.', 'is_admin' => false, 'permissions' => [
                'teams' => ['view', 'update'],
                'vehicles' => ['view', 'update'],
                'assets' => ['view', 'update'],
                'asset_logs' => ['view', 'create', 'update'],
                'vacations' => ['view', 'approve'],
                'order_references' => ['view', 'create', 'update'],
            ]],
            'Medewerker' => ['description' => 'Ziet het materieel van de ploeg en kan schade melden.', 'is_admin' => false, 'permissions' => [
                'asset_logs' => ['create'],
            ]],
            'Gast / Magazijn' => ['description' => 'Medewerkers zonder ploeg: geen rechten.', 'is_admin' => false, 'is_guest' => true, 'permissions' => []],
        ];

        foreach ($roles as $name => $attributes) {
            Role::firstOrCreate(['name' => $name], $attributes);
        }

        $categories = [
            'Meettoestellen' => 12,
            'Elektrisch handgereedschap' => 12,
            'Verlengkabels & haspels' => 12,
            'Ladders & stellingen' => 12,
            'Persoonlijke beschermingsmiddelen' => 6,
            'Werfverlichting & werfkasten' => 12,
        ];

        foreach ($categories as $name => $months) {
            AssetCategory::firstOrCreate(['name' => $name], ['inspection_interval_months' => $months]);
        }
    }
}
