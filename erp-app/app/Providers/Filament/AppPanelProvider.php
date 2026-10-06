<?php

namespace App\Providers\Filament;

use App\Filament\Pages\VacationCalendar;
use App\Filament\Widgets\ControlsDue;
use App\Filament\Widgets\ErpStats;
use App\Filament\Widgets\InspectionsDue;
use App\Filament\Widgets\LowStock;
use App\Filament\Widgets\OpenDamages;
use App\Providers\Filament\Concerns\ConfiguresErpPanel;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;

/**
 * The employee side: every active user, with access to modules based on their role's permissions.
 */
class AppPanelProvider extends PanelProvider
{
    use ConfiguresErpPanel;

    public function panel(Panel $panel): Panel
    {
        return $this->configureErpPanel($panel, 'app', '', 'ERP')
            ->default()
            ->discoverResources(in: app_path('Filament/App/Resources'), for: 'App\Filament\App\Resources')
            ->discoverPages(in: app_path('Filament/App/Pages'), for: 'App\Filament\App\Pages')
            ->discoverWidgets(in: app_path('Filament/App/Widgets'), for: 'App\Filament\App\Widgets')
            ->pages([
                Dashboard::class,
                VacationCalendar::class,
            ])
            ->widgets([
                ErpStats::class,
                ControlsDue::class,
                InspectionsDue::class,
                OpenDamages::class,
                LowStock::class,
            ]);
    }
}
