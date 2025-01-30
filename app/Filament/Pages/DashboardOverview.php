<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Transaction;
use App\Models\Card;
use App\Models\User;
use Carbon\Carbon;

class DashboardOverview extends Page
{
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?string $title = '';
    protected static string $view = 'filament.pages.dashboard-overview';

    public $transferAmount = '';
    public $selectedUser = null;

    // For Quick Transfer
    public function getQuickTransferUsersProperty()
    {
        return User::take(3)->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role,
                'avatar' => $user->image,
            ];
        });
    }

    // For Cards
    public function getCardsProperty()
    {
        return Card::limit(2)->get();
    }

    // For Recent Transactions
    public function getRecentTransactionsProperty()
    {
        return Transaction::latest()
            ->take(3)
            ->get()
            ->map(function ($transaction) {
                return [
                    'icon' => $this->getTransactionIcon($transaction->type),
                    'description' => $transaction->description,
                    'date' => $transaction->created_at->format('d F Y'),
                    'amount' => $transaction->amount,
                    'color' => $this->getTransactionColor($transaction->type),
                ];
            });
    }

    // For Weekly Activity
    public function getWeeklyActivityProperty()
    {
        $dates = collect(range(0, 6))->map(function ($days) {
            $date = now()->subDays($days);
            return [
                'date' => $date->format('D'),
                'deposits' => Transaction::whereDate('created_at', $date)
                    ->where('amount', '>', 0)
                    ->sum('amount'),
                'withdrawals' => Transaction::whereDate('created_at', $date)
                    ->where('amount', '<', 0)
                    ->sum('amount') * -1,
            ];
        })->reverse();

        return [
            'labels' => $dates->pluck('date'),
            'deposits' => $dates->pluck('deposits'),
            'withdrawals' => $dates->pluck('withdrawals'),
        ];
    }

    // For Expense Statistics
    public function getExpenseStatsProperty()
    {
        return [
            ['label' => 'Entertainment', 'percentage' => 30, 'color' => '#1E293B'],
            ['label' => 'Bill Expense', 'percentage' => 15, 'color' => '#F97316'],
            ['label' => 'Investment', 'percentage' => 20, 'color' => '#EC4899'],
            ['label' => 'Others', 'percentage' => 35, 'color' => '#4F46E5'],
        ];
    }

    // For Balance History
    public function getBalanceHistoryProperty()
    {
        $months = collect(range(0, 6))->map(function ($monthsAgo) {
            $date = now()->subMonths($monthsAgo);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();

            // Get all transactions up to the end of each month
            $balance = Transaction::where('created_at', '<=', $endOfMonth)
                ->sum('amount');

            // Get monthly transactions for the tooltip
            $monthlyTransactions = Transaction::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->sum('amount');

            return [
                'month' => $date->format('M'),
                'balance' => round($balance, 2),
                'monthly_change' => round($monthlyTransactions, 2),
            ];
        })->reverse()->values();

        return [
            'labels' => $months->pluck('month'),
            'data' => $months->pluck('balance'),
            'monthly_changes' => $months->pluck('monthly_change'),
        ];
    }


    // Helper methods
    protected function getTransactionIcon($type)
    {
        return match($type) {
            'deposit' => 'heroicon-o-arrow-down',
            'withdrawal' => 'heroicon-o-arrow-up',
            default => 'heroicon-o-currency-dollar',
        };
    }

    protected function getTransactionColor($type)
    {
        return match($type) {
            'deposit' => 'success',
            'withdrawal' => 'danger',
            default => 'primary',
        };
    }

    public function sendTransfer()
    {
        $this->validate([
            'transferAmount' => 'required|numeric|min:0',
            'selectedUser' => 'required|exists:users,id',
        ]);

        // Process transfer logic here

        $this->reset(['transferAmount', 'selectedUser']);
        $this->notify('success', 'Transfer sent successfully!');
    }

    protected function getViewData(): array
    {
        return [
            'cards' => $this->cards,
            'recentTransactions' => $this->recentTransactions,
            'weeklyActivity' => $this->weeklyActivity,
            'expenseStats' => $this->expenseStats,
            'balanceHistory' => $this->balanceHistory,
            'quickTransferUsers' => $this->quickTransferUsers,
        ];
    }
}
