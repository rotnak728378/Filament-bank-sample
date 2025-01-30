<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Notifications\Notification;

class Setting extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Settings';
    protected static ?string $title = 'System Settings';
    protected static ?int $navigationSort = 10;
    protected static string $view = 'filament.pages.setting';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'site_name' => config('app.name'),
            'site_description' => config('app.description'),
            'enable_registration' => true,
            'maintenance_mode' => false,
            'default_locale' => config('app.locale'),
            'timezone' => config('app.timezone'),
            'enable_notifications' => true,
            'mail_from_address' => config('mail.from.address'),
            'mail_from_name' => config('mail.from.name'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Settings')
                    ->tabs([
                        Tab::make('General')
                            ->icon('heroicon-o-globe-alt')
                            ->schema([
                                Section::make('Site Information')
                                    ->description('Basic information about your application')
                                    ->schema([
                                        TextInput::make('site_name')
                                            ->required()
                                            ->maxLength(50)
                                            ->label('Site Name'),
                                        TextInput::make('site_description')
                                            ->maxLength(255)
                                            ->label('Site Description'),
                                    ]),

                                Section::make('System Configuration')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Toggle::make('enable_registration')
                                                    ->label('Enable Registration')
                                                    ->helperText('Allow new users to register'),
                                                Toggle::make('maintenance_mode')
                                                    ->label('Maintenance Mode')
                                                    ->helperText('Put the application in maintenance mode'),
                                            ]),
                                    ]),
                            ]),

                        Tab::make('Localization')
                            ->icon('heroicon-o-language')
                            ->schema([
                                Section::make('Regional Settings')
                                    ->schema([
                                        Select::make('default_locale')
                                            ->options([
                                                'en' => 'English',
                                                'es' => 'Spanish',
                                                'fr' => 'French',
                                                'de' => 'German',
                                            ])
                                            ->required()
                                            ->label('Default Language'),

                                        Select::make('timezone')
                                            ->options([
                                                'UTC' => 'UTC',
                                                'America/New_York' => 'Eastern Time',
                                                'America/Chicago' => 'Central Time',
                                                'America/Denver' => 'Mountain Time',
                                                'America/Los_Angeles' => 'Pacific Time',
                                            ])
                                            ->required()
                                            ->label('Timezone'),
                                    ]),
                            ]),

                        Tab::make('Notifications')
                            ->icon('heroicon-o-bell')
                            ->schema([
                                Section::make('Email Configuration')
                                    ->schema([
                                        Toggle::make('enable_notifications')
                                            ->label('Enable Email Notifications')
                                            ->helperText('Send email notifications to users'),

                                        TextInput::make('mail_from_address')
                                            ->email()
                                            ->required()
                                            ->label('From Address'),

                                        TextInput::make('mail_from_name')
                                            ->required()
                                            ->label('From Name'),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        // Here you would typically save these settings to your database
        // or update your configuration files

        Notification::make()
            ->title('Settings saved successfully')
            ->success()
            ->send();
    }
}
