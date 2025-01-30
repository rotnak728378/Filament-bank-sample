<?php

namespace App\Filament\Pages;

use App\Models\Service;
use Filament\Pages\Page;

class ServicePage extends Page
{
    protected static ?int $navigationSort = 7;
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $title = '';

    protected static ?string $navigationLabel = 'Services';

    protected static ?string $slug = 'services';

    protected static string $view = 'filament.pages.service-page';

    public function getViewData(): array
    {
        return [
            'services' => Service::query()
                ->where('status', 'active')
                ->get()
        ];
    }
}
