<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Transaction;
use App\Models\Card;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;

class TransactionPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';

    protected static ?string $navigationLabel = 'Transactions';

    protected static ?string $title = '';
    protected static string $view = 'filament.pages.transaction-page';

    public $activeTab = 'all';

    public function updatedActiveTab()
    {
        $this->resetTable(); // Reset table when tab changes
    }

    public function getCardsProperty()
    {
        return Card::limit(2)->get();
    }

    public function getMonthlyExpensesProperty()
    {
        // This shouldn't be affected by tab changes
        return Transaction::selectRaw('EXTRACT(MONTH FROM created_at) as month, SUM(CASE WHEN amount < 0 THEN ABS(amount) ELSE 0 END) as expense')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('expense', 'month')
            ->map(function ($amount, $month) {
                return [
                    'month' => date('M', mktime(0, 0, 0, $month, 1)),
                    'amount' => $amount,
                ];
            })->values();
    }

    public function getTableQuery()
    {
        $query = Transaction::query();

        return match ($this->activeTab) {
            'expense' => $query->where('amount', '<', 0),
            'income' => $query->where('amount', '>=', 0),
            default => $query
        };
    }

    protected function getTableColumns(): array
    {
        return [
            // IconColumn::make('type')
            //     ->icon(fn (string $state): string => match ($state) {
            //         'Shopping' => 'heroicon-o-shopping-cart',
            //         'Transfer' => 'heroicon-o-arrow-path',
            //         'Service' => 'heroicon-o-wrench',
            //         default => 'heroicon-o-banknotes',
            //     })
            //     ->color(fn ($record) => $record->amount < 0 ? 'danger' : 'success'),
            TextColumn::make('description')
                ->searchable(),
            TextColumn::make('id')
                ->label('Transaction ID'),
            TextColumn::make('type')
                ->badge()
                ->color(fn ($record) => match($record->type) {
                    'Shopping' => 'warning',
                    'Transfer' => 'info',
                    'Service' => 'success',
                    default => 'gray',
                }),
            TextColumn::make('card_last_four')
                ->label('Card')
                ->formatStateUsing(fn ($state) => "****".$state),
            TextColumn::make('created_at')
                ->label('Date')
                ->dateTime('d M, h:i A')
                ->sortable(),
            TextColumn::make('amount')
                ->money('USD')
                ->color(fn ($record) => $record->amount < 0 ? 'danger' : 'success')
                ->alignment('right'),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('type')
                ->options([
                    'Shopping' => 'Shopping',
                    'Transfer' => 'Transfer',
                    'Service' => 'Service',
                ]),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('download')
                ->label('Download')
                ->icon('heroicon-m-arrow-down-tray')
                ->url(fn ($record) => route('download.receipt', $record))
                ->openUrlInNewTab(),
        ];
    }

    protected function getTableDefaultSortColumn(): ?string
    {
        return 'created_at';
    }

    protected function getTableDefaultSortDirection(): ?string
    {
        return 'desc';
    }

    protected function getTablePaginationPageOptions(): array
    {
        return [10, 25, 50];
    }
    protected function getViewData(): array
    {
        return [
            'cards' => $this->cards,
            'monthlyExpenses' => $this->monthlyExpenses,
            'table' => $this->table,
        ];
    }
}
