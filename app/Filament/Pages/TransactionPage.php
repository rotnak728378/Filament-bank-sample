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
use Illuminate\Database\Eloquent\Model;

class TransactionPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';

    protected static ?string $navigationLabel = 'Transactions';

    protected static ?string $title = '';
    protected static string $view = 'filament.pages.transaction-page';

    public $activeTab = 'all';

    public static function getGlobalSearchResultTitle(Model $record): string|\Illuminate\Contracts\Support\Htmlable
    {
        return 'System Settings';
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'General' => 'Site configuration and system settings',
            'Localization' => 'Language and timezone settings',
            'Notifications' => 'Email and notification configuration'
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'settings',
            'configuration',
            'system',
            'email',
            'language',
            'timezone',
            'notifications'
        ];
    }

    public static function getGlobalSearchResults(string $search)
    {
        // Since Settings is a single page, we'll return it if any of the searchable terms match
        $searchTerms = self::getGloballySearchableAttributes();
        foreach ($searchTerms as $term) {
            if (str_contains(strtolower($term), strtolower($search))) {
                return collect([new class {
                    public $id = 'settings';
                }]);
            }
        }
        return collect();
    }

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return static::getUrl();
    }

    public function updatedActiveTab()
    {
        $this->resetTable();
        // Dispatch with monthlyExpenses as direct data
        $this->dispatch('update-chart', monthlyExpenses: $this->monthlyExpenses);
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
            TextColumn::make('description'),
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
