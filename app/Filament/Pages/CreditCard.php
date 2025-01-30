<?php

namespace App\Filament\Pages;

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
        $query = Card::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('holder_name', 'like', "%{$this->search}%")
                    ->orWhere('card_number', 'like', "%{$this->search}%")
                    ->orWhere('bank', 'like', "%{$this->search}%");
            });
        }

        return $query->get()->map(function ($card) {
            $card->bank_color = $this->bankColors[$card->bank] ?? 'gray';
            return $card;
        });
    }

    public function getCardExpenseStatsProperty()
    {
        return Card::groupBy('bank')
            ->selectRaw('bank as bank, count(*) as count')
            ->get()
            ->map(function ($stat) {
                $stat->color = $this->bankColors[$stat->bank] ?? 'gray';
                return $stat;
            });
    }

    public function addCard()
    {
        $validated = $this->validate([
            'cardType' => 'required',
            'cardName' => 'required|string|max:255',
            'cardNumber' => 'required|string|max:19',
            'expirationDate' => 'required|date',
        ]);

        Card::create([
            'type' => $validated['cardType'],
            'holder_name' => $validated['cardName'],
            'card_number' => $validated['cardNumber'],
            'expired_date' => $validated['expirationDate'],
            'balance' => 5756.00,
            'bank' => 'DBL Bank',
        ]);

        $this->reset(['cardNumber']);

        $this->notify('success', 'Card added successfully!');
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
