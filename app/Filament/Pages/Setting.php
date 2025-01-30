<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Setting extends Page
{
    protected static ?int $navigationSort = 9;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.setting';
}
