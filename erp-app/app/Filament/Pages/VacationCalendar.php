<?php

namespace App\Filament\Pages;

use App\Enums\NavigationGroup;
use App\Filament\App\Resources\MyVacations\MyVacationResource;
use App\Models\Team;
use App\Support\VacationCalendar as Calendar;
use BackedEnum;
use Carbon\CarbonImmutable;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Livewire\Attributes\Url;
use UnitEnum;

/**
 * Month view of public holidays and vacation (approved and still pending) of the people the user may see:
 * themselves and their team members, or everyone for administrators.
 */
class VacationCalendar extends Page
{
    protected string $view = 'filament.pages.vacation-calendar';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendar;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::HumanResources;

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'vacation-calendar';

    #[Url]
    public ?string $month = null;

    #[Url]
    public ?int $team = null;

    public static function canAccess(): bool
    {
        return modules()->vacations() && auth()->check();
    }

    public static function getNavigationLabel(): string
    {
        return __('erp.calendar.title');
    }

    public function getTitle(): string
    {
        return __('erp.calendar.title');
    }

    public function mount(): void
    {
        if (! preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', (string) $this->month)) {
            $this->month = now()->format('Y-m');
        }

        if ($this->team !== null && ! array_key_exists($this->team, $this->teamOptions())) {
            $this->team = null;
        }
    }

    public function previousMonth(): void
    {
        $this->month = $this->current()->subMonth()->format('Y-m');
    }

    public function nextMonth(): void
    {
        $this->month = $this->current()->addMonth()->format('Y-m');
    }

    public function thisMonth(): void
    {
        $this->month = now()->format('Y-m');
    }

    public function updatedTeam(mixed $value): void
    {
        $this->team = filled($value) && array_key_exists((int) $value, $this->teamOptions()) ? (int) $value : null;
    }

    /**
     * Teams the user can filter on: all for administrators, their own otherwise.
     *
     * @return array<int, string>
     */
    public function teamOptions(): array
    {
        if (! modules()->teams()) {
            return [];
        }

        return Team::query()->visibleTo(auth()->user())->orderBy('name')->pluck('name', 'id')->all();
    }

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        $month = $this->current();

        $calendar = app(Calendar::class)->month($month->year, $month->month, auth()->user(), $this->team);

        // "month" is taken by the public property (the ?month= query string), so the view gets "monthStart".
        return [
            'monthStart' => $calendar['month'],
            'weeks' => $calendar['weeks'],
            'people' => $calendar['people'],
            'teams' => $this->teamOptions(),
            'dayNames' => collect(range(0, 6))->map(fn (int $offset): string => now()->startOfWeek()->addDays($offset)->locale(app()->getLocale())->translatedFormat('D'))->all(),
        ];
    }

    /**
     * @return array<Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('requestVacation')
                ->label(__('erp.vacations.request'))
                ->icon(Heroicon::OutlinedPlus)
                ->url(fn (): string => MyVacationResource::getUrl(panel: 'app'))
                ->visible(fn (): bool => filament()->getCurrentPanel()?->getId() === 'app'),
        ];
    }

    protected function current(): CarbonImmutable
    {
        return CarbonImmutable::createFromFormat('Y-m-d', ($this->month ?? now()->format('Y-m')).'-01')->startOfDay();
    }
}
