<?php

namespace App\Support;

/**
 * Every permission a role can be granted on the employee side, grouped per area.
 * For "vacations", view and approve cover the requests of the user's team members;
 * requesting your own vacation needs no permission. "stock.adjust" lets employees raise or lower
 * quantities (e.g. after scanning a QR code) at the locations of their teams. "stock_prices.view"
 * shows market prices and stock values, which hold the special prices agreed with distributors.
 * A role stores them as {"vehicles": ["view", "update"], "assets": ["view"], ...}.
 * Administrator roles are granted everything and may also use the admin panel.
 */
class Permissions
{
    /**
     * Area => [module the area belongs to, abilities].
     *
     * @var array<string, array{module: string, abilities: list<string>}>
     */
    public const AREAS = [
        'teams' => ['module' => Modules::TEAMS, 'abilities' => ['view', 'create', 'update', 'delete']],
        'vehicles' => ['module' => Modules::FLEET, 'abilities' => ['view', 'create', 'update', 'delete']],
        'assets' => ['module' => Modules::ASSETS, 'abilities' => ['view', 'create', 'update', 'delete']],
        'asset_logs' => ['module' => Modules::ASSETS, 'abilities' => ['view', 'create', 'update', 'delete']],
        'vacations' => ['module' => Modules::VACATIONS, 'abilities' => ['view', 'approve']],
        'order_references' => ['module' => Modules::ORDERS, 'abilities' => ['view', 'create', 'update', 'delete']],
        'locations' => ['module' => Modules::LOCATIONS, 'abilities' => ['view', 'create', 'update', 'delete']],
        'stock_items' => ['module' => Modules::STOCK, 'abilities' => ['view', 'create', 'update', 'delete']],
        'stock' => ['module' => Modules::STOCK, 'abilities' => ['adjust']],
        'stock_prices' => ['module' => Modules::STOCK, 'abilities' => ['view']],
        'stock_master_data' => ['module' => Modules::STOCK, 'abilities' => ['view', 'create', 'update', 'delete']],
        'it_assets' => ['module' => Modules::IT, 'abilities' => ['view', 'create', 'update', 'delete', 'checkout', 'audit']],
        'it_licenses' => ['module' => Modules::IT, 'abilities' => ['view', 'create', 'update', 'delete', 'checkout', 'view_keys']],
        'it_items' => ['module' => Modules::IT, 'abilities' => ['view', 'create', 'update', 'delete', 'checkout']],
        'it_master_data' => ['module' => Modules::IT, 'abilities' => ['view', 'create', 'update', 'delete']],
    ];

    /**
     * Every permission, e.g. ['vehicles' => ['view', 'create', ...], ...].
     *
     * @return array<string, list<string>>
     */
    public static function all(): array
    {
        return array_map(fn (array $area): array => $area['abilities'], self::AREAS);
    }

    public static function moduleOf(string $area): ?string
    {
        return self::AREAS[$area]['module'] ?? null;
    }

    /**
     * Keep only known areas and abilities, so a crafted request cannot store
     * permissions that do not exist.
     *
     * @param  array<string, mixed>  $permissions
     * @return array<string, list<string>>
     */
    public static function sanitize(array $permissions): array
    {
        $clean = [];

        foreach (self::all() as $area => $abilities) {
            $granted = array_values(array_intersect($abilities, (array) ($permissions[$area] ?? [])));

            if ($granted !== []) {
                $clean[$area] = $granted;
            }
        }

        return $clean;
    }
}
