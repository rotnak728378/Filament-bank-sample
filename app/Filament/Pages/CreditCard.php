<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use App\Models\Card;

class CreditCard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationLabel = 'Credit Cards';

    protected static ?string $title = '';

    protected static ?int $navigationSort = 5;

    protected static string $view = 'filament.pages.credit-card';

    public $cardType = 'Classic';
    public $cardName = 'My Cards';
    public $cardNumber = '';
    public $expirationDate;
    public $search = '';

    protected $rules = [
        'cardType' => 'required|string|max:255',
        'cardName' => 'required|string|max:255',
        'cardNumber' => 'required|string|min:16|max:19',
        'expirationDate' => 'required|date',
    ];

    protected $bankColors = [
        'DBL Bank' => 'blue',
        'BRC Bank' => 'pink',
        'ABM Bank' => 'green',
        'MCP Bank' => 'yellow',
    ];

    public function mount()
    {
        $this->expirationDate = now()->format('d F Y');
    }

    public function getCardsProperty()
    {
        return Card::limit(3)->get();
    }

    public function getCardListProperty()
    {
        $query = Card::query()->where('user_id', auth()->id());

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('holder_name', 'like', "%{$this->search}%")
                    ->orWhere('card_number', 'like', "%{$this->search}%")
                    ->orWhere('bank', 'like', "%{$this->search}%");
            });
        }

        return $query->paginate(3)->through(function ($card) {
            $card->bank_color = $this->bankColors[$card->bank] ?? 'gray';
            return $card;
        });
    }

    public function getCardExpenseStatsProperty()
    {
        return Card::where('user_id', auth()->id())
            ->groupBy('bank')
            ->selectRaw('bank as bank, count(*) as count')
            ->get()
            ->map(function ($stat) {
                $stat->color = $this->bankColors[$stat->bank] ?? 'gray';
                return $stat;
            });
    }

    public function addCard()
    {
        $validated = $this->validate();

        try {
            Card::create([
                'type' => $validated['cardType'],
                'holder_name' => $validated['cardName'],
                'card_number' => $validated['cardNumber'],
                'expired_date' => $validated['expirationDate'],
                'balance' => 5756.00,
                'bank' => 'MCP Bank',
                'status' => 'active',
                'user_id' => auth()->id(),
            ]);

            $this->reset(['cardNumber', 'cardName']);
            $this->cardType = 'Classic'; // Reset to default

            Notification::make()
                ->title('Card added successfully!')
                ->success()
                ->send();

        } catch (\Exception $e) {
            echo $e;
            Notification::make()
                ->title('Error adding card')
                ->danger()
                ->send();
        }
    }

    public function blockCard($cardId)
    {
        $card = Card::findOrFail($cardId);
        $card->update(['status' => 'blocked']);
        $this->notify('success', 'Card blocked successfully!');
    }

    public function getCardSettingProperty()
    {
        return [
            [
                'icon' => '/images/block-card.png',
                'title' => 'Block Card',
                'description' => 'Instantly block your card',
                'bg' => 'yellow',
                'action' => 'blockCard'
            ],
            [
                'icon' => '/images/lock.png',
                'title' => 'Change Pin Code',
                'description' => 'Choose another pin code',
                'bg' => 'blue'
            ],
            [
                'icon' => '/images/google-pay.png',
                'title' => 'Add to Google Pay',
                'description' => 'Withdraw without any card',
                'bg' => 'pink'
            ],
            [
                'icon' => '/images/apple-pay.png',
                'title' => 'Add to Apple Pay',
                'description' => 'Withdraw without any card',
                'bg' => 'pink'
            ],
            [
                'icon' => '/images/apple-pay.png',
                'title' => 'Add to Apple Store',
                'description' => 'Withdraw without any card',
                'bg' => 'cyan'
            ]
        ];
    }

    public static function getGlobalSearchResultTitle(Card $record): string
    {
        return $record->holder_name;
    }

    public static function getGlobalSearchResultDetails(Card $record): array
    {
        return [
            'Card Number' => substr($record->card_number, -4),
            'Bank' => $record->bank,
            'Status' => ucfirst($record->status),
            'Balance' => number_format($record->balance, 2),
        ];
    }

    public static function getGlobalSearchResults(string $search)
    {
        return Card::where('holder_name', 'like', "%{$search}%")
            ->orWhere('card_number', 'like', "%{$search}%")
            ->orWhere('bank', 'like', "%{$search}%")
            ->limit(10)
            ->get();
    }

    public static function getGlobalSearchResultUrl(Card $record): string
    {
        return static::getUrl();
    }

    public static function getGlobalSearchResultActions(Card $record): array
    {
        return [
            Action::make('block')
                ->label('Block Card')
                ->icon('heroicon-o-ban')
                ->color('danger')
                ->url(static::getUrl() . "?action=block&card={$record->id}"),

            ActionGroup::make([
                Action::make('view')
                    ->label('View Details')
                    ->icon('heroicon-o-eye')
                    ->url(static::getUrl() . "?card={$record->id}"),
                Action::make('edit')
                    ->label('Edit Card')
                    ->icon('heroicon-o-pencil')
                    ->url(static::getUrl() . "?action=edit&card={$record->id}"),
            ]),
        ];
    }

    protected function getViewData(): array
    {
        return [
            'cards' => $this->cards,
            'cardList' => $this->cardList,
            'cardExpenseStats' => $this->cardExpenseStats,
            'cardSetting' => $this->cardSetting,
        ];
    }
}
