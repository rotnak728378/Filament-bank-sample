<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LoanResource\Pages;
use App\Models\Loan;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

class LoanResource extends Resource
{
    protected static ?int $navigationSort = 6;
    protected static ?string $model = Loan::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
    {
        return "Loan #" . $record->id . " - " . $record->amount;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Borrower' => $record->user->name,
            'Status' => ucfirst($record->status),
            'Due Date' => $record->due_date->format('M d, Y'),
            'Balance' => $record->remaining_amount,
            'Interest Rate' => $record->interest_rate . '%'
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'id',
            'user.name',
            'status',
            'purpose',
            'investment.investment_type'
        ];
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable(),
                Select::make('investment_id')
                    ->relationship('investment', 'investment_type')
                    ->nullable()
                    ->searchable(),
                TextInput::make('amount')
                    ->numeric()
                    ->required()
                    ->prefix('$')
                    ->maxValue(999999999999999.99),
                TextInput::make('interest_rate')
                    ->numeric()
                    ->required()
                    ->suffix('%')
                    ->step('0.01')
                    ->maxValue(100),
                DatePicker::make('start_date')
                    ->required(),
                DatePicker::make('due_date')
                    ->required()
                    ->after('start_date'),
                TextInput::make('paid_amount')
                    ->numeric()
                    ->prefix('$')
                    ->default(0)
                    ->maxValue(999999999999999.99),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'active' => 'Active',
                        'paid' => 'Paid',
                        'defaulted' => 'Defaulted',
                    ])
                    ->required(),
                Textarea::make('purpose')
                    ->nullable()
                    ->maxLength(65535),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('investment.investment_type')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('amount')
                    ->money()
                    ->sortable(),
                TextColumn::make('remaining_amount')
                    ->money()
                    ->sortable()
                    ->label('Balance'),
                TextColumn::make('interest_rate')
                    ->suffix('%')
                    ->sortable(),
                TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('due_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('paid_amount')
                    ->money()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'active' => 'success',
                        'paid' => 'info',
                        'defaulted' => 'danger',
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
                EditAction::make("Edit"),
                DeleteAction::make("Delete"),
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
            'index' => Pages\ListLoans::route('/'),
            'create' => Pages\CreateLoan::route('/create'),
            'edit' => Pages\EditLoan::route('/{record}/edit'),
        ];
    }
}
