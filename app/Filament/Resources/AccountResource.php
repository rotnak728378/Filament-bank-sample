<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccountResource\Pages;
use App\Models\Account;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

class AccountResource extends Resource
{
    protected static ?int $navigationSort = 3;
    protected static ?string $model = Account::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';

    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
    {
        return "Account #" . $record->account_number;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Owner' => $record->user->name,
            'Type' => ucfirst($record->type),
            'Balance' => $record->balance,
            'Status' => ucfirst($record->status)
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['account_number', 'user.name', 'type', 'status'];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable(),
                TextInput::make('account_number')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Select::make('type')
                    ->options([
                        'savings' => 'Savings',
                        'checking' => 'Checking',
                        'investment' => 'Investment',
                    ])
                    ->required(),
                TextInput::make('balance')
                    ->numeric()
                    ->required()
                    ->default(0)
                    ->prefix('$')
                    ->maxValue(999999999999999.99),
                Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'frozen' => 'Frozen',
                        'closed' => 'Closed',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('account_number')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'savings' => 'info',
                        'checking' => 'success',
                        'investment' => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('balance')
                    ->money()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        'frozen' => 'warning',
                        'closed' => 'gray',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAccounts::route('/'),
            'create' => Pages\CreateAccount::route('/create'),
            'edit' => Pages\EditAccount::route('/{record}/edit'),
        ];
    }
}
