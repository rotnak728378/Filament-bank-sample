<x-filament-panels::page>
    <div class="space-y-6">
        <!-- My Cards Section -->
        <section>
            <h2 class="mb-4 text-xl font-semibold">My Cards</h2>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                @foreach ($cards as $card)
                    <div>
                        <div
                            class="rounded-tl-[30px] m-0 rounded-tr-[30px] p-6 h-[180px] {{ $loop->iteration == 1 ? 'bg-gradient-to-br from-blue-600 to-blue-400 text-white' : ($loop->iteration == 2 ? 'bg-gradient-to-br from-blue-900 to-purple-900 text-white' : 'bg-white border text-black') }}">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <p class="text-sm opacity-80">Balance</p>
                                    <p class="text-2xl font-semibold">${{ number_format($card->balance, 2) }}</p>
                                </div>
                                @if ($loop->iteration != 3)
                                    <div class="w-10 h-10">
                                        <img width="35" src="{{ asset('/images/chip.png') }}" alt="">
                                    </div>
                                @else
                                    <div class="w-10 h-10">
                                        <img width="35" src="{{ asset('/images/chip-gray.png') }}" alt="">
                                    </div>
                                @endif

                            </div>

                            <div class="space-y-3">
                                <div class="flex items-end justify-between">
                                    <div>
                                        <p class="text-sm opacity-80">CARD HOLDER</p>
                                        <p>{{ $card->holder_name }}</p>
                                    </div>

                                    <div>
                                        <p class="text-sm opacity-80">VALID THRU</p>
                                        <p>{{ \Carbon\Carbon::parse($card->expired_date)->format('m/y') }}</p>
                                    </div>
                                    <div></div>
                                </div>
                            </div>
                        </div>
                        <div
                            class="{{ $loop->iteration == 1 ? 'from-blue-600 to-blue-400' : ($loop->iteration == 2 ? 'from-blue-900 to-purple-900' : 'bg-white') }} bg-gradient-to-br opacity-90 rounded-bl-[30px] m-0 rounded-br-[30px] px-6 pt-3 m-0 flex items-center justify-between z-99 {{ $loop->iteration != 3 ? '' : 'border-1 border' }}">
                            <div class="h-10">
                                <p class="text-[22px] {{ $loop->iteration != 3 ? 'text-white' : 'text-black' }}">
                                    {{ substr_replace($card->card_number, ' **** **** ', 4, 8) }}
                                </p>
                            </div>
                            <div class="w-10 h-10">
                                <img width="35"
                                    src="{{ asset($loop->iteration != 3 ? '/images/merged-circle.png' : '/images/merged-circle-dark.png') }}"
                                    alt="merged-circle">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Statistics and List Section -->
        <div class="grid grid-cols-3 gap-6">
            <!-- Card Expense Statistics -->
            <section class="col-span-1 fi-section rounded-xl">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Card Expense Statistics</h3>
                <div class="p-6 bg-white shadow-sm h-[300px] rounded-xl ring-1 ring-gray-950/5" wire:ignore x-data="pieChart(@js($cardExpenseStats))">
                    <canvas x-ref="canvas"></canvas>
                </div>
            </section>

            <!-- Card List -->
            <section class="col-span-2 fi-section rounded-xl">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Card List</h3>
                <div class="space-y-4">
                    @foreach ($cardList as $card)
                        <div class="flex items-center justify-between p-3 h-[90px] bg-white rounded-2xl ring-1 ring-gray-950/5">
                            <div class="flex items-center space-x-12">
                                <div
                                    class="w-[60px] h-[60px] rounded-lg p-0 {{ $card->bank == 'DBL Bank' ? 'bg-blue-100' : ($card->bank == 'BRC Bank' ? 'bg-pink-100' : 'bg-yellow-100') }} flex items-center justify-center">
                                    <img width="24"
                                        src="{{ asset($card->bank == 'DBL Bank' ? '/images/credit-card-blue.png' : ($card->bank == 'BRC Bank' ? '/images/credit-card-pink.png' : '/images/credit-card-yellow.png')) }}"
                                        alt="">
                                </div>
                                <div>
                                    <p>Card Type</p>
                                    <p class="text-sm text-gray-600">{{ $card->type }}</p>
                                </div>
                                <div>
                                    <p>Bank</p>
                                    <p class="text-sm text-gray-600">{{ $card->bank }}</p>
                                </div>
                                <div>
                                    <p>Card Number</p>
                                    <p class="text-sm text-gray-600">
                                        {{ substr_replace($card->card_number, ' **** **** ', 4, 8) }}</p>
                                </div>
                                <div>
                                    <p>Namain Card</p>
                                    <p class="text-sm text-gray-600">{{ $card->holder_name }}</p>
                                </div>
                                <a href="#" class="text-blue-600 hover:text-blue-800">View Details</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>

        <!-- Add New Card and Settings Section -->
        <div class="grid grid-cols-3 gap-6">
            <!-- Add New Card -->
            <!-- Add New Card Section -->
            <section class="col-span-2 fi-section">
                <h2 class="mb-4 text-xl font-semibold">Add New Card</h2>

                <div class="bg-white h-[550px] shadow-sm rounded-xl ring-1 ring-gray-950/5" style="padding: 55px;">
                    <p class="mb-[50px]" style="color: #718EBF; margin-bottom: 50px;">
                        Credit Card generally means a plastic card issued by Scheduled Commercial Banks
                        assigned to a Cardholder, with a credit limit, that can be used to purchase goods
                        and services on credit or obtain cash advances.
                    </p>

                    <form wire:submit="addCard" class="space-y-6">
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <!-- Card Type -->
                            <div>
                                <label for="cardType" class="block mb-2 text-gray-900">Card Type</label>
                                <input type="text" id="cardType" wire:model="cardType" value="Classic"
                                    class="w-full px-4 py-3 text-gray-400 bg-white border-gray-200 rounded-xl">
                            </div>

                            <!-- Name On Card -->
                            <div>
                                <label for="cardName" class="block mb-2 text-gray-900">Name On Card</label>
                                <input type="text" id="cardName" wire:model="cardName" value="My Cards"
                                    class="w-full px-4 py-3 text-gray-400 bg-white border-gray-200 rounded-xl">
                            </div>

                            <!-- Card Number -->
                            <div>
                                <label for="cardNumber" class="block mb-2 text-gray-900">Card Number</label>
                                <input type="text" id="cardNumber" wire:model="cardNumber"
                                    placeholder="**** **** **** ****"
                                    class="w-full px-4 py-3 text-gray-400 bg-white border-gray-200 rounded-xl">
                            </div>

                            <!-- Expiration Date -->
                            <div>
                                <label for="expirationDate" class="block mb-2 text-gray-900">Expiration Date</label>
                                <input type="date" id="expirationDate" wire:model="expirationDate"
                                    value="25 January 2025"
                                    class="w-full px-4 py-3 text-gray-400 bg-white border-gray-200 rounded-xl">
                            </div>
                        </div>

                        <button type="submit"
                            class="px-6 py-3 font-semibold text-white transition bg-blue-600 rounded-xl hover:bg-blue-700">
                            Add Card
                        </button>
                    </form>
                </div>
            </section>

            <!-- Card Settings -->
            <section class="col-span-1 fi-section">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Card Setting</h3>
                <div class="h-[550px] p-6 space-y-4 bg-white shadow-sm rounded-xl ring-1 ring-gray-950/5">
                    @foreach ($cardSetting as $setting)
                        <div
                            class="flex items-center p-4 space-x-4 transition rounded-lg cursor-pointer hover:bg-gray-50">
                            <div
                                class="rounded-2xl bg-{{ $setting['bg'] }}-100 flex items-center justify-center p-4" style="{{$loop->iteration == 4 || $loop->iteration == 5 ? 'background: #DCFAF8;' : ''}}">
                                {{-- <span class="text-{{ $setting['bg'] }}-600">{{ $setting['icon'] }}</span> --}}
                                <img width="24" src="{{asset($setting['icon'])}}" alt="{{$setting['icon']}}">
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">{{ $setting['title'] }}</p>
                                <p class="text-sm text-gray-600">{{ $setting['description'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('pieChart', (stats) => ({
                    init() {
                        const ctx = this.$refs.canvas.getContext('2d');
                        new Chart(ctx, {
                            type: 'doughnut',
                            data: {
                                labels: stats.map(s => s.bank),
                                datasets: [{
                                    data: stats.map(s => s.count),
                                    backgroundColor: [
                                        '#3B82F6', // blue
                                        '#10B981', // green
                                        '#F59E0B', // yellow
                                        '#EF4444', // red
                                    ],
                                }],
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                    },
                                },
                            },
                        });
                    },
                }));
            });
        </script>
    @endpush
</x-filament-panels::page>
