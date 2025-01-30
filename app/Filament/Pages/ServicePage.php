<?php

namespace App\Filament\Pages;

use App\Models\Service;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Model;

class ServicePage extends Page
{
    protected static ?int $navigationSort = 7;
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $title = '';

    protected static ?string $navigationLabel = 'Services';

    protected static ?string $slug = 'services';

    protected static string $view = 'filament.pages.service-page';

    public static function getGlobalSearchResultTitle(Model $record): string|\Illuminate\Contracts\Support\Htmlable
    {
        return 'Services';
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Active Services' => Service::where('status', 'active')->count() . ' services available',
            'Description' => 'View and manage available services'
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'services',
            'service',
            'manage services'
        ];
    }

    public static function getGlobalSearchResults(string $search)
    {
        // Return a result if searching for 'service' related terms
        if (str_contains(strtolower($search), 'service')) {
            return collect([new class {
                public $id = 'services';
            }]);
        }
        return collect();
    }

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return static::getUrl();
    }

    public function getViewData(): array
    {
        return [
            'services' => Service::query()
                ->where('status', 'active')
                ->get()
        ];
    }
}
